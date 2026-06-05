<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\ElectionPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Mail\CandidateRegistrationSuccess;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\CandidateAcceptedMail;
use App\Mail\CandidateRejectedMail;

class CandidateController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $period = ElectionPeriod::where('status', 'registration')
            ->latest('election_year')
            ->first();

        if (!$period) {
            return response()->json([
                'message' => 'Pendaftaran belum dibuka.',
                'data' => null,
            ], 400);
        }

        if ($period->registration_start && now()->lt($period->registration_start)) {
            return response()->json([
                'message' => 'Pendaftaran belum dimulai.',
                'data' => null,
            ], 400);
        }

        if ($period->registration_end && now()->gt($period->registration_end)) {
            return response()->json([
                'message' => 'Pendaftaran sudah ditutup.',
                'data' => null,
            ], 400);
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:150'],
            'student_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('candidates', 'student_number')
                    ->where('period_id', $period->id),
            ],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'faculty' => ['nullable', 'string', 'max:150'],
            'study_program' => ['nullable', 'string', 'max:150'],
            'semester' => ['nullable', 'integer', 'min:1', 'max:14'],
            'vision' => ['nullable', 'string'],
            'mission' => ['nullable', 'string'],
            'photo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'cv_file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        $photoPath = null;
        $cvPath = null;

        try {
            if ($request->hasFile('photo_file')) {
                $photoPath = $request->file('photo_file')->store('candidates/photos', 'public');
            }

            if ($request->hasFile('cv_file')) {
                $cvPath = $request->file('cv_file')->store('candidates/cv', 'public');
            }

            $candidate = DB::transaction(function () use ($validated, $period, $photoPath, $cvPath) {
                $registrationNumber = $this->generateRegistrationNumber($period);

                return Candidate::create([
                    'period_id' => $period->id,
                    'registration_number' => $registrationNumber,
                    'full_name' => $validated['full_name'],
                    'student_number' => $validated['student_number'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'] ?? null,
                    'faculty' => $validated['faculty'] ?? null,
                    'study_program' => $validated['study_program'] ?? null,
                    'semester' => $validated['semester'] ?? null,
                    'vision' => $validated['vision'] ?? null,
                    'mission' => $validated['mission'] ?? null,
                    'photo_file' => $photoPath,
                    'cv_file' => $cvPath,
                    'status' => 'pending',
                ]);
            });

            $emailSent = true;
            try {
                Mail::to($candidate->email)->send(new CandidateRegistrationSuccess($candidate));
            } catch (\Throwable $mailException) {
                $emailSent = false;

                Log::warning('Email pendaftaran gagal dikirim.', [
                    'candidate_id' => $candidate->id,
                    'email' => $candidate->email,
                    'error' => $mailException->getMessage(),
                ]);
            }

            return response()->json([
                'message' => 'Pendaftaran berhasil disimpan.',
                'data' => [
                    'candidate' => $candidate,
                    'registration_number' => $candidate->registration_number,
                    'email_sent' => $emailSent,
                ],
            ], 201);
        } catch (\Throwable $e) {
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }

            if ($cvPath) {
                Storage::disk('public')->delete($cvPath);
            }

            return response()->json([
                'message' => 'Pendaftaran gagal disimpan.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function index(Request $request): JsonResponse
    {
        if ($forbidden = $this->ensureAdmin()) {
            return $forbidden;
        }

        $perPage = min(max((int) $request->get('per_page', 10), 1), 100);

        $candidates = Candidate::query()
            ->with(['period', 'validator'])
            ->when($request->filled('period_id'), function ($query) use ($request) {
                $query->where('period_id', $request->period_id);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = $request->keyword;

                $query->where(function ($q) use ($keyword) {
                    $q->where('registration_number', 'like', "%{$keyword}%")
                        ->orWhere('full_name', 'like', "%{$keyword}%")
                        ->orWhere('student_number', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%")
                        ->orWhere('faculty', 'like', "%{$keyword}%")
                        ->orWhere('study_program', 'like', "%{$keyword}%");
                });
            })
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'message' => 'Data calon berhasil diambil.',
            'data' => $candidates,
        ]);
    }

    public function show(Candidate $candidate): JsonResponse
    {
        if ($forbidden = $this->ensureAdmin()) {
            return $forbidden;
        }

        $candidate->load([
            'period',
            'validator',
            'interview',
            'scores.criterion',
            'scores.jury',
            'arasResult',
        ]);

        return response()->json([
            'message' => 'Detail calon berhasil diambil.',
            'data' => [
                'candidate' => $candidate,
            ],
        ]);
    }

    public function update(Request $request, Candidate $candidate): JsonResponse
    {
        if ($forbidden = $this->ensureAdmin()) {
            return $forbidden;
        }

        $validated = $request->validate([
            'full_name' => ['sometimes', 'required', 'string', 'max:150'],
            'student_number' => [
                'sometimes',
                'required',
                'string',
                'max:50',
                Rule::unique('candidates', 'student_number')
                    ->where('period_id', $candidate->period_id)
                    ->ignore($candidate->id),
            ],
            'email' => ['sometimes', 'required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'faculty' => ['nullable', 'string', 'max:150'],
            'study_program' => ['nullable', 'string', 'max:150'],
            'semester' => ['nullable', 'integer', 'min:1', 'max:14'],
            'vision' => ['nullable', 'string'],
            'mission' => ['nullable', 'string'],
            'photo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'cv_file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        $oldPhoto = $candidate->photo_file;
        $oldCv = $candidate->cv_file;

        $newPhoto = null;
        $newCv = null;

        try {
            if ($request->hasFile('photo_file')) {
                $newPhoto = $request->file('photo_file')->store('candidates/photos', 'public');
                $validated['photo_file'] = $newPhoto;
            }

            if ($request->hasFile('cv_file')) {
                $newCv = $request->file('cv_file')->store('candidates/cv', 'public');
                $validated['cv_file'] = $newCv;
            }

            $candidate->update($validated);

            if ($newPhoto && $oldPhoto) {
                Storage::disk('public')->delete($oldPhoto);
            }

            if ($newCv && $oldCv) {
                Storage::disk('public')->delete($oldCv);
            }

            return response()->json([
                'message' => 'Data calon berhasil diperbarui.',
                'data' => [
                    'candidate' => $candidate->fresh(['period', 'validator']),
                ],
            ]);
        } catch (\Throwable $e) {
            if ($newPhoto) {
                Storage::disk('public')->delete($newPhoto);
            }

            if ($newCv) {
                Storage::disk('public')->delete($newCv);
            }

            return response()->json([
                'message' => 'Data calon gagal diperbarui.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function destroy(Candidate $candidate): JsonResponse
    {
        if ($forbidden = $this->ensureAdmin()) {
            return $forbidden;
        }

        if ($candidate->photo_file) {
            Storage::disk('public')->delete($candidate->photo_file);
        }

        if ($candidate->cv_file) {
            Storage::disk('public')->delete($candidate->cv_file);
        }

        $candidate->delete();

        return response()->json([
            'message' => 'Data calon berhasil dihapus.',
            'data' => null,
        ]);
    }

    public function validateCandidate(Candidate $candidate): JsonResponse
    {
        if ($forbidden = $this->ensureAdmin()) {
            return $forbidden;
        }

        if ($candidate->status !== 'pending') {
            return response()->json([
                'message' => 'Hanya calon dengan status pending yang dapat divalidasi.',
                'data' => null,
            ], 400);
        }

        $candidate->update([
            'status' => 'valid',
            'validated_by' => auth()->id(),
            'validated_at' => now(),
            'rejection_reason' => null,
        ]);

        $candidate = $candidate->fresh(['period', 'validator']);

        $emailSent = true;

        try {
            Mail::to($candidate->email)->send(new CandidateAcceptedMail($candidate));
        } catch (\Throwable $mailException) {
            $emailSent = false;

            Log::warning('Email penerimaan calon gagal dikirim.', [
                'candidate_id' => $candidate->id,
                'email' => $candidate->email,
                'error' => $mailException->getMessage(),
            ]);
        }

        return response()->json([
            'message' => 'Calon berhasil divalidasi.',
            'data' => [
                'candidate' => $candidate,
                'email_sent' => $emailSent,
            ],
        ]);
    }

    public function rejectCandidate(Request $request, Candidate $candidate): JsonResponse
    {
        if ($forbidden = $this->ensureAdmin()) {
            return $forbidden;
        }

        if ($candidate->status !== 'pending') {
            return response()->json([
                'message' => 'Hanya calon dengan status pending yang dapat ditolak.',
                'data' => null,
            ], 400);
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string'],
        ]);

        $candidate->update([
            'status' => 'invalid',
            'validated_by' => auth()->id(),
            'validated_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        $candidate = $candidate->fresh(['period', 'validator']);

        $emailSent = true;

        try {
            Mail::to($candidate->email)->send(new CandidateRejectedMail($candidate));
        } catch (\Throwable $mailException) {
            $emailSent = false;

            Log::warning('Email penolakan calon gagal dikirim.', [
                'candidate_id' => $candidate->id,
                'email' => $candidate->email,
                'error' => $mailException->getMessage(),
            ]);
        }

        return response()->json([
            'message' => 'Calon berhasil ditolak.',
            'data' => [
                'candidate' => $candidate,
                'email_sent' => $emailSent,
            ],
        ]);
    }

    private function generateRegistrationNumber(ElectionPeriod $period): string
    {
        $latestCandidate = Candidate::where('period_id', $period->id)
            ->where('registration_number', 'like', 'DK-' . $period->election_year . '-%')
            ->lockForUpdate()
            ->orderByDesc('id')
            ->first();

        $nextNumber = 1;

        if ($latestCandidate) {
            $lastNumber = (int) substr($latestCandidate->registration_number, -3);
            $nextNumber = $lastNumber + 1;
        }

        return 'DK-' . $period->election_year . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    private function ensureAdmin(): ?JsonResponse
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Anda tidak memiliki hak akses untuk fitur ini.',
                'data' => null,
            ], 403);
        }

        return null;
    }
}
