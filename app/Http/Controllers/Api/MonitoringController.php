<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class MonitoringController extends Controller
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
            return $this->error('Akses ditolak. Hanya admin yang dapat mengakses monitoring penilaian.', 403);
        }

        return null;
    }

    public function scores(Request $request): JsonResponse
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
            $periodId = (int) $request->period_id;

            $criteria = DB::table('criteria')
                ->where('period_id', $periodId)
                ->where('is_active', true)
                ->orderBy('code')
                ->get([
                    'id',
                    'code',
                    'name',
                    'weight',
                    'type',
                    'min_score',
                    'max_score',
                    'is_active',
                ]);

            $candidates = DB::table('candidates')
                ->where('period_id', $periodId)
                ->whereNotIn('status', ['pending', 'invalid'])
                ->orderBy('registration_number')
                ->orderBy('created_at')
                ->get([
                    'id',
                    'registration_number',
                    'full_name',
                    'student_number',
                    'study_program',
                    'status',
                ]);

            $scores = DB::table('scores')
                ->select(
                    'candidate_id',
                    'criterion_id',
                    DB::raw('COUNT(*) as score_count'),
                    DB::raw('AVG(score) as average_score')
                )
                ->where('period_id', $periodId)
                ->groupBy('candidate_id', 'criterion_id')
                ->get();

            $scoreMap = [];

            foreach ($scores as $score) {
                $scoreMap[(int) $score->candidate_id][(int) $score->criterion_id] = [
                    'score_count' => (int) $score->score_count,
                    'average_score' => round((float) $score->average_score, 3),
                ];
            }

            $criteriaCount = $criteria->count();
            $candidateRows = [];

            foreach ($candidates as $candidate) {
                $candidateId = (int) $candidate->id;
                $scoredCriteriaCount = 0;
                $missingCriteria = [];
                $averageValues = [];

                foreach ($criteria as $criterion) {
                    $criterionId = (int) $criterion->id;

                    if (isset($scoreMap[$candidateId][$criterionId])) {
                        $scoredCriteriaCount++;
                        $averageValues[] = $scoreMap[$candidateId][$criterionId]['average_score'];
                    } else {
                        $missingCriteria[] = [
                            'criterion_id' => $criterionId,
                            'criterion_code' => $criterion->code,
                            'criterion_name' => $criterion->name,
                        ];
                    }
                }

                $completionPercentage = $criteriaCount > 0
                    ? round(($scoredCriteriaCount / $criteriaCount) * 100, 2)
                    : 0;

                $candidateRows[] = [
                    'id' => $candidateId,
                    'registration_number' => $candidate->registration_number,
                    'full_name' => $candidate->full_name,
                    'student_number' => $candidate->student_number,
                    'study_program' => $candidate->study_program,
                    'status' => $candidate->status,
                    'scored_criteria_count' => $scoredCriteriaCount,
                    'criteria_count' => $criteriaCount,
                    'completion_percentage' => $completionPercentage,
                    'is_complete' => $criteriaCount > 0 && $scoredCriteriaCount === $criteriaCount,
                    'average_score' => count($averageValues)
                        ? round(array_sum($averageValues) / count($averageValues), 3)
                        : null,
                    'missing_criteria' => $missingCriteria,
                ];
            }

            $criteriaRows = [];

            foreach ($criteria as $criterion) {
                $criterionId = (int) $criterion->id;
                $scoredCandidateCount = 0;

                foreach ($candidates as $candidate) {
                    if (isset($scoreMap[(int) $candidate->id][$criterionId])) {
                        $scoredCandidateCount++;
                    }
                }

                $candidateCount = $candidates->count();
                $completionPercentage = $candidateCount > 0
                    ? round(($scoredCandidateCount / $candidateCount) * 100, 2)
                    : 0;

                $criteriaRows[] = [
                    'id' => $criterionId,
                    'code' => $criterion->code,
                    'name' => $criterion->name,
                    'weight' => $criterion->weight,
                    'type' => $criterion->type,
                    'scored_candidate_count' => $scoredCandidateCount,
                    'candidate_count' => $candidateCount,
                    'missing_candidate_count' => max($candidateCount - $scoredCandidateCount, 0),
                    'completion_percentage' => $completionPercentage,
                ];
            }

            $juryRows = DB::table('users')
                ->leftJoin('jury_criteria', function ($join) use ($periodId) {
                    $join->on('users.id', '=', 'jury_criteria.user_id')
                        ->where('jury_criteria.period_id', '=', $periodId);
                })
                ->leftJoin('scores', function ($join) use ($periodId) {
                    $join->on('users.id', '=', 'scores.user_id')
                        ->where('scores.period_id', '=', $periodId);
                })
                ->where('users.role', 'juri')
                ->groupBy('users.id', 'users.name', 'users.email', 'users.phone', 'users.is_active')
                ->orderBy('users.name')
                ->get([
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.phone',
                    'users.is_active',
                    DB::raw('COUNT(DISTINCT jury_criteria.criterion_id) as assigned_criteria_count'),
                    DB::raw('COUNT(DISTINCT scores.id) as score_count'),
                ]);

            $totalCandidates = count($candidateRows);
            $completeCandidates = collect($candidateRows)->where('is_complete', true)->count();
            $incompleteCandidates = max($totalCandidates - $completeCandidates, 0);
            $scoreRecordsCount = DB::table('scores')
                ->where('period_id', $periodId)
                ->count();

            return $this->success([
                'period_id' => $periodId,
                'summary' => [
                    'total_candidates' => $totalCandidates,
                    'complete_candidates' => $completeCandidates,
                    'incomplete_candidates' => $incompleteCandidates,
                    'criteria_count' => $criteriaCount,
                    'juries_count' => $juryRows->count(),
                    'score_records_count' => $scoreRecordsCount,
                    'completion_percentage' => $totalCandidates > 0
                        ? round(($completeCandidates / $totalCandidates) * 100, 2)
                        : 0,
                ],
                'candidates' => $candidateRows,
                'criteria' => $criteriaRows,
                'juries' => $juryRows,
            ], 'Data monitoring penilaian berhasil diambil.');
        } catch (Throwable $e) {
            return $this->error('Data monitoring penilaian gagal diambil.', 500, $e->getMessage());
        }
    }
}