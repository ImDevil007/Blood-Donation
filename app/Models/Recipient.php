<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_code',
        'title',
        'first_name',
        'last_name',
        'dob',
        'gender',
        'blood_group',
        'contact_number',
        'email',
        'address',
        'city',
        'district',
        'hospital_name',
        'doctor_name',
        'admission_date',
        'blood_required_date',
        'blood_quantity_required',
        'request_status',
        'diagnosis',
        'notes',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'dob' => 'date',
        'admission_date' => 'date',
        'blood_required_date' => 'date',
        'status' => 'boolean',
    ];

    protected $hidden = [
        'created_by',
        'updated_by',
    ];

    // Relationships
    public function createBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updateBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Blood Group relationship
    public function userBloodGroup()
    {
        return $this->belongsTo(Lov::class, 'blood_group');
    }

    // Gender relationship
    public function userGender()
    {
        return $this->belongsTo(Lov::class, 'gender');
    }

    // Title relationship
    public function userTitle()
    {
        return $this->belongsTo(Lov::class, 'title');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopePending($query)
    {
        return $query->where('request_status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('request_status', 'accepted');
    }

    public function scopeFulfilled($query)
    {
        return $query->where('request_status', 'fulfilled');
    }

    public function scopeRejected($query)
    {
        return $query->where('request_status', 'rejected');
    }

    // Accessors
    public function getFullNameAttribute()
    {
        $title = $this->userTitle?->name ? $this->userTitle->name . ' ' : '';
        return $title . $this->first_name . ' ' . $this->last_name;
    }

    public function getAgeAttribute()
    {
        return $this->dob ? $this->dob->age : null;
    }
}
