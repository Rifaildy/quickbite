@extends('layouts.buyer')

@section('title', $canteen->name)

@section('buyer-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2">{{ $canteen->name }}</h1>
  <div class="btn-toolbar mb-2 mb-md-0">
      <a href="{{ route('buyer.canteens.index') }}" class="btn btn-sm btn-secondary">
          <i class="fas fa-arrow-left"></i> Back to Canteens
      </a>
  </div>
</div>

<div class="card mb-4">
  <div class="card-body">
      <p>{{ $canteen->description }}</p>
      <div class="d-flex justify-content-between align-items-center">
          <div>
              <span class="badge bg-primary">{{ $menus->total() }} Menu Items</span>
          </div>
          <a href="{{ route('buyer.orders.create', $canteen) }}" class="btn btn-primary">
              <i class="fas fa-shopping-cart me-1"></i> View Cart
          </a>
      </div>
  </div>
</div>

<div class="row mb-4">
  <div class="col-md-6">
      <form action="{{ route('buyer.canteens.show', $canteen) }}" method="GET">
          <div class="input-group">
              <input type="text" class="form-control" placeholder="Search menu items..." name="search" value="{{ request('search') }}">
              <button class="btn btn-primary" type="submit">
                  <i class="fas fa-search"></i> Search
              </button>
          </div>
      </form>
  </div>
  <div class="col-md-6">
      <div class="d-flex justify-content-end">
          <select name="category" class="form-select w-auto" onchange="window.location.href='{{ route('buyer.canteens.show', $canteen) }}?category='+this.value">
              <option value="">All Categories</option>
              @foreach($categories as $category)
                  <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
              @endforeach
          </select>
      </div>
  </div>
</div>

@if($menus->isEmpty())
  <div class="text-center py-5">
      <i class="fas fa-utensils fa-4x text-muted mb-3"></i>
      <h5>No menu items found</h5>
      <p class="text-muted">Try a different search term or check back later.</p>
  </div>
@else
  <div class="row">
      @foreach($menus as $menu)
          <div class="col-md-4 mb-4">
              <div class="card h-100">
                  @if($menu->image)
                      <img src="{{ asset('storage/' . $menu->image) }}" class="card-img-top" alt="{{ $menu->name }}" style="height: 200px; object-fit: cover;">
                  @else
                      <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                          <i class="fas fa-utensils fa-3x text-muted"></i>
                      </div>
                  @endif
                  <div class="card-body">
                      <h5 class="card-title">{{ $menu->name }}</h5>
                      <p class="card-text text-muted small">{{ $menu->category->name }}</p>
                      <p class="card-text">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                      <p class="card-text small">{{ Str::limit($menu->description, 100) }}</p>
                  </div>
                  <div class="card-footer bg-white d-flex justify-content-between">
                      <button type="button" class="btn btn-primary add-to-cart" data-menu-id="{{ $menu->id }}" data-menu-name="{{ $menu->name }}" data-menu-price="{{ $menu->price }}">
                          <i class="fas fa-cart-plus me-1"></i> Add to Cart
                      </button>
                      <button type="button" class="btn btn-outline-danger add-to-favorite" data-menu-id="{{ $menu->id }}">
                          <i class="fas fa-heart"></i>
                      </button>
                  </div>
              </div>
          </div>
      @endforeach
  </div>

  <div class="d-flex justify-content-center mt-4">
      {{ $menus->links() }}
  </div>

  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
      <a href="{{ route('buyer.orders.create', $canteen) }}" class="btn btn-lg btn-primary rounded-circle shadow" id="cart-button">
          <i class="fas fa-shopping-cart"></i>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-count">
              0
          </span>
      </a>
  </div>
@endif

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
      // Initialize cart from localStorage
      let cart = JSON.parse(localStorage.getItem('cart_{{ $canteen->id }}')) || [];
      updateCartCount();
      
      // Add to cart functionality
      const addToCartButtons = document.querySelectorAll('.add-to-cart');
      addToCartButtons.forEach(button => {
          button.addEventListener('click', function() {
              const menuId = this.dataset.menuId;
              const menuName = this.dataset.menuName;
              const menuPrice = parseFloat(this.dataset.menuPrice);
              
              // Check if item already in cart
              const existingItem = cart.find(item => item.menuId === menuId);
              
              if (existingItem) {
                  existingItem.quantity += 1;
              } else {
                  cart.push({
                      menuId: menuId,
                      name: menuName,
                      price: menuPrice,
                      quantity: 1
                  });
              }
              
              // Save to localStorage
              localStorage.setItem('cart_{{ $canteen->id }}', JSON.stringify(cart));
              
              // Update UI
              updateCartCount();
              
              // Show toast notification
              showToast(`Added ${menuName} to cart`);
          });
      });
      
      // Add to favorite functionality
      const addToFavoriteButtons = document.querySelectorAll('.add-to-favorite');
      addToFavoriteButtons.forEach(button => {
          button.addEventListener('click', function() {
              const menuId = this.dataset.menuId;
              
              // Create a form and submit it
              const form = document.createElement('form');
              form.method = 'POST';
              form.action = '{{ route('buyer.favorites.store') }}';
              
              // Add CSRF token
              const csrfToken = document.createElement('input');
              csrfToken.type = 'hidden';
              csrfToken.name = '_token';
              csrfToken.value = '{{ csrf_token() }}';
              form.appendChild(csrfToken);
              
              // Add favorable_type
              const favorableType = document.createElement('input');
              favorableType.type = 'hidden';
              favorableType.name = 'favorable_type';
              favorableType.value = 'App\\Models\\Menu';
              form.appendChild(favorableType);
              
              // Add favorable_id
              const favorableId = document.createElement('input');
              favorableId.type = 'hidden';
              favorableId.name = 'favorable_id';
              favorableId.value = menuId;
              form.appendChild(favorableId);
              
              document.body.appendChild(form);
              form.submit();
          });
      });
      
      function updateCartCount() {
          const count = cart.reduce((total, item) => total + item.quantity, 0);
          document.getElementById('cart-count').textContent = count;
          
          // Hide badge if count is 0
          if (count === 0) {
              document.getElementById('cart-count').style.display = 'none';
          } else {
              document.getElementById('cart-count').style.display = 'block';
          }
      }
      
      function showToast(message) {
          // Create toast element
          const toastEl = document.createElement('div');
          toastEl.className = 'toast align-items-center text-white bg-primary border-0';
          toastEl.setAttribute('role', 'alert');
          toastEl.setAttribute('aria-live', 'assertive');
          toastEl.setAttribute('aria-atomic', 'true');
          
          toastEl.innerHTML = `
              <div class="d-flex">
                  <div class="toast-body">
                      ${message}
                  </div>
                  <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
              </div>
          `;
          
          // Add to container
          const toastContainer = document.createElement('div');
          toastContainer.className = 'toast-container position-fixed bottom-0 start-0 p-3';
          toastContainer.style.zIndex = '11';
          toastContainer.appendChild(toastEl);
          document.body.appendChild(toastContainer);
          
          // Initialize and show toast
          const toast = new bootstrap.Toast(toastEl, {
              delay: 3000
          });
          toast.show();
          
          // Remove from DOM after hiding
          toastEl.addEventListener('hidden.bs.toast', function() {
              document.body.removeChild(toastContainer);
          });
      }
  });
</script>
@endpush
@endsection

