<?php
// app/Http/Controllers/Admin/OrderController.php
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
 
class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user');
 
        if ($request->filled('status'))  $query->where('status', $request->status);
        if ($request->filled('payment')) $query->where('payment_status', $request->payment);
        if ($request->filled('search')) {
            $query->where('order_number','like','%'.$request->search.'%')
                  ->orWhere('shipping_name','like','%'.$request->search.'%')
                  ->orWhere('shipping_phone','like','%'.$request->search.'%');
        }
 
        $orders = $query->latest()->paginate(15)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }
 
    public function show(Order $order)
    {
        $order->load('user','items');
        return view('admin.orders.show', compact('order'));
    }
 
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status'           => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,refunded',
            'payment_status'   => 'required|in:pending,paid,failed,refunded',
            'tracking_number'  => 'nullable|string|max:100',
            'courier_name'     => 'nullable|string|max:100',
        ]);
 
        $order->update($request->only('status','payment_status','tracking_number','courier_name'));
 
        if ($request->status === 'delivered') {
            $order->update(['delivered_at' => now()]);
        }
 
        return back()->with('success','Order updated!');
    }
 
    public function destroy(Order $order)
    {
        $order->update(['status' => 'cancelled']);
        return back()->with('success','Order cancelled.');
    }
}
 