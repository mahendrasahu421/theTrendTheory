<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['user', 'attendance' => fn($q) => $q->whereMonth('date', now()->month)]);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('employee_id', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('designation', 'like', "%{$s}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $employees = $query->orderBy('name')->paginate(15)->withQueryString();
        
        $departments = Employee::whereNotNull('department')->distinct()->pluck('department');
        $totalCount  = Employee::count();
        $activeCount = Employee::where('status', 'active')->count();

        return view('admin.employees.index', compact('employees', 'departments', 'totalCount', 'activeCount'));
    }

    public function create()
    {
        return view('admin.employees.form', [
            'employee' => new Employee(['status' => 'active', 'joining_date' => now()->format('Y-m-d')])
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'nullable|email|max:255',
            'phone'        => 'nullable|string|max:30',
            'department'   => 'nullable|string|max:100',
            'designation'  => 'nullable|string|max:100',
            'role'         => 'nullable|string|max:100',
            'salary'       => 'nullable|numeric|min:0',
            'joining_date' => 'nullable|date',
            'status'       => 'required|in:active,inactive,on_leave,terminated',
            'address'      => 'nullable|string|max:500',
        ]);

        $employee = Employee::create($validated);

        return redirect()->route('admin.employees.index')->with('success', "Employee {$employee->name} added successfully.");
    }

    public function show(Employee $employee)
    {
        $employee->load(['attendance' => fn($q) => $q->latest('date')->limit(30)]);
        return view('admin.employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        return view('admin.employees.form', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'nullable|email|max:255',
            'phone'        => 'nullable|string|max:30',
            'department'   => 'nullable|string|max:100',
            'designation'  => 'nullable|string|max:100',
            'role'         => 'nullable|string|max:100',
            'salary'       => 'nullable|numeric|min:0',
            'joining_date' => 'nullable|date',
            'status'       => 'required|in:active,inactive,on_leave,terminated',
            'address'      => 'nullable|string|max:500',
        ]);

        $employee->update($validated);

        return redirect()->route('admin.employees.index')->with('success', "Employee {$employee->name} updated successfully.");
    }

    public function destroy(Employee $employee)
    {
        $name = $employee->name;
        $employee->delete();

        return redirect()->route('admin.employees.index')->with('success', "Employee {$name} deleted successfully.");
    }
}
