@extends('layouts.admin')

@section('title', 'Manage Canteens')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manage Canteens</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.canteens.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Add New Canteen
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <form action="{{ route('admin.canteens.index') }}" method="GET" class="d-flex">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search canteens..." name="search" value="{{ $search ?? '' }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <div class="d-flex justify-content-end">
                    <select name="status" class="form-select form-select-sm w-auto" onchange="window.location.href='{{ route('admin.canteens.index') }}?status='+this.value">
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
                        <th>Name</th>
                        <th>Owner</th>
                        <th>Status</th>
                        <th>Menus</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($canteens as $canteen)
                        <tr>
                            <td>{{ $canteen->id }}</td>
                            <td>{{ $canteen->name }}</td>
                            <td>{{ $canteen->user->name }}</td>
                            <td>
                                @if($canteen->status)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $canteen->menus_count ?? $canteen->menus->count() }}</td>
                            <td>{{ $canteen->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.canteens.show', $canteen) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.canteens.edit', $canteen) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $canteen->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $canteen->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $canteen->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $canteen->id }}">Confirm Delete</h5>
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
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No canteens found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $canteens->links() }}
        </div>
    </div>
</div>
@endsection

