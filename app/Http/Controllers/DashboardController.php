<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Carbon\CarbonPeriod;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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

        // Fetch notifications that haven't expired yet
        try {
            $notifications = Notification::where('expired_at', '>', now())
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (QueryException $e) {
            $notifications = collect();
        }

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
            'notifications'      => $notifications,
        ]);
    }

    public function salesReport(Request $request)
    {
        $filter = $request->input('filter', 'daily'); // default daily
        $year   = $request->input('year', date('Y'));
        $month  = $request->input('month', date('m')); // only for daily

        $driver = DB::getDriverName(); // "sqlite" or "mysql"

        if ($filter === 'daily') {
            $start = Carbon::createFromDate($year, $month, 1);
            $end   = $start->copy()->endOfMonth();

            $dates = collect(CarbonPeriod::create($start, $end))->map(fn($date) => [
                'period' => $date->format('Y-m-d'),
                'total_sales' => 0,
            ])->keyBy('period');

            if ($driver === 'sqlite') {
                $sales = DB::table('sales')
                    ->selectRaw("date(sale_date) as period, SUM(total_price) as total_sales")
                    ->whereRaw("strftime('%Y', sale_date) = ?", [$year])
                    ->whereRaw("strftime('%m', sale_date) = ?", [str_pad($month, 2, '0', STR_PAD_LEFT)])
                    ->groupBy('period')
                    ->pluck('total_sales', 'period');
            } else { // mysql
                $sales = DB::table('sales')
                    ->selectRaw("DATE(sale_date) as period, SUM(total_price) as total_sales")
                    ->whereYear('sale_date', $year)
                    ->whereMonth('sale_date', $month)
                    ->groupBy('period')
                    ->pluck('total_sales', 'period');
            }

            $report = $dates->map(function ($day) use ($sales) {
                $day['total_sales'] = $sales->get($day['period'], 0);
                return $day;
            })->values();
        } elseif ($filter === 'monthly') {
            $months = collect(range(1, 12))->map(fn($m) => [
                'period' => $m,
                'total_sales' => 0,
            ])->keyBy('period');

            if ($driver === 'sqlite') {
                $sales = DB::table('sales')
                    ->selectRaw("CAST(strftime('%m', sale_date) AS INTEGER) as period, SUM(total_price) as total_sales")
                    ->whereRaw("strftime('%Y', sale_date) = ?", [$year])
                    ->groupBy('period')
                    ->pluck('total_sales', 'period');
            } else { // mysql
                $sales = DB::table('sales')
                    ->selectRaw("MONTH(sale_date) as period, SUM(total_price) as total_sales")
                    ->whereYear('sale_date', $year)
                    ->groupBy('period')
                    ->pluck('total_sales', 'period');
            }

            $report = $months->map(function ($m) use ($sales) {
                $m['total_sales'] = $sales->get($m['period'], 0);
                return $m;
            })->values();
        } elseif ($filter === 'yearly') {
            if ($driver === 'sqlite') {
                $report = DB::table('sales')
                    ->selectRaw("strftime('%Y', sale_date) as period, SUM(total_price) as total_sales")
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();
            } else { // mysql
                $report = DB::table('sales')
                    ->selectRaw("YEAR(sale_date) as period, SUM(total_price) as total_sales")
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();
            }
        } else {
            return response()->json(['error' => 'Invalid filter option'], 400);
        }

        return response()->json([
            'filter' => $filter,
            'year'   => $year,
            'month'  => $month,
            'report' => $report,
        ]);
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
            // Not enough data → return zeros
            $forecasts = [];
            $lastDate = new \DateTime();
            for ($h = 1; $h <= $forecastPeriods; $h++) {
                $futureDate = (clone $lastDate)->modify("+{$h} day")->format("Y-m-d");
                $forecasts[] = [
                    'date' => $futureDate,
                    'value' => 0
                ];
            }

            return [
                'levels'   => [],
                'trends'   => [],
                'forecast' => $forecasts
            ];
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

        // Forecast Future
        $forecasts = [];
        $lastDate = new \DateTime();
        for ($h = 1; $h <= $forecastPeriods; $h++) {
            $futureDate = (clone $lastDate)->modify("+{$h} day")->format("Y-m-d");
            $forecasts[] = [
                'date' => $futureDate,
                'value' => $level + $h * $trend
            ];
        }

        return [
            'levels'   => $levels,
            'trends'   => $trends,
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
