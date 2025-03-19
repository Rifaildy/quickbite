@extends('layouts.buyer')

@section('title', 'My Favorites')

@section('buyer-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2">My Favorites</h1>
</div>

<div class="row mb-4">
  <div class="col-md-6">
      <ul class="nav nav-tabs">
          <li class="nav-item">
              <a class="nav-link {{ $type == 'canteens' ? 'active' : '' }}" href="{{ route('buyer.favorites.index', ['type' => 'canteens']) }}">Favorite Canteens</a>
          </li>
          <li class="nav-item">
              <a class="nav-link {{ $type == 'menus' ? 'active' : '' }}" href="{{ route('buyer.favorites.index', ['type' => 'menus']) }}">Favorite Menu Items</a>
          </li>
      </ul>
  </div>
</div>

@if($type == 'canteens')
  @if($favoriteCanteens->isEmpty())
      <div class="text-center py-5">
          <i class="fas fa-heart fa-4x text-muted mb-3"></i>
          <h5>No favorite canteens yet</h5>
          <p class="text-muted">Browse canteens and add them to your favorites.</p>
          <a href="{{ route('buyer.canteens.index') }}" class="btn btn-primary">Browse Canteens</a>
      </div>
  @else
      <div class="row">
          @foreach($favoriteCanteens as $favorite)
              <div class="col-md-4 mb-4">
                  <div class="card h-100">
                      <div class="card-body">
                          <div class="d-flex justify-content-between align-items-start">
                              <h5 class="card-title">{{ $favorite->favorable->name }}</h5>
                              <form action="{{ route('buyer.favorites.destroy', $favorite) }}" method="POST">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-sm btn-outline-danger">
                                      <i class="fas fa-heart-broken"></i>
                                  </button>
                              </form>
                          </div>
                          <p class="card-text text-muted small">
                              <i class="fas fa-utensils me-1"></i> {{ $favorite->favorable->menus_count ?? 0 }} menu items
                          </p>
                          <p class="card-text">{{ Str::limit($favorite->favorable->description, 100) }}</p>
                      </div>
                      <div class="card-footer bg-white">
                          <a href="{{ route('buyer.canteens.show', $favorite->favorable) }}" class="btn btn-primary w-100">
                              <i class="fas fa-eye me-1"></i> View Menu
                          </a>
                      </div>
                  </div>
              </div>
          @endforeach
      </div>
  @endif
@else
  @if($favoriteMenus->isEmpty())
      <div class="text-center py-5">
          <i class="fas fa-heart fa-4x text-muted mb-3"></i>
          <h5>No favorite menu items yet</h5>
          <p class="text-muted">Browse menus and add items to your favorites.</p>
          <a href="{{ route('buyer.canteens.index') }}" class="btn btn-primary">Browse Canteens</a>
      </div>
  @else
      <div class="row">
          @foreach($favoriteMenus as $favorite)
              <div class="col-md-4 mb-4">
                  <div class="card h-100">
                      @if($favorite->favorable->image)
                          <img src="{{ asset('storage/' . $favorite->favorable->image) }}" class="card-img-top" alt="{{ $favorite->favorable->name }}" style="height: 200px; object-fit: cover;">
                      @else
                          <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                              <i class="fas fa-utensils fa-3x text-muted"></i>
                          </div>
                      @endif
                      <div class="card-body">
                          <div class="d-flex justify-content-between align-items-start">
                              <h5 class="card-title">{{ $favorite->favorable->name }}</h5>
                              <form action="{{ route('buyer.favorites.destroy', $favorite) }}" method="POST">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-sm btn-outline-danger">
                                      <i class="fas fa-heart-broken"></i>
                                  </button>
                              </form>
                          </div>
                          <p class="card-text text-muted small">{{ $favorite->favorable->category->name }}</p>
                          <p class="card-text">Rp {{ number_format($favorite->favorable->price, 0, ',', '.') }}</p>
                          <p class="card-text small">{{ Str::limit($favorite->favorable->description, 100) }}</p>
                          <p class="card-text text-muted small">
                              <i class="fas fa-store me-1"></i> {{ $favorite->favorable->canteen->name }}
                          </p>
                      </div>
                      <div class="card-footer bg-white d-flex justify-content-between">
                          <a href="{{ route('buyer.canteens.show', $favorite->favorable->canteen) }}" class="btn btn-primary flex-grow-1 me-2">
                              <i class="fas fa-eye me-1"></i> View Canteen
                          </a>
                          <button type="button" class="btn btn-success add-to-cart" 
                                  data-menu-id="{{ $favorite->favorable->id }}" 
                                  data-menu-name="{{ $favorite->favorable->name }}" 
                                  data-menu-price="{{ $favorite->favorable->price }}"
                                  data-canteen-id="{{ $favorite->favorable->canteen->id }}">
                              <i class="fas fa-cart-plus"></i>
                          </button>
                      </div>
                  </div>
              </div>
          @endforeach
      </div>
  @endif
@endif
@endsection
