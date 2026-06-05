<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ArasResult;
use App\Models\Candidate;
use App\Models\Criterion;
use App\Models\ElectionPeriod;
use App\Models\Score;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AnnouncementController extends Controller
{
    private function success($data = null, string $message = 'Berhasil', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    private function error(string $message = 'Terjadi kesalahan', int $status = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }

    private function adminOnly(Request $request): ?JsonResponse
    {
        if (!$request->user() || $request->user()->role !== 'admin') {
            return $this->error('Akses ditolak. Hanya admin yang dapat mengelola pengumuman.', 403);
        }

        return null;
    }

    public function checkReadiness(Request $request): JsonResponse
    {
        if ($deny = $this->adminOnly($request)) {
            return $deny;
        }

        $validator = Validator::make($request->all(), [
            'period_id' => ['required', 'integer', 'exists:election_periods,id'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validasi gagal.', 422, $validator->errors());
        }

        $readiness = $this->buildReadinessReport((int) $request->period_id);

        return $this->success($readiness, $readiness['ready']
            ? 'Data siap dipublikasikan.'
            : 'Data belum siap dipublikasikan.'
        );
    }

    public function publish(Request $request): JsonResponse
    {
        if ($deny = $this->adminOnly($request)) {
            return $deny;
        }

        $validator = Validator::make($request->all(), [
            'period_id' => ['required', 'integer', 'exists:election_periods,id'],
            'announcement_note' => ['nullable', 'string', 'max:3000'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validasi gagal.', 422, $validator->errors());
        }

        try {
            return DB::transaction(function () use ($request) {
                $periodId = (int) $request->period_id;

                $readiness = $this->buildReadinessReport($periodId);

                if (!$readiness['ready']) {
                    return $this->error(
                        'Pengumuman belum bisa dipublikasikan karena data belum lengkap.',
                        422,
                        $readiness
                    );
                }

                $period = ElectionPeriod::find($periodId);

                $period->update([
                    'is_result_published' => true,
                    'result_published_at' => now(),
                    'result_published_by' => $request->user()->id,
                    'announcement_note' => $request->input('announcement_note'),
                ]);

                return $this->success([
                    'period_id' => $period->id,
                    'is_result_published' => $period->is_result_published,
                    'result_published_at' => $period->result_published_at,
                    'announcement_note' => $period->announcement_note,
                    'readiness' => $readiness,
                ], 'Pengumuman hasil seleksi berhasil dipublikasikan.');
            });
        } catch (Throwable $e) {
            return $this->error('Publikasi pengumuman gagal.', 500, $e->getMessage());
        }
    }

    public function unpublish(Request $request): JsonResponse
    {
        if ($deny = $this->adminOnly($request)) {
            return $deny;
        }

        $validator = Validator::make($request->all(), [
            'period_id' => ['required', 'integer', 'exists:election_periods,id'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validasi gagal.', 422, $validator->errors());
        }

        $period = ElectionPeriod::find((int) $request->period_id);

        $period->update([
            'is_result_published' => false,
            'result_published_at' => null,
            'result_published_by' => null,
        ]);

        return $this->success([
            'period_id' => $period->id,
            'is_result_published' => $period->is_result_published,
        ], 'Publikasi pengumuman berhasil dibatalkan.');
    }

    public function publicResults(Request $request): JsonResponse
    {
        $periodId = $request->query('period_id');

        $periodQuery = ElectionPeriod::query()
            ->where('is_result_published', true);

        if ($periodId) {
            $periodQuery->where('id', $periodId);
        }

        $period = $periodQuery
            ->orderByDesc('election_year')
            ->first();

        if (!$period) {
            return $this->success([
                'is_published' => false,
                'period' => null,
                'announcement_note' => null,
                'results' => [],
            ], 'Hasil seleksi belum dipublikasikan.');
        }

        $results = ArasResult::query()
            ->with('candidate')
            ->where('period_id', $period->id)
            ->orderBy('final_rank')
            ->get();

        return $this->success([
            'is_published' => true,
            'period' => $period,
            'announcement_note' => $period->announcement_note,
            'published_at' => $period->result_published_at,
            'results' => $results,
        ], 'Hasil seleksi berhasil diambil.');
    }

    private function buildReadinessReport(int $periodId): array
    {
        $period = ElectionPeriod::find($periodId);

        $criteria = Criterion::query()
            ->where('period_id', $periodId)
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        $eligibleCandidates = Candidate::query()
            ->where('period_id', $periodId)
            ->whereNotIn('status', ['pending', 'invalid'])
            ->orderBy('registration_number')
            ->get();

        $arasResults = ArasResult::query()
            ->where('period_id', $periodId)
            ->get();

        $missingScores = [];

        foreach ($eligibleCandidates as $candidate) {
            foreach ($criteria as $criterion) {
                $hasScore = Score::query()
                    ->where('period_id', $periodId)
                    ->where('candidate_id', $candidate->id)
                    ->where('criterion_id', $criterion->id)
                    ->exists();

                if (!$hasScore) {
                    $missingScores[] = [
                        'candidate_id' => $candidate->id,
                        'candidate_name' => $candidate->full_name,
                        'criterion_id' => $criterion->id,
                        'criterion_code' => $criterion->code,
                        'criterion_name' => $criterion->name,
                    ];
                }
            }
        }

        $warnings = [];

        if ($criteria->isEmpty()) {
            $warnings[] = 'Belum ada kriteria aktif pada periode ini.';
        }

        if ($eligibleCandidates->isEmpty()) {
            $warnings[] = 'Belum ada kandidat yang layak dipublikasikan pada periode ini.';
        }

        if (!empty($missingScores)) {
            $warnings[] = 'Masih ada kandidat yang belum memiliki nilai lengkap.';
        }

        if ($arasResults->isEmpty()) {
            $warnings[] = 'Hasil ARAS belum dihitung.';
        }

        if (
            !$arasResults->isEmpty()
            && $eligibleCandidates->isNotEmpty()
            && $arasResults->count() !== $eligibleCandidates->count()
        ) {
            $warnings[] = 'Jumlah hasil ARAS tidak sama dengan jumlah kandidat yang layak dipublikasikan. Hitung ulang ARAS.';
        }

        return [
            'ready' => empty($warnings),
            'period_id' => $period?->id,
            'election_year' => $period?->election_year,
            'criteria_count' => $criteria->count(),
            'eligible_candidate_count' => $eligibleCandidates->count(),
            'aras_result_count' => $arasResults->count(),
            'missing_score_count' => count($missingScores),
            'missing_score_samples' => array_slice($missingScores, 0, 30),
            'warnings' => $warnings,
        ];
    }
}