<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $canteen = Auth::user()->canteen;
        
        if (!$canteen) {
            return redirect()->route('seller.canteens.create')
                ->with('info', 'Please create your canteen first.');
        }
        
        $categories = Category::all();
        
        $menus = $canteen->menus()
            ->when($request->category, function ($query, $category) {
                return $query->where('category_id', $category);
            })
            ->when($request->has('status'), function ($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(9);
            
        return view('seller.menus.index', compact('menus', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $canteen = Auth::user()->canteen;
        
        if (!$canteen) {
            return redirect()->route('seller.canteens.create')
                ->with('info', 'Please create your canteen first.');
        }
        
        // Make sure we have categories in the database
        $categories = Category::all();
        
        // If no categories exist, create some default ones
        if ($categories->isEmpty()) {
            $defaultCategories = [
                'Main Course', 'Appetizer', 'Dessert', 'Beverage', 'Snack'
            ];
            
            foreach ($defaultCategories as $name) {
                Category::create(['name' => $name]);
            }
            
            $categories = Category::all();
        }
        
        return view('seller.menus.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $canteen = Auth::user()->canteen;
        
        if (!$canteen) {
            return redirect()->route('seller.canteens.create')
                ->with('info', 'Please create your canteen first.');
        }
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'boolean'],
        ]);
        
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'canteen_id' => $canteen->id,
            'status' => $request->status,
        ];
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menus', 'public');
        }
        
        Menu::create($data);
        
        return redirect()->route('seller.menus.index')
            ->with('success', 'Menu item created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        $canteen = Auth::user()->canteen;
        
        if (!$canteen || $menu->canteen_id !== $canteen->id) {
            return abort(403, 'Unauthorized action.');
        }
        
        $categories = Category::all();
        
        return view('seller.menus.edit', compact('menu', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $canteen = Auth::user()->canteen;
        
        if (!$canteen || $menu->canteen_id !== $canteen->id) {
            return abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'boolean'],
        ]);
        
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'status' => $request->status,
        ];
        
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            
            $data['image'] = $request->file('image')->store('menus', 'public');
        } elseif ($request->has('delete_image') && $menu->image) {
            Storage::disk('public')->delete($menu->image);
            $data['image'] = null;
        }
        
        $menu->update($data);
        
        return redirect()->route('seller.menus.index')
            ->with('success', 'Menu item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $canteen = Auth::user()->canteen;
        
        if (!$canteen || $menu->canteen_id !== $canteen->id) {
            return abort(403, 'Unauthorized action.');
        }
        
        // Delete image if exists
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }
        
        $menu->delete();
        
        return redirect()->route('seller.menus.index')
            ->with('success', 'Menu item deleted successfully.');
    }
}

