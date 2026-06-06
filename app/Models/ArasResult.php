<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArasResult extends Model
{
    protected $fillable = [
        'period_id',
        'candidate_id',
        'total_score',
        'utility_score',
        'final_rank',
        'calculated_by',
        'calculated_at',
    ];

    protected $casts = [
        'period_id' => 'integer',
        'candidate_id' => 'integer',
        'total_score' => 'decimal:6',
        'utility_score' => 'decimal:6',
        'final_rank' => 'integer',
        'calculated_by' => 'integer',
        'calculated_at' => 'datetime',
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(ElectionPeriod::class, 'period_id');
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }

    public function calculator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'calculated_by');
    }
}