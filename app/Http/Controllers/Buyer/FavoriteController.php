<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Canteen;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'buyer']);
    }

    public function index(Request $request)
    {
        $type = $request->type ?? 'canteens';

        if ($type == 'canteens') {
            $favoriteCanteens = Favorite::where('user_id', Auth::id())
                ->where('favorable_type', Canteen::class)
                ->with('favorable')
                ->get();
                
            return view('buyer.favorites.index', compact('favoriteCanteens', 'type'));
        } else {
            $favoriteMenus = Favorite::where('user_id', Auth::id())
                ->where('favorable_type', Menu::class)
                ->with(['favorable.canteen', 'favorable.category'])
                ->get();
                
            return view('buyer.favorites.index', compact('favoriteMenus', 'type'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'favorable_type' => ['required', 'in:App\Models\Canteen,App\Models\Menu'],
            'favorable_id' => ['required', 'integer'],
        ]);
        
        // Check if already favorited
        $exists = Favorite::where('user_id', Auth::id())
            ->where('favorable_type', $request->favorable_type)
            ->where('favorable_id', $request->favorable_id)
            ->exists();
            
        if ($exists) {
            return back()->with('info', 'Already in your favorites.');
        }
        
        Favorite::create([
            'user_id' => Auth::id(),
            'favorable_type' => $request->favorable_type,
            'favorable_id' => $request->favorable_id,
        ]);
        
        // Get the name of the favorited item for the success message
        $favorableModel = $request->favorable_type::find($request->favorable_id);
        $itemName = $favorableModel ? $favorableModel->name : 'Item';
        
        return back()->with('success', $itemName . ' added to favorites.');
    }

    public function destroy(Favorite $favorite)
    {
        if ($favorite->user_id !== Auth::id()) {
            return abort(403, 'Unauthorized action.');
        }
        
        // Get the name of the favorited item for the success message
        $itemName = $favorite->favorable ? $favorite->favorable->name : 'Item';

        $favorite->delete();
        
        return back()->with('success', $itemName . ' removed from favorites.');
    }
}
