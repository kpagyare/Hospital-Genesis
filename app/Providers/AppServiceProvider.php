<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
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
