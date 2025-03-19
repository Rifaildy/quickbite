@extends('layouts.admin')

@section('title', 'System Settings')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">System Settings</h1>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="list-group">
            <a href="{{ route('admin.settings.index') }}" class="list-group-item list-group-item-action active">
                <i class="fas fa-cog me-2"></i> General Settings
            </a>
            <a href="{{ route('admin.settings.system-info') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-info-circle me-2"></i> System Information
            </a>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">General Settings</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="app_name" class="form-label">Application Name</label>
                        <input type="text" class="form-control @error('app_name') is-invalid @enderror" id="app_name" name="app_name" value="{{ old('app_name', $settings['app_name']) }}" required>
                        @error('app_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="app_url" class="form-label">Application URL</label>
                        <input type="url" class="form-control @error('app_url') is-invalid @enderror" id="app_url" name="app_url" value="{{ old('app_url', $settings['app_url']) }}" required>
                        @error('app_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="app_timezone" class="form-label">Timezone</label>
                        <select class="form-select @error('app_timezone') is-invalid @enderror" id="app_timezone" name="app_timezone" required>
                            <option value="UTC" {{ $settings['app_timezone'] == 'UTC' ? 'selected' : '' }}>UTC</option>
                            <option value="Asia/Jakarta" {{ $settings['app_timezone'] == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta</option>
                            <option value="Asia/Singapore" {{ $settings['app_timezone'] == 'Asia/Singapore' ? 'selected' : '' }}>Asia/Singapore</option>
                            <option value="Asia/Kuala_Lumpur" {{ $settings['app_timezone'] == 'Asia/Kuala_Lumpur' ? 'selected' : '' }}>Asia/Kuala_Lumpur</option>
                            <option value="Asia/Bangkok" {{ $settings['app_timezone'] == 'Asia/Bangkok' ? 'selected' : '' }}>Asia/Bangkok</option>
                        </select>
                        @error('app_timezone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <hr>
                    
                    <h5 class="mb-3">Mail Settings</h5>
                    
                    <div class="mb-3">
                        <label for="mail_from_address" class="form-label">From Address</label>
                        <input type="email" class="form-control @error('mail_from_address') is-invalid @enderror" id="mail_from_address" name="mail_from_address" value="{{ old('mail_from_address', $settings['mail_from_address']) }}" required>
                        @error('mail_from_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="mail_from_name" class="form-label">From Name</label>
                        <input type="text" class="form-control @error('mail_from_name') is-invalid @enderror" id="mail_from_name" name="mail_from_name" value="{{ old('mail_from_name', $settings['mail_from_name']) }}" required>
                        @error('mail_from_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </div>
                </form>
                
                <hr>
                
                <h5 class="mb-3">Maintenance</h5>
                
                <form action="{{ route('admin.settings.clear-cache') }}" method="POST">
                    @csrf
                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-broom me-2"></i> Clear Application Cache
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

