<?php

namespace App\Services\Backend\Admin;

use App\Models\BloodTransfer;
use App\Models\BloodInventory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BloodTransferUpdateService
{
    public function approve(BloodTransfer $transfer): BloodTransfer
    {
        if (!$transfer->isPending()) {
            throw new \Exception('Only pending transfers can be approved.');
        }

        // Re-validate stock availability
        $availableStock = $this->getAvailableStock($transfer->source_bank, $transfer->blood_group, $transfer->blood_type);
        
        if ($availableStock < $transfer->quantity) {
            throw new \Exception('Insufficient stock at source bank. Available: ' . $availableStock . ' ' . $transfer->unit);
        }

        DB::beginTransaction();
        try {
            // Update transfer status
            $transfer->update([
                'status' => 'approved',
                'approved_by' => Auth::user()->id,
                'approved_at' => now(),
            ]);

            // Update inventory: Decrease from source bank
            $this->decreaseSourceInventory($transfer);

            // Update inventory: Increase to destination bank
            $this->increaseDestinationInventory($transfer);

            // Mark transfer as completed
            $transfer->update(['status' => 'completed']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $transfer;
    }

    public function reject(BloodTransfer $transfer, string $rejectionReason): BloodTransfer
    {
        if (!$transfer->isPending()) {
            throw new \Exception('Only pending transfers can be rejected.');
        }

        $transfer->update([
            'status' => 'rejected',
            'rejection_reason' => $rejectionReason,
            'approved_by' => Auth::user()->id,
            'approved_at' => now(),
        ]);

        return $transfer;
    }

    public function cancel(BloodTransfer $transfer): BloodTransfer
    {
        if (!$transfer->isPending()) {
            throw new \Exception('Only pending transfers can be cancelled.');
        }

        $transfer->update([
            'status' => 'cancelled',
        ]);

        return $transfer;
    }

    protected function decreaseSourceInventory(BloodTransfer $transfer): void
    {
        $inventories = BloodInventory::where('storage_location', $transfer->source_bank)
            ->where('blood_group', $transfer->blood_group)
            ->where('blood_type', $transfer->blood_type)
            ->where('status', true)
            ->orderBy('expiry_date', 'asc') // FIFO: First In First Out
            ->get();

        $remainingQuantity = $transfer->quantity;

        foreach ($inventories as $inventory) {
            if ($remainingQuantity <= 0) {
                break;
            }

            if ($inventory->quantity <= $remainingQuantity) {
                // Consume entire inventory entry
                $remainingQuantity -= $inventory->quantity;
                $inventory->update(['quantity' => 0, 'status' => false]);
            } else {
                // Partially consume inventory entry
                $inventory->decrement('quantity', $remainingQuantity);
                $remainingQuantity = 0;
            }
        }

        if ($remainingQuantity > 0) {
            throw new \Exception('Insufficient stock to complete transfer.');
        }
    }

    protected function increaseDestinationInventory(BloodTransfer $transfer): void
    {
        // Find existing inventory at destination with same blood group and blood type
        $existingInventory = BloodInventory::where('storage_location', $transfer->destination_bank)
            ->where('blood_group', $transfer->blood_group)
            ->where('blood_type', $transfer->blood_type)
            ->where('status', true)
            ->first();

        if ($existingInventory) {
            // Add to existing inventory
            $existingInventory->increment('quantity', $transfer->quantity);
        } else {
            // Create new inventory entry at destination
            BloodInventory::create([
                'inventory_id' => $this->generateInventoryId(),
                'blood_group' => $transfer->blood_group,
                'blood_type' => $transfer->blood_type,
                'quantity' => $transfer->quantity,
                'unit' => $transfer->unit,
                'collection_date' => now()->toDateString(),
                'expiry_date' => now()->addDays(42)->toDateString(), // Default 42 days expiry
                'storage_location' => $transfer->destination_bank,
                'temperature' => $transfer->temperature,
                'notes' => $transfer->notes,
                'status' => true,
                'created_by' => Auth::user()->id,
            ]);
        }
    }

    protected function getAvailableStock(string $bank, string $bloodGroup, string $bloodType): float
    {
        return BloodInventory::where('storage_location', $bank)
            ->where('blood_group', $bloodGroup)
            ->where('blood_type', $bloodType)
            ->where('status', true)
            ->sum('quantity');
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
}
