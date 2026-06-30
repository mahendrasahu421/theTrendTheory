<?php
// app/Http/Controllers/Admin/HRDashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Employee, Attendance, User};
use Illuminate\Support\Facades\DB;

class HRDashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = Employee::count();
        $activeEmp      = Employee::where('status','active')->count();
        $presentToday   = Attendance::where('date',today())->where('status','present')->count();
        $absentToday    = Attendance::where('date',today())->where('status','absent')->count();
        $onLeaveToday   = Attendance::where('date',today())->where('status','on_leave')->count();
        $notMarked      = $activeEmp - Attendance::where('date',today())->count();

        $employees = Employee::with(['user','attendance' => fn($q) => $q->whereMonth('date',now()->month)])
            ->orderBy('name')->get();

        $monthlyAttendance = DB::table('attendance')
            ->whereMonth('date', now()->month)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')->get()->keyBy('status');

        return view('admin.dashboards.hr', compact(
            'totalEmployees','activeEmp','presentToday','absentToday',
            'onLeaveToday','notMarked','employees','monthlyAttendance'
        ));
    }
}