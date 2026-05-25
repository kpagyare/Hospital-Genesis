<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id', 'user_id', 'first_name', 'last_name', 'email', 'phone',
        'specialization', 'qualification', 'experience_years', 'consultation_fee',
        'bio', 'photo', 'gender', 'available_days', 'available_from', 'available_to', 'status',
    ];

    protected $casts = ['available_days' => 'array'];

    public function user()         { return $this->belongsTo(User::class); }
    public function appointments() { return $this->hasMany(Appointment::class); }
    public function prescriptions(){ return $this->hasMany(Prescription::class); }
    public function labResults()   { return $this->hasMany(LabResult::class); }
    public function admissions()   { return $this->hasMany(Admission::class); }

    public function getFullNameAttribute(): string
    {
        return 'Dr. ' . $this->first_name . ' ' . $this->last_name;
    }

    public function getPhotoUrlAttribute(): string
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : asset('assets/images/default-doctor.png');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($doctor) {
            if (empty($doctor->doctor_id)) {
                $last = static::orderByDesc('id')->first();
                $number = $last ? ((int) substr($last->doctor_id, 4)) + 1 : 1;
                $doctor->doctor_id = 'DOC-' . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
