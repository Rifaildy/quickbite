<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        
        // Configure mail for local development
        if ($this->app->environment('local')) {
            $this->app->singleton('mailer', function ($app) {
                $app->configure('mail');
                return $app->loadComponent('mail', 'Illuminate\Mail\MailServiceProvider', 'mailer');
            });
        }
        
        // Add a custom Blade directive for translation
        Blade::directive('t', function ($expression) {
            return "<?php echo __($expression); ?>";
        });
    }
}

