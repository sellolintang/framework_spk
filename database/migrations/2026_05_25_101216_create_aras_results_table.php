<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aras_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('period_id')
                ->constrained('election_periods')
                ->cascadeOnDelete();

            $table->foreignId('candidate_id')
                ->constrained('candidates')
                ->cascadeOnDelete();

            $table->decimal('total_score', 12, 6);
            $table->decimal('utility_score', 12, 6);
            $table->unsignedInteger('final_rank');

            $table->foreignId('calculated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->dateTime('calculated_at')->useCurrent();

            $table->timestamps();

            $table->unique(['period_id', 'candidate_id']);
            $table->index(['period_id', 'final_rank']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aras_results');
    }
};
