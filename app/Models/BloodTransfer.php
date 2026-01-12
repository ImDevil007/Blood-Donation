<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'transfer_id',
        'source_bank',
        'destination_bank',
        'blood_group',
        'blood_type',
        'quantity',
        'unit',
        'temperature',
        'status',
        'rejection_reason',
        'notes',
        'requested_by',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'temperature' => 'decimal:2',
            'approved_at' => 'datetime',
        ];
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByBloodGroup($query, $bloodGroupId)
    {
        return $query->where('blood_group', $bloodGroupId);
    }

    // Relationships
    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function bloodGroup()
    {
        return $this->belongsTo(Lov::class, 'blood_group');
    }

    public function bloodType()
    {
        return $this->belongsTo(Lov::class, 'blood_type');
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }
}
