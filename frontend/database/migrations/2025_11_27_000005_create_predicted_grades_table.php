<?php

/**
 * Migration adding the predictedGrades table for storing projected scores per user and subject.
 * Inputs: none; invoked by Laravel's migration runner.
 * Outputs: creates the predictedGrades table with UUID identifiers, subject linkage, user foreign key, and score.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Inputs: none; invoked by Artisan migrate.
     * Outputs: void; persists the predictedGrades table with its constraints.
     */
    public function up(): void
    {
        Schema::create('predictedGrades', function (Blueprint $table) {
            $table->uuid('predictedGradeID')->primary();
            $table->foreignUuid('userID')
                ->constrained('users', 'id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignUuid('subjectID')
                ->constrained('subjects', 'uuid')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->double('score');
        });
    }

    /**
     * Reverse the migrations.
     *
     * Inputs: none; invoked by migration rollback commands.
     * Outputs: void; removes the predictedGrades table.
     */
    public function down(): void
    {
        Schema::dropIfExists('predictedGrades');
    }
};
