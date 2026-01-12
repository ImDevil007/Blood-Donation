<?php

namespace App\Services\Backend\Admin;

use App\Models\BloodTransfer;
use App\Models\BloodInventory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BloodTransferStoreService
{
    public function store(array $requestData): BloodTransfer
    {
        $validated = $this->validate($requestData);

        // Check if source bank has sufficient stock
        $availableStock = $this->getAvailableStock($validated['source_bank'], $validated['blood_group'], $validated['blood_type']);
        
        if ($availableStock < $validated['quantity']) {
            throw new \Exception('Insufficient stock at source bank. Available: ' . $availableStock . ' ' . $validated['unit']);
        }

        $transfer = BloodTransfer::create([
            'transfer_id' => $this->generateTransferId(),
            'source_bank' => $validated['source_bank'],
            'destination_bank' => $validated['destination_bank'],
            'blood_group' => $validated['blood_group'],
            'blood_type' => $validated['blood_type'],
            'quantity' => $validated['quantity'],
            'unit' => $validated['unit'],
            'temperature' => $validated['temperature'] ?? null,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
            'requested_by' => Auth::user()->id,
        ]);

        return $transfer;
    }

    protected function validate(array $data): array
    {
        return validator($data, [
            'source_bank' => 'required|string|max:100',
            'destination_bank' => 'required|string|max:100|different:source_bank',
            'blood_group' => 'required|string|exists:lovs,id',
            'blood_type' => 'required|string|exists:lovs,id',
            'quantity' => 'required|numeric|min:0.01|max:9999.99',
            'unit' => 'required|string|max:20',
            'temperature' => 'nullable|numeric|between:-50,50',
            'notes' => 'nullable|string|max:1000',
        ], [
            'destination_bank.different' => 'Source and destination banks must be different.',
        ])->validate();
    }

    protected function generateTransferId(): string
    {
        $prefix = 'BT';
        $year = date('Y');
        $month = date('m');

        $lastTransfer = BloodTransfer::where('transfer_id', 'like', $prefix . $year . $month . '%')
            ->orderBy('transfer_id', 'desc')
            ->first();

        if ($lastTransfer) {
            $lastNumber = (int) substr($lastTransfer->transfer_id, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    protected function getAvailableStock(string $bank, string $bloodGroup, string $bloodType): float
    {
        return BloodInventory::where('storage_location', $bank)
            ->where('blood_group', $bloodGroup)
            ->where('blood_type', $bloodType)
            ->where('status', true)
            ->sum('quantity');
    }
}
