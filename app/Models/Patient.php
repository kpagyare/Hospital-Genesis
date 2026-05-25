<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'user_id', 'first_name', 'last_name', 'email', 'phone',
        'date_of_birth', 'gender', 'blood_group', 'address', 'city', 'state',
        'country', 'photo', 'emergency_contact_name', 'emergency_contact_phone',
        'emergency_contact_relation', 'medical_history', 'allergies', 'status',
    ];

    protected $casts = ['date_of_birth' => 'date'];

    public function user()         { return $this->belongsTo(User::class); }
    public function appointments() { return $this->hasMany(Appointment::class); }
    public function admissions()   { return $this->hasMany(Admission::class); }
    public function invoices()     { return $this->hasMany(Invoice::class); }
    public function payments()     { return $this->hasMany(Payment::class); }
    public function prescriptions(){ return $this->hasMany(Prescription::class); }
    public function labResults()   { return $this->hasMany(LabResult::class); }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    public function getPhotoUrlAttribute(): string
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : asset('assets/images/default-patient.png');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($patient) {
            if (empty($patient->patient_id)) {
                $last = static::orderByDesc('id')->first();
                $number = $last ? ((int) substr($last->patient_id, 4)) + 1 : 1;
                $patient->patient_id = 'PAT-' . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
