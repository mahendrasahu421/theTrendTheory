<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffPermissionController extends Controller
{
    /**
     * Display all staff users and their permissions.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $roleFilter = $request->input('role');

        $query = Admin::with(['adminPermissions']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($roleFilter) {
            $query->where('role', $roleFilter);
        }

        $staffUsers = $query->latest()->paginate(15)->withQueryString();

        // Metrics
        $totalStaff = Admin::count();
        $superAdminCount = Admin::where('role', 'super_admin')->count();
        $adminCount = Admin::where('role', 'admin')->count();
        $subStaffCount = Admin::whereNotIn('role', ['super_admin', 'admin'])->count();

        $permissionGroups = Admin::getDefinedPermissionGroups();

        return view('admin.staff.index', compact(
            'staffUsers',
            'search',
            'roleFilter',
            'totalStaff',
            'superAdminCount',
            'adminCount',
            'subStaffCount',
            'permissionGroups'
        ));
    }

    /**
     * Show form to create new staff member.
     */
    public function create()
    {
        $permissionGroups = Admin::getDefinedPermissionGroups();
        $staff = new Admin();

        return view('admin.staff.form', [
            'staff'            => $staff,
            'permissionGroups' => $permissionGroups,
            'assignedPerms'    => [],
            'isEdit'           => false,
        ]);
    }

    /**
     * Store newly created staff and assign permissions.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:admins,email',
            'phone'        => 'nullable|string|max:20|unique:admins,phone',
            'role'         => ['required', 'string', Rule::in(['super_admin', 'admin', 'hr', 'product_manager', 'product_editor', 'support_staff', 'staff'])],
            'password'     => 'required|string|min:6',
            'permissions'  => 'nullable|array',
            'permissions.*'=> 'string',
        ]);

        $admin = Admin::create([
            'name'      => $request->input('name'),
            'email'     => $request->input('email'),
            'phone'     => $request->input('phone'),
            'role'      => $request->input('role'),
            'password'  => Hash::make($request->input('password')),
            'is_active' => true,
        ]);

        // If user is not super_admin or admin, sync custom permissions
        if (!in_array($admin->role, ['super_admin', 'admin'], true)) {
            $admin->syncPermissions($request->input('permissions', []));
        }

        return redirect()->route('admin.staff.index')
            ->with('success', "Staff member '{$admin->name}' created successfully with assigned permissions!");
    }

    /**
     * Show form to edit staff member and permissions.
     */
    public function edit(Admin $staff)
    {
        $permissionGroups = Admin::getDefinedPermissionGroups();
        $assignedPerms = $staff->adminPermissions()->pluck('permission')->toArray();

        return view('admin.staff.form', [
            'staff'            => $staff,
            'permissionGroups' => $permissionGroups,
            'assignedPerms'    => $assignedPerms,
            'isEdit'           => true,
        ]);
    }

    /**
     * Update staff member details and permissions.
     */
    public function update(Request $request, Admin $staff)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => ['required', 'email', 'max:255', Rule::unique('admins', 'email')->ignore($staff->id)],
            'phone'        => ['nullable', 'string', 'max:20', Rule::unique('admins', 'phone')->ignore($staff->id)],
            'role'         => ['required', 'string', Rule::in(['super_admin', 'admin', 'hr', 'product_manager', 'product_editor', 'support_staff', 'staff'])],
            'password'     => 'nullable|string|min:6',
            'permissions'  => 'nullable|array',
            'permissions.*'=> 'string',
        ]);

        // Prevent demoting the last super_admin
        if ($staff->role === 'super_admin' && $request->input('role') !== 'super_admin') {
            $otherSuper = Admin::where('role', 'super_admin')->where('id', '!=', $staff->id)->count();
            if ($otherSuper === 0) {
                return back()->with('error', 'Cannot change role. At least one Super Admin must remain in the system.');
            }
        }

        $updateData = [
            'name'  => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'role'  => $request->input('role'),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->input('password'));
        }

        $staff->update($updateData);

        // If user is not super_admin or admin, sync custom permissions
        if (!in_array($staff->role, ['super_admin', 'admin'], true)) {
            $staff->syncPermissions($request->input('permissions', []));
        } else {
            // Super Admin & Admin have all access; clean up individual overrides
            $staff->adminPermissions()->delete();
        }

        return redirect()->route('admin.staff.index')
            ->with('success', "Staff permissions for '{$staff->name}' updated successfully!");
    }

    /**
     * Toggle staff active status.
     */
    public function toggleStatus(Admin $staff)
    {
        $currentAdminId = Auth::guard('admin')->id() ?? Auth::id();
        if ($staff->id === $currentAdminId) {
            return back()->with('error', 'You cannot deactivate your own logged-in account.');
        }

        $staff->is_active = !$staff->is_active;
        $staff->save();

        $statusLabel = $staff->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Staff member '{$staff->name}' has been {$statusLabel}.");
    }

    /**
     * Delete staff member.
     */
    public function destroy(Admin $staff)
    {
        $currentAdminId = Auth::guard('admin')->id() ?? Auth::id();
        if ($staff->id === $currentAdminId) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($staff->role === 'super_admin') {
            $otherSuper = Admin::where('role', 'super_admin')->where('id', '!=', $staff->id)->count();
            if ($otherSuper === 0) {
                return back()->with('error', 'Cannot delete the only Super Admin.');
            }
        }

        $name = $staff->name;
        $staff->adminPermissions()->delete();
        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', "Staff member '{$name}' was deleted.");
    }
}
