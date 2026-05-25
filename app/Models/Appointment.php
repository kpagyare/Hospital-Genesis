<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id', 'patient_id', 'doctor_id', 'appointment_date',
        'appointment_time', 'reason', 'notes', 'status', 'type', 'fee',
        'is_paid', 'created_by',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'is_paid' => 'boolean',
        'fee' => 'decimal:2',
    ];

    public function patient()    { return $this->belongsTo(Patient::class); }
    public function doctor()     { return $this->belongsTo(Doctor::class); }
    public function createdBy()  { return $this->belongsTo(User::class, 'created_by'); }
    public function invoice()    { return $this->hasOne(Invoice::class); }
    public function prescription(){ return $this->hasOne(Prescription::class); }
    public function labResults() { return $this->hasMany(LabResult::class); }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'   => '<span class="badge bg-warning">Pending</span>',
            'confirmed' => '<span class="badge bg-info">Confirmed</span>',
            'completed' => '<span class="badge bg-success">Completed</span>',
            'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
            'no_show'   => '<span class="badge bg-secondary">No Show</span>',
            default     => '<span class="badge bg-secondary">' . $this->status . '</span>',
        };
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($apt) {
            if (empty($apt->appointment_id)) {
                $last = static::orderByDesc('id')->first();
                $number = $last ? ((int) substr($last->appointment_id, 4)) + 1 : 1;
                $apt->appointment_id = 'APT-' . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
