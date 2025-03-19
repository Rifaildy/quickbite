@extends('layouts.admin')

@section('title', 'Manage Orders')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manage Orders</h1>
</div>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <form action="{{ route('admin.orders.index') }}" method="GET" class="d-flex">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search orders..." name="search" value="{{ $search ?? '' }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end gap-2">
                    <select name="canteen_id" class="form-select form-select-sm" onchange="window.location.href='{{ route('admin.orders.index') }}?canteen_id='+this.value">
                        <option value="">All Canteens</option>
                        @foreach($canteens as $canteen)
                            <option value="{{ $canteen->id }}" {{ $canteenId == $canteen->id ? 'selected' : '' }}>{{ $canteen->name }}</option>
                        @endforeach
                    </select>
                    <select name="status" class="form-select form-select-sm" onchange="window.location.href='{{ route('admin.orders.index') }}?status='+this.value">
                        <option value="">All Status</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $status == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="ready_for_pickup" {{ $status == 'ready_for_pickup' ? 'selected' : '' }}>Ready for Pickup</option>
                        <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <select name="payment_status" class="form-select form-select-sm" onchange="window.location.href='{{ route('admin.orders.index') }}?payment_status='+this.value">
                        <option value="">All Payment Status</option>
                        <option value="pending" {{ $paymentStatus == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $paymentStatus == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="failed" {{ $paymentStatus == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
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
                        <th>Canteen</th>
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
                            <td>{{ $order->canteen->name }}</td>
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
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No orders found</td>
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

