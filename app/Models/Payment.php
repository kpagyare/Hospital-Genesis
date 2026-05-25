<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'payment_id', 'invoice_id', 'patient_id', 'amount', 'payment_date',
        'payment_method', 'transaction_reference', 'notes', 'received_by',
    ];

    protected $casts = ['payment_date' => 'date'];

    public function invoice()    { return $this->belongsTo(Invoice::class); }
    public function patient()    { return $this->belongsTo(Patient::class); }
    public function receivedBy() { return $this->belongsTo(User::class, 'received_by'); }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($pay) {
            if (empty($pay->payment_id)) {
                $last = static::orderByDesc('id')->first();
                $number = $last ? ((int) substr($last->payment_id, 4)) + 1 : 1;
                $pay->payment_id = 'PAY-' . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
