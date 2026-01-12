<?php

namespace App\Services\Backend\Admin;

use App\Models\BloodCollectionCamp;
use Illuminate\Support\Facades\Auth;

class BloodCollectionCampUpdateService
{
    public function update(BloodCollectionCamp $camp, array $requestData): BloodCollectionCamp
    {
        $validated = $this->validate($camp, $requestData);

        $updateData = [
            'name' => $validated['name'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'target_donors' => $validated['target_donors'],
            'actual_donors' => $validated['actual_donors'],
            'organizer_name' => $validated['organizer_name'],
            'organizer_contact' => $validated['organizer_contact'],
            'status' => $this->determineStatus($validated['start_date'], $validated['end_date'], $camp->status),
            'notes' => $validated['notes'],
            'updated_by' => Auth::user()->id,
        ];

        $camp->update($updateData);

        return $camp;
    }

    protected function validate(BloodCollectionCamp $camp, array $data): array
    {
        return validator($data, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'target_donors' => 'required|integer|min:1|max:10000',
            'actual_donors' => 'required|integer|max:10000',
            'organizer_name' => 'required|string|max:255',
            'organizer_contact' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ])->validate();
    }

    protected function determineStatus(string $startDate, string $endDate, string $currentStatus): string
    {
        $today = now()->toDateString();

        // Don't change status if camp is completed or cancelled
        if (in_array($currentStatus, ['completed', 'cancelled'])) {
            return $currentStatus;
        }

        if ($startDate > $today) {
            return 'scheduled';
        } elseif ($startDate <= $today && $endDate >= $today) {
            return 'ongoing';
        } else {
            return 'completed';
        }
    }
}
