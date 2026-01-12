<?php

namespace App\Services\Backend\Admin;

use App\Models\BloodInventory;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BloodInventoryStoreService
{
    public function store(array $requestData): BloodInventory
    {
        $validated = $this->validate($requestData);

        $inventory = BloodInventory::create([
            'inventory_id' => $this->generateInventoryId(),
            'blood_group' => $validated['blood_group'],
            'blood_type' => $validated['blood_type'],
            'quantity' => $validated['quantity'],
            'unit' => $validated['unit'],
            'collection_date' => $validated['collection_date'],
            'expiry_date' => $validated['expiry_date'],
            'storage_location' => $validated['storage_location'],
            'temperature' => $validated['temperature'],
            'notes' => $validated['notes'],
            'status' => true,
            'created_by' => Auth::user()->id,
        ]);

        return $inventory;
    }

    protected function validate(array $data): array
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

    protected function generateInventoryId(): string
    {
        $prefix = 'BINV';
        $year = date('Y');
        $month = date('m');

        $lastInventory = BloodInventory::where('inventory_id', 'like', $prefix . $year . $month . '%')
            ->orderBy('inventory_id', 'desc')
            ->first();

        if ($lastInventory) {
            $lastNumber = (int) substr($lastInventory->inventory_id, -4);
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
}




