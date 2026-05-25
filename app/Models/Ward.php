<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'ward_type', 'total_beds', 'bed_charge_per_day', 'description', 'status',
    ];

    public function beds()      { return $this->hasMany(Bed::class); }
    public function availableBeds() { return $this->hasMany(Bed::class)->where('status', 'available'); }
}
