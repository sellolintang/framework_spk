<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('criteria', function (Blueprint $table) {
            $table->id();

            $table->foreignId('period_id')
                ->constrained('election_periods')
                ->cascadeOnDelete();

            $table->string('code', 50);
            $table->string('name', 150);
            $table->decimal('weight', 8, 4)->default(1.0000);
            $table->enum('type', ['benefit', 'cost'])->default('benefit');
            $table->decimal('min_score', 8, 2)->default(0.00);
            $table->decimal('max_score', 8, 2)->default(100.00);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['period_id', 'code']);
            $table->unique(['period_id', 'name']);
            $table->index(['period_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('criteria');
    }
};
