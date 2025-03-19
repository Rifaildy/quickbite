<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Canteen;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $canteenId = $request->input('canteen_id');
        $status = $request->input('status');
        $paymentStatus = $request->input('payment_status');
        $search = $request->input('search');
        
        $orders = Order::with(['user', 'canteen'])
            ->when($canteenId, function ($query) use ($canteenId) {
                return $query->where('canteen_id', $canteenId);
            })
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($paymentStatus, function ($query) use ($paymentStatus) {
                return $query->where('payment_status', $paymentStatus);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('canteen', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $canteens = Canteen::all();
        
        return view('admin.orders.index', compact(
            'orders', 
            'canteens', 
            'canteenId', 
            'status', 
            'paymentStatus', 
            'search'
        ));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'canteen', 'orderItems.menu']);
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'in:pending,processing,ready_for_pickup,completed,cancelled'],
        ]);
        
        $order->status = $request->status;
        $order->save();
        
        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order status updated successfully.');
    }

    /**
     * Update the payment status.
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => ['required', 'in:pending,paid,failed'],
        ]);
        
        $order->payment_status = $request->payment_status;
        
        // If payment is marked as paid and order is pending, update order status to processing
        if ($request->payment_status === 'paid' && $order->status === 'pending') {
            $order->status = 'processing';
        }
        
        $order->save();
        
        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Payment status updated successfully.');
    }
}

