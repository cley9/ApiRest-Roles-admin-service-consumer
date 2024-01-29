<?php

use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ViewController;
// use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::post('/create-user', [AdminLoginController::class, 'createUserAdmin'])->name('create.admin.api');
Route::post('/login', [AdminLoginController::class, 'login'])->name('login.admin.api');
    Route::middleware(['jwt.apiRest.tonarum','jwt.user.admin'])->group(function () {
        Route::get('/perfil', [AdminLoginController::class, 'perfil'])->name('perfil.admin.api');
    
    Route::get('/view-token-user', [AdminLoginController::class, 'viewToken'])->name('viewPerfil.admin.api');
});