@extends('layouts.admin')

@section('title', 'Manage Menus')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manage Menus</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.menus.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Add New Menu
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <form action="{{ route('admin.menus.index') }}" method="GET" class="d-flex">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search menus..." name="search" value="{{ $search ?? '' }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <div class="d-flex justify-content-end gap-2">
                    <select name="canteen_id" class="form-select form-select-sm" onchange="window.location.href='{{ route('admin.menus.index') }}?canteen_id='+this.value">
                        <option value="">All Canteens</option>
                        @foreach($canteens as $canteen)
                            <option value="{{ $canteen->id }}" {{ $canteenId == $canteen->id ? 'selected' : '' }}>{{ $canteen->name }}</option>
                        @endforeach
                    </select>
                    <select name="category_id" class="form-select form-select-sm" onchange="window.location.href='{{ route('admin.menus.index') }}?category_id='+this.value">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <select name="status" class="form-select form-select-sm" onchange="window.location.href='{{ route('admin.menus.index') }}?status='+this.value">
                        <option value="">All Status</option>
                        <option value="1" {{ isset($status) && $status == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ isset($status) && $status == '0' ? 'selected' : '' }}>Inactive</option>
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
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Canteen</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $menu)
                        <tr>
                            <td>{{ $menu->id }}</td>
                            <td>
                                @if($menu->image)
                                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="fas fa-utensils text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $menu->name }}</td>
                            <td>Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                            <td>{{ $menu->canteen->name }}</td>
                            <td>{{ $menu->category->name }}</td>
                            <td>
                                @if($menu->status)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.menus.show', $menu) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $menu->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
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
                                                Are you sure you want to delete menu item <strong>{{ $menu->name }}</strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No menu items found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $menus->links() }}
        </div>
    </div>
</div>
@endsection

