<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BloodInventory;
use App\Models\Lov;
use App\Models\User;
use Carbon\Carbon;

class BloodInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get blood groups from LOV table
        $bloodGroups = Lov::where('lov_category_id', 3)->get();
        $bloodTypes = Lov::where('lov_category_id', 4)->get();
        $adminUser = User::where('email', 'admin@example.com')->first();

        if (!$adminUser) {
            $adminUser = User::first();
        }

        if (!$adminUser) {
            $this->command->error('No admin user found. Please run UserSeeder first.');
            return;
        }

        $inventoryData = [
            // A+ Blood
            [
                'blood_group' => $bloodGroups->where('name', 'A+')->first()?->id ?? $bloodGroups->first()->id,
                'blood_type' => $bloodTypes->where('name', 'Whole Blood')->first()?->id ?? $bloodTypes->first()?->id,
                'quantity' => 15.5,
                'unit' => 'units',
                'collection_date' => Carbon::now()->subDays(5),
                'expiry_date' => Carbon::now()->addDays(37),
                'storage_location' => 'Refrigerator A - Shelf 1',
                'temperature' => 4.0,
                'notes' => 'Fresh donation from regular donor',
            ],
            [
                'blood_group' => $bloodGroups->where('name', 'A+')->first()?->id ?? $bloodGroups->first()->id,
                'blood_type' => $bloodTypes->where('name', 'Red Blood Cells')->first()?->id ?? $bloodTypes->first()?->id,
                'quantity' => 8.0,
                'unit' => 'units',
                'collection_date' => Carbon::now()->subDays(10),
                'expiry_date' => Carbon::now()->addDays(32),
                'storage_location' => 'Refrigerator A - Shelf 2',
                'temperature' => 4.0,
                'notes' => 'Processed red blood cells',
            ],

            // A- Blood
            [
                'blood_group' => $bloodGroups->where('name', 'A-')->first()?->id ?? $bloodGroups->first()->id,
                'blood_type' => $bloodTypes->where('name', 'Whole Blood')->first()?->id ?? $bloodTypes->first()?->id,
                'quantity' => 12.0,
                'unit' => 'units',
                'collection_date' => Carbon::now()->subDays(3),
                'expiry_date' => Carbon::now()->addDays(39),
                'storage_location' => 'Refrigerator B - Shelf 1',
                'temperature' => 4.0,
                'notes' => 'Emergency stock',
            ],

            // B+ Blood
            [
                'blood_group' => $bloodGroups->where('name', 'B+')->first()?->id ?? $bloodGroups->first()->id,
                'blood_type' => $bloodTypes->where('name', 'Whole Blood')->first()?->id ?? $bloodTypes->first()?->id,
                'quantity' => 20.0,
                'unit' => 'units',
                'collection_date' => Carbon::now()->subDays(1),
                'expiry_date' => Carbon::now()->addDays(41),
                'storage_location' => 'Refrigerator B - Shelf 2',
                'temperature' => 4.0,
                'notes' => 'Recent collection from blood drive',
            ],
            [
                'blood_group' => $bloodGroups->where('name', 'B+')->first()?->id ?? $bloodGroups->first()->id,
                'blood_type' => $bloodTypes->where('name', 'Platelets')->first()?->id ?? $bloodTypes->first()?->id,
                'quantity' => 5.0,
                'unit' => 'units',
                'collection_date' => Carbon::now()->subDays(2),
                'expiry_date' => Carbon::now()->addDays(3),
                'storage_location' => 'Platelet Agitator - Unit 1',
                'temperature' => 22.0,
                'notes' => 'Platelet concentrate - expires soon',
            ],

            // B- Blood
            [
                'blood_group' => $bloodGroups->where('name', 'B-')->first()?->id ?? $bloodGroups->first()->id,
                'blood_type' => $bloodTypes->where('name', 'Whole Blood')->first()?->id ?? $bloodTypes->first()?->id,
                'quantity' => 6.0,
                'unit' => 'units',
                'collection_date' => Carbon::now()->subDays(15),
                'expiry_date' => Carbon::now()->addDays(27),
                'storage_location' => 'Refrigerator C - Shelf 1',
                'temperature' => 4.0,
                'notes' => 'Rare blood type - low stock',
            ],

            // AB+ Blood
            [
                'blood_group' => $bloodGroups->where('name', 'AB+')->first()?->id ?? $bloodGroups->first()->id,
                'blood_type' => $bloodTypes->where('name', 'Whole Blood')->first()?->id ?? $bloodTypes->first()?->id,
                'quantity' => 18.0,
                'unit' => 'units',
                'collection_date' => Carbon::now()->subDays(7),
                'expiry_date' => Carbon::now()->addDays(35),
                'storage_location' => 'Refrigerator C - Shelf 2',
                'temperature' => 4.0,
                'notes' => 'Universal recipient blood type',
            ],
            [
                'blood_group' => $bloodGroups->where('name', 'AB+')->first()?->id ?? $bloodGroups->first()->id,
                'blood_type' => $bloodTypes->where('name', 'Plasma')->first()?->id ?? $bloodTypes->first()?->id,
                'quantity' => 25.0,
                'unit' => 'ml',
                'collection_date' => Carbon::now()->subDays(20),
                'expiry_date' => Carbon::now()->addDays(345),
                'storage_location' => 'Freezer -20°C',
                'temperature' => -20.0,
                'notes' => 'Frozen plasma - long shelf life',
            ],

            // AB- Blood
            [
                'blood_group' => $bloodGroups->where('name', 'AB-')->first()?->id ?? $bloodGroups->first()->id,
                'blood_type' => $bloodTypes->where('name', 'Whole Blood')->first()?->id ?? $bloodTypes->first()?->id,
                'quantity' => 4.0,
                'unit' => 'units',
                'collection_date' => Carbon::now()->subDays(25),
                'expiry_date' => Carbon::now()->addDays(17),
                'storage_location' => 'Refrigerator D - Shelf 1',
                'temperature' => 4.0,
                'notes' => 'Rare blood type - expiring soon',
            ],

            // O+ Blood
            [
                'blood_group' => $bloodGroups->where('name', 'O+')->first()?->id ?? $bloodGroups->first()->id,
                'blood_type' => $bloodTypes->where('name', 'Whole Blood')->first()?->id ?? $bloodTypes->first()?->id,
                'quantity' => 30.0,
                'unit' => 'units',
                'collection_date' => Carbon::now()->subDays(2),
                'expiry_date' => Carbon::now()->addDays(40),
                'storage_location' => 'Refrigerator A - Shelf 3',
                'temperature' => 4.0,
                'notes' => 'Most common blood type - high demand',
            ],
            [
                'blood_group' => $bloodGroups->where('name', 'O+')->first()?->id ?? $bloodGroups->first()->id,
                'blood_type' => $bloodTypes->where('name', 'Red Blood Cells')->first()?->id ?? $bloodTypes->first()?->id,
                'quantity' => 22.0,
                'unit' => 'units',
                'collection_date' => Carbon::now()->subDays(5),
                'expiry_date' => Carbon::now()->addDays(37),
                'storage_location' => 'Refrigerator A - Shelf 4',
                'temperature' => 4.0,
                'notes' => 'Processed O+ red blood cells',
            ],

            // O- Blood
            [
                'blood_group' => $bloodGroups->where('name', 'O-')->first()?->id ?? $bloodGroups->first()->id,
                'blood_type' => $bloodTypes->where('name', 'Whole Blood')->first()?->id ?? $bloodTypes->first()?->id,
                'quantity' => 10.0,
                'unit' => 'units',
                'collection_date' => Carbon::now()->subDays(1),
                'expiry_date' => Carbon::now()->addDays(41),
                'storage_location' => 'Emergency Refrigerator',
                'temperature' => 4.0,
                'notes' => 'Universal donor blood - emergency use only',
            ],

            // Some expired items for testing
            [
                'blood_group' => $bloodGroups->where('name', 'A+')->first()?->id ?? $bloodGroups->first()->id,
                'blood_type' => $bloodTypes->where('name', 'Whole Blood')->first()?->id ?? $bloodTypes->first()?->id,
                'quantity' => 5.0,
                'unit' => 'units',
                'collection_date' => Carbon::now()->subDays(50),
                'expiry_date' => Carbon::now()->subDays(8),
                'storage_location' => 'Quarantine Area',
                'temperature' => 4.0,
                'notes' => 'Expired - pending disposal',
                'status' => false,
            ],
        ];

        foreach ($inventoryData as $data) {
            BloodInventory::create([
                'inventory_id' => $this->generateInventoryId(),
                'blood_group' => $data['blood_group'],
                'blood_type' => $data['blood_type'],
                'quantity' => $data['quantity'],
                'unit' => $data['unit'],
                'collection_date' => $data['collection_date'],
                'expiry_date' => $data['expiry_date'],
                'storage_location' => $data['storage_location'],
                'temperature' => $data['temperature'],
                'notes' => $data['notes'],
                'status' => $data['status'] ?? true,
                'created_by' => $adminUser->id,
            ]);
        }

        $this->command->info('Blood inventory seeded successfully!');
    }

    private function generateInventoryId(): string
    {
        $prefix = 'BINV';
        $year = date('Y');
        $month = date('m');

        $lastInventory = BloodInventory::where('inventory_id', 'like', $prefix . $year . $month . '%')
            ->orderBy('inventory_id', 'desc')
            ->first();

        if ($lastInventory) {
            $lastNumber = (int) substr($lastInventory->inventory_id, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}

