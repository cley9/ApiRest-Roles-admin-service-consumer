<?php

use App\Http\Controllers\User_consumer\LoginController as User_consumerLoginController;
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
Route::post('/create-user', [User_consumerLoginController::class, 'createUser'])->name('create.user.api');
Route::post('/login', [User_consumerLoginController::class, 'loginLocalUser'])->name('login.user.api');
    Route::middleware(['jwt.apiRest.tonarum','jwt.user.consumer'])->group(function () {
    Route::get('/perfil', [User_consumerLoginController::class, 'perfil'])->name('perfil.consumer.api');
    
    Route::get('/view-token-user', [User_consumerLoginController::class, 'viewToken'])->name('viewPerfil.user.api');
});