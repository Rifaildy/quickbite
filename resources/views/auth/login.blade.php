@extends('layouts.app')

@section('title', __('auth.login'))

@section('content')
<div class="container-fluid vh-100">
   <div class="row h-100">
       <!-- Form Login -->
       <div class="col-md-6 d-flex align-items-center justify-content-center px-5">
           <div class="w-100">
               <h2 class="fw-bold" style="color: #bb0718;">{{ __('Login QuickBite.') }}</h2>
               <p class="text-muted">Pesan MakananMu Sekarang Juga!!!</p>

               <form method="POST" action="{{ route('login') }}" class="mt-4">
                   @csrf
                   <div class="mb-3">
                       <label for="email" class="form-label">{{ __('auth.email_address') }}</label>
                       <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                       @error('email')
                           <span class="invalid-feedback" role="alert">
                               <strong>{{ $message }}</strong>
                           </span>
                       @enderror
                   </div>

                   <div class="mb-3">
                       <label for="password" class="form-label">{{ __('auth.password') }}</label>
                       <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                       @error('password')
                           <span class="invalid-feedback" role="alert">
                               <strong>{{ $message }}</strong>
                           </span>
                       @enderror
                   </div>

                   <div class="mb-3 form-check">
                       <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                       <label class="form-check-label" for="remember">
                           {{ __('auth.remember_me') }}
                       </label>
                   </div>

                   <div class="d-grid gap-2">
                       <button type="submit" class="btn" style="background-color: #ffd109; color: black;">
                           {{ __('auth.login') }}
                       </button>
                   </div>

                   <div class="text-center mt-3">
                       @if (Route::has('password.request'))
                           <a class="btn btn-link" href="{{ route('password.request') }}" style="color: #bb0718;">
                               {{ __('auth.forgot_password') }}
                           </a>
                       @endif
                   </div>

                   <p class="mt-3 text-center">{{ __('auth.dont_have_account') }} <a href="{{ route('register') }}" style="color: #bb0718;">{{ __('auth.register') }}</a></p>
               </form>
           </div>
       </div>
       
       <!-- Gambar -->
       <div class="col-md-6 d-none d-md-block p-0">
           <img src="{{ asset('images/quickbite.jpg') }}" class="img-fluid vh-100 w-100" style="object-fit: cover;">
       </div>
   </div>
</div>
@endsection
