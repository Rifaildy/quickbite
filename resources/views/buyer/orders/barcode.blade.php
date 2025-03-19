@extends('layouts.buyer')

@section('title', 'Order Barcode')

@section('buyer-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Order Barcode</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('buyer.orders.show', $order) }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Order
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Order #{{ $order->order_number }}</h5>
            </div>
            <div class="card-body text-center">
    @if($order->status == 'completed')
        <div class="alert alert-success mb-4">
            <h5 class="alert-heading">Your order has been completed!</h5>
            <p class="mb-0">Thank you for your purchase.</p>
        </div>
    @else
        <div class="alert alert-info mb-4">
            <h5 class="alert-heading">Your order is being prepared!</h5>
            <p class="mb-0">Present this barcode to the seller when collecting your order.</p>
        </div>
    @endif
    
    <div class="mb-4">
        <img src="{{ asset('storage/barcodes/' . $order->barcode . '.png') }}" alt="Order Barcode" class="img-fluid" style="max-width: 250px;">
    </div>
    
    <div class="mb-4">
        <h5>{{ $order->barcode }}</h5>
    </div>
    
    <div class="mb-4">
        <p><strong>Canteen:</strong> {{ $order->canteen->name }}</p>
        <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
        <p><strong>Total:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
        <p><strong>Status:</strong> 
            @if($order->status == 'pending')
                <span class="badge bg-warning">Pending</span>
            @elseif($order->status == 'processing')
                <span class="badge bg-info">Processing</span>
            @elseif($order->status == 'completed')
                <span class="badge bg-success">Completed</span>
            @elseif($order->status == 'cancelled')
                <span class="badge bg-danger">Cancelled</span>
            @endif
        </p>
    </div>
    
    <div class="d-grid gap-2">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-print me-2"></i> Print Barcode
        </button>
    </div>
</div>
        </div>
    </div>
</div>

<style>
    @media print {
        .navbar, .sidebar, .btn-toolbar, footer, .btn {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .card-header {
            background-color: white !important;
            border-bottom: 1px solid #ddd !important;
        }
    }
</style>
@endsection

