<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class JuryScoringController extends Controller
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

    private function juryOnly(Request $request): ?JsonResponse
    {
        if (!$request->user() || $request->user()->role !== 'juri') {
            return $this->error('Akses ditolak. Hanya juri yang dapat mengakses penilaian.', 403);
        }

        return null;
    }

    public function index(Request $request): JsonResponse
    {
        if ($deny = $this->juryOnly($request)) {
            return $deny;
        }

        $validator = Validator::make($request->all(), [
            'period_id' => ['required', 'integer', 'exists:election_periods,id'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validasi gagal.', 422, $validator->errors());
        }

        try {
            $userId = $request->user()->id;
            $periodId = (int) $request->period_id;

            $isPublished = DB::table('election_periods')
                ->where('id', $periodId)
                ->value('is_result_published');

            if ($isPublished) {
                return $this->error(
                    'Penilaian sudah dikunci karena hasil seleksi telah dipublikasikan.',
                    403
                );
            }

            $assignedCriteria = DB::table('jury_criteria')
                ->join('criteria', 'jury_criteria.criterion_id', '=', 'criteria.id')
                ->where('jury_criteria.user_id', $userId)
                ->where('jury_criteria.period_id', $periodId)
                ->where('criteria.is_active', true)
                ->orderBy('criteria.code')
                ->get([
                    'criteria.id',
                    'criteria.code',
                    'criteria.name',
                    'criteria.weight',
                    'criteria.type',
                    'criteria.min_score',
                    'criteria.max_score',
                ]);

            $assignedCriteriaIds = $assignedCriteria
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->toArray();

            $assignedCriteriaCount = count($assignedCriteriaIds);

            $candidates = DB::table('candidates')
                ->leftJoin('interviews', function ($join) use ($periodId) {
                    $join->on('candidates.id', '=', 'interviews.candidate_id')
                        ->where('interviews.period_id', '=', $periodId);
                })
                ->where('candidates.period_id', $periodId)
                ->whereNotIn('candidates.status', ['pending', 'invalid'])
                ->whereNotNull('interviews.id')
                ->orderBy('interviews.scheduled_at')
                ->orderBy('candidates.registration_number')
                ->get([
                    'candidates.id',
                    'candidates.registration_number',
                    'candidates.full_name',
                    'candidates.student_number',
                    'candidates.study_program',
                    'candidates.status',
                    'interviews.scheduled_at',
                    'interviews.location',
                    'interviews.status as interview_status',
                ]);

            $scoreRows = collect();

            if (!empty($assignedCriteriaIds)) {
                $scoreRows = DB::table('scores')
                    ->select(
                        'candidate_id',
                        DB::raw('COUNT(DISTINCT criterion_id) as scored_criteria_count'),
                        DB::raw('AVG(score) as average_score')
                    )
                    ->where('period_id', $periodId)
                    ->where('user_id', $userId)
                    ->whereIn('criterion_id', $assignedCriteriaIds)
                    ->groupBy('candidate_id')
                    ->get();
            }

            $scoreMap = [];

            foreach ($scoreRows as $scoreRow) {
                $scoreMap[(int) $scoreRow->candidate_id] = [
                    'scored_criteria_count' => (int) $scoreRow->scored_criteria_count,
                    'average_score' => round((float) $scoreRow->average_score, 2),
                ];
            }

            $rows = [];

            foreach ($candidates as $candidate) {
                $candidateId = (int) $candidate->id;

                $scoreInfo = $scoreMap[$candidateId] ?? [
                    'scored_criteria_count' => 0,
                    'average_score' => null,
                ];

                $scoredCriteriaCount = $scoreInfo['scored_criteria_count'];
                $isComplete = $assignedCriteriaCount > 0 && $scoredCriteriaCount >= $assignedCriteriaCount;

                $rows[] = [
                    'id' => $candidateId,
                    'registration_number' => $candidate->registration_number,
                    'full_name' => $candidate->full_name,
                    'student_number' => $candidate->student_number,
                    'study_program' => $candidate->study_program,
                    'candidate_status' => $candidate->status,
                    'scheduled_at' => $candidate->scheduled_at,
                    'location' => $candidate->location,
                    'interview_status' => $candidate->interview_status,
                    'assigned_criteria_count' => $assignedCriteriaCount,
                    'scored_criteria_count' => $scoredCriteriaCount,
                    'completion_percentage' => $assignedCriteriaCount > 0
                        ? round(($scoredCriteriaCount / $assignedCriteriaCount) * 100, 2)
                        : 0,
                    'average_score' => $scoreInfo['average_score'],
                    'is_complete' => $isComplete,
                ];
            }

            return $this->success([
                'period_id' => $periodId,
                'assigned_criteria_count' => $assignedCriteriaCount,
                'assigned_criteria' => $assignedCriteria,
                'candidates' => $rows,
            ], 'Data peserta penilaian berhasil diambil.');
        } catch (Throwable $e) {
            return $this->error('Data peserta penilaian gagal diambil.', 500, $e->getMessage());
        }
    }

    public function show(Request $request, string $candidate): JsonResponse
    {
        if ($deny = $this->juryOnly($request)) {
            return $deny;
        }

        $validator = Validator::make($request->all(), [
            'period_id' => ['required', 'integer', 'exists:election_periods,id'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validasi gagal.', 422, $validator->errors());
        }

        try {
            $userId = $request->user()->id;
            $periodId = (int) $request->period_id;
            $candidateId = (int) $candidate;

            $candidateData = DB::table('candidates')
                ->leftJoin('interviews', function ($join) use ($periodId) {
                    $join->on('candidates.id', '=', 'interviews.candidate_id')
                        ->where('interviews.period_id', '=', $periodId);
                })
                ->where('candidates.id', $candidateId)
                ->where('candidates.period_id', $periodId)
                ->whereNotIn('candidates.status', ['pending', 'invalid'])
                ->first([
                    'candidates.id',
                    'candidates.registration_number',
                    'candidates.full_name',
                    'candidates.student_number',
                    'candidates.study_program',
                    'candidates.status',
                    'interviews.scheduled_at',
                    'interviews.location',
                    'interviews.status as interview_status',
                ]);

            if (!$candidateData) {
                return $this->error('Peserta tidak ditemukan atau belum dapat dinilai.', 404);
            }

            $criteria = DB::table('jury_criteria')
                ->join('criteria', 'jury_criteria.criterion_id', '=', 'criteria.id')
                ->where('jury_criteria.user_id', $userId)
                ->where('jury_criteria.period_id', $periodId)
                ->where('criteria.is_active', true)
                ->orderBy('criteria.code')
                ->get([
                    'criteria.id',
                    'criteria.code',
                    'criteria.name',
                    'criteria.weight',
                    'criteria.type',
                    'criteria.min_score',
                    'criteria.max_score',
                ]);

            if ($criteria->isEmpty()) {
                return $this->error('Belum ada kriteria yang ditugaskan kepada juri ini.', 422);
            }

            $existingScores = DB::table('scores')
                ->where('period_id', $periodId)
                ->where('candidate_id', $candidateId)
                ->where('user_id', $userId)
                ->get([
                    'criterion_id',
                    'score',
                    'updated_at',
                ]);

            $scoreMap = [];

            foreach ($existingScores as $score) {
                $scoreMap[(int) $score->criterion_id] = [
                    'score' => (float) $score->score,
                    'updated_at' => $score->updated_at,
                ];
            }

            $criteriaRows = $criteria->map(function ($criterion) use ($scoreMap) {
                $criterionId = (int) $criterion->id;

                return [
                    'id' => $criterionId,
                    'code' => $criterion->code,
                    'name' => $criterion->name,
                    'weight' => $criterion->weight,
                    'type' => $criterion->type,
                    'min_score' => $criterion->min_score,
                    'max_score' => $criterion->max_score,
                    'score' => $scoreMap[$criterionId]['score'] ?? null,
                    'updated_at' => $scoreMap[$criterionId]['updated_at'] ?? null,
                ];
            })->values();

            $filledCount = $criteriaRows->filter(fn ($item) => $item['score'] !== null)->count();
            $criteriaCount = $criteriaRows->count();

            return $this->success([
                'period_id' => $periodId,
                'candidate' => $candidateData,
                'criteria' => $criteriaRows,
                'summary' => [
                    'criteria_count' => $criteriaCount,
                    'filled_count' => $filledCount,
                    'is_complete' => $criteriaCount > 0 && $filledCount === $criteriaCount,
                    'completion_percentage' => $criteriaCount > 0
                        ? round(($filledCount / $criteriaCount) * 100, 2)
                        : 0,
                ],
            ], 'Form penilaian peserta berhasil diambil.');
        } catch (Throwable $e) {
            return $this->error('Form penilaian peserta gagal diambil.', 500, $e->getMessage());
        }
    }

    public function saveScores(Request $request, string $candidate): JsonResponse
    {
        if ($deny = $this->juryOnly($request)) {
            return $deny;
        }

        $validator = Validator::make($request->all(), [
            'period_id' => ['required', 'integer', 'exists:election_periods,id'],
            'scores' => ['required', 'array', 'min:1'],
            'scores.*.criterion_id' => ['required', 'integer', 'exists:criteria,id'],
            'scores.*.score' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validasi gagal.', 422, $validator->errors());
        }

        try {
            return DB::transaction(function () use ($request, $candidate) {
                $userId = $request->user()->id;
                $periodId = (int) $request->period_id;
                $candidateId = (int) $candidate;

                $candidateExists = DB::table('candidates')
                    ->where('id', $candidateId)
                    ->where('period_id', $periodId)
                    ->whereNotIn('status', ['pending', 'invalid'])
                    ->exists();

                if (!$candidateExists) {
                    return $this->error('Peserta tidak ditemukan atau belum dapat dinilai.', 404);
                }

                $assignedCriteria = DB::table('jury_criteria')
                    ->join('criteria', 'jury_criteria.criterion_id', '=', 'criteria.id')
                    ->where('jury_criteria.user_id', $userId)
                    ->where('jury_criteria.period_id', $periodId)
                    ->where('criteria.is_active', true)
                    ->get([
                        'criteria.id',
                        'criteria.code',
                        'criteria.name',
                        'criteria.min_score',
                        'criteria.max_score',
                    ]);

                if ($assignedCriteria->isEmpty()) {
                    return $this->error('Belum ada kriteria yang ditugaskan kepada juri ini.', 422);
                }

                $criteriaMap = [];

                foreach ($assignedCriteria as $criterion) {
                    $criteriaMap[(int) $criterion->id] = $criterion;
                }

                $payloadScores = collect($request->input('scores'));
                $submittedCriterionIds = $payloadScores
                    ->pluck('criterion_id')
                    ->map(fn ($id) => (int) $id)
                    ->unique()
                    ->values()
                    ->toArray();

                $assignedCriterionIds = array_keys($criteriaMap);

                sort($submittedCriterionIds);
                sort($assignedCriterionIds);

                if ($submittedCriterionIds !== $assignedCriterionIds) {
                    return $this->error(
                        'Nilai harus diisi untuk semua kriteria yang ditugaskan.',
                        422
                    );
                }

                $now = now();
                $savedCount = 0;

                foreach ($payloadScores as $scoreItem) {
                    $criterionId = (int) $scoreItem['criterion_id'];
                    $scoreValue = (float) $scoreItem['score'];

                    $criterion = $criteriaMap[$criterionId] ?? null;

                    if (!$criterion) {
                        return $this->error('Juri tidak memiliki akses untuk menilai salah satu kriteria.', 403);
                    }

                    $minScore = (float) ($criterion->min_score ?? 0);
                    $maxScore = (float) ($criterion->max_score ?? 100);

                    if ($scoreValue < $minScore || $scoreValue > $maxScore) {
                        return $this->error(
                            "Nilai {$criterion->code} harus berada pada rentang {$minScore} sampai {$maxScore}.",
                            422
                        );
                    }

                    DB::table('scores')->updateOrInsert(
                        [
                            'period_id' => $periodId,
                            'candidate_id' => $candidateId,
                            'user_id' => $userId,
                            'criterion_id' => $criterionId,
                        ],
                        [
                            'score' => $scoreValue,
                            'updated_at' => $now,
                            'created_at' => $now,
                        ]
                    );

                    $savedCount++;
                }

                return $this->success([
                    'period_id' => $periodId,
                    'candidate_id' => $candidateId,
                    'saved_count' => $savedCount,
                ], 'Nilai peserta berhasil disimpan.');
            });
        } catch (Throwable $e) {
            return $this->error('Nilai peserta gagal disimpan.', 500, $e->getMessage());
        }
    }

    public function history(Request $request): JsonResponse
    {
        if ($deny = $this->juryOnly($request)) {
            return $deny;
        }

        $validator = Validator::make($request->all(), [
            'period_id' => ['required', 'integer', 'exists:election_periods,id'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validasi gagal.', 422, $validator->errors());
        }

        try {
            $userId = $request->user()->id;
            $periodId = (int) $request->period_id;

            $isPublished = (bool) DB::table('election_periods')
                ->where('id', $periodId)
                ->value('is_result_published');

            $assignedCriteriaCount = DB::table('jury_criteria')
                ->join('criteria', 'jury_criteria.criterion_id', '=', 'criteria.id')
                ->where('jury_criteria.user_id', $userId)
                ->where('jury_criteria.period_id', $periodId)
                ->where('criteria.is_active', true)
                ->count();

            $rows = DB::table('scores')
                ->join('candidates', 'scores.candidate_id', '=', 'candidates.id')
                ->where('scores.period_id', $periodId)
                ->where('scores.user_id', $userId)
                ->groupBy(
                    'candidates.id',
                    'candidates.registration_number',
                    'candidates.full_name',
                    'candidates.student_number',
                    'candidates.study_program'
                )
                ->orderByDesc(DB::raw('MAX(scores.updated_at)'))
                ->get([
                    'candidates.id',
                    'candidates.registration_number',
                    'candidates.full_name',
                    'candidates.student_number',
                    'candidates.study_program',
                    DB::raw('COUNT(DISTINCT scores.criterion_id) as scored_criteria_count'),
                    DB::raw('AVG(scores.score) as average_score'),
                    DB::raw('MAX(scores.updated_at) as last_updated_at'),
                ]);

            $historyRows = $rows->map(function ($row) use ($assignedCriteriaCount) {
                $scoredCriteriaCount = (int) $row->scored_criteria_count;

                return [
                    'candidate_id' => (int) $row->id,
                    'registration_number' => $row->registration_number,
                    'full_name' => $row->full_name,
                    'student_number' => $row->student_number,
                    'study_program' => $row->study_program,
                    'scored_criteria_count' => $scoredCriteriaCount,
                    'assigned_criteria_count' => $assignedCriteriaCount,
                    'completion_percentage' => $assignedCriteriaCount > 0
                        ? round(($scoredCriteriaCount / $assignedCriteriaCount) * 100, 2)
                        : 0,
                    'is_complete' => $assignedCriteriaCount > 0 && $scoredCriteriaCount >= $assignedCriteriaCount,
                    'average_score' => round((float) $row->average_score, 2),
                    'last_updated_at' => $row->last_updated_at,
                ];
            })->values();

            $totalScoreRecords = DB::table('scores')
                ->where('period_id', $periodId)
                ->where('user_id', $userId)
                ->count();

            return $this->success([
                'period_id' => $periodId,
                'is_result_published' => $isPublished,
                'can_edit' => !$isPublished,
                'summary' => [
                    'scored_candidate_count' => $historyRows->count(),
                    'score_records_count' => $totalScoreRecords,
                    'assigned_criteria_count' => $assignedCriteriaCount,
                    'average_score' => $historyRows->count() > 0
                        ? round($historyRows->avg('average_score'), 2)
                        : 0,
                ],
                'histories' => $historyRows,
            ], 'Riwayat penilaian berhasil diambil.');
        } catch (Throwable $e) {
            return $this->error('Riwayat penilaian gagal diambil.', 500, $e->getMessage());
        }
    }

    public function historyDetail(Request $request, string $candidate): JsonResponse
    {
        if ($deny = $this->juryOnly($request)) {
            return $deny;
        }

        $validator = Validator::make($request->all(), [
            'period_id' => ['required', 'integer', 'exists:election_periods,id'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validasi gagal.', 422, $validator->errors());
        }

        try {
            $userId = $request->user()->id;
            $periodId = (int) $request->period_id;
            $candidateId = (int) $candidate;

            $isPublished = (bool) DB::table('election_periods')
                ->where('id', $periodId)
                ->value('is_result_published');

            $candidateData = DB::table('candidates')
                ->where('id', $candidateId)
                ->where('period_id', $periodId)
                ->first([
                    'id',
                    'registration_number',
                    'full_name',
                    'student_number',
                    'study_program',
                    'status',
                ]);

            if (!$candidateData) {
                return $this->error('Peserta tidak ditemukan.', 404);
            }

            $criteriaRows = DB::table('jury_criteria')
                ->join('criteria', 'jury_criteria.criterion_id', '=', 'criteria.id')
                ->leftJoin('scores', function ($join) use ($periodId, $candidateId, $userId) {
                    $join->on('criteria.id', '=', 'scores.criterion_id')
                        ->where('scores.period_id', '=', $periodId)
                        ->where('scores.candidate_id', '=', $candidateId)
                        ->where('scores.user_id', '=', $userId);
                })
                ->where('jury_criteria.user_id', $userId)
                ->where('jury_criteria.period_id', $periodId)
                ->where('criteria.is_active', true)
                ->orderBy('criteria.code')
                ->get([
                    'criteria.id as criterion_id',
                    'criteria.code',
                    'criteria.name',
                    'criteria.type',
                    'criteria.min_score',
                    'criteria.max_score',
                    'scores.score',
                    'scores.updated_at',
                ]);

            $filledCount = $criteriaRows->filter(fn ($item) => $item->score !== null)->count();
            $criteriaCount = $criteriaRows->count();

            return $this->success([
                'period_id' => $periodId,
                'is_result_published' => $isPublished,
                'can_edit' => !$isPublished,
                'candidate' => $candidateData,
                'summary' => [
                    'criteria_count' => $criteriaCount,
                    'filled_count' => $filledCount,
                    'completion_percentage' => $criteriaCount > 0
                        ? round(($filledCount / $criteriaCount) * 100, 2)
                        : 0,
                    'average_score' => $filledCount > 0
                        ? round($criteriaRows->whereNotNull('score')->avg('score'), 2)
                        : null,
                    'is_complete' => $criteriaCount > 0 && $filledCount === $criteriaCount,
                ],
                'criteria' => $criteriaRows,
            ], 'Detail riwayat penilaian berhasil diambil.');
        } catch (Throwable $e) {
            return $this->error('Detail riwayat penilaian gagal diambil.', 500, $e->getMessage());
        }   
    }
}