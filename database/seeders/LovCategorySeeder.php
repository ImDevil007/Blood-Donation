<?php

namespace Database\Seeders;

use App\Models\LovCategory;
use Illuminate\Database\Seeder;

class LovCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $lovCategories = [
            [
                'id' => '1',
                'name' => 'Gender types',
                'remarks' => 'Male, Femail, Other',
            ],
            [
                'id' => '2',
                'name' => 'Titles',
                'remarks' => 'Dr, Mr, Mrs',
            ],
            [
                'id' => '3',
                'name' => 'Blood Group',
                'remarks' => 'A+, A-, B+, B-, O',
            ],
            [
                'id' => '4',
                'name' => 'Blood Type',
                'remarks' => 'Whole Blood, Red Blood Cells, Platelets, Plasma, Cryoprecipitate',
            ],




            /****************************************************************************************/
            /** Permission Categories */
            [
                'id' => '100',
                'name' => 'Permission Categories',
                'remarks' => 'User, Role, Permission, Course',
            ],
            /****************************************************************************************/
        ];

        foreach ($lovCategories as $lovCategory) {
            LovCategory::create([
                'id' => $lovCategory['id'],
                'name' => $lovCategory['name'],
                'remarks' => $lovCategory['remarks']
            ]);
        }
    }
}
