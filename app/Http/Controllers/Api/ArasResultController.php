<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ArasResult;
use App\Models\Candidate;
use App\Models\Criterion;
use App\Models\Score;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ArasResultController extends Controller
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
            return $this->error('Akses ditolak. Hanya admin yang dapat mengakses hasil ARAS.', 403);
        }

        return null;
    }

    public function index(Request $request): JsonResponse
    {
        if ($deny = $this->adminOnly($request)) {
            return $deny;
        }

        $validator = Validator::make($request->all(), [
            'period_id' => ['nullable', 'integer', 'exists:election_periods,id'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validasi gagal.', 422, $validator->errors());
        }

        $query = ArasResult::with(['candidate', 'period', 'calculator'])
            ->orderBy('final_rank');

        if ($request->filled('period_id')) {
            $query->where('period_id', $request->period_id);
        }

        $results = $query->get();

        return $this->success($results, 'Data hasil ARAS berhasil diambil.');
    }

    public function show(Request $request, ArasResult $arasResult): JsonResponse
    {
        if ($deny = $this->adminOnly($request)) {
            return $deny;
        }

        return $this->success(
            $arasResult->load(['candidate', 'period', 'calculator']),
            'Detail hasil ARAS berhasil diambil.'
        );
    }

    public function destroy(Request $request, ArasResult $arasResult): JsonResponse
    {
        if ($deny = $this->adminOnly($request)) {
            return $deny;
        }

        $arasResult->delete();

        return $this->success(null, 'Data hasil ARAS berhasil dihapus.');
    }

    public function calculate(Request $request): JsonResponse
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

        try {
            return DB::transaction(function () use ($request) {
                $periodId = (int) $request->period_id;

                $criteria = Criterion::query()
                    ->where('period_id', $periodId)
                    ->where('is_active', true)
                    ->orderBy('code')
                    ->get();

                if ($criteria->isEmpty()) {
                    return $this->error('Tidak ada kriteria aktif pada periode ini.', 422);
                }

                $criteriaIds = $criteria->pluck('id')->toArray();

                $candidates = Candidate::query()
                    ->where('period_id', $periodId)
                    ->whereNotIn('status', ['pending', 'invalid'])
                    ->whereExists(function ($query) use ($periodId) {
                        $query->select(DB::raw(1))
                            ->from('scores')
                            ->whereColumn('scores.candidate_id', 'candidates.id')
                            ->where('scores.period_id', $periodId);
                    })
                    ->orderBy('registration_number')
                    ->orderBy('created_at')
                    ->get();

                if ($candidates->isEmpty()) {
                    return $this->error('Belum ada calon yang memiliki data nilai pada periode ini.', 422);
                }

                $candidateIds = $candidates->pluck('id')->toArray();

                $weightTotal = $criteria->sum(function ($criterion) {
                    return (float) $criterion->weight;
                });

                if ($weightTotal <= 0) {
                    return $this->error('Total bobot kriteria harus lebih besar dari 0.', 422);
                }

                $normalizedWeights = [];

                foreach ($criteria as $criterion) {
                    $normalizedWeights[$criterion->id] = (float) $criterion->weight / $weightTotal;
                }

                $averages = Score::query()
                    ->select(
                        'candidate_id',
                        'criterion_id',
                        DB::raw('AVG(score) as average_score'),
                        DB::raw('COUNT(*) as score_count')
                    )
                    ->where('period_id', $periodId)
                    ->whereIn('candidate_id', $candidateIds)
                    ->whereIn('criterion_id', $criteriaIds)
                    ->groupBy('candidate_id', 'criterion_id')
                    ->get();

                $averageMap = [];

                foreach ($averages as $average) {
                    $averageMap[(int) $average->candidate_id][(int) $average->criterion_id] = round(
                        (float) $average->average_score,
                        6
                    );
                }

                $matrix = [];
                $missingScores = [];

                foreach ($candidates as $candidate) {
                    foreach ($criteria as $criterion) {
                        $candidateId = (int) $candidate->id;
                        $criterionId = (int) $criterion->id;

                        if (!isset($averageMap[$candidateId][$criterionId])) {
                            $missingScores[] = [
                                'candidate_id' => $candidateId,
                                'candidate_name' => $candidate->full_name,
                                'criterion_id' => $criterionId,
                                'criterion_code' => $criterion->code,
                                'criterion_name' => $criterion->name,
                            ];

                            continue;
                        }

                        $matrix[$candidateId][$criterionId] = $averageMap[$candidateId][$criterionId];
                    }
                }

                if (!empty($missingScores)) {
                    return $this->error(
                        'Nilai belum lengkap untuk semua calon dan kriteria aktif.',
                        422,
                        [
                            'missing_count' => count($missingScores),
                            'missing_samples' => array_slice($missingScores, 0, 20),
                        ]
                    );
                }

                $idealValues = [];

                foreach ($criteria as $criterion) {
                    $criterionId = (int) $criterion->id;

                    $values = [];

                    foreach ($candidates as $candidate) {
                        $values[] = (float) $matrix[(int) $candidate->id][$criterionId];
                    }

                    if (empty($values)) {
                        return $this->error("Nilai untuk kriteria {$criterion->code} tidak ditemukan.", 422);
                    }

                    $type = strtolower((string) $criterion->type);

                    if ($type === 'cost') {
                        $idealValues[$criterionId] = min($values);
                    } else {
                        $idealValues[$criterionId] = max($values);
                    }
                }

                $transformedMatrix = [];
                $transformedIdeal = [];
                $columnTotals = [];

                foreach ($criteria as $criterion) {
                    $criterionId = (int) $criterion->id;
                    $type = strtolower((string) $criterion->type);

                    if ($type === 'cost') {
                        if ((float) $idealValues[$criterionId] <= 0) {
                            return $this->error(
                                "Kriteria cost {$criterion->code} memiliki nilai ideal 0 atau kurang, sehingga tidak bisa dinormalisasi.",
                                422
                            );
                        }

                        $transformedIdeal[$criterionId] = 1 / (float) $idealValues[$criterionId];
                    } else {
                        $transformedIdeal[$criterionId] = (float) $idealValues[$criterionId];
                    }

                    $columnTotals[$criterionId] = $transformedIdeal[$criterionId];

                    foreach ($candidates as $candidate) {
                        $candidateId = (int) $candidate->id;
                        $value = (float) $matrix[$candidateId][$criterionId];

                        if ($type === 'cost') {
                            if ($value <= 0) {
                                return $this->error(
                                    "Kriteria cost {$criterion->code} memiliki nilai 0 atau kurang pada calon {$candidate->full_name}.",
                                    422
                                );
                            }

                            $transformedValue = 1 / $value;
                        } else {
                            $transformedValue = $value;
                        }

                        $transformedMatrix[$candidateId][$criterionId] = $transformedValue;
                        $columnTotals[$criterionId] += $transformedValue;
                    }

                    if ($columnTotals[$criterionId] <= 0) {
                        return $this->error(
                            "Total kolom kriteria {$criterion->code} harus lebih besar dari 0.",
                            422
                        );
                    }
                }

                $idealScore = 0;

                foreach ($criteria as $criterion) {
                    $criterionId = (int) $criterion->id;

                    $normalizedIdeal = $transformedIdeal[$criterionId] / $columnTotals[$criterionId];
                    $weightedIdeal = $normalizedIdeal * $normalizedWeights[$criterionId];

                    $idealScore += $weightedIdeal;
                }

                if ($idealScore <= 0) {
                    return $this->error('Nilai ideal ARAS tidak valid.', 422);
                }

                $results = [];

                foreach ($candidates as $candidate) {
                    $candidateId = (int) $candidate->id;
                    $totalScore = 0;

                    foreach ($criteria as $criterion) {
                        $criterionId = (int) $criterion->id;

                        $normalizedValue = $transformedMatrix[$candidateId][$criterionId] / $columnTotals[$criterionId];
                        $weightedValue = $normalizedValue * $normalizedWeights[$criterionId];

                        $totalScore += $weightedValue;
                    }

                    $utilityScore = $totalScore / $idealScore;

                    $results[] = [
                        'period_id' => $periodId,
                        'candidate_id' => $candidateId,
                        'candidate_name' => $candidate->full_name,
                        'total_score' => round($totalScore, 6),
                        'utility_score' => round($utilityScore, 6),
                    ];
                }

                usort($results, function ($a, $b) {
                    if ($b['utility_score'] === $a['utility_score']) {
                        return $b['total_score'] <=> $a['total_score'];
                    }

                    return $b['utility_score'] <=> $a['utility_score'];
                });

                ArasResult::where('period_id', $periodId)->delete();

                $savedResults = [];

                foreach ($results as $index => $result) {
                    $arasResult = ArasResult::create([
                        'period_id' => $periodId,
                        'candidate_id' => $result['candidate_id'],
                        'total_score' => $result['total_score'],
                        'utility_score' => $result['utility_score'],
                        'final_rank' => $index + 1,
                        'calculated_by' => $request->user()->id,
                        'calculated_at' => now(),
                    ]);

                    $savedResults[] = [
                        'id' => $arasResult->id,
                        'candidate_id' => $result['candidate_id'],
                        'candidate_name' => $result['candidate_name'],
                        'total_score' => $result['total_score'],
                        'utility_score' => $result['utility_score'],
                        'final_rank' => $index + 1,
                    ];
                }

                return $this->success([
                    'period_id' => $periodId,
                    'candidate_count' => count($savedResults),
                    'criteria_count' => $criteria->count(),
                    'ideal_score' => round($idealScore, 6),
                    'results' => $savedResults,
                ], 'Perhitungan ARAS berhasil dilakukan.', 201);
            });
        } catch (Throwable $e) {
            return $this->error('Perhitungan ARAS gagal dilakukan.', 500, $e->getMessage());
        }
    }
}
