<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Canteen;
use App\Models\Menu;
use Illuminate\Http\Request;
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
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $canteenId = $request->input('canteen_id');
        $categoryId = $request->input('category_id');
        $status = $request->input('status');
        $search = $request->input('search');
        
        $menus = Menu::with(['canteen', 'category'])
            ->when($canteenId, function ($query) use ($canteenId) {
                return $query->where('canteen_id', $canteenId);
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->when($status !== null, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $canteens = Canteen::all();
        $categories = Category::all();
        
        return view('admin.menus.index', compact(
            'menus', 
            'canteens', 
            'categories', 
            'canteenId', 
            'categoryId', 
            'status', 
            'search'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $canteens = Canteen::where('status', true)->get();
        
        if ($canteens->isEmpty()) {
            return redirect()->route('admin.menus.index')
                ->with('info', 'No active canteens available. Activate a canteen first.');
        }
        
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
        
        return view('admin.menus.create', compact('canteens', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'canteen_id' => ['required', 'exists:canteens,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'boolean'],
        ]);
        
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'canteen_id' => $request->canteen_id,
            'category_id' => $request->category_id,
            'status' => $request->status,
        ];
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menus', 'public');
        }
        
        Menu::create($data);
        
        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        $menu->load(['canteen', 'category']);
        
        return view('admin.menus.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        $canteens = Canteen::all();
        $categories = Category::all();
        
        return view('admin.menus.edit', compact('menu', 'canteens', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'canteen_id' => ['required', 'exists:canteens,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'boolean'],
        ]);
        
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'canteen_id' => $request->canteen_id,
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
        
        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        // Check if menu has order items
        if ($menu->orderItems()->exists()) {
            return redirect()->route('admin.menus.index')
                ->with('error', 'Cannot delete menu item with existing orders.');
        }
        
        // Delete image if exists
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }
        
        $menu->delete();
        
        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu item deleted successfully.');
    }
}

