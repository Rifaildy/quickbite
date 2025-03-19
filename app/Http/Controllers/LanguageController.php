<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Change the application language.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch(Request $request, $locale)
    {
        // Validate if the locale is supported
        if (!in_array($locale, ['en', 'id'])) {
            $locale = 'en';
        }
        
        // Store the locale in session
        Session::put('locale', $locale);
        
        // Redirect back to the previous page
        return redirect()->back();
    }
}

