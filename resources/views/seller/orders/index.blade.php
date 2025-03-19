@extends('layouts.seller')

@section('title', 'Manage Orders')

@section('seller-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2">Manage Orders</h1>
</div>

<div class="card">
  <div class="card-header">
      <div class="row align-items-center">
          <div class="col">
              <h5 class="card-title mb-0">Orders</h5>
          </div>
          <div class="col-auto">
              <form action="{{ route('seller.orders.index') }}" method="GET" class="d-flex">
                  <select name="status" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                      <option value="">All Orders</option>
                      <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                      <option value="processing" {{ $status == 'processing' ? 'selected' : '' }}>Processing</option>
                      <option value="ready_for_pickup" {{ $status == 'ready_for_pickup' ? 'selected' : '' }}>Ready for Pickup</option>
                      <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                      <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                  </select>
              </form>
          </div>
      </div>
  </div>
  <div class="card-body">
      <div class="table-responsive">
          <table class="table table-striped">
              <thead>
                  <tr>
                      <th>Order #</th>
                      <th>Customer</th>
                      <th>Total</th>
                      <th>Status</th>
                      <th>Payment</th>
                      <th>Date</th>
                      <th>Actions</th>
                  </tr>
              </thead>
              <tbody>
                  @forelse($orders as $order)
                      <tr>
                          <td>{{ $order->order_number }}</td>
                          <td>{{ $order->user->name }}</td>
                          <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                          <td>
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
                          </td>
                          <td>
                              @if($order->payment_status == 'pending')
                                  <span class="badge bg-warning">Pending</span>
                              @elseif($order->payment_status == 'paid')
                                  <span class="badge bg-success">Paid</span>
                              @elseif($order->payment_status == 'failed')
                                  <span class="badge bg-danger">Failed</span>
                              @endif
                          </td>
                          <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                          <td>
                              <a href="{{ route('seller.orders.show', $order) }}" class="btn btn-sm btn-primary">View</a>
                          </td>
                      </tr>
                  @empty
                      <tr>
                          <td colspan="7" class="text-center">No orders found</td>
                      </tr>
                  @endforelse
              </tbody>
          </table>
      </div>

      <div class="d-flex justify-content-center mt-4">
          {{ $orders->links() }}
      </div>
  </div>
</div>
@endsection

