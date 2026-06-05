<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class JuryDashboardController extends Controller
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

    public function summary(Request $request): JsonResponse
    {
        if (!$request->user() || $request->user()->role !== 'juri') {
            return $this->error('Akses ditolak. Hanya juri yang dapat mengakses dashboard ini.', 403);
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

            $criteriaIds = $assignedCriteria->pluck('id')->map(fn ($id) => (int) $id)->toArray();
            $assignedCriteriaCount = count($criteriaIds);

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

            $scoreRows = collect();

            if (!empty($criteriaIds)) {
                $scoreRows = DB::table('scores')
                    ->select(
                        'candidate_id',
                        DB::raw('COUNT(DISTINCT criterion_id) as scored_criteria_count'),
                        DB::raw('COUNT(*) as score_count'),
                        DB::raw('AVG(score) as average_score')
                    )
                    ->where('period_id', $periodId)
                    ->where('user_id', $userId)
                    ->whereIn('criterion_id', $criteriaIds)
                    ->groupBy('candidate_id')
                    ->get();
            }

            $scoreMap = [];

            foreach ($scoreRows as $row) {
                $scoreMap[(int) $row->candidate_id] = [
                    'scored_criteria_count' => (int) $row->scored_criteria_count,
                    'score_count' => (int) $row->score_count,
                    'average_score' => round((float) $row->average_score, 2),
                ];
            }

            $candidateRows = [];
            $completedCandidates = 0;

            foreach ($candidates as $candidate) {
                $candidateId = (int) $candidate->id;
                $scoreInfo = $scoreMap[$candidateId] ?? [
                    'scored_criteria_count' => 0,
                    'score_count' => 0,
                    'average_score' => null,
                ];

                $scoredCriteriaCount = $scoreInfo['scored_criteria_count'];
                $isComplete = $assignedCriteriaCount > 0 && $scoredCriteriaCount >= $assignedCriteriaCount;

                if ($isComplete) {
                    $completedCandidates++;
                }

                $candidateRows[] = [
                    'id' => $candidateId,
                    'registration_number' => $candidate->registration_number,
                    'full_name' => $candidate->full_name,
                    'student_number' => $candidate->student_number,
                    'study_program' => $candidate->study_program,
                    'status' => $candidate->status,
                    'scored_criteria_count' => $scoredCriteriaCount,
                    'assigned_criteria_count' => $assignedCriteriaCount,
                    'completion_percentage' => $assignedCriteriaCount > 0
                        ? round(($scoredCriteriaCount / $assignedCriteriaCount) * 100, 2)
                        : 0,
                    'is_complete' => $isComplete,
                    'average_score' => $scoreInfo['average_score'],
                ];
            }

            $recentScores = collect();

            if (!empty($criteriaIds)) {
                $recentScores = DB::table('scores')
                    ->join('candidates', 'scores.candidate_id', '=', 'candidates.id')
                    ->join('criteria', 'scores.criterion_id', '=', 'criteria.id')
                    ->where('scores.period_id', $periodId)
                    ->where('scores.user_id', $userId)
                    ->whereIn('scores.criterion_id', $criteriaIds)
                    ->orderByDesc('scores.updated_at')
                    ->limit(5)
                    ->get([
                        'scores.id',
                        'scores.score',
                        'scores.updated_at',
                        'candidates.full_name as candidate_name',
                        'criteria.code as criterion_code',
                        'criteria.name as criterion_name',
                    ]);
            }

            $totalCandidates = count($candidateRows);
            $incompleteCandidates = max($totalCandidates - $completedCandidates, 0);

            $totalScoreRecords = DB::table('scores')
                ->where('period_id', $periodId)
                ->where('user_id', $userId)
                ->count();

            return $this->success([
                'period_id' => $periodId,
                'summary' => [
                    'assigned_criteria_count' => $assignedCriteriaCount,
                    'eligible_candidate_count' => $totalCandidates,
                    'completed_candidate_count' => $completedCandidates,
                    'incomplete_candidate_count' => $incompleteCandidates,
                    'score_records_count' => $totalScoreRecords,
                    'completion_percentage' => $totalCandidates > 0
                        ? round(($completedCandidates / $totalCandidates) * 100, 2)
                        : 0,
                ],
                'assigned_criteria' => $assignedCriteria,
                'candidates' => $candidateRows,
                'recent_scores' => $recentScores,
            ], 'Dashboard juri berhasil diambil.');
        } catch (Throwable $e) {
            return $this->error('Dashboard juri gagal diambil.', 500, $e->getMessage());
        }
    }
}