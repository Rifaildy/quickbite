<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <meta name="csrf-token" content="{{ csrf_token() }}">

 <title>{{ config('app.name') }} - @yield('title')</title>

 <!-- Fonts -->
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

 <!-- Bootstrap CSS -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 
 <!-- Font Awesome -->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
 
 <!-- Custom CSS -->
 <style>
     /* Prevent animations during window resize to avoid layout shifts */
     .resize-animation-stopper * {
         transition: none !important;
         animation: none !important;
     }
     :root {
         --sidebar-width: 250px;
         --sidebar-width-large: 280px;
         --navbar-height: 56px;
         --right-sidebar-width: 280px;
     }
     
     html {
         overflow-y: scroll; /* Always show scrollbar to prevent layout shifts */
     }
     
     body {
         font-family: 'Inter', sans-serif;
         background-color: #f8f9fa;
         padding-right: 0 !important; /* Prevent Bootstrap modal from adding padding */
         overflow-x: hidden; /* Prevent horizontal scrolling */
     }
     
     .navbar-brand {
         font-weight: 600;
     }
     
     /* Fixed sidebar */
     .sidebar {
         min-height: calc(100vh - var(--navbar-height));
         background-color: #bb0718;
         position: fixed;
         top: var(--navbar-height);
         left: 0;
         width: var(--sidebar-width);
         z-index: 100;
         transition: transform 0.3s ease-in-out;
         overflow-y: auto;
         box-shadow: 0 0 10px rgba(0,0,0,0.1);
     }
     
     .sidebar .nav-link {
         color: rgb(255, 255, 255);
         padding: 0.85rem 1.25rem;
         border-radius: 0.25rem;
         margin: 0.25rem 0.75rem;
         transition: all 0.2s;
     }
     
     .sidebar .nav-link:hover {
         background-color: #ffd109;
         color: #000;
     }
     
     .sidebar .nav-link.active {
         color: #fff;
         background-color: #ffd109;
         color: #000;
         font-weight: 500;
     }
     
     .sidebar .nav-link i {
         margin-right: 0.75rem;
         width: 20px;
         text-align: center;
     }
     
     /* Right sidebar */
     .right-sidebar {
         min-height: 100vh;
         background-color: #fff;
         position: fixed;
         top: 0;
         right: 0;
         width: var(--right-sidebar-width);
         z-index: 1050; /* Higher than regular sidebar */
         transform: translateX(100%);
         transition: transform 0.3s ease-in-out;
         overflow-y: auto;
         box-shadow: -2px 0 10px rgba(0,0,0,0.1);
     }
     
     .right-sidebar.show {
         transform: translateX(0);
     }
     
     .right-sidebar-header {
         display: flex;
         align-items: center;
         justify-content: space-between;
         padding: 1rem;
         border-bottom: 1px solid #dee2e6;
     }
     
     .right-sidebar-header h5 {
         margin-bottom: 0;
         font-weight: 600;
     }
     
     .right-sidebar-body {
         padding: 1rem;
     }
     
     .right-sidebar-backdrop {
         display: none;
         position: fixed;
         top: 0;
         left: 0;
         width: 100%;
         height: 100%;
         background-color: rgba(0, 0, 0, 0.5);
         z-index: 1040; /* Lower than right sidebar but higher than other elements */
     }
     
     .right-sidebar-backdrop.show {
         display: block;
     }
     
     .right-sidebar .nav-link {
         display: flex;
         align-items: center;
         padding: 0.75rem 1rem;
         color: #495057;
         border-radius: 0.25rem;
         transition: all 0.2s;
     }
     
     .right-sidebar .nav-link:hover {
         background-color: #f8f9fa;
         color: #212529;
     }
     
     .right-sidebar .nav-link i {
         margin-right: 0.75rem;
         width: 20px;
         text-align: center;
         color: #6c757d;
     }
     
     .right-sidebar .divider {
         height: 1px;
         background-color: #dee2e6;
         margin: 0.5rem 0;
     }
     
     /* Fixed main content */
     .main-content {
         margin-left: var(--sidebar-width);
         padding: 1.5rem;
         width: calc(100% - var(--sidebar-width));
         transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out;
         min-height: calc(100vh - var(--navbar-height));
     }
     
     /* Card styles */
     .card {
         border: none;
         box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
         margin-bottom: 1.5rem;
         border-radius: 0.5rem;
     }
     
     .card-header {
         background-color: #fff;
         border-bottom: 1px solid rgba(0, 0, 0, 0.125);
         font-weight: 600;
         padding: 1rem 1.25rem;
     }
     
     .card-body {
         padding: 1.25rem;
     }
     
     .card-footer {
         background-color: #f8f9fa;
         border-top: 1px solid rgba(0, 0, 0, 0.125);
         padding: 1rem 1.25rem;
     }
     
     /* Dashboard cards */
     .dashboard-card {
         transition: transform 0.2s;
     }
     
     .dashboard-card:hover {
         transform: translateY(-5px);
     }
     
     /* Button styles */
     .btn-primary {
         background-color: #0d6efd;
         border-color: #0d6efd;
     }
     
     /* Badge styles */
     .badge-counter {
         position: absolute;
         transform: scale(0.8);
         transform-origin: top right;
         right: 0.25rem;
         margin-top: -0.25rem;
     }
     
     .notification-badge {
         position: relative;
         top: -10px;
         right: 5px;
         padding: 3px 5px;
         border-radius: 50%;
         background: red;
         color: white;
     }
     
     /* Dropdown styles */
     .dropdown-menu {
         font-size: 0.85rem;
         box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
         border: none;
     }
     
     /* Table styles */
     .table {
         border-collapse: separate;
         border-spacing: 0;
         width: 100%;
     }
     
     .table thead th {
         background-color: #f8f9fa;
         border-bottom: 2px solid #dee2e6;
         padding: 0.75rem 1rem;
     }
     
     .table tbody td {
         padding: 0.75rem 1rem;
         vertical-align: middle;
     }
     
     .table th {
         font-weight: 600;
     }
     
     /* Table container */
     .table-responsive {
         overflow-x: auto;
         width: 100%;
     }
     
     /* Navbar styles */
     .navbar {
         box-shadow: 0 2px 4px rgba(0,0,0,0.1);
         height: var(--navbar-height);
         position: fixed;
         top: 0;
         right: 0;
         left: 0;
         z-index: 1030;
     }
     
     /* Language switcher */
     .language-switcher {
         display: flex;
         align-items: center;
     }
     
     .language-switcher .dropdown-menu {
         min-width: 100px;
     }
     
     .language-switcher .dropdown-item {
         display: flex;
         align-items: center;
     }
     
     .language-switcher .flag-icon {
         margin-right: 8px;
         width: 16px;
         height: 12px;
     }
     
     /* Desktop grid layouts */
     .dashboard-grid {
         display: grid;
         gap: 1.5rem;
         width: 100%;
     }
     
     /* Gear icon - hide on desktop */
     #rightSidebarToggle {
         display: inline-block;
     }
     
     @media (min-width: 768px) {
         #rightSidebarToggle {
             display: none;
         }
     }
     
     /* Mobile sidebar */
     @media (max-width: 767.98px) {
         .sidebar {
             transform: translateX(-100%);
             width: var(--sidebar-width-large);
         }
         
         .sidebar.show {
             transform: translateX(0);
         }
         
         .main-content {
             margin-left: 0;
             width: 100%;
             padding: 1rem;
         }
         
         .sidebar-backdrop {
             display: none;
             position: fixed;
             top: 0;
             left: 0;
             width: 100%;
             height: 100%;
             background-color: rgba(0, 0, 0, 0.5);
             z-index: 99;
         }
         
         .sidebar-backdrop.show {
             display: block;
         }
     }
     
     /* Sidebar toggle button */
     .sidebar-toggle {
         display: none;
     }
     
     @media (max-width: 767.98px) {
         .sidebar-toggle {
             display: inline-block;
         }
         
         .navbar-brand {
             position: absolute;
             left: 50%;
             transform: translateX(-50%);
             margin: 0;
         }
     }
     
     /* Medium screens */
     @media (min-width: 768px) and (max-width: 991.98px) {
         .dashboard-grid-2, .dashboard-grid-3, .dashboard-grid-4 {
             grid-template-columns: repeat(2, 1fr);
         }
     }
     
     /* Large screens */
     @media (min-width: 992px) {
         .dashboard-grid-2 {
             grid-template-columns: repeat(2, 1fr);
         }
         
         .dashboard-grid-3 {
             grid-template-columns: repeat(3, 1fr);
         }
         
         .dashboard-grid-4 {
             grid-template-columns: repeat(4, 1fr);
         }
         
         .container-fluid {
             padding-left: 2rem;
             padding-right: 2rem;
         }
     }
     
     /* Extra large screens */
     @media (min-width: 1600px) {
         :root {
             --sidebar-width: var(--sidebar-width-large);
         }
         
         .main-content {
             padding: 2rem;
         }
         
         .card {
             margin-bottom: 2rem;
         }
         
         .card-body {
             padding: 1.5rem;
         }
     }

     .right-sidebar .flag-icon {
         margin-right: 8px;
         width: 16px;
         height: 12px;
     }
 </style>

 @stack('styles')
</head>
<body>
 <div id="app">
     <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
         <div class="container-fluid px-4"> <!-- Changed to container-fluid with padding -->
             <button class="btn sidebar-toggle me-2" type="button" id="sidebarToggle">
                 <i class="fas fa-bars"></i>
             </button>
             
             <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name') }}
                </a>
             <button class="btn btn-link text-dark p-0 ms-auto" type="button" id="rightSidebarToggle">
                 <i class="fas fa-cog fa-lg"></i>
             </button>

             <div class="collapse navbar-collapse" id="navbarSupportedContent">
                 <!-- Left Side Of Navbar -->
                 <ul class="navbar-nav me-auto">
                     @auth
                         @if(Auth::user()->isAdmin())
                             <li class="nav-item d-md-none">
                                 <a class="nav-link" href="{{ route('admin.dashboard') }}">{{ __('general.dashboard') }}</a>
                             </li>
                         @elseif(Auth::user()->isSeller())
                             <li class="nav-item d-md-none">
                                 <a class="nav-link" href="{{ route('seller.dashboard') }}">{{ __('general.dashboard') }}</a>
                             </li>
                         @elseif(Auth::user()->isBuyer())
                             <li class="nav-item d-md-none">
                                 <a class="nav-link" href="{{ route('buyer.dashboard') }}">{{ __('general.dashboard') }}</a>
                             </li>
                         @endif
                     @endauth
                 </ul>

                 <!-- Right Side Of Navbar -->
                 <ul class="navbar-nav ms-auto">
                     <!-- Language Switcher -->
                     <li class="nav-item dropdown language-switcher">
                         <a id="languageDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                             @if(app()->getLocale() == 'en')
                                 <img src="{{ asset('images/flags/en.png') }}" alt="English" class="flag-icon"> EN
                             @else
                                 <img src="{{ asset('images/flags/id.png') }}" alt="Indonesian" class="flag-icon"> ID
                             @endif
                         </a>

                         <div class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                             <a class="dropdown-item" href="{{ route('language.switch', 'en') }}">
                                 <img src="{{ asset('images/flags/en.png') }}" alt="English" class="flag-icon"> {{ __('general.english') }}
                             </a>
                             <a class="dropdown-item" href="{{ route('language.switch', 'id') }}">
                                 <img src="{{ asset('images/flags/id.png') }}" alt="Indonesian" class="flag-icon"> {{ __('general.indonesian') }}
                             </a>
                         </div>
                     </li>
                     
                     <!-- Settings Button for Desktop -->
                     <li class="nav-item d-none d-md-block">
                         <a href="#" class="nav-link" id="desktopSettingsButton">
                             <i class="fas fa-cog"></i>
                         </a>
                     </li>
                     
                     <!-- Authentication Links -->
                     @guest
                         @if (Route::has('login'))
                             <li class="nav-item">
                                 <a class="nav-link" href="{{ route('login') }}">{{ __('general.login') }}</a>
                             </li>
                         @endif

                         @if (Route::has('register'))
                             <li class="nav-item">
                                 <a class="nav-link" href="{{ route('register') }}">{{ __('general.register') }}</a>
                             </li>
                         @endif
                     @else
                         <li class="nav-item dropdown">
                             <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                 {{ Auth::user()->name }}
                             </a>

                             <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                 <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                  document.getElementById('logout-form').submit();">
                                     {{ __('general.logout') }}
                                 </a>

                                 <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                     @csrf
                                 </form>
                             </div>
                         </li>
                     @endguest
                 </ul>
             </div>
         </div>
     </nav>

     <div style="padding-top: 56px;">
         @yield('content')
     </div>
     
     <!-- Sidebar backdrop for mobile -->
     <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
     
     <!-- Right Sidebar -->
     <div class="right-sidebar" id="rightSidebar">
         <div class="right-sidebar-header">
             <h5>{{ __('general.settings') }}</h5>
             <button type="button" class="btn-close" id="rightSidebarClose" aria-label="Close"></button>
         </div>
         <div class="right-sidebar-body">
             @guest
                 <div class="text-center py-4">
                     <p class="mb-3">{{ __('general.login_to_access_settings') }}</p>
                     <a href="{{ route('login') }}" class="btn btn-primary">{{ __('general.login') }}</a>
                     <a href="{{ route('register') }}" class="btn btn-outline-primary ms-2">{{ __('general.register') }}</a>
                 </div>
             @else
                 <div class="user-info mb-4">
                     <div class="d-flex align-items-center mb-3">
                         <div class="flex-shrink-0">
                             <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 20px;">
                                 {{ substr(Auth::user()->name, 0, 1) }}
                             </div>
                         </div>
                         <div class="flex-grow-1 ms-3">
                             <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                             <p class="text-muted small mb-0">{{ Auth::user()->email }}</p>
                         </div>
                     </div>
                 </div>
                 
                 <ul class="nav flex-column">
                     @if(Auth::user()->isAdmin())
                         <li class="nav-item">
                             <a href="{{ route('admin.dashboard') }}" class="nav-link">
                                 <i class="fas fa-tachometer-alt"></i> {{ __('general.dashboard') }}
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="{{ route('admin.settings.index') }}" class="nav-link">
                                 <i class="fas fa-cog"></i> {{ __('general.system_settings') }}
                             </a>
                         </li>
                     @elseif(Auth::user()->isSeller())
                         <li class="nav-item">
                             <a href="{{ route('seller.dashboard') }}" class="nav-link">
                                 <i class="fas fa-tachometer-alt"></i> {{ __('general.dashboard') }}
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="{{ route('seller.canteens.index') }}" class="nav-link">
                                 <i class="fas fa-store"></i> {{ __('seller.my_canteen') }}
                             </a>
                         </li>
                     @elseif(Auth::user()->isBuyer())
                         <li class="nav-item">
                             <a href="{{ route('buyer.dashboard') }}" class="nav-link">
                                 <i class="fas fa-tachometer-alt"></i> {{ __('general.dashboard') }}
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="{{ route('buyer.profile.index') }}" class="nav-link">
                                 <i class="fas fa-user"></i> {{ __('general.profile') }}
                             </a>
                         </li>
                     @endif
                     
                     <div class="divider"></div>
                     
                     <!-- Language Switcher -->
                     <li class="nav-item">
                         <div class="dropdown w-100">
                             <a class="nav-link dropdown-toggle" href="#" role="button" id="languageDropdownSidebar" data-bs-toggle="dropdown" aria-expanded="false">
                                 <i class="fas fa-globe"></i>
                                 @if(app()->getLocale() == 'en')
                                     {{ __('general.english') }}
                                 @else
                                     {{ __('general.indonesian') }}
                                 @endif
                             </a>
                             <ul class="dropdown-menu w-100" aria-labelledby="languageDropdownSidebar">
                                 <li>
                                     <a class="dropdown-item" href="{{ route('language.switch', 'en') }}">
                                         <img src="{{ asset('images/flags/en.png') }}" alt="English" class="flag-icon"> {{ __('general.english') }}
                                     </a>
                                 </li>
                                 <li>
                                     <a class="dropdown-item" href="{{ route('language.switch', 'id') }}">
                                         <img src="{{ asset('images/flags/id.png') }}" alt="Indonesian" class="flag-icon"> {{ __('general.indonesian') }}
                                     </a>
                                 </li>
                             </ul>
                         </div>
                     </li>
                     
                     <div class="divider"></div>
                     
                     <li class="nav-item">
                         <a href="{{ route('logout') }}" class="nav-link text-danger" 
                            onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                             <i class="fas fa-sign-out-alt"></i> {{ __('general.logout') }}
                         </a>
                         <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
                             @csrf
                         </form>
                     </li>
                 </ul>
             @endguest
         </div>
     </div>
     
     <!-- Right Sidebar Backdrop -->
     <div class="right-sidebar-backdrop" id="rightSidebarBackdrop"></div>
 </div>

 <!-- Bootstrap JS -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 
 <!-- jQuery -->
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 
 <!-- Sidebar Toggle Script -->
 <script>
     document.addEventListener('DOMContentLoaded', function() {
         // Left sidebar toggle
         const sidebarToggle = document.getElementById('sidebarToggle');
         const sidebar = document.querySelector('.sidebar');
         const sidebarBackdrop = document.getElementById('sidebarBackdrop');
         
         if (sidebarToggle && sidebar && sidebarBackdrop) {
             sidebarToggle.addEventListener('click', function() {
                 sidebar.classList.toggle('show');
                 sidebarBackdrop.classList.toggle('show');
             });
             
             sidebarBackdrop.addEventListener('click', function() {
                 sidebar.classList.remove('show');
                 sidebarBackdrop.classList.remove('show');
             });
             
             // Close sidebar when clicking on a nav link on mobile
             const navLinks = sidebar.querySelectorAll('.nav-link');
             navLinks.forEach(link => {
                 link.addEventListener('click', function() {
                     if (window.innerWidth < 768) {
                         sidebar.classList.remove('show');
                         sidebarBackdrop.classList.remove('show');
                     }
                 });
             });
         }
         
         // Right sidebar toggle
         const rightSidebarToggle = document.getElementById('rightSidebarToggle');
         const desktopSettingsButton = document.getElementById('desktopSettingsButton');
         const rightSidebar = document.getElementById('rightSidebar');
         const rightSidebarClose = document.getElementById('rightSidebarClose');
         const rightSidebarBackdrop = document.getElementById('rightSidebarBackdrop');
         
         function openRightSidebar() {
             rightSidebar.classList.add('show');
             rightSidebarBackdrop.classList.add('show');
             document.body.style.overflow = 'hidden'; // Prevent scrolling when sidebar is open
         }
         
         function closeRightSidebar() {
             rightSidebar.classList.remove('show');
             rightSidebarBackdrop.classList.remove('show');
             document.body.style.overflow = ''; // Restore scrolling
         }
         
         if (rightSidebarToggle && rightSidebar && rightSidebarClose && rightSidebarBackdrop) {
             rightSidebarToggle.addEventListener('click', openRightSidebar);
             
             if (desktopSettingsButton) {
                 desktopSettingsButton.addEventListener('click', function(e) {
                     e.preventDefault();
                     openRightSidebar();
                 });
             }
             
             rightSidebarClose.addEventListener('click', closeRightSidebar);
             
             rightSidebarBackdrop.addEventListener('click', closeRightSidebar);
             
             // Close right sidebar when clicking on a nav link
             const rightSidebarNavLinks = rightSidebar.querySelectorAll('.nav-link');
             rightSidebarNavLinks.forEach(link => {
                 link.addEventListener('click', function() {
                     if (!link.classList.contains('dropdown-toggle')) {
                         closeRightSidebar();
                     }
                 });
             });
         }
         
         // Prevent layout shifts when window is resized
         let resizeTimer;
         window.addEventListener('resize', function() {
             document.body.classList.add('resize-animation-stopper');
             clearTimeout(resizeTimer);
             resizeTimer = setTimeout(function() {
                 document.body.classList.remove('resize-animation-stopper');
             }, 400);
         });
     });
 </script>
 
 @stack('scripts')
</body>
</html>

