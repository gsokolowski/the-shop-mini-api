<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'price',
    ];

    protected function casts(): array
    {
        // treats it as money with 2 decimal places, not a raw DB/string/float value.
        return [
            'price' => 'decimal:2',
        ];
    }

    // Order belongs to User 
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Order belongs to Product
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
