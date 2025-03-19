<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
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
     * Display the settings page.
     */
    public function index()
    {
        $settings = [
            'app_name' => config('app.name'),
            'app_url' => config('app.url'),
            'app_timezone' => config('app.timezone'),
            'mail_from_address' => config('mail.from.address'),
            'mail_from_name' => config('mail.from.name'),
        ];
        
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update application settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'app_name' => ['required', 'string', 'max:255'],
            'app_url' => ['required', 'url'],
            'app_timezone' => ['required', 'string'],
            'mail_from_address' => ['required', 'email'],
            'mail_from_name' => ['required', 'string', 'max:255'],
        ]);
        
        // This is a simplified version. In a real application, you would update the .env file
        // or use a settings table in the database.
        
        // For demonstration purposes, we'll just show a success message
        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }

    /**
     * Clear application cache.
     */
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            Artisan::call('config:clear');
            
            return redirect()->route('admin.settings.index')
                ->with('success', 'Application cache cleared successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.index')
                ->with('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }

    /**
     * Show system information.
     */
    public function systemInfo()
    {
        $phpVersion = PHP_VERSION;
        $laravelVersion = app()->version();
        $serverSoftware = $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown';
        $databaseType = DB::connection()->getPdo()->getAttribute(\PDO::ATTR_DRIVER_NAME);
        $databaseVersion = DB::select('SELECT version()')[0]->{'version()'};
        
        return view('admin.settings.system-info', compact(
            'phpVersion',
            'laravelVersion',
            'serverSoftware',
            'databaseType',
            'databaseVersion'
        ));
    }
}

