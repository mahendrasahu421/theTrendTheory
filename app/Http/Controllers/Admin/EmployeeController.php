<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::latest()->paginate(20);

        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.form', ['employee' => new Employee()]);
    }

    public function store()
    {
        return back()->with('success', 'Employee forms are ready for wiring.');
    }

    public function show(Employee $employee)
    {
        return view('admin.employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        return view('admin.employees.form', compact('employee'));
    }

    public function update(Employee $employee)
    {
        return back()->with('success', 'Employee forms are ready for wiring.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return back()->with('success', 'Employee deleted.');
    }
}
