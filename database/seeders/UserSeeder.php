<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Donor;
use App\Services\DonorService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        $donorService = new DonorService();
        $user1 = User::create([
            'id' => 5,
            'title' => 4, // Mr.
            'first_name' => 'Sachin',
            'last_name' => 'Kavindu',
            'email' => 'bestkavindu@gmail.com',
            'phone' => '0771501502',
            'password' => bcrypt('asdasdasd'),
            'blood_group' => 13,
            'gender' => 1,
            'dob' => '1995-04-11 00:00:00',
        ]);

        $user2 = User::create([
            'id' => 3,
            'title' => 4, // Mr.
            'first_name' => 'Kasun',
            'last_name' => 'Jayarathna',
            'email' => 'kasun@gmail.com',
            'phone' => '0788456587',
            'password' => bcrypt('asdasdasd'),
            'blood_group' => 16,
            'gender' => 1,
            'dob' => '1994-07-22 00:00:00',
        ]);

        $user3 = User::create([
            'id' => 4,
            'title' => 4, // Mr.
            'first_name' => 'Sadew',
            'last_name' => 'Dullawa',
            'email' => 'sadew@gmail.com',
            'phone' => '0717598455',
            'password' => bcrypt('asdasdasd'),
            'blood_group' => 15,
            'gender' => 1,
            'dob' => '1993-09-10 00:00:00',
        ]);

        $user4 = User::create([
            'id' => 2,
            'title' => 4, // Mr.
            'first_name' => 'Pasan',
            'last_name' => 'Vithanage',
            'email' => 'pasan@gmail.com',
            'phone' => '0775656988',
            'password' => bcrypt('asdasdasd'),
            'blood_group' => 19,
            'gender' => 1,
            'dob' => '1992-06-18 00:00:00',
        ]);

        // Extra sample users
        $user5 = User::create([
            'id' => 6,
            'title' => 5, // Mrs.
            'first_name' => 'Nadeesha',
            'last_name' => 'Madushani',
            'email' => 'nadeesha@gmail.com',
            'phone' => '0772223344',
            'password' => bcrypt('asdasdasd'),
            'blood_group' => 14,
            'gender' => 2,
            'dob' => '1996-12-05 00:00:00',
        ]);

        $user6 = User::create([
            'id' => 7,
            'title' => 4, // Mr.
            'first_name' => 'Tharindu',
            'last_name' => 'Perera',
            'email' => 'tharindu@gmail.com',
            'phone' => '0714432211',
            'password' => bcrypt('asdasdasd'),
            'blood_group' => 17, 
            'gender' => 1,
            'dob' => '1991-02-27 00:00:00',
        ]);

        $user1->assignRole([2]);
        $user2->assignRole([3]);
        $user3->assignRole([3]);
        $user4->assignRole([4]);
        $user5->assignRole([2]);
        $user6->assignRole([5]);

        // Create Donor records for users with Donor role (user6)
        $this->createDonorForUser($user6, $donorService);
    }

    /**
     * Create Donor record for a User
     */
    protected function createDonorForUser(User $user, DonorService $donorService): void
    {
        // Check if donor already exists
        $existingDonor = Donor::where('email', $user->email)->first();

        if (!$existingDonor) {
            $donorService->createDonorFromUser([
                'title' => $user->title,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'blood_group' => $user->blood_group,
                'gender' => $user->gender,
                'dob' => $user->dob,
            ]);
        }
    }
}
