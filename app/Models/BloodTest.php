<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'blood_unit_id',
        'technician_id',
        'test_date',
        'hiv_result',
        'hepatitis_b_result',
        'hepatitis_c_result',
        'syphilis_result',
        'malaria_result',
        'blood_group',
        'overall_status',
        'test_notes',
        'lab_reference',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'test_date' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function scopePending($query)
    {
        return $query->where('overall_status', 'pending');
    }

    public function scopePassed($query)
    {
        return $query->where('overall_status', 'passed');
    }

    public function scopeFailed($query)
    {
        return $query->where('overall_status', 'failed');
    }

    public function scopeQuarantined($query)
    {
        return $query->where('overall_status', 'quarantined');
    }

    public function scopeApproved($query)
    {
        return $query->where('overall_status', 'passed');
    }

    public function scopeRejected($query)
    {
        return $query->where('overall_status', 'failed');
    }

    public function scopeByResult($query, $result)
    {
        return $query->where('overall_status', $result);
    }

    // Relationships
    public function bloodUnit()
    {
        return $this->belongsTo(BloodUnit::class);
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

    // Helper methods
    public function isPassed()
    {
        return $this->overall_status === 'passed';
    }

    public function isFailed()
    {
        return $this->overall_status === 'failed';
    }

    public function isQuarantined()
    {
        return $this->overall_status === 'quarantined';
    }

    public function isPending()
    {
        return $this->overall_status === 'pending';
    }

    public function getStatusBadge()
    {
        switch ($this->overall_status) {
            case 'pending':
                return 'warning';
            case 'passed':
                return 'success';
            case 'failed':
                return 'danger';
            case 'quarantined':
                return 'secondary';
            default:
                return 'secondary';
        }
    }

    public function hasAnyPositiveResult()
    {
        return in_array('positive', [
            $this->hiv_result,
            $this->hepatitis_b_result,
            $this->hepatitis_c_result,
            $this->syphilis_result,
            $this->malaria_result,
        ]);
    }

    public function getTestResultsArray()
    {
        return [
            'HIV' => $this->hiv_result,
            'Hepatitis B' => $this->hepatitis_b_result,
            'Hepatitis C' => $this->hepatitis_c_result,
            'Syphilis' => $this->syphilis_result,
            'Malaria' => $this->malaria_result,
            'Blood Group' => $this->blood_group_result,
        ];
    }
}
