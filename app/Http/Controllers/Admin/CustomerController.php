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
        return view('admin.customers.index');
    }

    public function ajax(Request $request)
    {
        $perPage = max(5, min((int) $request->get('per_page', 15), 100));
        $page = max(1, (int) $request->get('page', 1));
        $status = $request->get('status', '');

        $query = $this->customerQuery($request)
            ->withCount('orders')
            ->withSum(['orders as total_spent' => function ($query) {
                $query->where('payment_status', 'paid');
            }], 'total_amount');

        if ($status !== '') {
            $query->where('is_active', (bool) (int) $status);
        }

        $customers = $query->latest()->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $customers->map(function (User $customer) {
                return [
                    'id' => (int) $customer->id,
                    'name' => (string) ($customer->name ?? 'Customer'),
                    'email' => (string) ($customer->email ?? ''),
                    'phone' => (string) ($customer->phone ?? '-'),
                    'city' => (string) ($customer->city ?? '-'),
                    'orders_count' => (int) ($customer->orders_count ?? 0),
                    'total_spent' => (float) ($customer->total_spent ?? 0),
                    'joined' => optional($customer->created_at)->format('d M Y'),
                    'is_active' => (bool) $customer->is_active,
                    'show_url' => route('admin.customers.show', $customer),
                    'toggle_url' => route('admin.customers.toggle', $customer),
                ];
            }),
            'total' => $customers->total(),
            'per_page' => $customers->perPage(),
            'current_page' => $customers->currentPage(),
            'last_page' => $customers->lastPage(),
            'from' => $customers->firstItem() ?? 0,
            'to' => $customers->lastItem() ?? 0,
        ]);
    }
 
    public function show(User $user)
    {
        abort_unless($user->isCustomer(), 404);

        $user->load(['orders' => fn($q) => $q->latest()]);
        $totalSpent = $user->orders()->where('payment_status', 'paid')->sum('total_amount');
        
        $activities = \App\Models\UserActivity::where('user_id', $user->id)
            ->orWhere('session_id', request()->session()->getId())
            ->latest()
            ->limit(20)
            ->get();

        $visitorLogs = \App\Models\VisitorLog::where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.customers.show', compact('user', 'totalSpent', 'activities', 'visitorLogs'));
    }
 
    public function toggle(User $user)
    {
        abort_unless($user->isCustomer(), 404);

        $user->update(['is_active' => !$user->is_active]);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => (bool) $user->is_active,
                'message' => 'Customer status updated.',
            ]);
        }

        return back()->with('success', 'Customer status updated.');
    }

    private function customerQuery(Request $request)
    {
        $query = User::where('role', 'customer');

        if ($request->filled('search')) {
            $search = $request->string('search')->trim();

            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        return $query;
    }
}
