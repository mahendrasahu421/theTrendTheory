<?php
// app/Http/Controllers/Admin/CustomerController.php
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
 
class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role','customer');
 
        if ($request->filled('search')) {
            $query->where('name','like','%'.$request->search.'%')
                  ->orWhere('email','like','%'.$request->search.'%')
                  ->orWhere('phone','like','%'.$request->search.'%');
        }
 
        $customers = $query->withCount('orders')
            ->latest()->paginate(15)->withQueryString();
 
        return view('admin.customers.index', compact('customers'));
    }
 
    public function show(User $user)
    {
        $user->load('orders');
        $totalSpent = $user->orders()->where('payment_status','paid')->sum('total_amount');
        return view('admin.customers.show', compact('user','totalSpent'));
    }
 
    public function toggle(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'Customer status updated.');
    }
}