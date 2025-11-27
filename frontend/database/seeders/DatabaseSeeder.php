<?php

/**
 * Root database seeder coordinating application seeders for deterministic bootstrap data.
 *
 * Inputs: none; executed via Artisan db:seed or migrate --seed.
 * Outputs: populates baseline users and lookup tables required for development and testing.
 */

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Seeds the application with initial users and reference data.
 *
 * Inputs: none.
 * Outputs: persisted seed rows for immediate application use.
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Inputs: none.
     * Outputs: void; creates a test user and seeds lookup tables.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call(TypeSeeder::class);
    }
}
