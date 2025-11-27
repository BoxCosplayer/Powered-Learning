<?php

/**
 * Factory generating PredictedGrade model instances for tests and seeders.
 *
 * Inputs: none; invoked via PredictedGrade::factory() to create predicted scores with linked users and subjects.
 * Outputs: associative array of attribute defaults suitable for database insertion.
 */

namespace Database\Factories;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * Factory class for \App\Models\PredictedGrade providing default attribute generation.
 */
class PredictedGradeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * Inputs: none.
     * Outputs: array<string, mixed> containing UUID primary key, related user and subject identifiers, and predicted score.
     */
    public function definition(): array
    {
        return [
            'predictedGradeID' => Str::uuid()->toString(),
            'userID' => User::factory(),
            'subjectID' => Subject::factory(),
            'score' => fake()->randomFloat(2, 0, 100),
        ];
    }
}
