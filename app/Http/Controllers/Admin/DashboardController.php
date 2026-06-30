<?php
// app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return match($user->role) {
            'super_admin'    => redirect()->route('admin.dashboard.super'),
            'admin'          => redirect()->route('admin.dashboard.admin'),
            'hr'             => redirect()->route('admin.dashboard.hr'),
            'product_manager', 'product_editor' => redirect()->route('admin.dashboard.product_editor'),
            default          => abort(403),
        };
    }
}
