<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkAttendanceStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mark-attendance-status';

    /**
     * The console command description.
     *F
     * @var string
     */
    protected $description = 'Mark employees absent if they did not clock in by 5:00 PM';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        $cutoffTime = Carbon::today()->setTime(17, 0, 0); // 5:00 PM

        // Only run this logic after 5 PM
        if (Carbon::now()->lessThan($cutoffTime)) {
            $this->warn("It’s not yet 5 PM. Absent marking skipped.");
            return;
        }

        $employees = Employee::whereDoesntHave('attendances', function ($q) use ($today) {
            $q->whereDate('date', $today);
        })->get();

        foreach ($employees as $employee) {
            $attendance = Attendance::where('employee_id', $employee->id)
                ->whereDate('date', $today)
                ->first();

            if (!$attendance) {
                Attendance::firstOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'date'        => $today,
                    ],
                    [
                        'status'  => 'Absent',
                        'remarks' => 'No time-in record for today',
                    ]
                );


                $this->info("Employee {$employee->name} marked as Absent.");
            }
        }

        $this->info("Absent marking process completed for {$today->toDateString()}.");
    }
}
