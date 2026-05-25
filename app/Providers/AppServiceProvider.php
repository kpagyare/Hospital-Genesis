<?php

namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Auto-seed on first deployment (runs once when users table is empty)
        if (app()->environment('production')) {
            try {
                if (\App\Models\User::count() === 0) {
                    Artisan::call('db:seed', ['--force' => true]);
                }
            } catch (\Throwable $e) {
                // Database not ready yet — skip silently
            }
        }

        // @can_role(['role1','role2']) ... @endcan_role
        Blade::directive('can_role', function ($roles) {
            return "<?php if(auth()->check() && (auth()->user()->role === 'super_admin' || in_array(auth()->user()->role, $roles))): ?>";
        });
        Blade::directive('endcan_role', function () {
            return '<?php endif; ?>';
        });

        // Share settings globally
        view()->composer('*', function ($view) {
            if (auth()->check()) {
                $view->with('_settings', \App\Models\Setting::first());
            }
        });
    }
}
