<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Canteen;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'buyer']);
    }

    /**
     * Show the buyer dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        
        $totalOrders = $user->orders()->count();
        $pendingOrders = $user->orders()->whereIn('status', ['pending', 'processing'])->count();
        $completedOrders = $user->orders()->where(  'status',['pending', 'processing'])->count();
        $completedOrders = $user->orders()->where('status', 'completed')->count();
        
        $recentOrders = $user->orders()
            ->with(['canteen', 'orderItems.menu'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $canteens = Canteen::where('status', true)
            ->withCount('menus')
            ->having('menus_count', '>', 0)
            ->take(4)
            ->get();
            
        return view('buyer.dashboard', compact(
            'totalOrders', 
            'pendingOrders', 
            'completedOrders', 
            'recentOrders',
            'canteens'
        ));
    }
}

