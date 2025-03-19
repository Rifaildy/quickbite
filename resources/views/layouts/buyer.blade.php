@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
      <!-- Sidebar -->
      <div class="sidebar">
          <div class="position-sticky pt-3">
              <ul class="nav flex-column">
                  <li class="nav-item">
                      <a class="nav-link {{ request()->routeIs('buyer.dashboard') ? 'active' : '' }}" href="{{ route('buyer.dashboard') }}">
                          <i class="fas fa-tachometer-alt"></i> {{ __('general.dashboard') }}
                      </a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link {{ request()->routeIs('buyer.canteens.*') ? 'active' : '' }}" href="{{ route('buyer.canteens.index') }}">
                          <i class="fas fa-store"></i> {{ __('buyer.browse_canteens') }}
                      </a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link {{ request()->routeIs('buyer.orders.*') ? 'active' : '' }}" href="{{ route('buyer.orders.index') }}">
                          <i class="fas fa-shopping-cart"></i> {{ __('buyer.my_orders') }}
                      </a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link {{ request()->routeIs('buyer.favorites.*') ? 'active' : '' }}" href="{{ route('buyer.favorites.index') }}">
                          <i class="fas fa-heart"></i> Favorities
                      </a>
                  </li>
              </ul>
          </div>
      </div>

      <!-- Main content -->
      <div class="main-content">
          @if(session('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                  {{ session('success') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
          @endif

          @if(session('error'))
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  {{ session('error') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
          @endif

          @if(session('info'))
              <div class="alert alert-info alert-dismissible fade show" role="alert">
                  {{ session('info') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
          @endif

          @yield('buyer-content')
      </div>
  </div>
</div>
@endsection

