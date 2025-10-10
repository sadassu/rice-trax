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
    public function index(Request $request)
    {
        // Get query parameters
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');
        $status = $request->query('status');

        // Build query
        $query = Attendance::with('employee'); // eager load if needed

        // Filter by date range
        if ($dateFrom && $dateTo) {
            $query->whereBetween('date', [$dateFrom, $dateTo]);
        } elseif ($dateFrom) {
            $query->whereDate('date', '>=', $dateFrom);
        } elseif ($dateTo) {
            $query->whereDate('date', '<=', $dateTo);
        }

        // Filter by status
        if ($status && in_array($status, ['present', 'absent', 'late', 'on_leave'])) {
            $query->where('status', $status);
        }

        // Get results (paginated)
        $attendances = $query->latest()->paginate(10)->appends($request->query());

        return inertia('Attendances/Attendances', [
            'attendances' => $attendances,
            'filters' => [
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'status' => $status,
            ],
        ]);
    }

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
            ->get();

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
        $validated = $request->validated();

        // Update fields
        $attendance->fill($validated);

        // If both time_in and time_out are present → recalculate earned_amount
        if (!empty($attendance->time_in) && !empty($attendance->time_out)) {
            $timeIn = Carbon::parse($attendance->time_in);
            $timeOut = Carbon::parse($attendance->time_out);

            // Calculate hours worked (can use diffInMinutes for more precision)
            $hoursWorked = $timeIn->diffInHours($timeOut);

            // Get rate from employee
            $rate = $attendance->employee->rate ?? 0;

            // Compute new earned amount
            $attendance->earned_amount = $hoursWorked * $rate;
        }

        $attendance->save();

        // Log the update activity
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'event'       => 'updated',
            'module'      => 'attendances',
            'description' => 'Updated Attendance ID: ' . $attendance->id,
            'properties'  => ['attendance_id' => $attendance->id],
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->header('User-Agent'),
        ]);

        return back()->with('success', 'Attendance updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Attendance $attendance)
    {
        // Store info before deleting (for log)
        $employeeName = $attendance->employee->name ?? 'Unknown';
        $attendanceId = $attendance->id;

        // Delete the record
        $attendance->delete();

        // Log the deletion
        ActivityLog::create([
            'user_id'    => Auth::id(),
            'event'      => 'deleted',
            'module'     => 'attendances',
            'description' => "Deleted Attendance of {$employeeName} (ID: {$attendanceId})",
            'properties' => ['attendance_id' => $attendanceId],
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        return back()->with('success', 'Attendance deleted successfully.');
    }
}
