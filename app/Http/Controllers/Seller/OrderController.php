<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\OrderReadyForPickup;
use App\Notifications\OrderCompleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class OrderController extends Controller
{
/**
 * Create a new controller instance.
 *
 * @return void
 */
public function __construct()
{
    $this->middleware(['auth', 'seller']);
}

/**
 * Display a listing of the resource.
 */
public function index(Request $request)
{
    $canteen = Auth::user()->canteen;
    
    if (!$canteen) {
        return redirect()->route('seller.canteens.create')
            ->with('info', 'Please create your canteen first.');
    }
    
    $status = $request->input('status', '');
    
    $orders = $canteen->orders()
        ->with(['user', 'orderItems.menu'])
        ->when($status, function ($query, $status) {
            return $query->where('status', $status);
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        
    return view('seller.orders.index', compact('orders', 'status'));
}

/**
 * Display the specified resource.
 */
public function show(Order $order)
{
    $canteen = Auth::user()->canteen;
    
    if ($order->canteen_id !== $canteen->id) {
        return abort(403, 'Unauthorized action.');
    }
    
    $order->load(['user', 'orderItems.menu']);
    
    return view('seller.orders.show', compact('order'));
}

/**
 * Update the order status.
 */
public function updateStatus(Request $request, Order $order)
{
    $canteen = Auth::user()->canteen;
    
    if ($order->canteen_id !== $canteen->id) {
        return abort(403, 'Unauthorized action.');
    }
    
    $request->validate([
        'status' => ['required', 'in:pending,processing,ready_for_pickup,completed,cancelled'],
    ]);
    
    $oldStatus = $order->status;
    $order->status = $request->status;
    
    // Generate barcode when order is ready for pickup if not already generated
    if ($request->status === 'ready_for_pickup' && !$order->barcode) {
        $barcode = 'ORD-' . $order->id . '-' . time();
        $order->barcode = $barcode;
        
        // Generate QR code
        $qrCode = QrCode::format('png')
            ->size(200)
            ->generate($barcode);
            
        // Store QR code
        $path = 'barcodes/' . $barcode . '.png';
        \Storage::disk('public')->put($path, $qrCode);
    }
    
    $order->save();
    
    // Send notification when status changes to ready_for_pickup
    if ($request->status === 'ready_for_pickup' && $oldStatus !== 'ready_for_pickup') {
        try {
            $order->user->notify(new OrderReadyForPickup($order));
        } catch (\Exception $e) {
            \Log::error('Failed to send ready for pickup notification: ' . $e->getMessage());
        }
    }
    
    return redirect()->route('seller.orders.show', $order)
        ->with('success', 'Order status updated successfully.');
}

/**
 * Confirm payment for the order.
 */
public function confirmPayment(Order $order)
{
    $canteen = Auth::user()->canteen;
    
    if ($order->canteen_id !== $canteen->id) {
        return abort(403, 'Unauthorized action.');
    }
    
    $order->payment_status = 'paid';
    $order->status = 'processing';
    $order->save();
    
    // Send notification to buyer
    try {
        $order->user->notify(new \App\Notifications\OrderPaid($order));
    } catch (\Exception $e) {
        \Log::error('Failed to send payment confirmation notification: ' . $e->getMessage());
    }
    
    return redirect()->route('seller.orders.show', $order)
        ->with('success', 'Payment confirmed successfully.');
}

/**
 * Scan barcode to verify order.
 */
public function scanBarcode()
{
    return view('seller.orders.scan');
}

/**
 * Verify barcode.
 */
public function verifyBarcode(Request $request)
{
    $request->validate([
        'barcode' => 'required',
    ]);
    
    $order = Order::where('barcode', $request->barcode)
        ->where('status', '!=', 'completed') // Changed from checking for 'completed' to not completed
        ->first();
    
    if (!$order) {
        return redirect()->route('seller.orders.scan')
            ->with('error', 'Invalid or already used barcode.');
    }
    
    $canteen = Auth::user()->canteen;
    
    if ($order->canteen_id !== $canteen->id) {
        return redirect()->route('seller.orders.scan')
            ->with('error', 'This order is not from your canteen.');
    }
    
    // Update order status to completed
    $order->status = 'completed';
    $order->save();
    
    // Notify user
    try {
        $order->user->notify(new OrderCompleted($order));
    } catch (\Exception $e) {
        // Log the error but continue processing
        \Log::error('Failed to send completion notification: ' . $e->getMessage());
    }
    
    return redirect()->route('seller.orders.show', $order)
        ->with('success', 'Order verified and marked as completed successfully.');
}
}

