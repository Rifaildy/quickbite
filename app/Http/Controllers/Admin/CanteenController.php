<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Canteen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CanteenController extends Controller
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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');
        
        $canteens = Canteen::with('user')
            ->when($status !== null, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.canteens.index', compact('canteens', 'status', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sellers = User::where('role', 'seller')
            ->whereDoesntHave('canteen')
            ->get();
            
        if ($sellers->isEmpty()) {
            return redirect()->route('admin.canteens.index')
                ->with('info', 'All sellers already have canteens. Create a new seller first.');
        }
        
        return view('admin.canteens.create', compact('sellers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'user_id' => [
                'required', 
                'exists:users,id',
                Rule::unique('canteens', 'user_id')->where(function ($query) {
                    return $query->whereNotNull('user_id');
                }),
            ],
            'status' => ['required', 'boolean'],
        ]);

        Canteen::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.canteens.index')
            ->with('success', 'Canteen created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Canteen $canteen)
    {
        $canteen->load(['user', 'menus', 'orders']);
        
        $totalMenus = $canteen->menus->count();
        $activeMenus = $canteen->menus->where('status', true)->count();
        $totalOrders = $canteen->orders->count();
        $totalSales = $canteen->orders->where('payment_status', 'paid')->sum('total_price');
        
        return view('admin.canteens.show', compact(
            'canteen', 
            'totalMenus', 
            'activeMenus', 
            'totalOrders', 
            'totalSales'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Canteen $canteen)
    {
        return view('admin.canteens.edit', compact('canteen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Canteen $canteen)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
        ]);

        $canteen->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.canteens.index')
            ->with('success', 'Canteen updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Canteen $canteen)
    {
        // Check if canteen has orders
        if ($canteen->orders()->exists()) {
            return redirect()->route('admin.canteens.index')
                ->with('error', 'Cannot delete canteen with existing orders.');
        }
        
        // Delete all menus associated with the canteen
        foreach ($canteen->menus as $menu) {
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            $menu->delete();
        }
        
        $canteen->delete();

        return redirect()->route('admin.canteens.index')
            ->with('success', 'Canteen deleted successfully.');
    }
}

