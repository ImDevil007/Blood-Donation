<?php

namespace Database\Seeders;

use App\Models\Lov;
use Illuminate\Database\Seeder;
class LovSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listOfValues = [
            // Gender types
            [
                'id' => '1',
                'name' => 'Male',
                'lov_category_id' => '1',
            ],
            [
                'id' => '2',
                'name' => 'female',
                'lov_category_id' => '1',
            ],
            [
                'id' => '3',
                'name' => 'Other',
                'lov_category_id' => '1',
            ],


            // Titles
            [
                'id' => '4',
                'name' => 'Mr.',
                'lov_category_id' => '2',
            ],
            [
                'id' => '5',
                'name' => 'Mrs.',
                'lov_category_id' => '2',
            ],
            [
                'id' => '6',
                'name' => 'Miss.',
                'lov_category_id' => '2',
            ],
            [
                'id' => '7',
                'name' => 'Dr',
                'lov_category_id' => '2',
            ],
            [
                'id' => '8',
                'name' => 'Prof',
                'lov_category_id' => '2',
            ],


            // Blood Groups
            [
                'id' => '13',
                'name' => 'A+',
                'lov_category_id' => '3',
            ],
            [
                'id' => '14',
                'name' => 'A-',
                'lov_category_id' => '3',
            ],
            [
                'id' => '15',
                'name' => 'B+',
                'lov_category_id' => '3',
            ],
            [
                'id' => '16',
                'name' => 'B-',
                'lov_category_id' => '3',
            ],
            [
                'id' => '17',
                'name' => 'AB+',
                'lov_category_id' => '3',
            ],
            [
                'id' => '18',
                'name' => 'AB-',
                'lov_category_id' => '3',
            ],
            [
                'id' => '19',
                'name' => 'O+',
                'lov_category_id' => '3',
            ],
            [
                'id' => '20',
                'name' => 'O-',
                'lov_category_id' => '3',
            ],

            // Blood Types
            [
                'id' => '21',
                'name' => 'Whole Blood',
                'lov_category_id' => '4',
            ],
            [
                'id' => '22',
                'name' => 'Red Blood Cells',
                'lov_category_id' => '4',
            ],
            [
                'id' => '23',
                'name' => 'Platelets',
                'lov_category_id' => '4',
            ],
            [
                'id' => '24',
                'name' => 'Plasma',
                'lov_category_id' => '4',
            ],
            [
                'id' => '25',
                'name' => 'Cryoprecipitate',
                'lov_category_id' => '4',
            ],







            /****************************************************************************************/
            /** Permission Categories */
            [
                'id' => '1000',
                'name' => 'Users',
                'lov_category_id' => '100',
            ],
            [
                'id' => '1001',
                'name' => 'Roles',
                'lov_category_id' => '100',
            ],
            [
                'id' => '100100',
                'name' => 'Permission Categories',
                'lov_category_id' => '100',
            ],
            [
                'id' => '1003',
                'name' => 'Permissions',
                'lov_category_id' => '100',
            ],
            /****************************************************************************************/
        ];

        foreach ($listOfValues as $value) {
            Lov::create([
                'id' => $value['id'],
                'name' => $value['name'],
                'lov_category_id' => $value['lov_category_id'],
                'remarks' => $value['remarks'] ?? ''
            ]);
        }
    }
}

