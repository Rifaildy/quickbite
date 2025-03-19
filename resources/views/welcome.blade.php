<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>{{ config('app.name') }}</title>
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
       body {
           font-family: 'Inter', sans-serif;
       }
       .hero {
           background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1498654896293-37aacf113fd9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
           background-size: cover;
           background-position: center;
           height: 60vh;
           display: flex;
           align-items: center;
           color: white;
       }
       .feature-icon {
           font-size: 2.5rem;
           color: #0d6efd;
           margin-bottom: 1rem;
       }
       .navbar {
           background-color: transparent !important;
           position: absolute;
           width: 100%;
           z-index: 1000;
       }
       .navbar-brand, .navbar-nav .nav-link {
           color: white !important;
       }
       .btn-primary {
           padding: 0.75rem 1.5rem;
           font-weight: 600;
       }
       .how-it-works {
           background-color: #f8f9fa;
           padding: 5rem 0;
       }
       .step {
           text-align: center;
           padding: 2rem;
       }
       .step-number {
           background-color: #0d6efd;
           color: white;
           width: 40px;
           height: 40px;
           border-radius: 50%;
           display: flex;
           align-items: center;
           justify-content: center;
           margin: 0 auto 1rem;
           font-weight: bold;
       }
       .testimonial {
           background-color: white;
           padding: 2rem;
           border-radius: 0.5rem;
           box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
           margin-bottom: 2rem;
       }
       .testimonial-author {
           font-weight: 600;
           margin-top: 1rem;
       }
       footer {
           background-color: #343a40;
           color: white;
           padding: 3rem 0;
       }
       footer a {
           color: rgba(255, 255, 255, 0.8);
           text-decoration: none;
       }
       footer a:hover {
           color: white;
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
           color: #212529;
       }
       
       .language-switcher .flag-icon {
           margin-right: 8px;
           width: 16px;
           height: 12px;
       }
       
       /* Card styles */
       .canteen-card {
           transition: transform 0.3s ease, box-shadow 0.3s ease;
           height: 100%;
       }
       
       .canteen-card:hover {
           transform: translateY(-5px);
           box-shadow: 0 10px 20px rgba(0,0,0,0.1);
       }
       
       .menu-card {
           transition: transform 0.3s ease, box-shadow 0.3s ease;
           height: 100%;
       }
       
       .menu-card:hover {
           transform: translateY(-5px);
           box-shadow: 0 10px 20px rgba(0,0,0,0.1);
       }
       
       .menu-card img {
           height: 180px;
           object-fit: cover;
       }
       
       .category-card {
           transition: transform 0.3s ease, box-shadow 0.3s ease;
           height: 100%;
           position: relative;
           overflow: hidden;
           border-radius: 0.5rem;
       }
       
       .category-card:hover {
           transform: translateY(-5px);
           box-shadow: 0 10px 20px rgba(0,0,0,0.1);
       }
       
       .category-card .category-overlay {
           position: absolute;
           bottom: 0;
           left: 0;
           right: 0;
           background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
           padding: 1rem;
           color: white;
       }
       
       .section-title {
           position: relative;
           margin-bottom: 2rem;
           padding-bottom: 1rem;
       }
       
       .section-title::after {
           content: '';
           position: absolute;
           bottom: 0;
           left: 0;
           width: 50px;
           height: 3px;
           background-color: #0d6efd;
       }
       
       .view-all-link {
           display: inline-block;
           margin-top: 1rem;
           font-weight: 600;
           color: #0d6efd;
           text-decoration: none;
       }
       
       .view-all-link:hover {
           text-decoration: underline;
       }
   </style>
</head>
<body>
   <!-- Navbar -->
   <nav class="navbar navbar-expand-md navbar-dark">
       <div class="container">
           <a class="navbar-brand" href="{{ url('/') }}">
               {{ config('app.name') }}
           </a>
           <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
               <span class="navbar-toggler-icon"></span>
           </button>

           <div class="collapse navbar-collapse" id="navbarSupportedContent">
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
                               @if(Auth::user()->isAdmin())
                                   <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                       {{ __('general.dashboard') }}
                                   </a>
                               @elseif(Auth::user()->isSeller())
                                   <a class="dropdown-item" href="{{ route('seller.dashboard') }}">
                                       {{ __('general.dashboard') }}
                                   </a>
                               @elseif(Auth::user()->isBuyer())
                                   <a class="dropdown-item" href="{{ route('buyer.dashboard') }}">
                                       {{ __('general.dashboard') }}
                                   </a>
                               @endif
                               
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

   <!-- Hero Section -->
   <section class="hero">
       <div class="container">
           <div class="row">
               <div class="col-md-8">
                   <h1 class="display-4 fw-bold mb-4">Selamat Datang di Kantin Kampus</h1>
                   <p class="lead mb-4">Temukan makanan lezat dari berbagai kantin di sekitar kampus Anda</p>
                   @guest
                       <div class="d-flex gap-3">
                           <a href="{{ route('register') }}" class="btn btn-primary">Mulai Sekarang</a>
                           <a href="{{ route('login') }}" class="btn btn-outline-light">Masuk</a>
                       </div>
                   @else
                       <div>
                           @if(Auth::user()->isAdmin())
                               <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Dashboard</a>
                           @elseif(Auth::user()->isSeller())
                               <a href="{{ route('seller.dashboard') }}" class="btn btn-primary">Dashboard</a>
                           @elseif(Auth::user()->isBuyer())
                               <a href="{{ route('buyer.dashboard') }}" class="btn btn-primary">Dashboard</a>
                           @endif
                       </div>
                   @endguest
               </div>
           </div>
       </div>
   </section>

   <!-- Canteens Section -->
   <section class="py-5">
       <div class="container">
           <h2 class="section-title">Kantin Populer</h2>
           <div class="row g-4">
           @forelse($canteens ?? [] as $canteen)

               <div class="col-md-4">
                   <div class="card canteen-card shadow-sm h-100">
                       <div class="card-body">
                           <h5 class="card-title">{{ $canteen->name }}</h5>
                           <p class="card-text text-muted">Dikelola oleh: {{ $canteen->seller->name }}</p>
                           <p class="card-text">{{ Str::limit($canteen->description, 100) }}</p>
                           @auth
                               @if(auth()->user()->isBuyer())
                               <a href="{{ route('buyer.canteens.show', $canteen->id) }}" class="btn btn-primary">Lihat Menu</a>
                               @else
                               <a href="#" class="btn btn-primary disabled">Lihat Menu</a>
                               @endif
                           @else
                               <div class="d-flex align-items-center">
                                   <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Masuk untuk Melihat</a>
                               </div>
                           @endauth
                       </div>
                   </div>
               </div>
               @empty
               <div class="col-12">
                   <div class="alert alert-info">Belum ada kantin yang tersedia.</div>
               </div>
               @endforelse
           </div>
           <div class="text-center mt-4">
               <a href="#" class="view-all-link">Lihat Semua Kantin <i class="fas fa-arrow-right ms-1"></i></a>
           </div>
       </div>
   </section>

   <!-- Popular Menus Section -->
   <section class="py-5 bg-light">
       <div class="container">
           <h2 class="section-title">Menu Populer</h2>
           <div class="row g-4">
           @forelse($popularMenus ?? [] as $menu)

               <div class="col-md-3">
                   <div class="card menu-card shadow-sm h-100">
                       <img src="{{ $menu->image ? asset('storage/' . $menu->image) : 'https://via.placeholder.com/300x200?text=Menu' }}" class="card-img-top" alt="{{ $menu->name }}">
                       <div class="card-body">
                           <h5 class="card-title">{{ $menu->name }}</h5>
                           <p class="card-text text-muted">{{ $menu->canteen->name }}</p>
                           <p class="card-text fw-bold">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                           @if($menu->category)
                           <span class="badge bg-primary">{{ $menu->category->name }}</span>
                           @endif
                       </div>
                       <div class="card-footer bg-white border-top-0">
                           @auth
                               @if(auth()->user()->isBuyer())
                               <a href="#" class="btn btn-sm btn-primary w-100">Pesan Sekarang</a>
                               @else
                               <a href="#" class="btn btn-sm btn-primary w-100 disabled">Pesan Sekarang</a>
                               @endif
                           @else
                               <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary w-100">Masuk untuk Memesan</a>
                           @endauth
                       </div>
                   </div>
               </div>
               @empty
               <div class="col-12">
                   <div class="alert alert-info">Belum ada menu yang tersedia.</div>
               </div>
               @endforelse
           </div>
           <div class="text-center mt-4">
               <a href="#" class="view-all-link">Lihat Semua Menu <i class="fas fa-arrow-right ms-1"></i></a>
           </div>
       </div>
   </section>

   <!-- Categories Section -->
   <section class="py-5">
       <div class="container">
           <h2 class="section-title">Kategori Menu</h2>
           <div class="row g-4">
           @forelse($categories ?? [] as $category)

               <div class="col-md-4">
                   <div class="card category-card shadow-sm h-100">
                       <img src="https://via.placeholder.com/400x200?text={{ $category->name }}" class="card-img" alt="{{ $category->name }}">
                       <div class="category-overlay">
                           <h5 class="card-title mb-0">{{ $category->name }}</h5>
                           <p class="card-text">{{ $category->menus_count }} menu</p>
                       </div>
                   </div>
               </div>
               @empty
               <div class="col-12">
                   <div class="alert alert-info">Belum ada kategori yang tersedia.</div>
               </div>
               @endforelse
           </div>
       </div>
   </section>

   <!-- How It Works Section -->
   <section class="how-it-works">
       <div class="container">
           <div class="text-center mb-5">
               <h2 class="fw-bold">Cara Kerja</h2>
               <p class="text-muted">Pesan makanan dari kantin kampus dengan mudah</p>
           </div>
           <div class="row">
               <div class="col-md-3">
                   <div class="step">
                       <div class="step-number">1</div>
                       <h4>Jelajahi Menu</h4>
                       <p class="text-muted">Lihat berbagai menu dari kantin di sekitar kampus</p>
                   </div>
               </div>
               <div class="col-md-3">
                   <div class="step">
                       <div class="step-number">2</div>
                       <h4>Pesan Makanan</h4>
                       <p class="text-muted">Pilih makanan favorit Anda dan tambahkan ke keranjang</p>
                   </div>
               </div>
               <div class="col-md-3">
                   <div class="step">
                       <div class="step-number">3</div>
                       <h4>Bayar Pesanan</h4>
                       <p class="text-muted">Lakukan pembayaran dengan metode yang tersedia</p>
                   </div>
               </div>
               <div class="col-md-3">
                   <div class="step">
                       <div class="step-number">4</div>
                       <h4>Ambil Makanan</h4>
                       <p class="text-muted">Ambil pesanan Anda di kantin saat sudah siap</p>
                   </div>
               </div>
           </div>
       </div>
   </section>

   <!-- CTA Section -->
   <section class="py-5 bg-primary text-white">
       <div class="container">
           <div class="row align-items-center">
               <div class="col-md-8">
                   <h2 class="fw-bold">Siap untuk Memesan?</h2>
                   <p class="lead mb-0">Daftar sekarang dan nikmati kemudahan memesan makanan di kantin kampus</p>
               </div>
               <div class="col-md-4 text-md-end mt-3 mt-md-0">
                   @guest
                   <a href="{{ route('register') }}" class="btn btn-light btn-lg">Daftar Sekarang</a>
                   @else
                   <a href="{{ auth()->user()->isBuyer() ? route('buyer.dashboard') : '#' }}" class="btn btn-light btn-lg">Pesan Sekarang</a>
                   @endguest
               </div>
           </div>
       </div>
   </section>

   <!-- Footer -->
   <footer>
       <div class="container">
           <div class="row">
               <div class="col-md-4 mb-4 mb-md-0">
                   <h5 class="mb-3">Kantin Kampus</h5>
                   <p>Mempermudah pemesanan makanan di kantin kampus untuk semua orang.</p>
               </div>
               <div class="col-md-2 mb-4 mb-md-0">
                   <h5 class="mb-3">Menu</h5>
                   <ul class="list-unstyled">
                       <li><a href="#">Beranda</a></li>
                       <li><a href="#">Kantin</a></li>
                       <li><a href="#">Menu</a></li>
                       <li><a href="#">Kontak</a></li>
                   </ul>
               </div>
               <div class="col-md-2 mb-4 mb-md-0">
                   <h5 class="mb-3">Dukungan</h5>
                   <ul class="list-unstyled">
                       <li><a href="#">FAQ</a></li>
                       <li><a href="#">Pusat Bantuan</a></li>
                       <li><a href="#">Syarat Layanan</a></li>
                       <li><a href="#">Kebijakan Privasi</a></li>
                   </ul>
               </div>
               <div class="col-md-4">
                   <h5 class="mb-3">Hubungi Kami</h5>
                   <ul class="list-unstyled">
                       <li><a href="#"><i class="fas fa-envelope me-2"></i>info@kantinkampus.com</a></li>
                       <li><a href="#"><i class="fas fa-phone me-2"></i>(123) 456-7890</a></li>
                       <li><a href="#"><i class="fas fa-map-marker-alt me-2"></i>Kampus Universitas, Gedung A</a></li>
                   </ul>
               </div>
           </div>
           <hr class="my-4">
           <div class="text-center">
               <p class="mb-0">&copy; {{ date('Y') }} Kantin Kampus. Hak Cipta Dilindungi.</p>
           </div>
       </div>
   </footer>

   <!-- Bootstrap JS -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

