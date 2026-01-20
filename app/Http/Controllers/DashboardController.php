<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Base query (ignore deleted records)
        $base = Employee::query()->whereNull('deleted_at');

        // ✅ Cards
        $totalEmployee = (clone $base)->count();

        // "New Employee" -> Joined Today (you can change to month if needed)
        $newEmployee = (clone $base)
            ->whereNotNull('date_of_joining')
            ->whereDate('date_of_joining', $today)
            ->count();

        /**
         * Your DB has only `is_active` (0/1) + `date_of_exit`
         * So we assume:
         * Active  => is_active = 1 AND date_of_exit IS NULL
         * OnLeave => is_active = 0 AND date_of_exit IS NULL
         * Exited  => date_of_exit IS NOT NULL
         */
        $onLeave = (clone $base)
            ->where('is_active', 0)
            ->whereNull('date_of_exit')
            ->count();

        // Not available in your DB currently
        $jobApplicants = 0;
        $overTime = 0;

        // ✅ Recent Workforce table
        $recentWorkforce = (clone $base)
            ->with(['department', 'designation'])
            ->latest('id')
            ->take(20)
            ->get();

        return view('dashboard.index', compact(
            'totalEmployee',
            'newEmployee',
            'onLeave',
            'jobApplicants',
            'overTime',
            'recentWorkforce'
        ));
    }
}
