<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'expense_id', 'title', 'category', 'amount', 'expense_date',
        'description', 'receipt', 'created_by',
    ];

    protected $casts = ['expense_date' => 'date'];

    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($exp) {
            if (empty($exp->expense_id)) {
                $last = static::orderByDesc('id')->first();
                $number = $last ? ((int) substr($last->expense_id, 4)) + 1 : 1;
                $exp->expense_id = 'EXP-' . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
