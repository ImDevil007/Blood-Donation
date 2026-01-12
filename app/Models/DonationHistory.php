<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'donation_date',
        'donation_type',
        'blood_volume',
        'collection_location',
        'camp_id',
        'technician_id',
        'test_results',
        'hemoglobin_level',
        'blood_pressure',
        'pulse_rate',
        'temperature',
        'weight_at_donation',
        'donation_status',
        'rejection_reason',
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
            'donation_date' => 'datetime',
            'test_results' => 'array',
            'hemoglobin_level' => 'decimal:2',
            'blood_pressure' => 'string',
            'pulse_rate' => 'integer',
            'temperature' => 'decimal:2',
            'weight_at_donation' => 'decimal:2',
        ];
    }

    public function scopeSuccessful($query)
    {
        return $query->where('donation_status', 'successful');
    }

    public function scopeRejected($query)
    {
        return $query->where('donation_status', 'rejected');
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('donation_date', [$startDate, $endDate]);
    }

    // Relationships
    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function createBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updateBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function donationType()
    {
        return $this->belongsTo(Lov::class, 'donation_type');
    }

    public function collectionLocation()
    {
        return $this->belongsTo(Lov::class, 'collection_location');
    }
}
