<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendance = Attendance::latest()->paginate(20);

        return view('admin.attendance.index', compact('attendance'));
    }

    public function store()
    {
        return back()->with('success', 'Attendance form is ready for wiring.');
    }
}
