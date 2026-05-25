<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    use HasFactory;

    protected $fillable = [
        'admission_id', 'patient_id', 'doctor_id', 'bed_id',
        'admission_date', 'discharge_date', 'diagnosis', 'treatment',
        'discharge_notes', 'status', 'total_charges', 'created_by',
    ];

    protected $casts = [
        'admission_date'  => 'date',
        'discharge_date'  => 'date',
        'total_charges'   => 'decimal:2',
    ];

    public function patient()   { return $this->belongsTo(Patient::class); }
    public function doctor()    { return $this->belongsTo(Doctor::class); }
    public function bed()       { return $this->belongsTo(Bed::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
    public function invoice()   { return $this->hasOne(Invoice::class); }

    public function getDaysStayedAttribute(): int
    {
        $end = $this->discharge_date ?? now();
        return (int) $this->admission_date->diffInDays($end);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($adm) {
            if (empty($adm->admission_id)) {
                $last = static::orderByDesc('id')->first();
                $number = $last ? ((int) substr($last->admission_id, 4)) + 1 : 1;
                $adm->admission_id = 'ADM-' . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
