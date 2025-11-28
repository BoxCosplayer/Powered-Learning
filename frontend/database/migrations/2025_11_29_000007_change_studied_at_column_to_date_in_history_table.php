<?php

/**
 * Migration altering the history table so studied_at stores only a calendar date.
 *
 * Inputs: none; invoked by Laravel's migration runner.
 * Outputs: changes the studied_at column from datetime to date to remove time components.
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
     * Outputs: void; updates the studied_at column to a date type.
     */
    public function up(): void
    {
        Schema::table('history', function (Blueprint $table) {
            $table->date('studied_at')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * Inputs: none; invoked by migration rollback commands.
     * Outputs: void; restores the studied_at column to a datetime type.
     */
    public function down(): void
    {
        Schema::table('history', function (Blueprint $table) {
            $table->dateTime('studied_at')->change();
        });
    }
};
