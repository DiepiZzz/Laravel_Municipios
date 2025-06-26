<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting(); // Si tienes límite de tasas configurado

        $this->routes(function () {
            // Carga las rutas API
            Route::middleware('api') // Este middleware aplica el prefijo /api por defecto
                ->prefix('api')      // Prefijo 'api' para todas las rutas cargadas aquí
                ->group(base_path('routes/api.php'));

            // Carga las rutas web (para las vistas si las usas)
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        // Puedes añadir tus rate limiters aquí si los necesitas
        // Ejemplo:
        // RateLimiter::for('api', function (Request $request) {
        //     return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        // });
    }
}
