<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; // Importa tu AuthController

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Ruta para la página de bienvenida
Route::get('/', function () {
    return view('welcome');
});

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas de registro
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Rutas de recuperación de contraseña (flujo completo)
// 1. Muestra el formulario para solicitar el restablecimiento de contraseña (ingresar email)
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
// 2. Procesa el envío del formulario y envía el email de restablecimiento
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
// 3. Muestra el formulario para restablecer la contraseña con el token (el enlace del email)
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
// 4. Procesa el restablecimiento de la contraseña (cuando el usuario envía la nueva contraseña)
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');


// Ruta de ejemplo para el dashboard (a donde se redirige después de login/registro exitoso)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard'); // Necesitarás crear esta vista (resources/views/dashboard.blade.php)
    })->name('dashboard');
});
