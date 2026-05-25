<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('period_id')
                ->constrained('election_periods')
                ->cascadeOnDelete();

            $table->string('registration_number', 50)->unique();
            $table->string('full_name', 150);
            $table->string('student_number', 50);
            $table->string('email', 150);
            $table->string('phone', 30)->nullable();
            $table->string('faculty', 150)->nullable();
            $table->string('study_program', 150)->nullable();
            $table->unsignedTinyInteger('semester')->nullable();

            $table->text('vision')->nullable();
            $table->text('mission')->nullable();

            $table->string('photo_file')->nullable();
            $table->string('cv_file')->nullable();

            $table->enum('status', [
                'pending',
                'valid',
                'invalid',
                'interview_scheduled',
                'interviewed',
                'scored',
            ])->default('pending');

            $table->foreignId('validated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->dateTime('validated_at')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->timestamps();

            $table->unique(['period_id', 'student_number']);
            $table->index(['period_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
