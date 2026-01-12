<?php

namespace App\Services;

use App\Models\Donor;
use Carbon\Carbon;

class DonorService
{
    /**
     * Generate unique donor ID
     */
    public function generateDonorId(): string
    {
        $prefix = 'DON';
        $year = date('Y');
        $month = date('m');

        $lastDonor = Donor::where('donor_id', 'like', $prefix . $year . $month . '%')
            ->orderBy('donor_id', 'desc')
            ->first();

        if ($lastDonor) {
            $lastNumber = (int) substr($lastDonor->donor_id, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate age from date of birth
     */
    public function calculateAge($dob): int
    {
        return Carbon::parse($dob)->diffInYears(Carbon::today());
    }

    /**
     * Check basic eligibility based on age only (for registration)
     */
    public function checkBasicEligibility(int $age): array
    {
        $isEligible = true;
        $reason = null;

        if ($age < 18) {
            $isEligible = false;
            $reason = 'Age below minimum requirement (18 years)';
        } elseif ($age > 65) {
            $isEligible = false;
            $reason = 'Age above maximum requirement (65 years)';
        }

        return [
            'is_eligible' => $isEligible,
            'eligibility_reason' => $reason,
        ];
    }

    /**
     * Create Donor record from User registration data
     */
    public function createDonorFromUser(array $userData): Donor
    {
        $age = $this->calculateAge($userData['dob']);
        $eligibility = $this->checkBasicEligibility($age);

        // Validate blood_group exists in lovs (valid IDs: 13-20)
        $bloodGroup = $userData['blood_group'];
        if ($bloodGroup < 13 || $bloodGroup > 20) {
            // Use a default valid blood group (O+ = 19) if invalid
            $bloodGroup = 19;
        }

        return Donor::create([
            'donor_id' => $this->generateDonorId(),
            'title' => $userData['title'] ?? null,
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name'],
            'email' => $userData['email'],
            'phone' => $userData['phone'],
            'address' => null,
            'city' => null,
            'district' => null,
            'blood_group' => $bloodGroup,
            'gender' => $userData['gender'],
            'dob' => $userData['dob'],
            'age' => $age,
            'weight' => null,
            'height' => null,
            'medical_history' => null,
            'allergies' => null,
            'medications' => null,
            'emergency_contact_name' => $userData['first_name'] . ' ' . $userData['last_name'], // Use user's name as default
            'emergency_contact_phone' => $userData['phone'], // Use user's phone as default
            'emergency_contact_relation' => 'Self', // Default relation
            'is_eligible' => $eligibility['is_eligible'],
            'eligibility_reason' => $eligibility['eligibility_reason'],
            'status' => true,
            'created_by' => null,
        ]);
    }
}
