@extends('layouts.buyer')

@section('title', 'Place Order')

@section('buyer-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2">Your Cart - {{ $canteen->name }}</h1>
  <div class="btn-toolbar mb-2 mb-md-0">
      <a href="{{ route('buyer.canteens.show', $canteen) }}" class="btn btn-sm btn-secondary">
          <i class="fas fa-arrow-left"></i> Back to Canteen
      </a>
  </div>
</div>

<div class="row">
  <div class="col-md-8">
      <div class="card mb-4">
          <div class="card-header">
              <h5 class="card-title mb-0">Cart Items</h5>
          </div>
          <div class="card-body">
              <form action="{{ route('buyer.orders.store', $canteen) }}" method="POST" id="order-form">
                  @csrf
                  
                  <div id="cart-items-container">
                      <!-- Cart items will be loaded here via JavaScript -->
                      <div class="text-center py-5" id="empty-cart-message">
                          <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                          <h5>Your cart is empty</h5>
                          <p class="text-muted">Add items from the menu to place an order.</p>
                          <a href="{{ route('buyer.canteens.show', $canteen) }}" class="btn btn-primary">Browse Menu</a>
                      </div>
                  </div>
              </form>
          </div>
      </div>
  </div>
  
  <div class="col-md-4">
      <div class="card">
          <div class="card-header">
              <h5 class="card-title mb-0">Order Summary</h5>
          </div>
          <div class="card-body">
              <div id="selected-items">
                  <!-- Selected items summary will be loaded here via JavaScript -->
              </div>
              
              <hr>
              
              <div class="d-flex justify-content-between fw-bold">
                  <span>Total:</span>
                  <span id="total-price">Rp 0</span>
              </div>
          </div>
          <div class="card-footer">
              <button type="button" class="btn btn-primary w-100" id="place-order-btn" disabled>Place Order</button>
          </div>
      </div>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
      // Load cart from localStorage
      const cart = JSON.parse(localStorage.getItem('cart_{{ $canteen->id }}')) || [];
      const cartItemsContainer = document.getElementById('cart-items-container');
      const emptyCartMessage = document.getElementById('empty-cart-message');
      const selectedItemsContainer = document.getElementById('selected-items');
      const totalPriceElement = document.getElementById('total-price');
      const placeOrderBtn = document.getElementById('place-order-btn');
      const orderForm = document.getElementById('order-form');
      
      // Function to update the cart display
      function updateCartDisplay() {
          if (cart.length === 0) {
              emptyCartMessage.style.display = 'block';
              selectedItemsContainer.innerHTML = '<p class="text-center text-muted">No items selected</p>';
              totalPriceElement.textContent = 'Rp 0';
              placeOrderBtn.disabled = true;
              return;
          }
          
          emptyCartMessage.style.display = 'none';
          
          // Create table for cart items
          let cartHtml = `
              <div class="table-responsive">
                  <table class="table table-hover">
                      <thead>
                          <tr>
                              <th>Item</th>
                              <th>Price</th>
                              <th>Quantity</th>
                              <th>Subtotal</th>
                              <th>Actions</th>
                          </tr>
                      </thead>
                      <tbody>
          `;
          
          let selectedItemsHtml = '<ul class="list-group list-group-flush">';
          let totalPrice = 0;
          
          cart.forEach((item, index) => {
              const subtotal = item.price * item.quantity;
              totalPrice += subtotal;
              
              cartHtml += `
                  <tr>
                      <td>${item.name}</td>
                      <td>Rp ${item.price.toLocaleString('id-ID')}</td>
                      <td>
                          <div class="input-group input-group-sm" style="width: 120px;">
                              <button type="button" class="btn btn-outline-secondary decrease-quantity" data-index="${index}">-</button>
                              <input type="number" class="form-control text-center quantity-input" value="${item.quantity}" min="1" data-index="${index}">
                              <button type="button" class="btn btn-outline-secondary increase-quantity" data-index="${index}">+</button>
                          </div>
                          <input type="hidden" name="menu_items[${index}][menu_id]" value="${item.menuId}">
                          <input type="hidden" name="menu_items[${index}][quantity]" value="${item.quantity}" class="quantity-hidden">
                      </td>
                      <td>Rp ${subtotal.toLocaleString('id-ID')}</td>
                      <td>
                          <button type="button" class="btn btn-sm btn-danger remove-item" data-index="${index}">
                              <i class="fas fa-trash"></i>
                          </button>
                      </td>
                  </tr>
              `;
              
              selectedItemsHtml += `
                  <li class="list-group-item px-0">
                      <div class="d-flex justify-content-between">
                          <div>
                              <span class="fw-medium">${item.name}</span>
                              <br>
                              <small class="text-muted">${item.quantity} x Rp ${item.price.toLocaleString('id-ID')}</small>
                          </div>
                          <div class="text-end">
                              Rp ${subtotal.toLocaleString('id-ID')}
                          </div>
                      </div>
                  </li>
              `;
          });
          
          cartHtml += `
                      </tbody>
                  </table>
              </div>
              <div class="d-flex justify-content-end mt-3">
                  <button type="button" class="btn btn-outline-danger" id="clear-cart">Clear Cart</button>
              </div>
          `;
          
          selectedItemsHtml += '</ul>';
          
          cartItemsContainer.innerHTML = cartHtml;
          selectedItemsContainer.innerHTML = selectedItemsHtml;
          totalPriceElement.textContent = `Rp ${totalPrice.toLocaleString('id-ID')}`;
          placeOrderBtn.disabled = false;
          
          // Add event listeners for quantity buttons
          document.querySelectorAll('.decrease-quantity').forEach(button => {
              button.addEventListener('click', function() {
                  const index = parseInt(this.dataset.index);
                  if (cart[index].quantity > 1) {
                      cart[index].quantity--;
                      updateCartItem(index);
                  }
              });
          });
          
          document.querySelectorAll('.increase-quantity').forEach(button => {
              button.addEventListener('click', function() {
                  const index = parseInt(this.dataset.index);
                  cart[index].quantity++;
                  updateCartItem(index);
              });
          });
          
          document.querySelectorAll('.quantity-input').forEach(input => {
              input.addEventListener('change', function() {
                  const index = parseInt(this.dataset.index);
                  const quantity = parseInt(this.value);
                  if (quantity >= 1) {
                      cart[index].quantity = quantity;
                      updateCartItem(index);
                  } else {
                      this.value = 1;
                      cart[index].quantity = 1;
                      updateCartItem(index);
                  }
              });
          });
          
          document.querySelectorAll('.remove-item').forEach(button => {
              button.addEventListener('click', function() {
                  const index = parseInt(this.dataset.index);
                  cart.splice(index, 1);
                  saveCart();
                  updateCartDisplay();
              });
          });
          
          document.getElementById('clear-cart').addEventListener('click', function() {
              cart.length = 0;
              saveCart();
              updateCartDisplay();
          });
      }
      
      // Function to update a specific cart item
      function updateCartItem(index) {
          document.querySelectorAll(`.quantity-hidden[name="menu_items[${index}][quantity]"]`).forEach(input => {
              input.value = cart[index].quantity;
          });
          saveCart();
          updateCartDisplay();
      }
      
      // Function to save cart to localStorage
      function saveCart() {
          localStorage.setItem('cart_{{ $canteen->id }}', JSON.stringify(cart));
      }
      
      // Initialize cart display
      updateCartDisplay();
      
      // Place order button event listener
      placeOrderBtn.addEventListener('click', function() {
          if (cart.length > 0) {
              orderForm.submit();
          }
      });
  });
</script>
@endpush
@endsection

