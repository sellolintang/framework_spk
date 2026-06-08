<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\Criterion;
use App\Models\ElectionPeriod;
use App\Models\Interview;
use App\Models\JuryCriterion;
use App\Models\Score;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            //DummyDataSeeder::class,
            FinalDataSeeder::class,
        ]);
    }
}
