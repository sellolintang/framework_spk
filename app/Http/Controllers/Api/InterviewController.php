<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Interview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Throwable;
use App\Mail\InterviewScheduledMail;
use Illuminate\Support\Facades\Mail;


class InterviewController extends Controller
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
            return $this->error('Akses ditolak. Hanya admin yang dapat melakukan aksi ini.', 403);
        }

        return null;
    }

    private function baseQuery()
    {
        return Interview::query()
            ->from('interviews')
            ->join('candidates', 'interviews.candidate_id', '=', 'candidates.id')
            ->join('election_periods', 'interviews.period_id', '=', 'election_periods.id')
            ->leftJoin('users as creators', 'interviews.created_by', '=', 'creators.id')
            ->select(
                'interviews.id',
                'interviews.period_id',
                'interviews.candidate_id',
                'interviews.scheduled_at',
                'interviews.location',
                'interviews.status',
                'interviews.created_by',
                'interviews.created_at',
                'interviews.updated_at',
                'candidates.registration_number',
                'candidates.full_name',
                'candidates.student_number',
                'candidates.email',
                'candidates.phone',
                'candidates.faculty',
                'candidates.study_program',
                'candidates.semester',
                'candidates.status as candidate_status',
                'election_periods.election_year',
                'creators.name as created_by_name'
            );
    }

    private function sendInterviewScheduledMail(object $interview): void
    {
        if (empty($interview->email)) {
            return;
        }

        try {
            Mail::to($interview->email)->send(new InterviewScheduledMail($interview));
        } catch (Throwable $e) {
            report($e);
        }
    }

    private function candidateStatusFromInterviewStatus(string $status, string $fallback = 'valid'): string
    {
        return match ($status) {
            'scheduled' => 'interview_scheduled',
            'completed' => 'interviewed',
            'absent', 'cancelled' => 'valid',
            default => $fallback,
        };
    }

    public function generate(Request $request): JsonResponse
    {
        if ($deny = $this->adminOnly($request)) {
            return $deny;
        }

        $validator = Validator::make($request->all(), [
            'period_id' => ['required', 'integer', 'exists:election_periods,id'],
            'interview_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'duration_minutes' => ['required', 'integer', 'min:5', 'max:120'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validasi gagal.', 422, $validator->errors());
        }

        try {
            return DB::transaction(function () use ($request) {
                $periodId = (int) $request->period_id;
                $durationMinutes = (int) $request->duration_minutes;

                $startDateTime = Carbon::createFromFormat(
                    'Y-m-d H:i',
                    $request->interview_date . ' ' . $request->start_time
                );

                $scheduledCandidateIds = DB::table('interviews')
                    ->where('period_id', $periodId)
                    ->pluck('candidate_id')
                    ->map(fn ($id) => (int) $id)
                    ->toArray();

                $candidates = DB::table('candidates')
                    ->where('period_id', $periodId)
                    ->where('status', 'valid')
                    ->when(!empty($scheduledCandidateIds), function ($query) use ($scheduledCandidateIds) {
                        $query->whereNotIn('id', $scheduledCandidateIds);
                    })
                    ->orderBy('registration_number')
                    ->orderBy('created_at')
                    ->get();

                if ($candidates->isEmpty()) {
                    return $this->error('Tidak ada calon valid yang belum memiliki jadwal wawancara.', 422);
                }

                $now = now();
                $rows = [];

                foreach ($candidates as $index => $candidate) {
                    $scheduledAt = $startDateTime->copy()->addMinutes($index * $durationMinutes);

                    $rows[] = [
                        'period_id' => $periodId,
                        'candidate_id' => $candidate->id,
                        'scheduled_at' => $scheduledAt,
                        'location' => $request->input('location'),
                        'status' => 'scheduled',
                        'created_by' => $request->user()->id,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                DB::table('interviews')->insert($rows);

                DB::table('candidates')
                    ->whereIn('id', $candidates->pluck('id')->toArray())
                    ->update([
                        'status' => 'interview_scheduled',
                        'updated_at' => $now,
                    ]);
                
                $createdInterviews = $this->baseQuery()
                    ->where('interviews.period_id', $periodId)
                    ->whereIn('interviews.candidate_id', $candidates->pluck('id')->toArray())
                    ->where('interviews.status', 'scheduled')
                    ->orderBy('interviews.scheduled_at')
                    ->get();

                foreach ($createdInterviews as $createdInterview) {
                    $this->sendInterviewScheduledMail($createdInterview);
                }

                return $this->success([
                    'generated_count' => count($rows),
                    'start_at' => $startDateTime->format('Y-m-d H:i:s'),
                    'duration_minutes' => $durationMinutes,
                    'location' => $request->input('location'),
                ], 'Jadwal wawancara berhasil dibuat otomatis.', 201);
            });
        } catch (Throwable $e) {
            return $this->error('Generate jadwal wawancara gagal.', 500, $e->getMessage());
        }
    }

    public function reset(Request $request): JsonResponse
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

                $interviews = DB::table('interviews')
                    ->where('period_id', $periodId)
                    ->get(['id', 'candidate_id']);

                if ($interviews->isEmpty()) {
                    return $this->success([
                        'deleted_count' => 0,
                        'restored_candidates_count' => 0,
                    ], 'Tidak ada jadwal wawancara yang perlu direset.');
                }

                $candidateIds = $interviews
                    ->pluck('candidate_id')
                    ->unique()
                    ->values()
                    ->toArray();

                $deletedCount = DB::table('interviews')
                    ->where('period_id', $periodId)
                    ->delete();

                $restoredCandidatesCount = DB::table('candidates')
                    ->whereIn('id', $candidateIds)
                    ->whereIn('status', ['interview_scheduled', 'interviewed'])
                    ->update([
                        'status' => 'valid',
                        'updated_at' => now(),
                    ]);

                return $this->success([
                    'deleted_count' => $deletedCount,
                    'restored_candidates_count' => $restoredCandidatesCount,
                ], 'Jadwal wawancara berhasil direset.');
            });
        } catch (Throwable $e) {
            return $this->error('Reset jadwal wawancara gagal.', 500, $e->getMessage());
        }
    }

    public function index(Request $request): JsonResponse
    {
        if ($deny = $this->adminOnly($request)) {
            return $deny;
        }

        $validator = Validator::make($request->all(), [
            'period_id' => ['nullable', 'integer', 'exists:election_periods,id'],
            'status' => ['nullable', Rule::in(['scheduled', 'completed', 'absent', 'cancelled'])],
            'date' => ['nullable', 'date'],
            'search' => ['nullable', 'string', 'max:100'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validasi gagal.', 422, $validator->errors());
        }

        $query = $this->baseQuery();

        if ($request->filled('period_id')) {
            $query->where('interviews.period_id', $request->period_id);
        }

        if ($request->filled('status')) {
            $query->where('interviews.status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('interviews.scheduled_at', $request->date);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('candidates.full_name', 'like', "%{$search}%")
                    ->orWhere('candidates.student_number', 'like', "%{$search}%")
                    ->orWhere('candidates.registration_number', 'like', "%{$search}%")
                    ->orWhere('candidates.study_program', 'like', "%{$search}%");
            });
        }

        $interviews = $query
            ->orderBy('interviews.scheduled_at')
            ->paginate($request->integer('per_page', 10));

        return $this->success($interviews, 'Data jadwal wawancara berhasil diambil.');
    }

    public function store(Request $request): JsonResponse
    {
        if ($deny = $this->adminOnly($request)) {
            return $deny;
        }

        $validator = Validator::make($request->all(), [
            'candidate_id' => [
                'required',
                'integer',
                'exists:candidates,id',
                Rule::unique('interviews', 'candidate_id'),
            ],
            'scheduled_at' => ['required', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(['scheduled', 'completed', 'absent', 'cancelled'])],
        ]);

        if ($validator->fails()) {
            return $this->error('Validasi gagal.', 422, $validator->errors());
        }

        $candidate = DB::table('candidates')
            ->where('id', $request->candidate_id)
            ->first();

        if (!$candidate) {
            return $this->error('Calon tidak ditemukan.', 404);
        }

        if ($candidate->status !== 'valid') {
            return $this->error('Calon harus berstatus valid sebelum dijadwalkan wawancara.', 422);
        }

        try {
            $status = $request->input('status', 'scheduled');

            $interview = Interview::create([
                'period_id' => $candidate->period_id,
                'candidate_id' => $candidate->id,
                'scheduled_at' => $request->scheduled_at,
                'location' => $request->input('location'),
                'status' => $status,
                'created_by' => $request->user()->id,
            ]);

            DB::table('candidates')
                ->where('id', $candidate->id)
                ->update([
                    'status' => $this->candidateStatusFromInterviewStatus($status, $candidate->status),
                    'updated_at' => now(),
                ]);

            $data = $this->baseQuery()
                ->where('interviews.id', $interview->id)
                ->first();

            if ($data && $data->status === 'scheduled') {
                $this->sendInterviewScheduledMail($data);
            }

            return $this->success($data, 'Jadwal wawancara berhasil dibuat.', 201);
        } catch (Throwable $e) {
            return $this->error('Jadwal wawancara gagal dibuat.', 500, $e->getMessage());
        }
    }

    public function show(Request $request, string $id): JsonResponse
    {
        if ($deny = $this->adminOnly($request)) {
            return $deny;
        }

        $interview = $this->baseQuery()
            ->where('interviews.id', $id)
            ->first();

        if (!$interview) {
            return $this->error('Jadwal wawancara tidak ditemukan.', 404);
        }

        return $this->success($interview, 'Detail jadwal wawancara berhasil diambil.');
    }

    public function update(Request $request, string $id): JsonResponse
    {
        if ($deny = $this->adminOnly($request)) {
            return $deny;
        }

        $interview = Interview::find($id);

        if (!$interview) {
            return $this->error('Jadwal wawancara tidak ditemukan.', 404);
        }

        $validator = Validator::make($request->all(), [
            'candidate_id' => [
                'sometimes',
                'required',
                'integer',
                'exists:candidates,id',
                Rule::unique('interviews', 'candidate_id')->ignore($interview->id),
            ],
            'scheduled_at' => ['sometimes', 'required', 'date'],
            'location' => ['sometimes', 'nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'required', Rule::in(['scheduled', 'completed', 'absent', 'cancelled'])],
        ]);

        if ($validator->fails()) {
            return $this->error('Validasi gagal.', 422, $validator->errors());
        }

        try {
            $candidateId = $request->input('candidate_id', $interview->candidate_id);
            $periodId = $interview->period_id;

            if ((int) $candidateId !== (int) $interview->candidate_id) {
                $newCandidate = DB::table('candidates')
                    ->where('id', $candidateId)
                    ->first();

                if (!$newCandidate) {
                    return $this->error('Calon pengganti tidak ditemukan.', 404);
                }

                if ($newCandidate->status !== 'valid') {
                    return $this->error('Calon pengganti harus berstatus valid.', 422);
                }

                $oldCandidate = DB::table('candidates')
                    ->where('id', $interview->candidate_id)
                    ->first();

                if ($oldCandidate && in_array($oldCandidate->status, ['interview_scheduled', 'interviewed'], true)) {
                    DB::table('candidates')
                        ->where('id', $oldCandidate->id)
                        ->update([
                            'status' => 'valid',
                            'updated_at' => now(),
                        ]);
                }

                $periodId = $newCandidate->period_id;
            }

            $status = $request->input('status', $interview->status);

            $interview->update([
                'period_id' => $periodId,
                'candidate_id' => $candidateId,
                'scheduled_at' => $request->input('scheduled_at', $interview->scheduled_at),
                'location' => $request->has('location')
                    ? $request->input('location')
                    : $interview->location,
                'status' => $status,
            ]);

            $candidate = DB::table('candidates')
                ->where('id', $candidateId)
                ->first();

            if ($candidate) {
                DB::table('candidates')
                    ->where('id', $candidateId)
                    ->update([
                        'status' => $this->candidateStatusFromInterviewStatus($status, $candidate->status),
                        'updated_at' => now(),
                    ]);
            }

            $data = $this->baseQuery()
                ->where('interviews.id', $interview->id)
                ->first();

            return $this->success($data, 'Jadwal wawancara berhasil diperbarui.');
        } catch (Throwable $e) {
            return $this->error('Jadwal wawancara gagal diperbarui.', 500, $e->getMessage());
        }
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        if ($deny = $this->adminOnly($request)) {
            return $deny;
        }

        $interview = Interview::find($id);

        if (!$interview) {
            return $this->error('Jadwal wawancara tidak ditemukan.', 404);
        }

        try {
            $candidate = DB::table('candidates')
                ->where('id', $interview->candidate_id)
                ->first();

            $interview->delete();

            if ($candidate && in_array($candidate->status, ['interview_scheduled', 'interviewed'], true)) {
                DB::table('candidates')
                    ->where('id', $candidate->id)
                    ->update([
                        'status' => 'valid',
                        'updated_at' => now(),
                    ]);
            }

            return $this->success(null, 'Jadwal wawancara berhasil dihapus.');
        } catch (Throwable $e) {
            return $this->error('Jadwal wawancara gagal dihapus.', 500, $e->getMessage());
        }
    }
}