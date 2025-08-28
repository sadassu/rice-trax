<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'price_per_kilo',
    ];

    public function batches()
    {
        return $this->hasMany(ProductBatch::class);
    }

    public function saleDetail()
    {
        return $this->hasMany(SaleDetail::class);
    }
}
