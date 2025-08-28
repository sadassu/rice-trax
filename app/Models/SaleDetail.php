<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleDetail extends Model
{
    /** @use HasFactory<\Database\Factories\SaleDetailFactory> */
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'kilograms',
        'price_per_kilo',
        'total_price',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
