<?php

/**
 * Migration adding the history table to capture study events against subjects and types per user.
 * Inputs: none; invoked by Laravel's migration runner.
 * Outputs: creates the history table with UUID identifiers, user linkage, subject and type references, scores, and timestamps.
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
     * Outputs: void; persists the history table with its constraints.
     */
    public function up(): void
    {
        Schema::create('history', function (Blueprint $table) {
            $table->uuid('historyEntryID')->primary();
            $table->foreignUuid('userID')
                ->constrained('users', 'id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignUuid('subjectID')
                ->constrained('subjects', 'uuid')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignUuid('typeID')
                ->constrained('types', 'uuid')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->double('score');
            $table->dateTime('studied_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * Inputs: none; invoked by migration rollback commands.
     * Outputs: void; removes the history table.
     */
    public function down(): void
    {
        Schema::dropIfExists('history');
    }
};
