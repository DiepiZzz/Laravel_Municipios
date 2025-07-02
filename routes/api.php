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
// Se recomienda versionar las APIs para facilitar futuras expansiones sin romper compatibilidad.
// Todas las rutas dentro de este grupo tendrán el prefijo '/api/v1'.
// =========================================================================

Route::prefix('v1')->group(function () {

    // ---------------------------------------------------------------------
    // Rutas públicas o de autenticación (sin protección de Sanctum por defecto)
    // Ejemplos: login, registro de usuarios, información pública de municipios.
    // ---------------------------------------------------------------------

    // Ejemplo de ruta para registrar un nuevo usuario (si lo implementas)
    // Route::post('/register', [AuthController::class, 'register']);
    // Ejemplo de ruta para iniciar sesión (si lo implementas)
    // Route::post('/login', [AuthController::class, 'login']);

    // Rutas para municipios que no requieren autenticación (ej. listar todos, ver uno)
    // Podrías decidir que la visualización de municipios es pública
    Route::get('municipios', [MunicipioController::class, 'index']);
    Route::get('municipios/{id}', [MunicipioController::class, 'show']);


    // ---------------------------------------------------------------------
    // Rutas protegidas por autenticación (Sanctum)
    // Aquí se agrupan las rutas que requieren que el usuario esté autenticado.
    // El middleware 'auth:sanctum' verifica el token de acceso.
    // ---------------------------------------------------------------------
    Route::middleware('auth:sanctum')->group(function () {

        // Rutas del Usuario
        // resource API crea automáticamente rutas para index, store, show, update, destroy
        // POST /api/v1/usuarios (Crear Usuario)
        // GET /api/v1/usuarios (Listar Usuarios)
        // GET /api/v1/usuarios/{id} (Mostrar Usuario)
        // PUT/PATCH /api/v1/usuarios/{id} (Actualizar Usuario)
        // DELETE /api/v1/usuarios/{id} (Eliminar Usuario)
        Route::apiResource('usuarios', UsuarioController::class)->except(['index', 'show']); // Los metodos index y show ya estan arriba para ser publicos
        // Si quisieras que el index y show de usuarios tambien estuvieran protegidos,
        // simplemente usa Route::apiResource('usuarios', UsuarioController::class);
        // y elimina las lineas de Route::get('usuarios', ...) en la seccion publica.


        // Rutas del Municipio (métodos que implican cambios: crear, actualizar, eliminar)
        // Solo los usuarios autenticados pueden crear, actualizar o eliminar municipios.
        // POST /api/v1/municipios
        // PUT/PATCH /api/v1/municipios/{id}
        // DELETE /api/v1/municipios/{id}
        Route::apiResource('municipios', MunicipioController::class)->except(['index', 'show'])->names('api.municipios'); // <--- ¡CAMBIO APLICADO AQUÍ!


        // Ruta para obtener el usuario autenticado actualmente
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        // ---------------------------------------------------------------------
        // Ejemplo de rutas con Rate Limiting (limitación de peticiones)
        // Puedes aplicar límites a grupos específicos de rutas.
        // Necesitas configurar el rate limiter 'api' en app/Providers/RouteServiceProvider.php
        // ---------------------------------------------------------------------
        Route::middleware('throttle:api')->group(function () {
            // Ejemplo: Ruta que solo puede ser accedida 60 veces por minuto por IP
            // Route::get('/some-limited-resource', function () {
            //     return response()->json(['message' => 'Este recurso tiene límite de peticiones.']);
            // });
        });

    }); // Fin del grupo 'auth:sanctum'

}); // Fin del grupo 'v1'

// =========================================================================
// API Version 2 (ejemplo futuro)
// Cuando tu API evolucione, puedes añadir nuevas versiones aquí
// =========================================================================
/*
Route::prefix('v2')->group(function () {
    // Nuevas rutas o rutas modificadas para la versión 2
    // Route::apiResource('nuevos-recursos', NewController::class);
});
*/
