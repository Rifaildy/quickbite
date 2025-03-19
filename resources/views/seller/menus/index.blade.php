@extends('layouts.seller')

@section('title', 'Manage Menus')

@section('seller-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manage Menus</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('seller.menus.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Add New Menu
        </a>
    </div>
</div>

@if(!Auth::user()->canteen)
    <div class="alert alert-warning">
        <h4 class="alert-heading">Canteen Required!</h4>
        <p>You need to create a canteen before you can add menu items.</p>
        <hr>
        <p class="mb-0">
            <a href="{{ route('seller.canteens.create') }}" class="btn btn-primary">Create Canteen</a>
        </p>
    </div>
@else
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="card-title mb-0">Menu Items</h5>
                </div>
                <div class="col-auto">
                    <form action="{{ route('seller.menus.index') }}" method="GET" class="d-flex">
                        <select name="category" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <select name="status" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($menus->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-utensils fa-4x text-muted mb-3"></i>
                    <h5>No menu items found</h5>
                    <p class="text-muted">Start adding menu items to your canteen.</p>
                    <a href="{{ route('seller.menus.create') }}" class="btn btn-primary">Add Menu Item</a>
                </div>
            @else
                <div class="row">
                    @foreach($menus as $menu)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                @if($menu->image)
                                    <img src="{{ asset('storage/' . $menu->image) }}" class="card-img-top" alt="{{ $menu->name }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fas fa-utensils fa-3x text-muted"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h5 class="card-title">{{ $menu->name }}</h5>
                                        <span class="badge {{ $menu->status ? 'bg-success' : 'bg-danger' }}">
                                            {{ $menu->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                    <p class="card-text text-muted small">{{ $menu->category->name }}</p>
                                    <p class="card-text">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                                    <p class="card-text small">{{ Str::limit($menu->description, 100) }}</p>
                                </div>
                                <div class="card-footer bg-white">
                                    <div class="btn-group w-100" role="group">
                                        <a href="{{ route('seller.menus.edit', $menu) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $menu->id }}">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $menu->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $menu->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $menu->id }}">Confirm Delete</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete <strong>{{ $menu->name }}</strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('seller.menus.destroy', $menu) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $menus->links() }}
                </div>
            @endif
        </div>
    </div>
@endif
@endsection

