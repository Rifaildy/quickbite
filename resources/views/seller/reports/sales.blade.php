@extends('layouts.seller')

@section('title', 'Sales History')

@section('seller-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Sales History</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('seller.reports.export-sales', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-download"></i> Export
            </a>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>
</div>

<!-- Date Range Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('seller.reports.sales') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Report Navigation -->
<div class="row mb-4">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('seller.reports.index') }}">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('seller.reports.sales') }}">Sales History</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('seller.reports.menu-performance') }}">Menu Performance</a>
            </li>
        </ul>
    </div>
</div>

<!-- Sales History Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Sales History ({{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }})</h5>
    </div>
    <div class="card-body">
        @if($orders->isEmpty())
            <p class="text-center text-muted my-5">No sales data available for this period</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th class="text-end">Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>
                                    <button class="btn btn-sm btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#items{{ $order->id }}" aria-expanded="false">
                                        {{ $order->orderItems->count() }} items
                                    </button>
                                </td>
                                <td class="text-end">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td>
                                    @if($order->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($order->status == 'processing')
                                        <span class="badge bg-info">Processing</span>
                                    @elseif($order->status == 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($order->status == 'cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('seller.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr class="collapse" id="items{{ $order->id }}">
                                <td colspan="7" class="p-0">
                                    <div class="p-3 bg-light">
                                        <h6 class="mb-2">Order Items</h6>
                                        <table class="table table-sm mb-0">
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
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

@endsection

