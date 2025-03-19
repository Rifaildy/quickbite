@extends('layouts.admin')

@section('title', __('admin.admin_dashboard'))

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
   <h1 class="h2">{{ __('admin.admin_dashboard') }}</h1>
</div>

<div class="row">
   <div class="col-md-3 mb-4">
       <div class="card text-white bg-primary">
           <div class="card-body">
               <div class="d-flex justify-content-between align-items-center">
                   <div>
                       <h6 class="card-title">{{ __('admin.total_users') }}</h6>
                       <h2 class="mb-0">{{ $totalUsers }}</h2>
                   </div>
                   <i class="fas fa-users fa-2x"></i>
               </div>
           </div>
           <div class="card-footer d-flex align-items-center justify-content-between">
               <a href="{{ route('admin.users.index') }}" class="text-black text-decoration-none">{{ __('View Details') }}</a>
               <i class="fas fa-angle-right"></i>
           </div>
       </div>
   </div>
   <div class="col-md-3 mb-4">
       <div class="card text-white bg-success">
           <div class="card-body">
               <div class="d-flex justify-content-between align-items-center">
                   <div>
                       <h6 class="card-title">{{ __('admin.total_canteens') }}</h6>
                       <h2 class="mb-0">{{ $totalCanteens }}</h2>
                   </div>
                   <i class="fas fa-store fa-2x"></i>
               </div>
           </div>
           <div class="card-footer d-flex align-items-center justify-content-between">
               <a href="#" class="text-black text-decoration-none">{{ __('View Details') }}</a>
               <i class="fas fa-angle-right"></i>
           </div>
       </div>
   </div>
   <div class="col-md-3 mb-4">
       <div class="card text-white bg-info">
           <div class="card-body">
               <div class="d-flex justify-content-between align-items-center">
                   <div>
                       <h6 class="card-title">{{ __('admin.total_menus') }}</h6>
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
       <div class="card text-white bg-warning">
           <div class="card-body">
               <div class="d-flex justify-content-between align-items-center">
                   <div>
                       <h6 class="card-title">{{ __('admin.total_orders') }}</h6>
                       <h2 class="mb-0">{{ $totalOrders }}</h2>
                   </div>
                   <i class="fas fa-shopping-cart fa-2x"></i>
               </div>
           </div>
           <div class="card-footer d-flex align-items-center justify-content-between">
               <a href="#" class="text-black text-decoration-none">{{ __('View Details') }}</a>
               <i class="fas fa-angle-right"></i>
           </div>
       </div>
   </div>
</div>

<div class="row">
   <div class="col-md-12">
       <div class="card">
           <div class="card-header">
               <h5 class="card-title mb-0">{{ __('admin.recent_orders') }}</h5>
           </div>
           <div class="card-body">
               <div class="table-responsive">
                   <table class="table table-striped">
                       <thead>
                           <tr>
                               <th>{{ __('admin.order_number') }}</th>
                               <th>{{ __('admin.customer') }}</th>
                               <th>{{ __('general.canteen') }}</th>
                               <th>{{ __('general.total') }}</th>
                               <th>{{ __('general.status') }}</th>
                               <th>{{ __('general.date') }}</th>
                               <th>{{ __('general.actions') }}</th>
                           </tr>
                       </thead>
                       <tbody>
                           @forelse($recentOrders as $order)
                               <tr>
                                   <td>{{ $order->order_number }}</td>
                                   <td>{{ $order->user->name }}</td>
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
                                       <a href="#" class="btn btn-sm btn-primary">{{ __('general.view') }}</a>
                                   </td>
                               </tr>
                           @empty
                               <tr>
                                   <td colspan="7" class="text-center">{{ __('general.no_orders_found') }}</td>
                               </tr>
                           @endforelse
                       </tbody>
                   </table>
               </div>
           </div>
       </div>
   </div>
</div>
@endsection

