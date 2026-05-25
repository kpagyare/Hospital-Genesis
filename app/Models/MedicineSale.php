<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineSale extends Model
{
    protected $fillable = [
        'sale_id', 'patient_id', 'prescription_id', 'sale_date',
        'total_amount', 'discount', 'paid_amount', 'payment_method', 'sold_by',
    ];

    protected $casts = ['sale_date' => 'date'];

    public function patient()      { return $this->belongsTo(Patient::class); }
    public function prescription() { return $this->belongsTo(Prescription::class); }
    public function items()        { return $this->hasMany(MedicineSaleItem::class, 'sale_id'); }
    public function soldBy()       { return $this->belongsTo(User::class, 'sold_by'); }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($sale) {
            if (empty($sale->sale_id)) {
                $last = static::orderByDesc('id')->first();
                $number = $last ? ((int) substr($last->sale_id, 4)) + 1 : 1;
                $sale->sale_id = 'SAL-' . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
