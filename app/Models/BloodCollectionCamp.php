<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BloodCollectionCamp extends Model
{
    use HasFactory;

    protected $fillable = [
        'camp_id',
        'name',
        'description',
        'location',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'target_donors',
        'actual_donors',
        'collected_units',
        'organizer_name',
        'organizer_contact',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
            'target_donors' => 'integer',
            'actual_donors' => 'integer',
            'collected_units' => 'integer',
        ];
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now()->toDateString());
    }

    public function scopePast($query)
    {
        return $query->where('end_date', '<', now()->toDateString());
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

    // Helper methods
    public function isUpcoming()
    {
        return $this->start_date > now()->toDateString();
    }

    public function isOngoing()
    {
        $today = now()->toDateString();
        return $this->start_date <= $today && $this->end_date >= $today && $this->status === 'ongoing';
    }

    public function isCompleted()
    {
        return $this->end_date < now()->toDateString() || $this->status === 'completed';
    }

    public function getDurationInDays()
    {
        return Carbon::parse($this->start_date)->diffInDays(Carbon::parse($this->end_date)) + 1;
    }

    public function getSuccessRate()
    {
        if ($this->target_donors == 0) return 0;
        return round(($this->actual_donors / $this->target_donors) * 100, 2);
    }

    public function getStatusBadge()
    {
        switch ($this->status) {
            case 'scheduled':
                return 'info';
            case 'ongoing':
                return 'success';
            case 'completed':
                return 'primary';
            case 'cancelled':
                return 'danger';
            default:
                return 'secondary';
        }
    }
}
