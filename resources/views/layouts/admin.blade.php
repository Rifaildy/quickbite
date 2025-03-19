@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
      <!-- Sidebar -->
      <div class="sidebar">
          <div class="position-sticky pt-3">
              <ul class="nav flex-column">
                  <li class="nav-item">
                      <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                          <i class="fas fa-tachometer-alt"></i> {{ __('general.dashboard') }}
                      </a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                          <i class="fas fa-users"></i> {{ __('general.users') }}
                      </a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link {{ request()->routeIs('admin.canteens.*') ? 'active' : '' }}" href="{{ route('admin.canteens.index') }}">
                          <i class="fas fa-store"></i> {{ __('general.canteens') }}
                      </a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}" href="{{ route('admin.menus.index') }}">
                          <i class="fas fa-utensils"></i> {{ __('general.menus') }}
                      </a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                          <i class="fas fa-tags"></i> {{ __('general.categories') }}
                      </a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                          <i class="fas fa-shopping-cart"></i> {{ __('general.orders') }}
                      </a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                          <i class="fas fa-cog"></i> {{ __('general.settings') }}
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

          @yield('admin-content')
      </div>
  </div>
</div>
@endsection

