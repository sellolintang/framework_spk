<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElectionPeriod extends Model
{
    protected $fillable = [
        'election_year',
        'registration_start',
        'registration_end',
        'interview_start',
        'interview_end',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'election_year' => 'integer',
            'registration_start' => 'datetime',
            'registration_end' => 'datetime',
            'interview_start' => 'datetime',
            'interview_end' => 'datetime',
        ];
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class, 'period_id');
    }

    public function criteria()
    {
        return $this->hasMany(Criterion::class, 'period_id');
    }

    public function juryCriteria()
    {
        return $this->hasMany(JuryCriterion::class, 'period_id');
    }

    public function interviews()
    {
        return $this->hasMany(Interview::class, 'period_id');
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'period_id');
    }

    public function arasResults()
    {
        return $this->hasMany(ArasResult::class, 'period_id');
    }
}
