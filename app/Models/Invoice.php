<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number', 'patient_id', 'appointment_id', 'admission_id',
        'invoice_date', 'due_date', 'subtotal', 'discount', 'tax',
        'total_amount', 'paid_amount', 'due_amount', 'status', 'notes', 'created_by',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date'     => 'date',
    ];

    public function patient()     { return $this->belongsTo(Patient::class); }
    public function appointment() { return $this->belongsTo(Appointment::class); }
    public function admission()   { return $this->belongsTo(Admission::class); }
    public function items()       { return $this->hasMany(InvoiceItem::class); }
    public function payments()    { return $this->hasMany(Payment::class); }
    public function createdBy()   { return $this->belongsTo(User::class, 'created_by'); }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'paid'           => '<span class="badge bg-success">Paid</span>',
            'partially_paid' => '<span class="badge bg-warning">Partial</span>',
            'overdue'        => '<span class="badge bg-danger">Overdue</span>',
            'draft'          => '<span class="badge bg-secondary">Draft</span>',
            'cancelled'      => '<span class="badge bg-dark">Cancelled</span>',
            default          => '<span class="badge bg-info">' . ucfirst($this->status) . '</span>',
        };
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($inv) {
            if (empty($inv->invoice_number)) {
                $last = static::orderByDesc('id')->first();
                $number = $last ? ((int) substr($last->invoice_number, 4)) + 1 : 1;
                $inv->invoice_number = 'INV-' . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
