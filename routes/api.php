<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\MunicipioController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí es donde puedes registrar las rutas API para tu aplicación. Estas
| rutas son cargadas por el RouteServiceProvider dentro de un grupo que
| es asignado al grupo de middleware "api". ¡Disfruta construyendo tu API!
|
| Por defecto, todas las rutas aquí tienen el prefijo '/api'.
|
*/

// =========================================================================
// API Version 1 (v1)
// =========================================================================

Route::prefix('v1')->group(function () {

    // ---------------------------------------------------------------------
    // Rutas públicas o de autenticación (sin protección de Sanctum por defecto)
    // ---------------------------------------------------------------------

    // Rutas para municipios que no requieren autenticación (ej. listar todos, ver uno)
    Route::get('municipios', [MunicipioController::class, 'index']);
    Route::get('municipios/{id}', [MunicipioController::class, 'show']);

    // NUEVA RUTA: Rutas para usuarios que no requieren autenticación (solo listar y mostrar)
    // ESTO HACE QUE GET /api/v1/usuarios y GET /api/v1/usuarios/{id} sean públicos
    Route::get('usuarios', [UsuarioController::class, 'index']);
    Route::get('usuarios/{id}', [UsuarioController::class, 'show']);


    // ---------------------------------------------------------------------
    // Rutas protegidas por autenticación (Sanctum)
    // ---------------------------------------------------------------------
    Route::middleware('auth:sanctum')->group(function () {

        // Rutas del Usuario (solo store, update, destroy están aquí, ya que index y show son públicos)
        Route::apiResource('usuarios', UsuarioController::class)->except(['index', 'show']);


        // Rutas del Municipio (solo store, update, destroy están aquí, ya que index y show son públicos)
        Route::apiResource('municipios', MunicipioController::class)->except(['index', 'show']);


        // Ruta para obtener el usuario autenticado actualmente
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

    }); // Fin del grupo 'auth:sanctum'

}); // Fin del grupo 'v1'
