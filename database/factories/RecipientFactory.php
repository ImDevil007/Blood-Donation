<?php

namespace Database\Factories;

use App\Models\Recipient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipientFactory extends Factory
{
    protected $model = Recipient::class;

    public function definition()
    {
        $bloodGroups = ['13', '14', '15', '16', '17', '18', '19', '20'];
        $genders = ['1', '2', '3'];
        $titles = ['4', '5', '6', '7', '8'];
        $statuses = ['pending', 'accepted', 'fulfilled', 'rejected'];

        return [
            'patient_code' => 'PAT' . $this->faker->unique()->numberBetween(202405100, 202405999),
            'title' => $this->faker->randomElement($titles),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'dob' => $this->faker->date('Y-m-d', '-18 years'),
            'gender' => $this->faker->randomElement($genders),
            'blood_group' => $this->faker->randomElement($bloodGroups),
            'contact_number' => $this->faker->numerify('##########'),
            'email' => $this->faker->safeEmail,
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'district' => $this->faker->city,
            'hospital_name' => $this->faker->randomElement(['General Hospital', 'City Medical Center', 'Mercy Hospital', null]),
            'doctor_name' => $this->faker->name('male'),
            'admission_date' => ($dt = $this->faker->optional()->dateTimeBetween('-1 month', 'now')) ? $dt->format('Y-m-d') : null,
            'blood_required_date' => ($dt = $this->faker->optional()->dateTimeBetween('now', '+1 month')) ? $dt->format('Y-m-d') : null,
            'blood_quantity_required' => $this->faker->numberBetween(1, 5),
            'request_status' => $this->faker->randomElement($statuses),
            'diagnosis' => $this->faker->sentence,
            'notes' => $this->faker->optional()->sentence,
            'status' => true,
            'created_by' => \App\Models\User::inRandomOrder()->first()?->id ?? 1,
        ];
    }
}
