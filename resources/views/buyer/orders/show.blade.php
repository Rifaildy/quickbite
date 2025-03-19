@extends('layouts.buyer')

@section('title', 'Order Details')

@section('buyer-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2">Order #{{ $order->order_number }}</h1>
  <div class="btn-toolbar mb-2 mb-md-0">
      <a href="{{ route('buyer.orders.index') }}" class="btn btn-sm btn-secondary">
          <i class="fas fa-arrow-left"></i> Back to Orders
      </a>
  </div>
</div>

<div class="row">
  <div class="col-md-8">
      <div class="card mb-4">
          <div class="card-header">
              <h5 class="card-title mb-0">Order Items</h5>
          </div>
          <div class="card-body">
              <div class="table-responsive">
                  <table class="table">
                      <thead>
                          <tr>
                              <th>Item</th>
                              <th>Price</th>
                              <th>Quantity</th>
                              <th class="text-end">Subtotal</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach($order->orderItems as $item)
                              <tr>
                                  <td>{{ $item->menu->name }}</td>
                                  <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                  <td>{{ $item->quantity }}</td>
                                  <td class="text-end">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                              </tr>
                          @endforeach
                      </tbody>
                      <tfoot>
                          <tr>
                              <th colspan="3" class="text-end">Total:</th>
                              <th class="text-end">Rp {{ number_format($order->total_price, 0, ',', '.') }}</th>
                          </tr>
                      </tfoot>
                  </table>
              </div>
          </div>
      </div>

      <div class="card">
          <div class="card-header">
              <h5 class="card-title mb-0">Order Actions</h5>
          </div>
          <div class="card-body">
              @if($order->payment_status == 'pending')
                  <div class="alert alert-warning">
                      <h5 class="alert-heading">Payment Required</h5>
                      <p>Your order has been placed but payment is still pending. Please complete the payment to process your order.</p>
                      <a href="{{ route('buyer.orders.payment', $order) }}" class="btn btn-primary">Pay Now</a>
                  </div>
              @elseif($order->status == 'ready_for_pickup')
                  <div class="alert alert-primary">
                      <h5 class="alert-heading">Order Ready for Pickup</h5>
                      <p>Your order is ready for pickup! Please show the QR code when collecting your order.</p>
                      @if($order->barcode)
                          <a href="{{ route('buyer.orders.barcode', $order) }}" class="btn btn-success">View QR Code</a>
                      @endif
                  </div>
              @elseif($order->status == 'completed')
                  <div class="alert alert-success">
                      <h5 class="alert-heading">Order Completed</h5>
                      <p>Your order has been completed. Thank you for your purchase!</p>
                      @if($order->barcode)
                          <a href="{{ route('buyer.orders.barcode', $order) }}" class="btn btn-success">View QR Code</a>
                      @endif
                  </div>
              @elseif($order->status == 'processing' && $order->barcode)
                  <div class="alert alert-info">
                      <h5 class="alert-heading">Order in Progress</h5>
                      <p>Your order is being prepared. You will be notified when it's ready for pickup.</p>
                      <a href="{{ route('buyer.orders.barcode', $order) }}" class="btn btn-success">View QR Code</a>
                  </div>
              @elseif($order->status == 'cancelled')
                  <div class="alert alert-danger">
                      <h5 class="alert-heading">Order Cancelled</h5>
                      <p>This order has been cancelled.</p>
                  </div>
              @endif
          </div>
      </div>
  </div>
  
  <div class="col-md-4">
      <div class="card mb-4">
          <div class="card-header">
              <h5 class="card-title mb-0">Order Information</h5>
          </div>
          <div class="card-body">
              <div class="mb-3">
                  <label class="form-label fw-bold">Order Number</label>
                  <p>{{ $order->order_number }}</p>
              </div>
              <div class="mb-3">
                  <label class="form-label fw-bold">Order Date</label>
                  <p>{{ $order->created_at->format('d M Y H:i') }}</p>
              </div>
              <div class="mb-3">
                  <label class="form-label fw-bold">Status</label>
                  <p>
                      @if($order->status == 'pending')
                          <span class="badge bg-warning">Pending</span>
                      @elseif($order->status == 'processing')
                          <span class="badge bg-info">Processing</span>
                      @elseif($order->status == 'ready_for_pickup')
                          <span class="badge bg-primary">Ready for Pickup</span>
                      @elseif($order->status == 'completed')
                          <span class="badge bg-success">Completed</span>
                      @elseif($order->status == 'cancelled')
                          <span class="badge bg-danger">Cancelled</span>
                      @endif
                  </p>
              </div>
              <div class="mb-3">
                  <label class="form-label fw-bold">Payment Status</label>
                  <p>
                      @if($order->payment_status == 'pending')
                          <span class="badge bg-warning">Pending</span>
                      @elseif($order->payment_status == 'paid')
                          <span class="badge bg-success">Paid</span>
                      @elseif($order->payment_status == 'failed')
                          <span class="badge bg-danger">Failed</span>
                      @endif
                  </p>
              </div>
          </div>
      </div>

      <div class="card">
          <div class="card-header">
              <h5 class="card-title mb-0">Canteen Information</h5>
          </div>
          <div class="card-body">
              <div class="mb-3">
                  <label class="form-label fw-bold">Name</label>
                  <p>{{ $order->canteen->name }}</p>
              </div>
              <div class="mb-3">
                  <label class="form-label fw-bold">Description</label>
                  <p>{{ $order->canteen->description ?? 'No description available' }}</p>
              </div>
          </div>
      </div>
  </div>
</div>
@endsection

