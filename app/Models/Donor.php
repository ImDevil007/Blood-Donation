<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'title',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'district',
        'blood_group',
        'gender',
        'dob',
        'age',
        'weight',
        'height',
        'medical_history',
        'allergies',
        'medications',
        'last_donation_date',
        'total_donations',
        'is_eligible',
        'eligibility_reason',
        'photo_path',
        'id_proof_path',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'status',
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
            'dob' => 'date',
            'last_donation_date' => 'date',
            'is_eligible' => 'boolean',
            'status' => 'boolean',
        ];
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeEligible($query)
    {
        return $query->where('is_eligible', true);
    }

    public function scopeByBloodGroup($query, $bloodGroupId)
    {
        return $query->where('blood_group', $bloodGroupId);
    }

    public function scopeByGender($query, $genderId)
    {
        return $query->where('gender', $genderId);
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

    public function userGender()
    {
        return $this->belongsTo(Lov::class, 'gender');
    }

    public function userTitle()
    {
        return $this->belongsTo(Lov::class, 'title');
    }

    public function userBloodGroup()
    {
        return $this->belongsTo(Lov::class, 'blood_group');
    }

    public function donationHistories()
    {
        return $this->hasMany(DonationHistory::class);
    }

    public function latestDonation()
    {
        return $this->hasOne(DonationHistory::class)->latest();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
