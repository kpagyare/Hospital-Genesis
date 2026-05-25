<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_id', 'name', 'category_id', 'generic_name', 'brand', 'type',
        'unit', 'purchase_price', 'selling_price', 'stock_quantity',
        'low_stock_alert', 'expiry_date', 'manufacturer', 'description', 'status',
    ];

    protected $casts = ['expiry_date' => 'date'];

    public function category()          { return $this->belongsTo(MedicineCategory::class); }
    public function prescriptionItems() { return $this->hasMany(PrescriptionItem::class); }
    public function saleItems()         { return $this->hasMany(MedicineSaleItem::class); }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->low_stock_alert;
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($med) {
            if (empty($med->medicine_id)) {
                $last = static::orderByDesc('id')->first();
                $number = $last ? ((int) substr($last->medicine_id, 4)) + 1 : 1;
                $med->medicine_id = 'MED-' . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
