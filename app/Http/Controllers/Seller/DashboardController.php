<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
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
        $this->middleware(['auth', 'seller']);
    }

    /**
     * Show the seller dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $canteen = Auth::user()->canteen;
        
        if (!$canteen) {
            return redirect()->route('seller.canteens.create')
                ->with('info', 'Please create your canteen first.');
        }
        
        $totalMenus = $canteen->menus()->count();
        $totalOrders = $canteen->orders()->count();
        $pendingOrders = $canteen->orders()->where('status', 'pending')->count();
        $completedOrders = $canteen->orders()->where('status', 'completed')->count();
        
        $recentOrders = $canteen->orders()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        return view('seller.dashboard', compact(
            'canteen',
            'totalMenus', 
            'totalOrders', 
            'pendingOrders', 
            'completedOrders', 
            'recentOrders'
        ));
    }
}

