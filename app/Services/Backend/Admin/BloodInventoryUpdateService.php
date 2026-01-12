<?php

namespace App\Services\Backend\Admin;

use App\Models\BloodInventory;
use Illuminate\Support\Facades\Auth;

class BloodInventoryUpdateService
{
    public function update(BloodInventory $inventory, array $requestData): BloodInventory
    {
        $validated = $this->validate($inventory, $requestData);

        $updateData = [
            'blood_group' => $validated['blood_group'],
            'blood_type' => $validated['blood_type'],
            'quantity' => $validated['quantity'],
            'unit' => $validated['unit'],
            'collection_date' => $validated['collection_date'],
            'expiry_date' => $validated['expiry_date'],
            'storage_location' => $validated['storage_location'],
            'temperature' => $validated['temperature'],
            'notes' => $validated['notes'],
            'updated_by' => Auth::user()->id,
        ];

        $inventory->update($updateData);

        return $inventory;
    }

    protected function validate(BloodInventory $inventory, array $data): array
    {
        return validator($data, [
            'blood_group' => 'required|string|exists:lovs,id',
            'blood_type' => 'nullable|string|exists:lovs,id',
            'quantity' => 'required|numeric|min:0.01|max:9999.99',
            'unit' => 'required|string|max:20',
            'collection_date' => 'required|date|before_or_equal:today',
            'expiry_date' => 'required|date|after:collection_date',
            'storage_location' => 'nullable|string|max:100',
            'temperature' => 'nullable|numeric|between:-50,50',
            'notes' => 'nullable|string|max:1000',
        ])->validate();
    }
}




