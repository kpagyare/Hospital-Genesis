<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = [
        'staff_id', 'user_id', 'first_name', 'last_name', 'email', 'phone',
        'department', 'position', 'join_date', 'salary', 'photo', 'gender',
        'address', 'emergency_contact', 'status',
    ];

    protected $casts = ['join_date' => 'date'];

    public function user() { return $this->belongsTo(User::class); }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($stf) {
            if (empty($stf->staff_id)) {
                $last = static::orderByDesc('id')->first();
                $number = $last ? ((int) substr($last->staff_id, 4)) + 1 : 1;
                $stf->staff_id = 'STF-' . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
