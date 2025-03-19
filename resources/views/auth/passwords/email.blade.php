@extends('layouts.app')

@section('title', __('auth.reset_password'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ __('auth.reset_password') }}</h4>
                </div>

                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label">{{ __('auth.email_address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="form-text text-muted mt-2">
                                {{ __('passwords.email_instructions') }}
                            </small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                {{ __('auth.send_password_reset_link') }}
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="card-footer bg-light text-center">
                    <a href="{{ route('login') }}" class="btn btn-link">
                        <i class="fas fa-arrow-left me-1"></i> {{ __('auth.back_to_login') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

