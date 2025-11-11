<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $employees = Employee::with('attendances')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render(
            'Employees/Employees',
            [
                'employees'   => $employees,
                'searchTerm' => $request->search,
            ]
        );
    }

    public function computeSalary(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($validated['start_date'])->startOfDay();
        $endDate   = Carbon::parse($validated['end_date'])->endOfDay();

        $attendances = $employee->attendances()
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', 'present')
            ->get();

        $totalHours = 0;

        foreach ($attendances as $attendance) {
            if ($attendance->time_in && $attendance->time_out) {
                $timeIn  = Carbon::parse($attendance->time_in);
                $timeOut = Carbon::parse($attendance->time_out);

                if ($timeOut->greaterThanOrEqualTo($timeIn)) {
                    $hours = $timeOut->floatDiffInHours($timeIn);
                    $totalHours += $hours;
                }
            }
        }

        $totalSalary = round($totalHours * $employee->rate, 2);

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'event'       => 'computed',
            'module'      => 'salary',
            'description' => 'Computed salary for employee: ' . $employee->name .
                ' (ID: ' . $employee->id . ') from ' .
                $startDate->toDateString() . ' to ' . $endDate->toDateString(),
            'properties'  => [
                'employee_id' => $employee->id,
                'total_hours' => round($totalHours, 2),
                'total_salary' => round($totalSalary, 2),
                'period' => [
                    'start_date' => $startDate->toDateString(),
                    'end_date'   => $endDate->toDateString(),
                ]
            ],
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->header('User-Agent'),
        ]);

        return response()->json([
            'employee_id'   => $employee->id,
            'employee_name' => $employee->name,
            'total_hours'   => round($totalHours, 2),
            'total_salary'  => $totalSalary,
            'period'        => [
                'start_date' => $startDate->toDateString(),
                'end_date'   => $endDate->toDateString(),
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
    public function store(StoreEmployeeRequest $request)
    {
        $employee = Employee::create($request->validated());

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'event'      => 'created',
            'module'     => 'employees',
            'description' => 'Created Employee: ' . $employee->name,
            'properties' => ['employee_id' => $employee->id],
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        return redirect()->route('attendances.index')
            ->with('message', 'Employee added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $employee->load('attendances');

        // Return data to the Inertia view
        return Inertia::render('Employees/ShowSpecificEmployee', [
            'employee' => $employee,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $employee->update($request->validated());

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'event'       => 'updated',
            'module'      => 'employees',
            'description' => 'Updated Employee: ' . $employee->name,
            'properties'  => ['employee_id' => $employee->id],
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->header('User-Agent'),
        ]);

        return redirect()
            ->back()
            ->with('message', 'Employee updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'event'       => 'deleted',
            'module'      => 'employee',
            'description' => 'Deleted employee: ' . $employee->name,
            'properties'  => ['employee_id' => $employee->id],
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->header('User-Agent'),
        ]);

        return redirect()->back()
            ->with('message', 'Employee deleted successfully!');
    }
}
