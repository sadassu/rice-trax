<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductBatch extends Model
{
    /** @use HasFactory<\Database\Factories\ProductBatchFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'kg_remaining',
        'price_per_sack',
        'batch_number',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
