<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('period_id')
                ->constrained('election_periods')
                ->cascadeOnDelete();

            $table->foreignId('candidate_id')
                ->constrained('candidates')
                ->cascadeOnDelete();

            $table->dateTime('scheduled_at');
            $table->string('location')->nullable();

            $table->enum('status', [
                'scheduled',
                'completed',
                'absent',
                'cancelled',
            ])->default('scheduled');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->unique('candidate_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
