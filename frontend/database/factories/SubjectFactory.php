<?php

/**
 * Factory generating Subject model instances for tests and seeders.
 *
 * Inputs: none; invoked via Subject::factory() to create subjects with UUIDs and names.
 * Outputs: associative array of attribute defaults suitable for database insertion.
 */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * Factory class for \App\Models\Subject providing default attribute generation.
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * Inputs: none.
     * Outputs: array<string, mixed> containing uuid and subject name.
     */
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid()->toString(),
            'name' => fake()->randomElement([
                'Mathematics',
                'Physics',
                'Chemistry',
                'Biology',
                'History',
                'Geography',
                'English Literature',
                'Computer Science',
            ]),
        ];
    }
}
