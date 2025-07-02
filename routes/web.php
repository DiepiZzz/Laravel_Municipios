<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MunicipioController;

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


Route::get('/', function () {
    return view('welcome');
});


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);


Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');



Route::middleware('auth:web')->group(function () {
    
    Route::get('/municipios', [MunicipioController::class, 'index'])->name('municipios.index');
    Route::get('/municipios/create', [MunicipioController::class, 'create'])->name('municipios.create');
    Route::post('/municipios/guardar', [MunicipioController::class, 'store'])->name('municipios.store');

    
    Route::get('/municipios/{id}/edit', [MunicipioController::class, 'edit'])->name('municipios.edit');
    Route::put('/municipios/{id}', [MunicipioController::class, 'update'])->name('municipios.update');
    Route::delete('/municipios/{id}', [MunicipioController::class, 'destroy'])->name('municipios.destroy');

    
    Route::get('/municipios/graficas', [MunicipioController::class, 'showGraficas'])->name('municipios.graficas');

    
    Route::post('/municipios/graficas/pdf', [MunicipioController::class, 'downloadGraficasPdf'])->name('municipios.downloadPdf');

    
    
    
    
});
