<?php

namespace Database\Factories;

use App\Models\BloodCollectionCamp;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BloodCollectionCamp>
 */
class BloodCollectionCampFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BloodCollectionCamp::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-30 days', '+30 days');
        $endDate = (clone $startDate)->modify('+' . $this->faker->numberBetween(1, 3) . ' days');
        
        $targetDonors = $this->faker->numberBetween(50, 500);
        $actualDonors = $this->faker->numberBetween(0, $targetDonors);
        $collectedUnits = $actualDonors; // Assuming 1 unit per donor
        
        // Determine status based on dates
        $today = now()->toDateString();
        $status = 'scheduled';
        if ($startDate->format('Y-m-d') <= $today && $endDate->format('Y-m-d') >= $today) {
            $status = 'ongoing';
        } elseif ($endDate->format('Y-m-d') < $today) {
            $status = 'completed';
        }

        return [
            'camp_id' => 'CAMP' . $this->faker->unique()->numberBetween(1000, 9999),
            'name' => $this->faker->randomElement([
                'Community Blood Drive',
                'Corporate Blood Donation Camp',
                'University Blood Collection',
                'Hospital Blood Drive',
                'Emergency Blood Collection',
                'Annual Blood Donation Camp',
                'Mobile Blood Collection Unit',
                'School Blood Drive',
                'Religious Community Blood Drive',
                'Workplace Blood Donation'
            ]) . ' - ' . $this->faker->city(),
            'description' => $this->faker->optional(0.8)->paragraph(),
            'location' => $this->faker->randomElement([
                'City Hospital',
                'Community Center',
                'University Campus',
                'Corporate Office',
                'Shopping Mall',
                'School Auditorium',
                'Religious Center',
                'Government Building',
                'Sports Complex',
                'Library'
            ]) . ', ' . $this->faker->city(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'start_time' => $this->faker->randomElement(['08:00', '09:00', '10:00']),
            'end_time' => $this->faker->randomElement(['16:00', '17:00', '18:00']),
            'target_donors' => $targetDonors,
            'actual_donors' => $actualDonors,
            'collected_units' => $collectedUnits,
            'organizer_name' => $this->faker->randomElement([
                'Red Cross Society',
                'Local Hospital',
                'University Health Center',
                'Corporate CSR Team',
                'Community Health Organization',
                'Government Health Department',
                'NGO Health Initiative',
                'Religious Community Group',
                'School Health Committee',
                'Emergency Response Team'
            ]),
            'organizer_contact' => $this->faker->numerify('##########'),
            'status' => $status,
            'notes' => $this->faker->optional(0.4)->sentence(),
            'created_by' => 1, // Admin user
            'updated_by' => 1, // Admin user
        ];
    }
}
