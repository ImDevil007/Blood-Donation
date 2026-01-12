<?php

namespace Database\Seeders;

use App\Models\BloodCollectionCamp;
use Illuminate\Database\Seeder;

class BloodCollectionCampSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 15 blood collection camps
        BloodCollectionCamp::factory(15)->create();
    }
}