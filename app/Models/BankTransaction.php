<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankTransaction extends Model
{
    protected $fillable = [
        'service_order_id',
        'bank_txn_id',
        'amount',
        'description',
        'matched_at',
    ];

    protected $casts = [
        'amount' => 'integer',
        'matched_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class, 'service_order_id');
    }
}
