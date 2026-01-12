<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'dashboard' => ['view'],
            'user'      => ['view', 'create', 'edit', 'delete'],
            'role'      => ['view', 'create', 'edit', 'delete'],
            'permission'=> ['view', 'create', 'edit', 'delete'],
            // future modules can be added here
            // 'post' => ['view', 'create', 'edit', 'delete'],
        ];

        foreach ($permissions as $module => $actions) {
            foreach ($actions as $action) {
                $name = "{$action} {$module}";
                Permission::firstOrCreate(['name' => $name]);
            }
        }
    }
}
