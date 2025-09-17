<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Http\Requests\StoreActivityLogRequest;
use App\Http\Requests\UpdateActivityLogRequest;
use Inertia\Inertia;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = ActivityLog::with('user');

        // Filter by user
        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
        }

        // Filter by module
        if (request('module')) {
            $query->where('module', request('module'));
        }

        // Filter by event
        if (request('event')) {
            $query->where('event', request('event'));
        }

        // Filter by date
        if (request('date')) {
            $query->whereDate('created_at', request('date'));
        }

        // Filter by month
        if (request('month')) {
            $query->whereMonth('created_at', request('month'));
        }

        // Filter by year
        if (request('year')) {
            $query->whereYear('created_at', request('year'));
        }

        $logs = $query->latest()->paginate(10);

        return Inertia::render('ActivityLogs/Activity', [
            'logs' => $logs,
            'filters' => [
                'user_id' => request('user_id'),
                'module'  => request('module'),
                'event'   => request('event'),
                'date'    => request('date'),
                'month'   => request('month'),
                'year'    => request('year'),
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
    public function store(StoreActivityLogRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ActivityLog $activityLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ActivityLog $activityLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivityLogRequest $request, ActivityLog $activityLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ActivityLog $activityLog)
    {
        //
    }
}
