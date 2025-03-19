<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Canteen;
use App\Models\Category;
use Illuminate\Http\Request;

class CanteenController extends Controller
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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $canteens = Canteen::when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->withCount('menus')
            ->orderBy('name')
            ->paginate(9);
        
        return view('buyer.canteens.index', compact('canteens'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Canteen $canteen)
    {
        if (!$canteen->status) {
            return redirect()->route('buyer.canteens.index')
                ->with('error', 'This canteen is currently unavailable.');
        }
        
        $categories = Category::all();
        
        $menus = $canteen->menus()
            ->when($request->category, function ($query, $category) {
                return $query->where('category_id', $category);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(9);
            
        return view('buyer.canteens.show', compact('canteen', 'menus', 'categories'));
    }
}

