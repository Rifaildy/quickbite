@extends('layouts.admin')

@section('title', 'Create Canteen')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Create New Canteen</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.canteens.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Canteens
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Canteen Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.canteens.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Canteen Name</label>
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
                        <label for="user_id" class="form-label">Seller (Owner)</label>
                        <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                            <option value="">Select Seller</option>
                            @foreach($sellers as $seller)
                                <option value="{{ $seller->id }}" {{ old('user_id') == $seller->id ? 'selected' : '' }}>{{ $seller->name }} ({{ $seller->email }})</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                        @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Create Canteen</button>
                        <a href="{{ route('admin.canteens.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

