@extends('layouts.seller')

@section('title', 'Order Details')

@section('seller-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2">Order #{{ $order->order_number }}</h1>
  <div class="btn-toolbar mb-2 mb-md-0">
      <a href="{{ route('seller.orders.index') }}" class="btn btn-sm btn-secondary">
          <i class="fas fa-arrow-left" class="btn btn-sm btn-secondary"></i> Back to Orders
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
              <div class="row">
                  <div class="col-md-6 mb-3">
                      <div class="card h-100">
                          <div class="card-body">
                              <h6 class="card-title">Update Order Status</h6>
                              <form action="{{ route('seller.orders.update-status', $order) }}" method="POST">
                                  @csrf
                                  @method('PATCH')
                                  <div class="mb-3">
                                      <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                          <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                          <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                          <option value="ready_for_pickup" {{ $order->status == 'ready_for_pickup' ? 'selected' : '' }}>Ready for Pickup</option>
                                          <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                          <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                      </select>
                                      @error('status')
                                          <div class="invalid-feedback">{{ $message }}</div>
                                      @enderror
                                  </div>
                                  <button type="submit" class="btn btn-primary">Update Status</button>
                              </form>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-6 mb-3">
                      <div class="card h-100">
                          <div class="card-body">
                              <h6 class="card-title">Payment Actions</h6>
                              @if($order->payment_status == 'pending')
                                  <p>Payment is pending. Confirm when payment is received.</p>
                                  <form action="{{ route('seller.orders.confirm-payment', $order) }}" method="POST">
                                      @csrf
                                      @method('PATCH')
                                      <button type="submit" class="btn btn-success">Confirm Payment</button>
                                  </form>
                              @elseif($order->payment_status == 'paid')
                                  <p class="text-success">Payment has been confirmed.</p>
                              @else
                                  <p class="text-danger">Payment failed or was cancelled.</p>
                              @endif
                          </div>
                      </div>
                  </div>
              </div>
              
              @if(($order->status == 'ready_for_pickup' || $order->status == 'completed') && $order->barcode)
                  <div class="row mt-3">
                      <div class="col-12">
                          <div class="card">
                              <div class="card-body">
                                  <h6 class="card-title">Order Barcode</h6>
                                  <p>The customer will present this barcode when picking up their order.</p>
                                  <div class="text-center">
                                      <img src="{{ asset('storage/barcodes/' . $order->barcode . '.png') }}" alt="Order Barcode" class="img-fluid" style="max-width: 200px;">
                                      <p class="mt-2">{{ $order->barcode }}</p>
                                  </div>
                              </div>
                          </div>
                      </div>
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
              <h5 class="card-title mb-0">Customer Information</h5>
          </div>
          <div class="card-body">
              <div class="mb-3">
                  <label class="form-label fw-bold">Name</label>
                  <p>{{ $order->user->name }}</p>
              </div>
              <div class="mb-3">
                  <label class="form-label fw-bold">Email</label>
                  <p>{{ $order->user->email }}</p>
              </div>
          </div>
      </div>
  </div>
</div>
@endsection

