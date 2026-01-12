<?php

namespace Database\Seeders;

use App\Models\Recipient;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LovCategorySeeder::class,
            LovSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            CreateAdminUserSeeder::class,
            UserSeeder::class,
            RecipientSeeder::class,
            DonorSeeder::class,
            BloodInventorySeeder::class,
            BloodUnitSeeder::class,
            BloodTestSeeder::class,
            BloodCollectionCampSeeder::class
        ]);
    }
}
