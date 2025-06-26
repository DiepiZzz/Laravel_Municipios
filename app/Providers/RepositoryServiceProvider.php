<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\UserRepositoryInterface; // Importa la interfaz de Usuario
use App\Repositories\UserRepository;     // Importa la implementación de Usuario
use App\Interfaces\MunicipioRepositoryInterface; // Importa la interfaz de Municipio
use App\Repositories\MunicipioRepository;     // Importa la implementación de Municipio

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Enlazar la interfaz UserRepositoryInterface a su implementación UserRepository
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        // Enlazar la interfaz MunicipioRepositoryInterface a su implementación MunicipioRepository
        $this->app->bind(MunicipioRepositoryInterface::class, MunicipioRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
