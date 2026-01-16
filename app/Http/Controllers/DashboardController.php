<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();

        // ✅ Workforce stats (dynamic)
        $totalWorkforce  = Employee::count();
        $joinedToday     = Employee::whereDate('date_of_joining', $today)->count();
        $activeWorkforce = Employee::whereNull('date_of_exit')->count();

        // Since no leave table exists, we will treat "Inactive" as employees having date_of_exit (or you can use soft-delete if your model supports it)
        $inactiveWorkforce = Employee::whereNotNull('date_of_exit')->count();

        $exitedWorkforce = Employee::whereNotNull('date_of_exit')->count();
        $exitedToday     = Employee::whereDate('date_of_exit', $today)->count();

        // ✅ Percentage placeholders (optional dynamic)
        // Keeping 0 if you don’t have history tables.
        $joinedChangePct = 0;
        $activeChangePct = 0;
        $exitedChangePct = 0;

        $stats = [
            'totalWorkforce'     => $totalWorkforce,
            'joinedToday'        => $joinedToday,
            'activeWorkforce'    => $activeWorkforce,
            'inactiveWorkforce'  => $inactiveWorkforce,
            'exitedWorkforce'    => $exitedWorkforce,
            'exitedToday'        => $exitedToday,
            'joinedChangePct'    => $joinedChangePct,
            'activeChangePct'    => $activeChangePct,
            'exitedChangePct'    => $exitedChangePct,
        ];

        // ✅ Recent Job Application (No table provided, so using employees as dynamic demo)
        $recentApplicants = Employee::with(['designation'])
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        // ✅ Leave Table structure → show ALL employees (as requested)
        $leaveEmployees = Employee::with(['designation'])
            ->orderByDesc('id')
            ->get();

        return view('dashboard.index', compact('stats', 'recentApplicants', 'leaveEmployees'));
    }
}
