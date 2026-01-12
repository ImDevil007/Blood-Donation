<?php

namespace App\Services\Backend\Admin;

use App\Models\BloodCollectionCamp;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BloodCollectionCampStoreService
{
    public function store(array $requestData): BloodCollectionCamp
    {
        $validated = $this->validate($requestData);

        $camp = BloodCollectionCamp::create([
            'camp_id' => $this->generateCampId(),
            'name' => $validated['name'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'target_donors' => $validated['target_donors'],
            'organizer_name' => $validated['organizer_name'],
            'organizer_contact' => $validated['organizer_contact'],
            'status' => $this->determineStatus($validated['start_date'], $validated['end_date']),
            'notes' => $validated['notes'],
            'created_by' => Auth::user()->id,
        ]);

        return $camp;
    }

    protected function validate(array $data): array
    {
        return validator($data, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'target_donors' => 'required|integer|min:1|max:10000',
            'organizer_name' => 'required|string|max:255',
            'organizer_contact' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ])->validate();
    }

    protected function generateCampId(): string
    {
        $prefix = 'CAMP';
        $year = date('Y');
        $month = date('m');

        $lastCamp = BloodCollectionCamp::where('camp_id', 'like', $prefix . $year . $month . '%')
            ->orderBy('camp_id', 'desc')
            ->first();

        if ($lastCamp) {
            $lastNumber = (int) substr($lastCamp->camp_id, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    protected function determineStatus(string $startDate, string $endDate): string
    {
        $today = now()->toDateString();

        if ($startDate > $today) {
            return 'scheduled';
        } elseif ($startDate <= $today && $endDate >= $today) {
            return 'ongoing';
        } else {
            return 'completed';
        }
    }
}
