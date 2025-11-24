<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class CronController extends Controller
{
    public function markAttendance()
    {
        try {
            Artisan::call('app:mark-attendance-status');
            $output = Artisan::output();

            return response()->json([
                'success' => true,
                'message' => 'Attendance marking completed successfully.',
                'output' => $output,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to run MarkAttendanceStatus: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to execute attendance marking.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function cleanupNotifications()
    {
        try {
            Artisan::call('notifications:cleanup');
            $output = Artisan::output();

            return response()->json([
                'success' => true,
                'message' => 'Expired notifications cleaned up successfully.',
                'output' => $output,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to run DeleteExpiredNotifications: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to cleanup notifications.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
