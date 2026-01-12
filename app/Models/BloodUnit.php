<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BloodUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'donor_id',
        'blood_group',
        'blood_type',
        'collection_date',
        'expiry_date',
        'volume',
        'storage_location',
        'temperature',
        'hemoglobin_level',
        'status',
        'is_used',
        'used_date',
        'used_for',
        'notes',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'collection_date' => 'date',
            'expiry_date' => 'date',
            'used_date' => 'date',
            'volume' => 'decimal:2',
            'temperature' => 'decimal:2',
            'hemoglobin_level' => 'decimal:2',
            'test_results' => 'array',
            'status' => 'boolean',
            'is_used' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_used', false)
                    ->where('status', true)
                    ->where('expiry_date', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<=', now());
    }

    public function scopeExpiringSoon($query, $days = 7)
    {
        return $query->where('expiry_date', '<=', now()->addDays($days))
                    ->where('expiry_date', '>', now());
    }

    public function scopeByBloodGroup($query, $bloodGroupId)
    {
        return $query->where('blood_group', $bloodGroupId);
    }

    public function scopeByBloodType($query, $bloodTypeId)
    {
        return $query->where('blood_type', $bloodTypeId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('is_used', $status);
    }

    // Relationships
    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function createBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updateBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function bloodGroup()
    {
        return $this->belongsTo(Lov::class, 'blood_group');
    }

    public function bloodType()
    {
        return $this->belongsTo(Lov::class, 'blood_type');
    }

    // Helper methods
    public function isExpired()
    {
        return $this->expiry_date <= now();
    }

    public function isExpiringSoon($days = 7)
    {
        return $this->expiry_date <= now()->addDays($days) && $this->expiry_date > now();
    }

    public function getDaysUntilExpiry()
    {
        return now()->diffInDays($this->expiry_date, false);
    }

    public function getStatusBadge()
    {
        if ($this->is_used) {
            return 'used';
        } elseif ($this->isExpired()) {
            return 'expired';
        } elseif ($this->isExpiringSoon()) {
            return 'expiring_soon';
        } else {
            return 'available';
        }
    }
}
