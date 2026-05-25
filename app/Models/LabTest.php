<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabTest extends Model
{
    protected $fillable = [
        'test_code', 'name', 'category_id', 'price', 'normal_range', 'unit', 'description', 'status',
    ];

    public function category()   { return $this->belongsTo(LabTestCategory::class, 'category_id'); }
    public function labResults() { return $this->hasMany(LabResult::class); }
}
