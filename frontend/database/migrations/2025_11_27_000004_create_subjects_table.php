<?php

/**
 * Migration establishing the subjects table for storing subject names used in recommendation scoring.
 * Inputs: none; invoked by Laravel's migration runner.
 * Outputs: creates the subjects table with a UUID primary key and subject name field.
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
     * Outputs: void; persists the subjects table.
     */
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * Inputs: none; invoked by migration rollback commands.
     * Outputs: void; removes the subjects table.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
