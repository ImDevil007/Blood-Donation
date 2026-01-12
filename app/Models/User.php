<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'blood_group',
        'gender',
        'dob',
        'age',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function scopeIsAdmin()
    {
        return $this->roles->contains('id', 1);
    }

    public function scopeIsStaff()
    {
        return $this->roles->contains('id', 2);
    }

    public function scopeIsDoctor()
    {
        return $this->roles->contains('id', 3);
    }

    public function scopeIsLabTechnician()
    {
        return $this->roles->contains('id', 4);
    }
    public function scopeIsDonor()
    {
        return $this->roles->contains('id', 5);
    }

    public function userGender()
    {
        return $this->hasOne(Lov::class, 'id', 'gender');
    }

    public function userTitle()
    {
        return $this->hasOne(Lov::class, 'id', 'title');
    }

    public function userBloodGroup()
    {
        return $this->hasOne(Lov::class, 'id', 'blood_group');
    }

    public function donor()
    {
        return $this->hasOne(Donor::class, 'email', 'email');
    }
}
