<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    use HasFactory;

    protected $fillable = [
        'bed_number', 'ward_id', 'status', 'bed_type', 'charge_per_day',
    ];

    public function ward()      { return $this->belongsTo(Ward::class); }
    public function admissions(){ return $this->hasMany(Admission::class); }
    public function currentAdmission() {
        return $this->hasOne(Admission::class)->where('status', 'admitted');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'available'   => '<span class="badge bg-success">Available</span>',
            'occupied'    => '<span class="badge bg-danger">Occupied</span>',
            'maintenance' => '<span class="badge bg-warning">Maintenance</span>',
            default       => '<span class="badge bg-secondary">' . $this->status . '</span>',
        };
    }
}
