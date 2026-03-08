<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Expense extends Model
{
    protected $fillable = ['date', 'category', 'amount', 'description', 'member_id', 'proof_image', 'invoice_number'];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($expense) {
            $expense->invoice_number = 'INV-' . date('Y') . '-' . str_pad(Expense::count() + 1, 4, '0', STR_PAD_LEFT);
        });
    }
}
