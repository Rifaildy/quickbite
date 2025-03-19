<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
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
     * Display the sales report dashboard.
     */
    public function index(Request $request)
    {
        $canteen = Auth::user()->canteen;
        
        if (!$canteen) {
            return redirect()->route('seller.canteens.create')
                ->with('info', 'Please create your canteen first.');
        }

        // Get date range from request or use default (current month)
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date')) 
            : Carbon::now()->startOfMonth();
        
        $endDate = $request->input('end_date') 
            ? Carbon::parse($request->input('end_date')) 
            : Carbon::now()->endOfDay();
        
        // Ensure end date is not before start date
        if ($endDate->lt($startDate)) {
            $endDate = $startDate->copy()->endOfMonth();
        }

        // Get period type (daily, monthly, yearly)
        $periodType = $request->input('period_type', 'daily');

        // Get completed orders within date range
        $orders = $canteen->orders()
            ->where('status', 'completed')
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->get();

        // Calculate total sales and order count
        $totalSales = $orders->sum('total_price');
        $orderCount = $orders->count();
        
        // Get top selling menu items
        $topSellingItems = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->select(
                'menus.id',
                'menus.name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_sales')
            )
            ->where('orders.canteen_id', $canteen->id)
            ->where('orders.status', 'completed')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->groupBy('menus.id', 'menus.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        // Get sales data grouped by period
        $salesData = [];
        $salesLabels = [];
        
        if ($periodType === 'daily') {
            // For daily reports, group by day
            $salesByPeriod = $orders->groupBy(function ($order) {
                return Carbon::parse($order->created_at)->format('Y-m-d');
            });
            
            // Create a date range for all days in the period
            $period = Carbon::parse($startDate)->daysUntil($endDate);
            
            foreach ($period as $date) {
                $dateStr = $date->format('Y-m-d');
                $salesLabels[] = $date->format('M d');
                $salesData[] = isset($salesByPeriod[$dateStr]) 
                    ? $salesByPeriod[$dateStr]->sum('total_price') 
                    : 0;
            }
        } elseif ($periodType === 'monthly') {
            // For monthly reports, group by month
            $salesByPeriod = $orders->groupBy(function ($order) {
                return Carbon::parse($order->created_at)->format('Y-m');
            });
            
            // Create a date range for all months in the period
            $period = Carbon::parse($startDate)->startOfMonth()->monthsUntil($endDate->endOfMonth());
            
            foreach ($period as $date) {
                $dateStr = $date->format('Y-m');
                $salesLabels[] = $date->format('M Y');
                $salesData[] = isset($salesByPeriod[$dateStr]) 
                    ? $salesByPeriod[$dateStr]->sum('total_price') 
                    : 0;
            }
        } else {
            // For yearly reports, group by year
            $salesByPeriod = $orders->groupBy(function ($order) {
                return Carbon::parse($order->created_at)->format('Y');
            });
            
            // Create a date range for all years in the period
            $period = Carbon::parse($startDate)->startOfYear()->yearsUntil($endDate->endOfYear());
            
            foreach ($period as $date) {
                $dateStr = $date->format('Y');
                $salesLabels[] = $date->format('Y');
                $salesData[] = isset($salesByPeriod[$dateStr]) 
                    ? $salesByPeriod[$dateStr]->sum('total_price') 
                    : 0;
            }
        }

        // Get order status distribution
        $orderStatusDistribution = $canteen->orders()
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return view('seller.reports.index', compact(
            'canteen',
            'startDate',
            'endDate',
            'periodType',
            'totalSales',
            'orderCount',
            'topSellingItems',
            'salesData',
            'salesLabels',
            'orderStatusDistribution'
        ));
    }

    /**
     * Display detailed sales report.
     */
    public function sales(Request $request)
    {
        $canteen = Auth::user()->canteen;
        
        if (!$canteen) {
            return redirect()->route('seller.canteens.create')
                ->with('info', 'Please create your canteen first.');
        }

        // Get date range from request or use default (current month)
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date')) 
            : Carbon::now()->startOfMonth();
        
        $endDate = $request->input('end_date') 
            ? Carbon::parse($request->input('end_date')) 
            : Carbon::now()->endOfDay();

        // Get orders within date range
        $orders = $canteen->orders()
            ->with(['user', 'orderItems.menu'])
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('seller.reports.sales', compact('canteen', 'startDate', 'endDate', 'orders'));
    }

    /**
     * Display menu performance report.
     */
    public function menuPerformance(Request $request)
    {
        $canteen = Auth::user()->canteen;
        
        if (!$canteen) {
            return redirect()->route('seller.canteens.create')
                ->with('info', 'Please create your canteen first.');
        }

        // Get date range from request or use default (current month)
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date')) 
            : Carbon::now()->startOfMonth();
        
        $endDate = $request->input('end_date') 
            ? Carbon::parse($request->input('end_date')) 
            : Carbon::now()->endOfDay();

        // Get menu performance data
        $menuPerformance = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->join('categories', 'menus.category_id', '=', 'categories.id')
            ->select(
                'menus.id',
                'menus.name',
                'categories.name as category_name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_sales'),
                DB::raw('COUNT(DISTINCT orders.id) as order_count')
            )
            ->where('orders.canteen_id', $canteen->id)
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->groupBy('menus.id', 'menus.name', 'categories.name')
            ->orderBy('total_sales', 'desc')
            ->paginate(15);

        // Get category performance data
        $categoryPerformance = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->join('categories', 'menus.category_id', '=', 'categories.id')
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_sales'),
                DB::raw('COUNT(DISTINCT menus.id) as menu_count')
            )
            ->where('orders.canteen_id', $canteen->id)
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_sales', 'desc')
            ->get();

        return view('seller.reports.menu-performance', compact(
            'canteen', 
            'startDate', 
            'endDate', 
            'menuPerformance', 
            'categoryPerformance'
        ));
    }

    /**
     * Export sales report to CSV.
     */
    public function exportSales(Request $request)
    {
        $canteen = Auth::user()->canteen;
        
        if (!$canteen) {
            return redirect()->route('seller.canteens.create')
                ->with('info', 'Please create your canteen first.');
        }

        // Get date range from request
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date')) 
            : Carbon::now()->startOfMonth();
        
        $endDate = $request->input('end_date') 
            ? Carbon::parse($request->input('end_date')) 
            : Carbon::now()->endOfDay();

        // Get orders within date range
        $orders = $canteen->orders()
            ->with(['user', 'orderItems.menu'])
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->orderBy('created_at', 'desc')
            ->get();

        // Create CSV file
        $filename = 'sales_report_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Add CSV header
            fputcsv($file, [
                'Order Number',
                'Date',
                'Customer',
                'Items',
                'Total Price',
                'Status'
            ]);
            
            // Add order data
            foreach ($orders as $order) {
                $items = [];
                foreach ($order->orderItems as $item) {
                    $items[] = $item->quantity . 'x ' . $item->menu->name;
                }
                
                fputcsv($file, [
                    $order->order_number,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->user->name,
                    implode(', ', $items),
                    $order->total_price,
                    $order->status
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

