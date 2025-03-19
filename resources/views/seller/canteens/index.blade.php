@extends('layouts.seller')

@section('title', 'My Canteen')

@section('seller-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">My Canteen</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('seller.canteens.edit', $canteen) }}" class="btn btn-sm btn-primary">
            <i class="fas fa-edit"></i> Edit Canteen
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Canteen Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Name</label>
                    <p>{{ $canteen->name }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Description</label>
                    <p>{{ $canteen->description ?? 'No description available' }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <p>
                        @if($canteen->status)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Created On</label>
                    <p>{{ $canteen->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Last Updated</label>
                    <p>{{ $canteen->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Statistics</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Total Menu Items</label>
                    <p>{{ $canteen->menus()->count() }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Active Menu Items</label>
                    <p>{{ $canteen->menus()->where('status', true)->count() }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Total Orders</label>
                    <p>{{ $canteen->orders()->count() }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Pending Orders</label>
                    <p>{{ $canteen->orders()->where('status', 'pending')->count() }}</p>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('seller.menus.create') }}" class="btn btn-primary w-100">
                    <i class="fas fa-plus"></i> Add Menu Item
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('seller.menus.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-utensils"></i> Manage Menu
                    </a>
                    <a href="{{ route('seller.orders.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-shopping-cart"></i> View Orders
                    </a>
                    <a href="{{ route('seller.orders.scan') }}" class="btn btn-outline-primary">
                        <i class="fas fa-qrcode"></i> Scan Order
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

