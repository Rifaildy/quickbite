@extends('layouts.app')

@section('title', __('auth.register'))

@section('content')
<div class="container-fluid vh-100">
   <div class="row h-100">
       <!-- Form Register -->
       <div class="col-md-6 d-flex align-items-center justify-content-center px-5">
           <div class="w-100">
               <h2 class="fw-bold" style="color: #bb0718;">{{ __('auth.register') }}</h2>
               <p class="text-muted">Buat akun Anda dan mulai pengalaman pesan makanan yang mudah dan cepat!</p>

               <form method="POST" action="{{ route('register') }}" class="mt-4">
                   @csrf
                   <div class="mb-3">
                       <label for="name" class="form-label">{{ __('auth.name') }}</label>
                       <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                       @error('name')
                           <span class="invalid-feedback" role="alert">
                               <strong>{{ $message }}</strong>
                           </span>
                       @enderror
                   </div>

                   <div class="mb-3">
                       <label for="email" class="form-label">{{ __('auth.email_address') }}</label>
                       <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                       @error('email')
                           <span class="invalid-feedback" role="alert">
                               <strong>{{ $message }}</strong>
                           </span>
                       @enderror
                   </div>

                   <div class="mb-3">
                       <label for="password" class="form-label">{{ __('auth.password') }}</label>
                       <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                       @error('password')
                           <span class="invalid-feedback" role="alert">
                               <strong>{{ $message }}</strong>
                           </span>
                       @enderror
                   </div>

                   <div class="mb-3">
                       <label for="password-confirm" class="form-label">{{ __('auth.confirm_password') }}</label>
                       <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                   </div>

                   <div class="mb-3">
                       <label for="role" class="form-label">{{ __('auth.register_as') }}</label>
                       <select id="role" class="form-select @error('role') is-invalid @enderror" name="role" required>
                           <option value="buyer" {{ old('role') == 'buyer' ? 'selected' : '' }}>{{ __('general.buyer') }}</option>
                           <option value="seller" {{ old('role') == 'seller' ? 'selected' : '' }}>{{ __('general.seller') }}</option>
                       </select>
                       @error('role')
                           <span class="invalid-feedback" role="alert">
                               <strong>{{ $message }}</strong>
                           </span>
                       @enderror
                   </div>

                   <div class="d-grid gap-2">
                       <button type="submit" class="btn" style="background-color: #ffd109; color: black;">
                           {{ __('auth.register') }}
                       </button>
                   </div>
               </form>

               <p class="mt-3 text-center">{{ __('auth.already_have_account') }} <a href="{{ route('login') }}" style="color: #bb0718;">{{ __('auth.login') }}</a></p>
           </div>
       </div>
       
       <!-- Gambar -->
       <div class="col-md-6 d-none d-md-block p-0">
           <img src="{{ asset('images/quickbite.jpg') }}" class="img-fluid vh-100 w-100" style="object-fit: cover;">
       </div>
   </div>
</div>
@endsection