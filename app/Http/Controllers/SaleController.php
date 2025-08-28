<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use Inertia\Inertia;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Sale::query();

        // Filter by month
        if (request('month')) {
            $query->whereMonth('created_at', request('month'));
        }

        // Filter by year
        if (request('year')) {
            $query->whereYear('created_at', request('year'));
        }

        // Filter by specific date
        if (request('date')) {
            $query->whereDate('created_at', request('date'));
        }

        $sales = $query->latest()->paginate(10);

        return Inertia::render('Sales/Sales', [
            'sale' => $sales,
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
