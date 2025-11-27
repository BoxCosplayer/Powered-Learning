<?php

/**
 * Factory generating User model instances with UUID primary keys for tests and seeders.
 *
 * Inputs: none; invoked via User::factory() to create users with IDs, credentials, and verified timestamps.
 * Outputs: associative array of attribute defaults suitable for database insertion.
 */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories.Factory<\App\Models\User>
 *
 * Factory class providing default attribute generation for User models.
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     *
     * Inputs: none.
     * Outputs: nullable string storing the hashed password for reuse.
     *
     * @var string|null
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * Inputs: none.
     * Outputs: array<string, mixed> containing UUID id, name, email, verification timestamp, password, and token.
     */
    public function definition(): array
    {
        return [
            'id' => Str::uuid()->toString(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * Inputs: none.
     * Outputs: static instance with state closure clearing verification timestamp.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
