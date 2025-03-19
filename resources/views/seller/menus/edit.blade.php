@extends('layouts.seller')

@section('title', 'Edit Menu Item')

@section('seller-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Menu Item</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('seller.menus.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Menus
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Menu Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('seller.menus.update', $menu) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Menu Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $menu->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $menu->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="price" class="form-label">Price (Rp)</label>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $menu->price) }}" min="0" step="1000" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $menu->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Menu Image</label>
                        @if($menu->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="delete_image" name="delete_image" value="1">
                                <label class="form-check-label" for="delete_image">
                                    Delete current image
                                </label>
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Upload a new image to replace the current one (optional).</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status_active" value="1" {{ old('status', $menu->status ? '1' : '0') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_active">Active</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status_inactive" value="0" {{ old('status', $menu->status ? '1' : '0') == '0' ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_inactive">Inactive</label>
                            </div>
                        </div>
                        <div class="form-text">Inactive menu items will not be visible to buyers.</div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Update Menu Item</button>
                        <a href="{{ route('seller.menus.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

