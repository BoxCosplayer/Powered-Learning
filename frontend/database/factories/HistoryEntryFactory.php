<?php

/**
 * Factory generating HistoryEntry model instances for tests and seeders.
 *
 * Inputs: none; invoked via HistoryEntry::factory() to create study history records with linked users, subjects, and types.
 * Outputs: associative array of attribute defaults suitable for database insertion.
 */

namespace Database\Factories;

use App\Models\Subject;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * Factory class for \App\Models\HistoryEntry providing default attribute generation.
 */
class HistoryEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * Inputs: none.
     * Outputs: array<string, mixed> containing UUID primary key, related identifiers, score, and studied timestamp.
     */
    public function definition(): array
    {
        return [
            'historyEntryID' => Str::uuid()->toString(),
            'userID' => User::factory(),
            'subjectID' => Subject::factory(),
            'typeID' => Type::factory(),
            'score' => fake()->randomFloat(2, 0, 100),
            'studied_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
