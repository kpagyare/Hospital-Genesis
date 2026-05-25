<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id', 'patient_id', 'doctor_id', 'appointment_id',
        'prescription_date', 'diagnosis', 'notes', 'status',
    ];

    protected $casts = ['prescription_date' => 'date'];

    public function patient()     { return $this->belongsTo(Patient::class); }
    public function doctor()      { return $this->belongsTo(Doctor::class); }
    public function appointment() { return $this->belongsTo(Appointment::class); }
    public function items()       { return $this->hasMany(PrescriptionItem::class); }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($pre) {
            if (empty($pre->prescription_id)) {
                $last = static::orderByDesc('id')->first();
                $number = $last ? ((int) substr($last->prescription_id, 4)) + 1 : 1;
                $pre->prescription_id = 'PRE-' . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
