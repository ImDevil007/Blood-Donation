<?php

namespace App\Services\Backend\Admin;

use App\Models\Donor;
use App\Rules\PhoneNumber;
use App\Rules\AgeMatchesDob;
use Illuminate\Support\Facades\Auth;

class DonorUpdateService
{
    public function update(Donor $donor, array $requestData): Donor
    {
        $validated = $this->validate($donor, $requestData);

        $updateData = [
            'title' => $validated['title'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'district' => $validated['district'],
            'blood_group' => $validated['blood_group'],
            'gender' => $validated['gender'],
            'dob' => $validated['dob'],
            'age' => $validated['age'],
            'weight' => $validated['weight'],
            'height' => $validated['height'],
            'medical_history' => $validated['medical_history'],
            'allergies' => $validated['allergies'],
            'medications' => $validated['medications'],
            'emergency_contact_name' => $validated['emergency_contact_name'],
            'emergency_contact_phone' => $validated['emergency_contact_phone'],
            'emergency_contact_relation' => $validated['emergency_contact_relation'],
            'is_eligible' => $this->checkEligibility($validated),
            'eligibility_reason' => $this->getEligibilityReason($validated),
            'updated_by' => Auth::user()->id,
        ];

        $donor->update($updateData);

        return $donor;
    }

    protected function validate(Donor $donor, array $data): array
    {
        return validator($data, [
            'title' => 'nullable|string|max:10',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:donors,email,' . $donor->id,
            'phone' => ['required', new PhoneNumber],
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'blood_group' => 'required|string',
            'gender' => 'required|string',
            'dob' => 'required|date|before:today',
            'age' => ['required', 'integer', 'min:18', 'max:65', new AgeMatchesDob($data['dob'] ?? null)],
            'weight' => 'required|numeric|min:45|max:200',
            'height' => 'nullable|numeric|min:100|max:250',
            'medical_history' => 'nullable|string|max:1000',
            'allergies' => 'nullable|string|max:500',
            'medications' => 'nullable|string|max:500',
            'emergency_contact_name' => 'required|string|max:100',
            'emergency_contact_phone' => ['required', new PhoneNumber],
            'emergency_contact_relation' => 'required|string|max:50',
        ])->validate();
    }

    protected function checkEligibility(array $data): bool
    {
        // Basic eligibility checks
        if ($data['age'] < 18 || $data['age'] > 65) {
            return false;
        }

        if ($data['weight'] < 45) {
            return false;
        }

        return true;
    }

    protected function getEligibilityReason(array $data): ?string
    {
        if ($data['age'] < 18) {
            return 'Age below minimum requirement (18 years)';
        }

        if ($data['age'] > 65) {
            return 'Age above maximum requirement (65 years)';
        }

        if ($data['weight'] < 45) {
            return 'Weight below minimum requirement (45 kg)';
        }

        return null;
    }
}
