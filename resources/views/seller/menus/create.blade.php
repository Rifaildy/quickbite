@extends('layouts.seller')

@section('title', 'Add Menu Item')

@section('seller-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Add Menu Item</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('seller.menus.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Menus
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
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Menu Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('seller.menus.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Menu Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="price" class="form-label">Price (Rp)</label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" min="0" step="1000" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Menu Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Upload an image of your menu item (optional).</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="status_active" value="1" {{ old('status', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status_active">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="status_inactive" value="0" {{ old('status') == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status_inactive">Inactive</label>
                                </div>
                            </div>
                            <div class="form-text">Inactive menu items will not be visible to buyers.</div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Add Menu Item</button>
                            <a href="{{ route('seller.menus.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

