<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Employee, Attendance, User};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HRDashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = Employee::count();
        $activeEmp      = Employee::where('status', 'active')->count();
        $presentToday   = Attendance::where('date', today())->where('status', 'present')->count();
        $absentToday    = Attendance::where('date', today())->where('status', 'absent')->count();
        $onLeaveToday   = Attendance::where('date', today())->where('status', 'on_leave')->count();
        $notMarked      = max(0, $activeEmp - Attendance::where('date', today())->count());
        $attendanceRate = $activeEmp > 0 ? round(($presentToday / $activeEmp) * 100) : 0;

        $employees = Employee::with(['user', 'attendance' => fn($q) => $q->whereMonth('date', now()->month)->whereYear('date', now()->year)])
            ->orderBy('name')->get();

        $monthlyAttendance = DB::table('attendance')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')->get()->keyBy('status');

        $presentMonth = $monthlyAttendance['present']->count ?? 0;
        $absentMonth  = $monthlyAttendance['absent']->count ?? 0;
        $leaveMonth   = $monthlyAttendance['on_leave']->count ?? 0;

        $deptBreakdown = Employee::select('department', DB::raw('count(*) as count'))
            ->whereNotNull('department')
            ->groupBy('department')
            ->orderByDesc('count')
            ->get();

        $recentAttendanceLogs = Attendance::with('employee')
            ->latest('created_at')
            ->limit(6)
            ->get();

        return view('admin.dashboards.hr', compact(
            'totalEmployees', 'activeEmp', 'presentToday', 'absentToday',
            'onLeaveToday', 'notMarked', 'attendanceRate', 'employees',
            'monthlyAttendance', 'presentMonth', 'absentMonth', 'leaveMonth',
            'deptBreakdown', 'recentAttendanceLogs'
        ));
    }
}