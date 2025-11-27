<?php

/**
 * Seeder pre-populating the types lookup table with canonical weighting entries.
 *
 * Inputs: none; invoked via Artisan db:seed or migrate --seed.
 * Outputs: inserts or updates fixed type rows identified by UUID to keep lookup data stable.
 */

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Seeds the types table with deterministic UUIDs and descriptive labels.
 *
 * Inputs: none.
 * Outputs: persisted lookup rows available for weighting study history entries.
 */
class TypeSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * Inputs: none.
     * Outputs: void; upserts canonical type rows into the database.
     */
    public function run(): void
    {
        $types = [
            ['uuid' => '15802384-a9b5-45ad-94ba-41f7ef08c618', 'type' => 'Not Studied', 'weight' => 0.0],
            ['uuid' => 'ed305e82-912e-436d-a1f1-7d95ca4dee2e', 'type' => 'Revision',    'weight' => 0.1],
            ['uuid' => '8fd619a5-c942-4da9-8a4d-3397d3fde8d0', 'type' => 'Homework',    'weight' => 0.2],
            ['uuid' => 'aff044ea-78c2-403a-a68c-4ca4cfe167f7', 'type' => 'Quiz',        'weight' => 0.3],
            ['uuid' => '967ebf11-e4b5-45bd-bb40-b99cec3e4f37', 'type' => 'Topic Test',  'weight' => 0.4],
            ['uuid' => '947cbd8d-5e48-4a69-9000-38fb4a54f457', 'type' => 'Mock Exam',   'weight' => 0.5],
            ['uuid' => 'ea591509-cc40-445a-ae31-0bd01dcb1439', 'type' => 'Exam',        'weight' => 0.6],
        ];

        Type::query()->upsert($types, ['uuid']);
    }
}
