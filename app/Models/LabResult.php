<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabResult extends Model
{
    protected $fillable = [
        'result_id', 'patient_id', 'doctor_id', 'lab_test_id', 'appointment_id',
        'test_date', 'result_value', 'remarks', 'report_file', 'performed_by', 'status',
    ];

    protected $casts = ['test_date' => 'date'];

    public function patient()     { return $this->belongsTo(Patient::class); }
    public function doctor()      { return $this->belongsTo(Doctor::class); }
    public function labTest()     { return $this->belongsTo(LabTest::class, 'lab_test_id'); }
    public function appointment() { return $this->belongsTo(Appointment::class); }
    public function performedBy() { return $this->belongsTo(User::class, 'performed_by'); }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($res) {
            if (empty($res->result_id)) {
                $last = static::orderByDesc('id')->first();
                $number = $last ? ((int) substr($last->result_id, 4)) + 1 : 1;
                $res->result_id = 'RES-' . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
