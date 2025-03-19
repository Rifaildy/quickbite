@extends('layouts.app')

@section('content')
<div class="container-fluid">
<div class="row">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="position-sticky pt-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}" href="{{ route('seller.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> {{ __('general.dashboard') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('seller.canteens.*') ? 'active' : '' }}" href="{{ route('seller.canteens.index') }}">
                        <i class="fas fa-store"></i> {{ __('seller.my_canteen') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('seller.menus.*') ? 'active' : '' }}" href="{{ route('seller.menus.index') }}">
                        <i class="fas fa-utensils"></i> {{ __('general.menus') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('seller.orders.*') ? 'active' : '' }}" href="{{ route('seller.orders.index') }}">
                        <i class="fas fa-shopping-cart"></i> {{ __('general.orders') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('seller.orders.scan') ? 'active' : '' }}" href="{{ route('seller.orders.scan') }}">
                        <i class="fas fa-qrcode"></i> {{ __('seller.scan_barcode') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('seller.reports.*') ? 'active' : '' }}" href="{{ route('seller.reports.index') }}">
                        <i class="fas fa-chart-bar"></i> Reports
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

        @yield('seller-content')
    </div>
</div>
</div>
@endsection

