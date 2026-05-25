<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'photo', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    public function isSuperAdmin(): bool { return $this->role === 'super_admin'; }
    public function isDoctor(): bool     { return $this->role === 'doctor'; }
    public function isNurse(): bool      { return $this->role === 'nurse'; }
    public function isReceptionist(): bool { return $this->role === 'receptionist'; }
    public function isPharmacist(): bool { return $this->role === 'pharmacist'; }
    public function isLabStaff(): bool   { return $this->role === 'lab_staff'; }
    public function isAccountant(): bool { return $this->role === 'accountant'; }
    public function isPatient(): bool    { return $this->role === 'patient'; }

    public function doctor()        { return $this->hasOne(Doctor::class); }
    public function patient()       { return $this->hasOne(Patient::class); }
    public function staff()         { return $this->hasOne(Staff::class); }
    public function notifications() { return $this->hasMany(Notification::class); }

    public function getPhotoUrlAttribute(): string
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : asset('assets/images/default-avatar.png');
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'super_admin'  => 'Super Admin',
            'doctor'       => 'Doctor',
            'nurse'        => 'Nurse',
            'receptionist' => 'Receptionist',
            'pharmacist'   => 'Pharmacist',
            'lab_staff'    => 'Lab Staff',
            'accountant'   => 'Accountant',
            'patient'      => 'Patient',
            default        => ucfirst($this->role),
        };
    }
}
