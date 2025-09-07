<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $forecastedSale     = $this->forecastSales();
        $topMostSaleProduct = $this->topTenMostSaleProduct();
        $topFewSaleProduct  = $this->topTenFewSaleProduct();
        $lowStockProducts   = $this->lowStocksProduct();
        $recentLogs         = $this->recentActivityLogs();

        return Inertia::render('Dashboard/AdminDashboard', [
            'forecastedSale'     => $forecastedSale,
            'totals' => [
                'today' => Sale::totalSalesToday(),
                'week'  => Sale::totalSalesWeek(),
                'month' => Sale::totalSalesMonth(),
            ],
            'topMostSaleProduct' => $topMostSaleProduct,
            'topFewSaleProduct'  => $topFewSaleProduct,
            'lowStockProducts'   => $lowStockProducts,
            'recentLogs'         => $recentLogs,
        ]);
    }

    private function getSale()
    {
        $sale = Sale::all();
        return $sale;
    }

    // Inventory Turnover Rate

    // Dead Stock / Slow-Moving Items

    //holts linear can capture TREND growing and declining
    //it cant handle the seasonality( if there is a evens)

    //!!the zero value should not be count so it will not affect the equation

    // first take the average value per day
    private function holtsLinearTrendSale(array $sales, float $alpha = 0.8, float $beta = 0.2, int $forecastPeriods = 5)
    {
        $n = count($sales);
        if ($n < 2) {
            throw new \Exception("Need at least 2 non-zero data points for Holt’s Linear Trend.");
        }

        // --- Initialization ---
        $level = $sales[0];
        $trend = $sales[1] - $sales[0];

        $levels = [$level];
        $trends = [$trend];

        // --- Update Level & Trend ---
        for ($t = 1; $t < $n; $t++) {
            $lastLevel = $level;
            $level = $alpha * $sales[$t] + (1 - $alpha) * ($level + $trend);
            $trend = $beta * ($level - $lastLevel) + (1 - $beta) * $trend;

            $levels[] = $level;
            $trends[] = $trend;
        }

        // --- Forecast Future ---
        $forecasts = [];
        for ($h = 1; $h <= $forecastPeriods; $h++) {
            $forecasts[] = $level + $h * $trend;
        }

        return [
            'levels' => $levels,
            'trends' => $trends,
            'forecast' => $forecasts
        ];
    }

    public function forecastSales()
    {
        $dailySales = DB::table('sales')
            ->selectRaw('sale_date, SUM(total_price) as daily_revenue')
            ->groupBy('sale_date')
            ->havingRaw('SUM(total_price) > 0')
            ->orderBy('sale_date')
            ->pluck('daily_revenue')
            ->toArray();

        return $this->holtsLinearTrendSale($dailySales, 0.8, 0.2, 7);
    }

    private function topTenMostSaleProduct()
    {
        return SaleDetail::select(
            'products.name',
            DB::raw('SUM(sale_details.total_price) as total_sales'),
            DB::raw('SUM(sale_details.kilograms) as total_kilograms')
        )
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->groupBy('products.name')
            ->orderByDesc('total_sales')
            ->limit(10)
            ->get();
    }

    private function topTenFewSaleProduct()
    {
        return SaleDetail::select(
            'products.name',
            DB::raw('SUM(sale_details.total_price) as total_sales'),
            DB::raw('SUM(sale_details.kilograms) as total_kilograms')
        )
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->groupBy('products.name')
            ->orderBy('total_sales', 'asc')
            ->limit(10)
            ->get();
    }

    private function lowStocksProduct()
    {
        return Product::select(
            'products.id',
            'products.name',
            DB::raw('COALESCE(SUM(product_batches.kg_remaining), 0) as total_remaining')
        )
            ->leftJoin('product_batches', 'products.id', '=', 'product_batches.product_id')
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_remaining', 'asc')
            ->get();
    }


    private function recentActivityLogs()
    {
        return ActivityLog::with('user')
            ->latest()
            ->take(7)
            ->get();
    }
}
