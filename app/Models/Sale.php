<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    /** @use HasFactory<\Database\Factories\SaleFactory> */
    use HasFactory;

    protected $fillable = [
        'total_price',
        'sale_date',
        'amount_paid',
        'change'
    ];

    public function saleDetail(): HasMany
    {
        return $this->hasMany(SaleDetail::class);
    }
}
