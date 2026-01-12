<?php

namespace Database\Factories;

use App\Models\BloodTest;
use App\Models\BloodUnit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BloodTest>
 */
class BloodTestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BloodTest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bloodUnit = BloodUnit::inRandomOrder()->first();
        $technician = User::whereHas('roles', function ($q) {
            $q->where('name', 'lab_technician');
        })->inRandomOrder()->first();

        $testDate = $this->faker->dateTimeBetween('-30 days', 'now');

        // Generate test results
        $results = ['negative', 'positive', 'pending'];
        $hivResult = $this->faker->randomElement($results);
        $hepatitisBResult = $this->faker->randomElement($results);
        $hepatitisCResult = $this->faker->randomElement($results);
        $syphilisResult = $this->faker->randomElement($results);
        $malariaResult = $this->faker->randomElement($results);

        // Determine overall status based on results
        $testResults = [$hivResult, $hepatitisBResult, $hepatitisCResult, $syphilisResult, $malariaResult];

        $overallStatus = 'pending';
        if (!in_array('pending', $testResults)) {
            if (in_array('positive', $testResults)) {
                $overallStatus = 'quarantined';
            } else {
                $overallStatus = 'passed';
            }
        }

        return [
            'test_id' => 'TEST' . $this->faker->unique()->numberBetween(1000, 9999),
            'blood_unit_id' => $bloodUnit->id,
            'technician_id' => $this->faker->randomElement([2, 7]),
            'test_date' => $testDate,
            'hiv_result' => $hivResult,
            'hepatitis_b_result' => $hepatitisBResult,
            'hepatitis_c_result' => $hepatitisCResult,
            'syphilis_result' => $syphilisResult,
            'malaria_result' => $malariaResult,
            'blood_group' => $this->faker->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'overall_status' => $overallStatus,
            'test_notes' => $this->faker->optional(0.3)->sentence(),
            'lab_reference' => $this->faker->optional(0.7)->bothify('LAB-####-####'),
            'created_by' => 1, // Admin user
            'updated_by' => 1, // Admin user
        ];
    }
}
