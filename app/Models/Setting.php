<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'hospital_name', 'hospital_email', 'hospital_phone', 'hospital_address',
        'hospital_logo', 'currency', 'timezone', 'footer_text',
    ];

    public static function getValue(string $key, $default = null)
    {
        $setting = static::first();
        return $setting ? ($setting->$key ?? $default) : $default;
    }
}
