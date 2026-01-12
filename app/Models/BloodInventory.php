<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BloodInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'blood_group',
        'blood_type',
        'quantity',
        'unit',
        'collection_date',
        'expiry_date',
        'storage_location',
        'temperature',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'collection_date' => 'date',
            'expiry_date' => 'date',
            'quantity' => 'decimal:2',
            'temperature' => 'decimal:2',
            'status' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeByBloodGroup($query, $bloodGroupId)
    {
        return $query->where('blood_group', $bloodGroupId);
    }

    // Relationships
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
}




