<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = User::create([
            'id' => 1,
            'title' => 4,
            'first_name' => 'Sarada',
            'last_name' => 'Bhagya',
            'email' => 'sarada@axus.lk',
            'phone' => '0771751740',
            'password' => bcrypt('asd@123'),
            'blood_group' => 15,
            'gender' => 1,
            'dob' => '1990-03-17 00:00:02',
            // 'email_verified_at' => '2023-05-21 08:53:14',
        ]);

        // Get Admin role (id 1) and assign all permissions
        $adminRole = Role::find(1);
        $permissions = Permission::pluck('name')->toArray();
        $adminRole->syncPermissions($permissions);

        // Assign Admin role to user
        $superAdmin->assignRole($adminRole->name);
    }
}
