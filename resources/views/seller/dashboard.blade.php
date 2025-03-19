@extends('layouts.seller')

@section('title', __('seller.seller_dashboard'))

@section('seller-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
   <h1 class="h2">{{ __('seller.seller_dashboard') }}</h1>
</div>

@if(!$canteen)
   <div class="alert alert-info">
       <h4 class="alert-heading">{{ __('seller.welcome_message') }}</h4>
       <p>{{ __('seller.seller_welcome_desc') }}</p>
       <hr>
       <p class="mb-0">
           <a href="#" class="btn btn-primary">{{ __('seller.create_canteen_profile') }}</a>
       </p>
   </div>
@else
   <div class="row">
       <div class="col-md-3 mb-4">
           <div class="card text-white bg-primary">
               <div class="card-body">
                   <div class="d-flex justify-content-between align-items-center">
                       <div>
                           <h6 class="card-title">{{ __('seller.total_menu_items') }}</h6>
                           <h2 class="mb-0">{{ $totalMenus }}</h2>
                       </div>
                       <i class="fas fa-utensils fa-2x"></i>
                   </div>
               </div>
               <div class="card-footer d-flex align-items-center justify-content-between">
                   <a href="#" class="text-black text-decoration-none">{{ __('View Details') }}</a>
                   <i class="fas fa-angle-right"></i>
               </div>
           </div>
       </div>
       <div class="col-md-3 mb-4">
           <div class="card text-white bg-success">
               <div class="card-body">
                   <div class="d-flex justify-content-between align-items-center">
                       <div>
                           <h6 class="card-title">{{ __('seller.total_orders') }}</h6>
                           <h2 class="mb-0">{{ $totalOrders }}</h2>
                       </div>
                       <i class="fas fa-shopping-cart fa-2x"></i>
                   </div>
               </div>
               <div class="card-footer d-flex align-items-center justify-content-between">
                   <a href="{{ route('seller.orders.index') }}" class="text-black text-decoration-none">{{ __('View Details') }}</a>
                   <i class="fas fa-angle-right"></i>
               </div>
           </div>
       </div>
       <div class="col-md-3 mb-4">
           <div class="card text-white bg-warning">
               <div class="card-body">
                   <div class="d-flex justify-content-between align-items-center">
                       <div>
                           <h6 class="card-title">{{ __('seller.pending_orders') }}</h6>
                           <h2 class="mb-0">{{ $pendingOrders }}</h2>
                       </div>
                       <i class="fas fa-clock fa-2x"></i>
                   </div>
               </div>
               <div class="card-footer d-flex align-items-center justify-content-between">
                   <a href="{{ route('seller.orders.index', ['status' => 'pending']) }}" class="text-black text-decoration-none">{{ __('View Details') }}</a>
                   <i class="fas fa-angle-right"></i>
               </div>
           </div>
       </div>
       <div class="col-md-3 mb-4">
           <div class="card text-white bg-info">
               <div class="card-body">
                   <div class="d-flex justify-content-between align-items-center">
                       <div>
                           <h6 class="card-title">{{ __('seller.completed_orders') }}</h6>
                           <h2 class="mb-0">{{ $completedOrders }}</h2>
                       </div>
                       <i class="fas fa-check-circle fa-2x"></i>
                   </div>
               </div>
               <div class="card-footer d-flex align-items-center justify-content-between">
                   <a href="{{ route('seller.orders.index', ['status' => 'completed']) }}" class="text-black text-decoration-none">{{ __('View Details') }}</a>
                   <i class="fas fa-angle-right"></i>
               </div>
           </div>
       </div>
   </div>

   <div class="row">
       <div class="col-md-8">
           <div class="card">
               <div class="card-header">
                   <h5 class="card-title mb-0">Recent Orders</h5>
               </div>
               <div class="card-body">
                   <div class="table-responsive">
                       <table class="table table-striped">
                           <thead>
                               <tr>
                                   <th>{{ __('seller.order_number') }}</th>
                                   <th>{{ __('Customer') }}</th>
                                   <th>{{ __('Total') }}</th>
                                   <th>{{ __('general.status') }}</th>
                                   <th>{{ __('Date') }}</th>
                                   <th>{{ __('general.actions') }}</th>
                               </tr>
                           </thead>
                           <tbody>
                               @forelse($recentOrders as $order)
                                   <tr>
                                       <td>{{ $order->order_number }}</td>
                                       <td>{{ $order->user->name }}</td>
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
                                           <a href="{{ route('seller.orders.show', $order) }}" class="btn btn-sm btn-primary">{{ __('general.view') }}</a>
                                       </td>
                                   </tr>
                               @empty
                                   <tr>
                                       <td colspan="6" class="text-center">No orders Found</td>
                                   </tr>
                               @endforelse
                           </tbody>
                       </table>
                   </div>
               </div>
               <div class="card-footer">
                   <a href="{{ route('seller.orders.index') }}" class="btn btn-sm btn-primary">View All Orders</a>
               </div>
           </div>
       </div>
       <div class="col-md-4">
           <div class="card">
               <div class="card-header">
                   <h5 class="card-title mb-0">{{ __('seller.canteen_information') }}</h5>
               </div>
               <div class="card-body">
                   <div class="mb-3">
                       <label class="form-label fw-bold">{{ __('Name') }}</label>
                       <p>{{ $canteen->name }}</p>
                   </div>
                   <div class="mb-3">
                       <label class="form-label fw-bold">{{ __('Description') }}</label>
                       <p>{{ $canteen->description ?? __('general.no_description') }}</p>
                   </div>
                   <div class="mb-3">
                       <label class="form-label fw-bold">{{ __('general.status') }}</label>
                       <p>
                           @if($canteen->status)
                               <span class="badge bg-success">{{ __('general.active') }}</span>
                           @else
                               <span class="badge bg-danger">{{ __('general.inactive') }}</span>
                           @endif
                       </p>
                   </div>
               </div>
               <div class="card-footer">
                   <a href="#" class="btn btn-sm btn-primary">{{ __('seller.edit_canteen') }}</a>
               </div>
           </div>
       </div>
   </div>
@endif
@endsection

