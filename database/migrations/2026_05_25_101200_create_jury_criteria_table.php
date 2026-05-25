<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jury_criteria', function (Blueprint $table) {
            $table->id();

            $table->foreignId('period_id')
                ->constrained('election_periods')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('criterion_id')
                ->constrained('criteria')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['period_id', 'user_id', 'criterion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jury_criteria');
    }
};
