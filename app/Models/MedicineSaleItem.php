<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineSaleItem extends Model
{
    protected $fillable = ['sale_id', 'medicine_id', 'quantity', 'unit_price', 'total'];

    public function sale()     { return $this->belongsTo(MedicineSale::class, 'sale_id'); }
    public function medicine() { return $this->belongsTo(Medicine::class); }
}
