<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\UserRepositoryInterface; 
use App\Repositories\UserRepository;     
use App\Interfaces\MunicipioRepositoryInterface; 
use App\Repositories\MunicipioRepository;     

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        
        $this->app->bind(MunicipioRepositoryInterface::class, MunicipioRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
    }
}
