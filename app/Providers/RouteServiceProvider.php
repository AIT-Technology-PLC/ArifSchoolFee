<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            // Auth Controllers
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // Api & Invokable Controllers
            Route::middleware(['web', 'auth', 'isEmployeeEnabled'])
                ->group(base_path('routes/other.php'));

            // Action Controllers
            Route::middleware(['web', 'auth', 'isEmployeeEnabled'])
                ->group(base_path('routes/action.php'));

            // Resource Controllers
            Route::middleware(['web', 'auth', 'isEmployeeEnabled'])
                ->group(base_path('routes/resource.php'));

            // Report Controllers
            Route::middleware(['web', 'auth', 'isEmployeeEnabled'])
                ->group(base_path('routes/reports.php'));

            // Admin Controllers
            Route::middleware(['web', 'auth', 'isAdmin'])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));

            // Service Center Controllers
            Route::middleware(['web', 'auth', 'isServiceCenter'])
                ->prefix('srervice-centers')
                ->name('service-centers.')
                ->group(base_path('routes/servicecenter.php'));
        });

    }
}
