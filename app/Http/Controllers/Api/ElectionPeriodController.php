<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ElectionPeriod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ElectionPeriodController extends Controller
{
    public function index(Request $request)
    {
        $query = ElectionPeriod::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('election_year')) {
            $query->where('election_year', $request->election_year);
        }

        $periods = $query
            ->orderByDesc('election_year')
            ->paginate($request->get('per_page', 10));

        return response()->json([
            'message' => 'Data periode pemilihan berhasil diambil.',
            'data' => $periods,
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'election_year' => [
                'required',
                'integer',
                'digits:4',
                'unique:election_periods,election_year',
            ],
            'registration_start' => ['nullable', 'date'],
            'registration_end' => ['nullable', 'date', 'after_or_equal:registration_start'],
            'interview_start' => ['nullable', 'date'],
            'interview_end' => ['nullable', 'date', 'after_or_equal:interview_start'],
            'status' => [
                'required',
                Rule::in([
                    'draft',
                    'registration',
                    'interview',
                    'scoring',
                    'finished',
                ]),
            ],
        ]);

        $period = ElectionPeriod::create($validated);

        return response()->json([
            'message' => 'Periode pemilihan berhasil dibuat.',
            'data' => $period,
        ], 201);
    }

    public function show(ElectionPeriod $period)
    {
        return response()->json([
            'message' => 'Detail periode pemilihan berhasil diambil.',
            'data' => $period,
        ], 200);
    }

    public function update(Request $request, ElectionPeriod $period)
    {
        $validated = $request->validate([
            'election_year' => [
                'sometimes',
                'required',
                'integer',
                'digits:4',
                Rule::unique('election_periods', 'election_year')->ignore($period->id),
            ],
            'registration_start' => ['nullable', 'date'],
            'registration_end' => ['nullable', 'date', 'after_or_equal:registration_start'],
            'interview_start' => ['nullable', 'date'],
            'interview_end' => ['nullable', 'date', 'after_or_equal:interview_start'],
            'status' => [
                'sometimes',
                'required',
                Rule::in([
                    'draft',
                    'registration',
                    'interview',
                    'scoring',
                    'finished',
                ]),
            ],
        ]);

        $period->update($validated);

        return response()->json([
            'message' => 'Periode pemilihan berhasil diperbarui.',
            'data' => $period->fresh(),
        ], 200);
    }

    public function destroy(ElectionPeriod $period)
    {
        $period->delete();

        return response()->json([
            'message' => 'Periode pemilihan berhasil dihapus.',
        ], 200);
    }
}
