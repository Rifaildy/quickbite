<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Canteen;
use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalUsers = User::count();
        $totalCanteens = Canteen::count();
        $totalMenus = Menu::count();
        $totalOrders = Order::count();
        
        $recentOrders = Order::with(['user', 'canteen'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalCanteens', 
            'totalMenus', 
            'totalOrders', 
            'recentOrders'
        ));
    }
}

