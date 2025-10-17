<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

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

    protected $casts = [
        'sale_date' => 'datetime',
    ];

    public function saleDetails(): HasMany
    {
        return $this->hasMany(SaleDetail::class);
    }

    // 🔹 Total sales for today
    public static function totalSalesToday()
    {
        return self::whereDate('sale_date', Carbon::today())
            ->sum('total_price');
    }

    // 🔹 Total sales for this week
    public static function totalSalesWeek()
    {
        return self::whereBetween('sale_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('total_price');
    }

    // 🔹 Total sales for this month
    public static function totalSalesMonth()
    {
        return self::whereYear('sale_date', Carbon::now()->year)
            ->whereMonth('sale_date', Carbon::now()->month)
            ->sum('total_price');
    }
}
