<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
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
     * Display the user's profile.
     */
    public function index()
    {
        $user = Auth::user();
        
        $totalOrders = $user->orders()->count();
        $lastOrder = $user->orders()->latest()->first();
        
        // Get user preferences (in a real app, this would be stored in a separate table)
        $preferences = $user->preferences ?? [
            'email_notifications' => true,
            'order_updates' => true,
            'promotional_emails' => false,
        ];
        
        return view('buyer.profile.index', compact('totalOrders', 'lastOrder', 'preferences'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();
        
        return redirect()->route('buyer.profile.index')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the user's preferences.
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        
        // In a real app, you would store these preferences in a separate table
        $preferences = [
            'email_notifications' => $request->input('preferences.email_notifications', false) ? true : false,
            'order_updates' => $request->input('preferences.order_updates', false) ? true : false,
            'promotional_emails' => $request->input('preferences.promotional_emails', false) ? true : false,
        ];
        
        // For this example, we'll just pretend we saved them
        // $user->preferences = $preferences;
        // $user->save();
        
        return redirect()->route('buyer.profile.index')
            ->with('success', 'Preferences updated successfully.');
    }
}

