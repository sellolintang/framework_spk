<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('election_periods', function (Blueprint $table) {
            $table->id();
            $table->year('election_year')->unique();
            $table->dateTime('registration_start')->nullable();
            $table->dateTime('registration_end')->nullable();
            $table->dateTime('interview_start')->nullable();
            $table->dateTime('interview_end')->nullable();
            $table->enum('status', [
                'draft',
                'registration',
                'interview',
                'scoring',
                'finished',
            ])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('election_periods');
    }
};
