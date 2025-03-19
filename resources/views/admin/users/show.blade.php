@extends('layouts.admin')

@section('title', 'User Details')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">User Details</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary me-2">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary me-2">
            <i class="fas fa-edit"></i> Edit
        </a>
        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
            <i class="fas fa-trash"></i> Delete
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">User Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Name</label>
                    <p>{{ $user->name }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <p>{{ $user->email }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Role</label>
                    <p>
                        @if($user->isAdmin())
                            <span class="badge bg-danger">Admin</span>
                        @elseif($user->isSeller())
                            <span class="badge bg-success">Seller</span>
                        @elseif($user->isBuyer())
                            <span class="badge bg-primary">Buyer</span>
                        @endif
                    </p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Registered On</label>
                    <p>{{ $user->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Last Updated</label>
                    <p>{{ $user->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        @if($user->isSeller() && $user->canteen)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Canteen Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Canteen Name</label>
                        <p>{{ $user->canteen->name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <p>{{ $user->canteen->description ?? 'No description available' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <p>
                            @if($user->canteen->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endif
        
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Activity</h5>
            </div>
            <div class="card-body">
                @if($user->isBuyer())
                    <div class="mb-3">
                        <label class="form-label fw-bold">Total Orders</label>
                        <p>{{ $user->orders->count() }}</p>
                    </div>
                    @if($user->orders->count() > 0)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Last Order</label>
                            <p>{{ $user->orders->sortByDesc('created_at')->first()->created_at->format('d M Y H:i') }}</p>
                        </div>
                    @endif
                @elseif($user->isSeller() && $user->canteen)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Total Menus</label>
                        <p>{{ $user->canteen->menus->count() }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Total Orders Received</label>
                        <p>{{ $user->canteen->orders->count() }}</p>
                    </div>
                @endif
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
                Are you sure you want to delete user <strong>{{ $user->name }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

