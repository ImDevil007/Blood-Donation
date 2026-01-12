<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['id' => '1', 'name' => 'Admin']);
        Role::create(['id' => '2', 'name' => 'Staff']);
        Role::create(['id' => '3', 'name' => 'Doctor']);
        Role::create(['id' => '4', 'name' => 'Lab Technician']);
        Role::create(['id' => '5', 'name' => 'Donor']);
    }
}
