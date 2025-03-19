@extends('layouts.seller')

@section('title', 'Edit Canteen')

@section('seller-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Canteen</h1>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Canteen Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('seller.canteens.update', $canteen) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Canteen Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $canteen->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $canteen->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Describe your canteen, including the type of food you serve, operating hours, etc.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status_active" value="1" {{ $canteen->status ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_active">Active</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status_inactive" value="0" {{ !$canteen->status ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_inactive">Inactive</label>
                            </div>
                        </div>
                        <div class="form-text">When inactive, your canteen will not be visible to buyers.</div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Update Canteen</button>
                        <a href="{{ route('seller.dashboard') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

