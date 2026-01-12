<?php

namespace Database\Seeders;

use App\Models\BloodTest;
use Illuminate\Database\Seeder;

class BloodTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 20 blood tests
        BloodTest::factory(20)->create();
    }
}
