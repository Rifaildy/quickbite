<?php

namespace App\Http\Controllers;

use App\Models\Canteen;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Display the welcome page with canteens and menus
     */
    public function index()
    {
        // Get featured canteens
        $canteens = Canteen::with('user')
            ->take(6)
            ->get();

        // Get popular menus
        $popularMenus = Menu::with(['canteen', 'category'])
            ->where('is_available', true)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Get menu categories
        $categories = Category::withCount('menus')
            ->having('menus_count', '>', 0)
            ->get();

        return view('welcome', compact('canteens', 'popularMenus', 'categories'));
    }
}

