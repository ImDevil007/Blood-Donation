<?php

namespace Database\Factories;

use App\Models\Donor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonorFactory extends Factory
{
    protected $model = Donor::class;

    public function definition()
    {
        $bloodGroups = ['13', '14', '15', '16', '17', '18', '19', '20']; // LOV IDs for blood groups
        $genders = ['1', '2', '3']; // LOV IDs for Genders
        $titles = ['4', '5', '6', '7']; // LOV IDs for Titles
        $relations = ['Father', 'Mother', 'Brother', 'Sister', 'Spouse', 'Friend']; // Emergency contact relations

        $age = $this->faker->numberBetween(18, 65);
        $weight = $this->faker->numberBetween(45, 100);
        $height = $this->faker->numberBetween(150, 190);

        return [
            'donor_id' => 'DON' . date('Y') . date('m') . str_pad($this->faker->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'title' => $this->faker->randomElement($titles),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->numerify('##########'),
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'district' => $this->faker->state,
            'blood_group' => $this->faker->randomElement($bloodGroups),
            'gender' => $this->faker->randomElement($genders),
            'dob' => $this->faker->date('Y-m-d', '-' . $age . ' years'),
            'age' => $age,
            'weight' => $weight,
            'height' => $height,
            'medical_history' => $this->faker->optional(0.3)->sentence,
            'allergies' => $this->faker->optional(0.2)->sentence,
            'medications' => $this->faker->optional(0.2)->sentence,
            'last_donation_date' => $this->faker->optional(0.7)->dateTimeBetween('-1 year', 'now')?->format('Y-m-d'),
            'total_donations' => $this->faker->numberBetween(0, 10),
            'is_eligible' => $this->faker->boolean(80),
            'eligibility_reason' => $this->faker->optional(0.15)->sentence,
            'emergency_contact_name' => $this->faker->name,
            'emergency_contact_phone' => $this->faker->numerify('##########'),
            'emergency_contact_relation' => $this->faker->randomElement($relations),
            'status' => $this->faker->boolean(90),
            'created_by' => \App\Models\User::inRandomOrder()->first()?->id ?? 1,
        ];
    }
}
