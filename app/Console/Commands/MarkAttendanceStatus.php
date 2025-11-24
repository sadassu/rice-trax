<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
     *
     * @var string
     */
    protected $description = 'Mark employees absent if no attendance, or auto time-out if no time-out by 5:00 PM';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        $now = Carbon::now();
        $startTime = Carbon::today()->setTime(18, 0, 0); // 6:00 PM
        $endTime = Carbon::tomorrow()->setTime(0, 0, 0); // 12:00 AM
        $autoTimeOut = Carbon::today()->setTime(18, 0, 0); // 5:00 PM

        // Only run between 6 PM and 12 AM
        if ($now->lt($startTime) || $now->gte($endTime)) {
            $this->warn("Current time is outside 6 PM - 12 AM. Attendance marking skipped.");
            return;
        }

        // 1️⃣ Mark employees absent if no attendance record for today
        $employeesWithoutAttendance = Employee::whereDoesntHave('attendances', function ($q) use ($today) {
            $q->whereDate('date', $today);
        })->get();

        foreach ($employeesWithoutAttendance as $employee) {
            Attendance::firstOrCreate(
                [
                    'employee_id' => $employee->id,
                    'date'        => $today,
                ],
                [
                    'status'  => 'absent',
                    'remarks' => 'No time-in record for today',
                ]
            );

            $this->info("Employee {$employee->name} marked as Absent.");
        }

        // 2️⃣ Auto time-out employees who clocked in but not clocked out
        $attendances = Attendance::whereDate('date', $today)
            ->whereNotNull('time_in')
            ->whereNull('time_out')
            ->get();

        foreach ($attendances as $attendance) {
            $attendance->update([
                'time_out' => $autoTimeOut, // always 6 PM
                'status'   => 'present',
                'remarks'  => 'Automatically timed out at 6 PM',
            ]);

            // Create notification for the employee
            Notification::create([
                'title'          => 'Automatic Time Out',
                'message'        => "You were automatically timed out at 6:00 PM on {$today->toFormattedDateString()}.",
                'recipient_role' => 'admin',
                'expires_at'     => now()->addDays(3),
            ]);

            $this->info("Employee ID {$attendance->employee_id} auto timed out at 6:00 PM.");
        }

        $this->info("Attendance auto-marking completed for {$today->toDateString()}.");
        Log::channel('daily')->info("MarkAttendanceStatus ran at " . $now);
    }
}
