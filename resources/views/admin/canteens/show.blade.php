@extends('layouts.admin')

@section('title', 'Canteen Details')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Canteen Details</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.canteens.index') }}" class="btn btn-sm btn-secondary me-2">
            <i class="fas fa-arrow-left"></i> Back to Canteens
        </a>
        <a href="{{ route('admin.canteens.edit', $canteen) }}" class="btn btn-sm btn-primary me-2">
            <i class="fas fa-edit"></i> Edit
        </a>
        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
            <i class="fas fa-trash"></i> Delete
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
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
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Owner Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Name</label>
                    <p>{{ $canteen->user->name }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <p>{{ $canteen->user->email }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Role</label>
                    <p><span class="badge bg-success">Seller</span></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Registered On</label>
                    <p>{{ $canteen->user->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.users.show', $canteen->user) }}" class="btn btn-sm btn-primary">View User Profile</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Canteen Statistics</h5>
                <div>
                    <a href="{{ route('admin.menus.index', ['canteen_id' => $canteen->id]) }}" class="btn btn-sm btn-outline-primary me-2">
                        <i class="fas fa-utensils"></i> View Menus
                    </a>
                    <a href="{{ route('admin.orders.index', ['canteen_id' => $canteen->id]) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-shopping-cart"></i> View Orders
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Menus</h5>
                                <h2 class="mb-0">{{ $totalMenus }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Active Menus</h5>
                                <h2 class="mb-0">{{ $activeMenus }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Orders</h5>
                                <h2 class="mb-0">{{ $totalOrders }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Sales</h5>
                                <h2 class="mb-0">Rp {{ number_format($totalSales, 0, ',', '.') }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete canteen <strong>{{ $canteen->name }}</strong>?
                <p class="text-danger mt-2">
                    <i class="fas fa-exclamation-triangle"></i> This will also delete all menus associated with this canteen.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.canteens.destroy', $canteen) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

