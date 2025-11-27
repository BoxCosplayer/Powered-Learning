<?php

/**
 * Migration establishing the types table for weighting categories in subject recommendations.
 * Inputs: none; invoked by Laravel's migration runner.
 * Outputs: creates the types table with a UUID primary key, descriptive label, and weighting value.
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
     * Outputs: void; persists the types table with its columns.
     */
    public function up(): void
    {
        Schema::create('types', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('type');
            $table->double('weight');
        });
    }

    /**
     * Reverse the migrations.
     *
     * Inputs: none; invoked by migration rollback commands.
     * Outputs: void; removes the types table.
     */
    public function down(): void
    {
        Schema::dropIfExists('types');
    }
};
