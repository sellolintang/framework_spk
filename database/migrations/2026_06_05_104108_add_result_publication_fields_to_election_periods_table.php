<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('election_periods', function (Blueprint $table) {
            $table->boolean('is_result_published')
                ->default(false)
                ->after('status');

            $table->timestamp('result_published_at')
                ->nullable()
                ->after('is_result_published');

            $table->foreignId('result_published_by')
                ->nullable()
                ->after('result_published_at')
                ->constrained('users')
                ->nullOnDelete();

            $table->text('announcement_note')
                ->nullable()
                ->after('result_published_by');
        });
    }

    public function down(): void
    {
        Schema::table('election_periods', function (Blueprint $table) {
            $table->dropConstrainedForeignId('result_published_by');
            $table->dropColumn([
                'is_result_published',
                'result_published_at',
                'announcement_note',
            ]);
        });
    }
};