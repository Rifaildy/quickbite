<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Canteen;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Notifications\OrderPaid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
      $this->middleware(['auth', 'buyer']);
      
      // Set Midtrans configuration
      \Midtrans\Config::$serverKey = config('midtrans.server_key');
      \Midtrans\Config::$isProduction = config('midtrans.is_production');
      \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
      \Midtrans\Config::$is3ds = config('midtrans.is_3ds');
  }

  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
      $status = $request->input('status', '');
      
      $orders = Auth::user()->orders()
          ->with(['canteen', 'orderItems.menu'])
          ->when($status, function ($query, $status) {
              return $query->where('status', $status);
          })
          ->orderBy('created_at', 'desc')
          ->paginate(10);
          
      return view('buyer.orders.index', compact('orders', 'status'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(Canteen $canteen)
  {
      $canteen->load('menus');
      
      if ($canteen->menus->isEmpty()) {
          return redirect()->route('buyer.canteens.show', $canteen)
              ->with('error', 'This canteen has no menus available.');
      }
      
      return view('buyer.orders.create', compact('canteen'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request, Canteen $canteen)
  {
      $request->validate([
          'menu_items' => 'required|array',
          'menu_items.*.menu_id' => 'required|exists:menus,id',
          'menu_items.*.quantity' => 'required|integer|min:1',
      ]);
      
      $menuItems = $request->menu_items;
      $totalPrice = 0;
      
      // Calculate total price and validate menu items
      foreach ($menuItems as $item) {
          $menu = Menu::findOrFail($item['menu_id']);
          
          if ($menu->canteen_id !== $canteen->id) {
              return redirect()->back()
                  ->with('error', 'Invalid menu item selected.');
          }
          
          $totalPrice += $menu->price * $item['quantity'];
      }
      
      DB::beginTransaction();
      
      try {
          // Create order
          $order = Order::create([
              'order_number' => 'ORD-' . Str::random(8),
              'user_id' => Auth::id(),
              'canteen_id' => $canteen->id,
              'total_price' => $totalPrice,
              'status' => 'pending',
              'payment_status' => 'pending',
          ]);
          
          // Create order items
          foreach ($menuItems as $item) {
              $menu = Menu::findOrFail($item['menu_id']);
              
              OrderItem::create([
                  'order_id' => $order->id,
                  'menu_id' => $menu->id,
                  'quantity' => $item['quantity'],
                  'price' => $menu->price,
              ]);
          }
          
          DB::commit();
          
          return redirect()->route('buyer.orders.payment', $order)
              ->with('success', 'Order created successfully. Please complete the payment.');
              
      } catch (\Exception $e) {
          DB::rollBack();
          
          return redirect()->back()
              ->with('error', 'Failed to create order. Please try again.');
      }
  }

  /**
   * Display the specified resource.
   */
  public function show(Order $order)
  {
      if ($order->user_id !== Auth::id()) {
          return abort(403, 'Unauthorized action.');
      }
      
      $order->load(['canteen', 'orderItems.menu']);
      
      return view('buyer.orders.show', compact('order'));
  }

  /**
   * Show payment page for the order.
   */
  public function payment(Order $order)
  {
      if ($order->user_id !== Auth::id()) {
          return abort(403, 'Unauthorized action.');
      }
      
      if ($order->payment_status !== 'pending') {
          return redirect()->route('buyer.orders.show', $order)
              ->with('info', 'Payment has already been processed for this order.');
      }
      
      $order->load(['canteen', 'orderItems.menu']);
      
      // Set up Midtrans payment
      $params = [
          'transaction_details' => [
              'order_id' => $order->order_number,
              'gross_amount' => (int) $order->total_price,
          ],
          'customer_details' => [
              'first_name' => Auth::user()->name,
              'email' => Auth::user()->email,
          ],
          'item_details' => [],
      ];
      
      // Add order items to Midtrans params
      foreach ($order->orderItems as $item) {
          $params['item_details'][] = [
              'id' => $item->menu_id,
              'price' => (int) $item->price,
              'quantity' => $item->quantity,
              'name' => $item->menu->name,
          ];
      }
      
      // Get Snap Token
      $snapToken = \Midtrans\Snap::getSnapToken($params);
      
      return view('buyer.orders.payment', compact('order', 'snapToken'));
  }

  /**
   * Process payment for the order.
   */
  public function processPayment(Request $request, Order $order)
  {
      if ($order->user_id !== Auth::id()) {
          return abort(403, 'Unauthorized action.');
      }
      
      if ($order->payment_status !== 'pending') {
          return redirect()->route('buyer.orders.show', $order)
              ->with('info', 'Payment has already been processed for this order.');
      }
      
      $request->validate([
          'payment_type' => 'required',
          'transaction_id' => 'required',
          'transaction_status' => 'required',
      ]);
      
      // Update order with payment information
      $order->payment_type = $request->payment_type;
      $order->transaction_id = $request->transaction_id;
      
      // Update payment status based on transaction status
      if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
          $order->payment_status = 'paid';
          $order->status = 'processing';
          
          // Generate barcode when payment is completed
          $barcode = 'ORD-' . $order->id . '-' . time();
          $order->barcode = $barcode;
          
          // Generate QR code
          $qrCode = QrCode::format('png')
              ->size(200)
              ->generate($barcode);
          
          // Store QR code
          $path = 'barcodes/' . $barcode . '.png';
          \Storage::disk('public')->put($path, $qrCode);
          
          // Send notification to buyer
          try {
              $order->user->notify(new OrderPaid($order));
          } catch (\Exception $e) {
              // Log error but continue
              \Log::error('Failed to send payment notification: ' . $e->getMessage());
          }
          
          // Send notification to seller
          try {
              $order->canteen->user->notify(new \App\Notifications\NewOrderReceived($order));
          } catch (\Exception $e) {
              // Log error but continue
              \Log::error('Failed to send new order notification: ' . $e->getMessage());
          }
      } else {
          $order->payment_status = 'failed';
      }
      
      $order->save();
      
      if ($order->payment_status == 'paid') {
          return redirect()->route('buyer.orders.barcode', $order)
              ->with('success', 'Payment processed successfully. Your order is now being prepared. Please show this QR code when picking up your order.');
      } else {
          return redirect()->route('buyer.orders.show', $order)
              ->with('error', 'Payment failed. Please try again.');
      }
  }

  /**
   * Handle Midtrans notification callback.
   */
  public function handlePaymentNotification(Request $request)
  {
      $notificationBody = json_decode($request->getContent(), true);
      
      $transactionStatus = $notificationBody['transaction_status'];
      $orderId = $notificationBody['order_id'];
      $fraudStatus = $notificationBody['fraud_status'] ?? null;
      
      $order = Order::where('order_number', $orderId)->first();
      
      if (!$order) {
          return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
      }
      
      if ($transactionStatus == 'capture') {
          if ($fraudStatus == 'challenge') {
              $order->payment_status = 'pending';
          } else if ($fraudStatus == 'accept') {
              $order->payment_status = 'paid';
              $order->status = 'processing';
              
              // Generate barcode if not already generated
              if (!$order->barcode) {
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
              
              // Send notifications
              try {
                  $order->user->notify(new OrderPaid($order));
                  $order->canteen->user->notify(new \App\Notifications\NewOrderReceived($order));
              } catch (\Exception $e) {
                  \Log::error('Failed to send notification: ' . $e->getMessage());
              }
          }
      } else if ($transactionStatus == 'settlement') {
          $order->payment_status = 'paid';
          $order->status = 'processing';
          
          // Generate barcode if not already generated
          if (!$order->barcode) {
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
          
          // Send notifications
          try {
              $order->user->notify(new OrderPaid($order));
              $order->canteen->user->notify(new \App\Notifications\NewOrderReceived($order));
          } catch (\Exception $e) {
              \Log::error('Failed to send notification: ' . $e->getMessage());
          }
      } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
          $order->payment_status = 'failed';
      } else if ($transactionStatus == 'pending') {
          $order->payment_status = 'pending';
      }
      
      $order->save();
      
      return response()->json(['status' => 'success']);
  }

  /**
   * Show the barcode for the order.
   */
  public function barcode(Order $order)
  {
      if ($order->user_id !== Auth::id()) {
          return abort(403, 'Unauthorized action.');
      }
      
      if (!$order->barcode) {
          return redirect()->route('buyer.orders.show', $order)
              ->with('info', 'Barcode is not available for this order yet.');
      }
      
      return view('buyer.orders.barcode', compact('order'));
  }
}

