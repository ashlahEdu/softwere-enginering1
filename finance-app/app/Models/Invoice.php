<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = ['invoice_number', 'expense_id', 'org_name'];

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }
}
