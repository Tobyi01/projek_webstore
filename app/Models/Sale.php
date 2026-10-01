<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'transaction_number',
        'user_id',
        'customer',
        'payment_method',
        'subtotal',
        'discount_percent',
        'discount_amount',
        'tax',
        'other_fee',
        'total',
        'paid',
        'change',
        'sold_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_percent' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax' => 'decimal:2',
            'other_fee' => 'decimal:2',
            'total' => 'decimal:2',
            'paid' => 'decimal:2',
            'change' => 'decimal:2',
            'sold_at' => 'datetime',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}