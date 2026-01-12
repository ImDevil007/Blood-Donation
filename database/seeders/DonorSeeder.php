<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DonorSeeder extends Seeder
{
    public function run()
    {
        // Create 10 donors using factory
        $donors = Donor::factory()->count(10)->create();

        // Create User accounts for each donor if they don't exist
        foreach ($donors as $donor) {
            $user = User::where('email', $donor->email)->first();

            if (!$user) {
                // Calculate age from DOB
                $age = $donor->dob ? \Carbon\Carbon::parse($donor->dob)->diffInYears(\Carbon\Carbon::today()) : $donor->age;

                $user = User::create([
                    'title' => $donor->title,
                    'first_name' => $donor->first_name,
                    'last_name' => $donor->last_name,
                    'email' => $donor->email,
                    'phone' => $donor->phone,
                    'password' => Hash::make('password'),
                    'blood_group' => $donor->blood_group,
                    'gender' => $donor->gender,
                    'dob' => $donor->dob,
                    'age' => $age,
                ]);

                // Assign Donor role
                $user->assignRole(5);
            } else {
                // User exists, ensure they have Donor role
                if (!$user->hasRole('Donor')) {
                    $user->assignRole(5);
                }
            }
        }
    }
}
