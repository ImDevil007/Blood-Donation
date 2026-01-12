<?php

namespace Database\Factories;

use App\Models\BloodUnit;
use App\Models\Donor;
use App\Models\Lov;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BloodUnit>
 */
class BloodUnitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BloodUnit::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $donor = Donor::inRandomOrder()->first();
        $bloodGroups = Lov::where('lov_category_id', 3)->get(); // Blood Groups
        $bloodTypes = Lov::where('lov_category_id', 4)->get(); // Blood Types

        $bloodGroup = $bloodGroups->random();
        $bloodType = $bloodTypes->random();

        $collectionDate = $this->faker->dateTimeBetween('-30 days', 'now');
        $expiryDate = (clone $collectionDate)->modify('+42 days');

        return [
            'unit_id' => 'BU' . $this->faker->unique()->numberBetween(1000, 9999),
            'donor_id' => $donor->id,
            'blood_group' => $bloodGroup->id,
            'blood_type' => $bloodType->id,
            'collection_date' => $collectionDate,
            'expiry_date' => $expiryDate,
            'volume' => $this->faker->randomFloat(1, 400, 500),
            'storage_location' => $this->faker->randomElement(['Refrigerator A', 'Refrigerator B', 'Freezer 1', 'Shelf 2']),
            'temperature' => $this->faker->randomFloat(1, 2, 6),
            'hemoglobin_level' => $this->faker->randomFloat(1, 12.0, 16.0),
            'status' => $this->faker->randomElement([0, 1]),
            'is_used' => $this->faker->boolean(20), // 20% chance of being used
            'used_date' => $this->faker->optional(0.2)->dateTimeBetween('-10 days', 'now'),
            'used_for' => $this->faker->optional(0.2)->sentence,
            'notes' => $this->faker->optional(0.3)->sentence(),
            'created_by' => 1, // Admin user
            'updated_by' => 1,
        ];
    }
}
