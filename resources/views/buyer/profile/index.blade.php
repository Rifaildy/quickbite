@extends('layouts.buyer')

@section('title', 'My Profile')

@section('buyer-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">My Profile</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Personal Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('buyer.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Account Summary</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Member Since</label>
                    <p>{{ Auth::user()->created_at->format('d M Y') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Total Orders</label>
                    <p>{{ $totalOrders }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Last Order</label>
                    <p>{{ $lastOrder ? $lastOrder->created_at->format('d M Y H:i') : 'No orders yet' }}</p>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Preferences</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('buyer.profile.update-preferences') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Notification Preferences</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="email_notifications" name="preferences[email_notifications]" value="1" {{ $preferences['email_notifications'] ?? true ? 'checked' : '' }}>
                            <label class="form-check-label" for="email_notifications">
                                Email Notifications
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="order_updates" name="preferences[order_updates]" value="1" {{ $preferences['order_updates'] ?? true ? 'checked' : '' }}>
                            <label class="form-check-label" for="order_updates">
                                Order Status Updates
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="promotional_emails" name="preferences[promotional_emails]" value="1" {{ $preferences['promotional_emails'] ?? false ? 'checked' : '' }}>
                            <label class="form-check-label" for="promotional_emails">
                                Promotional Emails
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Save Preferences</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

