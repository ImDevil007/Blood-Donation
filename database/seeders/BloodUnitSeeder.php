<?php

namespace Database\Seeders;

use App\Models\BloodUnit;
use Illuminate\Database\Seeder;

class BloodUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 15 blood units
        BloodUnit::factory(15)->create();
    }
}
