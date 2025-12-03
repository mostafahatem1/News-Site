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
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';
    public const ADMIN = '/admin/index';



    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
       $this->configureRateLimiting();


        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        });
    }


    protected function configureRateLimiting()
    {

        // Limiter login
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip())
                ->response(fn() => api_response('Too many login attempts. Try again later in 60 seconds.', 429));
        });

        // Limiter register
        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip())
                ->response(fn() => api_response('Too many register attempts. Try again later.', 429));
        });

        // Limiter forgot/reset password
        RateLimiter::for('password', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip())
                ->response(fn() => api_response('Too many password reset attempts. Try again later.', 429));
        });

        // Limiter contact us
        RateLimiter::for('contact', function (Request $request) {
            return Limit::perMinute(1)->by($request->ip())
                ->response(fn() => api_response('Too many contact requests. Try again later.', 429));
        });

        // Limiter API
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip())
                ->response(fn() => api_response('Too many requests. Slow down!', 429));
        });


        // Limiter authenticated user routes
        RateLimiter::for('user_api', function (Request $request) {
            return Limit::perMinute(20) // 60
                ->by(optional($request->user())->id ?: $request->ip())
                ->response(fn() => api_response('Too many requests, please slow down!', 429));
        });
    }
}
