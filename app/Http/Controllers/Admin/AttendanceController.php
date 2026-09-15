<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Attendance, Employee};
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->get('date', today()->format('Y-m-d'));
        $employees = Employee::where('status', 'active')->orderBy('name')->get();

        $attendances = Attendance::with('employee')
            ->whereDate('date', $selectedDate)
            ->get()
            ->keyBy('employee_id');

        $presentCount = $attendances->where('status', 'present')->count();
        $absentCount  = $attendances->where('status', 'absent')->count();
        $leaveCount   = $attendances->where('status', 'on_leave')->count();
        $totalActive  = $employees->count();
        $unmarkedCount = max(0, $totalActive - $attendances->count());

        return view('admin.attendance.index', compact(
            'employees', 'attendances', 'selectedDate',
            'presentCount', 'absentCount', 'leaveCount', 'totalActive', 'unmarkedCount'
        ));
    }

    public function store(Request $request)
    {
        $date = $request->input('date', today()->format('Y-m-d'));
        $records = $request->input('attendance', []);

        foreach ($records as $employeeId => $data) {
            if (isset($data['status'])) {
                Attendance::updateOrCreate(
                    [
                        'employee_id' => $employeeId,
                        'date'        => $date,
                    ],
                    [
                        'status'    => $data['status'],
                        'check_in'  => $data['check_in'] ?? null,
                        'check_out' => $data['check_out'] ?? null,
                        'notes'     => $data['notes'] ?? null,
                    ]
                );
            }
        }

        return redirect()->route('admin.attendance.index', ['date' => $date])
            ->with('success', "Attendance records for {$date} saved successfully.");
    }
}
