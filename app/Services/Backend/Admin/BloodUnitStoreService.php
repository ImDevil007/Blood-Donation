<?php

namespace App\Services\Backend\Admin;

use App\Models\BloodUnit;
use App\Models\Donor;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BloodUnitStoreService
{
    public function store(array $requestData): BloodUnit
    {
        $validated = $this->validate($requestData);

        // Get blood group from donor if donor exists in DB
        $donor = Donor::find($validated['donor_id']);
        $bloodGroup = $donor && $donor->blood_group ? $donor->blood_group : $validated['blood_group'];

        $bloodUnit = BloodUnit::create([
            'unit_id' => $this->generateUnitId(),
            'donor_id' => $validated['donor_id'],
            'blood_group' => $bloodGroup,
            'blood_type' => $validated['blood_type'],
            'collection_date' => $validated['collection_date'],
            'expiry_date' => $this->calculateExpiryDate($validated['collection_date'], $validated['blood_type']),
            'volume' => $validated['volume'],
            'storage_location' => $validated['storage_location'] ?? null,
            'temperature' => $validated['temperature'] ?? null,
            'hemoglobin_level' => $validated['hemoglobin_level'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => true,
            'is_used' => false,
            'created_by' => Auth::user()->id,
        ]);

        // Update donor's total donations count
        $this->updateDonorDonationCount($validated['donor_id']);

        return $bloodUnit;
    }

    protected function validate(array $data): array
    {
        return validator($data, [
            'donor_id' => 'required|exists:donors,id',
            'blood_group' => 'required|string|exists:lovs,id',
            'blood_type' => 'nullable|string|exists:lovs,id',
            'collection_date' => 'required|date|before_or_equal:today',
            'volume' => 'required|numeric|min:0.1|max:1000',
            'storage_location' => 'nullable|string|max:100',
            'temperature' => 'nullable|numeric|between:-50,50',
            'hemoglobin_level' => 'nullable|numeric|min:0|max:20',
            'notes' => 'nullable|string|max:1000',
        ])->validate();
    }

    protected function generateUnitId(): string
    {
        $prefix = 'BUNIT';
        $year = date('Y');
        $month = date('m');

        $lastUnit = BloodUnit::where('unit_id', 'like', $prefix . $year . $month . '%')
            ->orderBy('unit_id', 'desc')
            ->first();

        if ($lastUnit) {
            $lastNumber = (int) substr($lastUnit->unit_id, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    protected function calculateExpiryDate(string $collectionDate, string $bloodType = null): string
    {
        $collection = Carbon::parse($collectionDate);

        // Different blood components have different shelf lives
        switch ($bloodType) {
            case 'whole_blood':
                return $collection->addDays(42)->format('Y-m-d'); // 42 days
            case 'platelets':
                return $collection->addDays(5)->format('Y-m-d'); // 5 days
            case 'plasma':
                return $collection->addDays(365)->format('Y-m-d'); // 1 year frozen
            case 'red_blood_cells':
                return $collection->addDays(42)->format('Y-m-d'); // 42 days
            default:
                return $collection->addDays(42)->format('Y-m-d'); // Default 42 days
        }
    }

    protected function updateDonorDonationCount(int $donorId): void
    {
        $donor = Donor::find($donorId);
        if ($donor) {
            $donor->increment('total_donations');
            $donor->update(['last_donation_date' => now()]);
        }
    }
}
