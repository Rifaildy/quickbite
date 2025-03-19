@extends('layouts.buyer')

@section('title', __('buyer.buyer_dashboard'))

@section('buyer-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
   <h1 class="h2">{{ __('buyer.buyer_dashboard') }}</h1>
</div>

<div class="row">
   <div class="col-md-4 mb-4">
       <div class="card text-white bg-primary">
           <div class="card-body">
               <div class="d-flex justify-content-between align-items-center">
                   <div>
                       <h6 class="card-title">{{ __('buyer.total_orders') }}</h6>
                       <h2 class="mb-0">{{ $totalOrders }}</h2>
                   </div>
                   <i class="fas fa-shopping-cart fa-2x"></i>
               </div>
           </div>
           <div class="card-footer d-flex align-items-center justify-content-between">
               <a href="{{ route('buyer.orders.index') }}" class="text-black text-decoration-none">{{ __('View Details') }}</a>
               <i class="fas fa-angle-right"></i>
           </div>
       </div>
   </div>
   <div class="col-md-4 mb-4">
       <div class="card text-white bg-warning">
           <div class="card-body">
               <div class="d-flex justify-content-between align-items-center">
                   <div>
                       <h6 class="card-title">{{ __('buyer.pending_orders') }}</h6>
                       <h2 class="mb-0">{{ $pendingOrders }}</h2>
                   </div>
                   <i class="fas fa-clock fa-2x"></i>
               </div>
           </div>
           <div class="card-footer d-flex align-items-center justify-content-between">
               <a href="{{ route('buyer.orders.index', ['status' => 'pending']) }}" class="text-black text-decoration-none">{{ __('View Details') }}</a>
               <i class="fas fa-angle-right"></i>
           </div>
       </div>
   </div>
   <div class="col-md-4 mb-4">
       <div class="card text-white bg-success">
           <div class="card-body">
               <div class="d-flex justify-content-between align-items-center">
                   <div>
                       <h6 class="card-title">{{ __('buyer.completed_orders') }}</h6>
                       <h2 class="mb-0">{{ $completedOrders }}</h2>
                   </div>
                   <i class="fas fa-check-circle fa-2x"></i>
               </div>
           </div>
           <div class="card-footer d-flex align-items-center justify-content-between">
               <a href="{{ route('buyer.orders.index', ['status' => 'completed']) }}" class="text-black text-decoration-none">{{ __('View Details') }}</a>
               <i class="fas fa-angle-right"></i>
           </div>
       </div>
   </div>
</div>

<div class="row">
   <div class="col-md-8">
       <div class="card mb-4">
           <div class="card-header">
               <h5 class="card-title mb-0">{{ __('buyer.recent_orders') }}</h5>
           </div>
           <div class="card-body">
               <div class="table-responsive">
                   <table class="table table-striped">
                       <thead>
                           <tr>
                               <th>{{ __('buyer.order_number') }}</th>
                               <th>{{ __('canteen') }}</th>
                               <th>{{ __('total') }}</th>
                               <th>{{ __('status') }}</th>
                               <th>{{ __('date') }}</th>
                               <th>{{ __('general.actions') }}</th>
                           </tr>
                       </thead>
                       <tbody>
                           @forelse($recentOrders as $order)
                               <tr>
                                   <td>{{ $order->order_number }}</td>
                                   <td>{{ $order->canteen->name }}</td>
                                   <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                   <td>
                                       @if($order->status == 'pending')
                                           <span class="badge bg-warning">{{ __('general.pending') }}</span>
                                       @elseif($order->status == 'processing')
                                           <span class="badge bg-info">{{ __('general.processing') }}</span>
                                       @elseif($order->status == 'ready_for_pickup')
                                           <span class="badge bg-primary">{{ __('general.ready_for_pickup') }}</span>
                                       @elseif($order->status == 'completed')
                                           <span class="badge bg-success">{{ __('general.completed') }}</span>
                                       @elseif($order->status == 'cancelled')
                                           <span class="badge bg-danger">{{ __('general.cancelled') }}</span>
                                       @endif
                                   </td>
                                   <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                   <td>
                                       <a href="{{ route('buyer.orders.show', $order) }}" class="btn btn-sm btn-primary">{{ __('general.view') }}</a>
                                   </td>
                               </tr>
                           @empty
                               <tr>
                                   <td colspan="6" class="text-center">{{ __('buyer.no_orders_found') }}</td>
                               </tr>
                           @endforelse
                       </tbody>
                   </table>
               </div>
           </div>
           <div class="card-footer">
               <a href="{{ route('buyer.orders.index') }}" class="btn btn-sm btn-primary">{{ __('buyer.view_all_orders') }}</a>
           </div>
       </div>
   </div>
   <div class="col-md-4">
       <div class="card">
           <div class="card-header">
               <h5 class="card-title mb-0">{{ __('buyer.featured_canteens') }}</h5>
           </div>
           <div class="card-body">
               @forelse($canteens as $canteen)
                   <div class="d-flex align-items-center mb-3">
                       <div class="flex-shrink-0">
                           <div class="bg-light rounded" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                               <i class="fas fa-store fa-2x text-primary"></i>
                           </div>
                       </div>
                       <div class="flex-grow-1 ms-3">
                           <h6 class="mb-0">{{ $canteen->name }}</h6>
                           <p class="text-muted mb-0">{{ $canteen->menus_count }} {{ __('buyer.menu_items') }}</p>
                           <a href="#" class="btn btn-sm btn-outline-primary mt-1">{{ __('buyer.order_now') }}</a>
                       </div>
                   </div>
                   @if(!$loop->last)
                       <hr>
                   @endif
               @empty
                   <p class="text-center">{{ __('general.no_canteens_available') }}</p>
               @endforelse
           </div>
           <div class="card-footer">
               <a href="#" class="btn btn-sm btn-primary">{{ __('buyer.view_all_canteens') }}</a>
           </div>
       </div>
   </div>
</div>
@endsection

