@extends('layouts.buyer')

@section('title', 'Browse Canteens')

@section('buyer-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2">Browse Canteens</h1>
</div>

<div class="row mb-4">
  <div class="col-md-6 offset-md-3">
      <form action="{{ route('buyer.canteens.index') }}" method="GET">
          <div class="input-group">
              <input type="text" class="form-control" placeholder="Search canteens..." name="search" value="{{ request('search') }}">
              <button class="btn btn-primary" type="submit">
                  <i class="fas fa-search"></i> Search
              </button>
          </div>
      </form>
  </div>
</div>

@if($canteens->isEmpty())
  <div class="text-center py-5">
      <i class="fas fa-store fa-4x text-muted mb-3"></i>
      <h5>No canteens found</h5>
      <p class="text-muted">Try a different search term or check back later.</p>
  </div>
@else
  <div class="row">
      @foreach($canteens as $canteen)
          <div class="col-md-4 mb-4">
              <div class="card h-100">
                  <div class="card-body">
                      <div class="d-flex justify-content-between align-items-start">
                          <h5 class="card-title">{{ $canteen->name }}</h5>
                          <button type="button" class="btn btn-sm btn-outline-danger add-to-favorite" data-canteen-id="{{ $canteen->id }}">
                              <i class="fas fa-heart"></i>
                          </button>
                      </div>
                      <p class="card-text text-muted small">
                          <i class="fas fa-utensils me-1"></i> {{ $canteen->menus_count }} menu items
                      </p>
                      <p class="card-text">{{ Str::limit($canteen->description, 100) }}</p>
                  </div>
                  <div class="card-footer bg-white">
                      <a href="{{ route('buyer.canteens.show', $canteen) }}" class="btn btn-primary w-100">
                          <i class="fas fa-eye me-1"></i> View Menu
                      </a>
                  </div>
              </div>
          </div>
      @endforeach
  </div>

  <div class="d-flex justify-content-center mt-4">
      {{ $canteens->links() }}
  </div>
@endif

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
      // Add to favorite functionality
      const addToFavoriteButtons = document.querySelectorAll('.add-to-favorite');
      addToFavoriteButtons.forEach(button => {
          button.addEventListener('click', function() {
              const canteenId = this.dataset.canteenId;
              
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
              favorableType.value = 'App\\Models\\Canteen';
              form.appendChild(favorableType);
              
              // Add favorable_id
              const favorableId = document.createElement('input');
              favorableId.type = 'hidden';
              favorableId.name = 'favorable_id';
              favorableId.value = canteenId;
              form.appendChild(favorableId);
              
              document.body.appendChild(form);
              form.submit();
          });
      });
  });
</script>
@endpush
@endsection

