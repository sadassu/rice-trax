<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\ActivityLog;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {}

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Get employees with their attendances
        $employees = Employee::with('attendances')
            ->when($request->search, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Get today's date
        $today = now()->toDateString();

        // Fetch today's attendances
        $todayAttendances = Attendance::with('employee')
            ->whereDate('date', $today)
            ->get();

        return Inertia::render('Attendances/CreateAttendance', [
            'todayAttendances' => $todayAttendances,
            'employees' => $employees,
            'searchTerm' => $request->search,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $today = now()->toDateString();

        $attendance = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            // First record today → Time In
            $attendance = Attendance::create([
                'employee_id' => $request->employee_id,
                'date'        => $today,
                'time_in'     => now(),
                'status'      => 'present',
            ]);

            $message = 'Time In recorded successfully.';
        } elseif (is_null($attendance->time_out)) {
            // Second record today → Time Out
            $attendance->time_out = now();

            // Calculate worked hours
            $hoursWorked = $attendance->time_in->diffInHours($attendance->time_out);

            // Get employee rate
            $rate = $attendance->employee->rate ?? 0;

            // Calculate earned amount
            $attendance->earned_amount = $hoursWorked * $rate;
            $attendance->save();

            $message = 'Time Out recorded successfully.';
        } else {
            return back()->withErrors([
                'attendance' => 'This employee already completed attendance today.',
            ]);
        }

        // Log the activity
        ActivityLog::create([
            'user_id'    => Auth::id(),
            'event'      => 'created',
            'module'     => 'attendances',
            'description' => 'Updated Attendance: ' . $attendance->employee_id,
            'properties' => ['attendance_id' => $attendance->id],
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        return back()->with('success', $message);
    }


    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttendanceRequest $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
