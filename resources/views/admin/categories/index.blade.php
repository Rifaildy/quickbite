@extends('layouts.admin')

@section('title', 'Manage Categories')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manage Categories</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Add New Category
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Menu Items</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->menus_count }}</td>
                            <td>{{ $category->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $category->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                &lt;!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $category->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $category->id }}">Confirm Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete category <strong>{{ $category->name }}</strong>?
                                                @if($category->menus_count > 0)
                                                <p class="text-danger mt-2">
                                                    <i class="fas fa-exclamation-triangle"></i> This category has {{ $category->menus_count }} menu items. You cannot delete it until all menu items are removed from this category.
                                                </p>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" {{ $category->menus_count > 0 ? 'disabled' : '' }}>Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No categories found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection

