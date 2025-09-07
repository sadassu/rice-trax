<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'price_per_kilo',
    ];

    public function batches(): HasMany
    {
        return $this->hasMany(ProductBatch::class);
    }

    public function saleDetail(): HasMany
    {
        return $this->hasMany(SaleDetail::class);
    }
}
