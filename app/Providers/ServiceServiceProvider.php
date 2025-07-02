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
        
        $this->app->bind(UserServiceInterface::class, UserService::class);

        
        $this->app->bind(MunicipioServiceInterface::class, MunicipioService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
    }
}
