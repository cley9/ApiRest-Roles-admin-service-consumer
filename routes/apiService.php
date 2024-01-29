<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\User_service\LoginController as User_serviceLoginController;
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
Route::post('/create-user', [User_serviceLoginController::class, 'createUser'])->name('create.user.api');
Route::post('/login', [User_serviceLoginController::class, 'loginLocalUser'])->name('login.user.api');
    Route::middleware(['jwt.apiRest.tonarum','jwt.user.service'])->group(function () {
        Route::get('/perfil', [User_serviceLoginController::class, 'perfil'])->name('perfil.service.api');
    
        Route::get('/view-token-user', [User_serviceLoginController::class, 'viewToken'])->name('viewPerfil.user.api');
});