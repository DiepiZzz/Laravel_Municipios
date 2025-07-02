<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\UserServiceInterface;
use App\Services\UserService;
use App\Interfaces\MunicipioServiceInterface;
use App\Services\MunicipioService;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Enlazar la interfaz UserServiceInterface a su implementación UserService
        $this->app->bind(UserServiceInterface::class, UserService::class);

        // Enlazar la interfaz MunicipioServiceInterface a su implementación MunicipioService
        $this->app->bind(MunicipioServiceInterface::class, MunicipioService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
