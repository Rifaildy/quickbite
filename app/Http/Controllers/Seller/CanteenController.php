<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Canteen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CanteenController extends Controller
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
     * Display the seller's canteen.
     */
    public function index()
    {
        $canteen = Auth::user()->canteen;
        
        if (!$canteen) {
            return redirect()->route('seller.canteens.create')
                ->with('info', 'Please create your canteen first.');
        }
        
        return view('seller.canteens.index', compact('canteen'));
    }

    /**
     * Show the form for creating a new canteen.
     */
    public function create()
    {
        // Check if seller already has a canteen
        if (Auth::user()->canteen) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'You already have a canteen.');
        }

        return view('seller.canteens.create');
    }

    /**
     * Store a newly created canteen in storage.
     */
    public function store(Request $request)
    {
        // Check if seller already has a canteen
        if (Auth::user()->canteen) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'You already have a canteen.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $canteen = Canteen::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => Auth::id(),
            'status' => true,
        ]);

        return redirect()->route('seller.dashboard')
            ->with('success', 'Canteen created successfully.');
    }

    /**
     * Show the form for editing the canteen.
     */
    public function edit(Canteen $canteen)
    {
        // Check if the canteen belongs to the authenticated seller
        if ($canteen->user_id !== Auth::id()) {
            return abort(403, 'Unauthorized action.');
        }

        return view('seller.canteens.edit', compact('canteen'));
    }

    /**
     * Update the specified canteen in storage.
     */
    public function update(Request $request, Canteen $canteen)
    {
        // Check if the canteen belongs to the authenticated seller
        if ($canteen->user_id !== Auth::id()) {
            return abort(403, 'Unauthorized action.');
        }

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

        return redirect()->route('seller.dashboard')
            ->with('success', 'Canteen updated successfully.');
    }
}

