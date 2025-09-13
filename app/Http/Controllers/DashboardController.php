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
    private function holtsLinearTrendSale(array $sales, array $dates, float $alpha = 0.8, float $beta = 0.2, int $forecastPeriods = 7)
    {
        $n = count($sales);
        if ($n < 2) {
            throw new \Exception("Need at least 2 non-zero data points for Holt’s Linear Trend.");
        }

        // Initialization
        $level = $sales[0];
        $trend = $sales[1] - $sales[0];

        $levels = [['date' => $dates[0], 'value' => $level]];
        $trends = [['date' => $dates[0], 'value' => $trend]];

        // Update Level & Trend
        for ($t = 1; $t < $n; $t++) {
            $lastLevel = $level;
            $level = $alpha * $sales[$t] + (1 - $alpha) * ($level + $trend);
            $trend = $beta * ($level - $lastLevel) + (1 - $beta) * $trend;

            $levels[] = ['date' => $dates[$t], 'value' => $level];
            $trends[] = ['date' => $dates[$t], 'value' => $trend];
        }

        // Forecast Future (add dates)
        $forecasts = [];
        $lastDate = new \DateTime(); // start forecasting from today
        for ($h = 1; $h <= $forecastPeriods; $h++) {
            $futureDate = (clone $lastDate)->modify("+{$h} day")->format("Y-m-d");
            $forecasts[] = [
                'date' => $futureDate,
                'value' => $level + $h * $trend
            ];
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
            ->selectRaw('DATE(sale_date) as sale_date, SUM(total_price) as daily_revenue')
            ->groupBy(DB::raw('DATE(sale_date)'))
            ->havingRaw('SUM(total_price) > 0')
            ->orderBy('sale_date')
            ->get();

        $dates = $dailySales->pluck('sale_date')->map(function ($d) {
            return date('Y-m-d', strtotime($d));
        })->toArray();

        $sales = $dailySales->pluck('daily_revenue')->toArray();

        return $this->holtsLinearTrendSale($sales, $dates, 0.8, 0.2, 7);
    }

    // DONE :: taking the top 10 most sale products
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

    //  MAYBE :: taking the lowstocks products
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

    // DONE :: taking the 7 recent activity logs
    private function recentActivityLogs()
    {
        return ActivityLog::with('user')
            ->latest()
            ->take(7)
            ->get();
    }
}
