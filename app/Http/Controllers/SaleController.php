<?php

namespace App\Http\Controllers;

use App\Exports\SaleExport;
use App\Models\Sale;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Imports\SaleImport;
use App\Models\ActivityLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $query = Sale::query()->with('user');

        // ✅ Only show all sales if the user is an admin
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        // Filter by month
        if (request('month')) {
            $query->whereMonth('sale_date', request('month'));
        }

        // Filter by year
        if (request('year')) {
            $query->whereYear('sale_date', request('year'));
        }

        // Filter by specific date
        if (request('date')) {
            $query->whereDate('sale_date', request('date'));
        }

        $sales = $query->latest()->paginate(10);

        return Inertia::render('Sales/Sales', [
            'sale' => $sales,
            'totals' => [
                'today' => Sale::totalSalesToday(),
                'week'  => Sale::totalSalesWeek(),
                'month' => Sale::totalSalesMonth(),
            ],
            'filters' => [
                'month' => request('month'),
                'year' => request('year'),
                'date' => request('date'),
            ],
            'years' => range(2020, date('Y')),
            'months' => [
                1 => 'January',
                2 => 'February',
                3 => 'March',
                4 => 'April',
                5 => 'May',
                6 => 'June',
                7 => 'July',
                8 => 'August',
                9 => 'September',
                10 => 'October',
                11 => 'November',
                12 => 'December',
            ],
        ]);
    }

    public function export(Request $request)
    {
        $request->validate([
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate   = Carbon::parse($request->end_date)->endOfDay();

        $fileName = 'Sales from ' . $startDate->format('F j, Y') . ' to ' . $endDate->format('F j, Y') . '.xlsx';
        $safeFileName = str_replace([' ', ','], ['_', ''], $fileName);

        // ✅ Log the export action
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'event'       => 'exported',
            'module'      => 'sales',
            'description' => 'Exported Sales data from ' . $startDate->format('F j, Y') . ' to ' . $endDate->format('F j, Y'),
            'properties'  => [
                'start_date' => $startDate->toDateTimeString(),
                'end_date'   => $endDate->toDateTimeString(),
            ],
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->header('User-Agent'),
        ]);

        return Excel::download(new SaleExport($startDate, $endDate), $safeFileName);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new SaleImport, $request->file('file'));

        // ✅ Log the import action
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'event'       => 'imported',
            'module'      => 'sales',
            'description' => 'Imported Sales data from Excel file: ' . $request->file('file')->getClientOriginalName(),
            'properties'  => [
                'file_name' => $request->file('file')->getClientOriginalName(),
                'file_size' => $request->file('file')->getSize(),
            ],
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->header('User-Agent'),
        ]);

        // ✅ Create notification for admins (or target role)
        Notification::create([
            'title'          => 'Sales Data Imported',
            'message'        => Auth::user()->name . ' imported sales data from "' . $request->file('file')->getClientOriginalName() . '"',
            'recipient_role' => 'admin',
            'expires_at'     => now()->addDays(7),
        ]);

        return back()->with('success', 'Sales imported successfully!');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSaleRequest $request, Sale $sale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        //
    }
}
