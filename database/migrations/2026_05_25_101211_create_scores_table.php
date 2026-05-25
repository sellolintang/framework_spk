<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->id();

            $table->foreignId('period_id')
                ->constrained('election_periods')
                ->cascadeOnDelete();

            $table->foreignId('candidate_id')
                ->constrained('candidates')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('criterion_id')
                ->constrained('criteria')
                ->cascadeOnDelete();

            $table->decimal('score', 8, 2);

            $table->timestamps();

            $table->unique(['candidate_id', 'user_id', 'criterion_id']);
            $table->index(['period_id', 'candidate_id']);
            $table->index(['period_id', 'criterion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
