<?php

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

Route::get('/', function () {
    return view('home');
});

Route::get('/token',[ViewController::class, 'token'])->name('token.admin');
Route::get('login/google', [LoginController::class, 'loginGoogle'])->name('login.user.index');
Route::get('login/google/callback', [LoginController::class, 'callback']);
Route::post('/createUserAdmin', [LoginController::class, 'createUserAdmin'])->name('create.admin.main');
Route::post('/loginAdmin', [LoginController::class, 'loginAdmin'])->name('loginAdmin.admin.main');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout.user.index');
Route::post('/createUser', [LoginController::class, 'createUser'])->name('create.user.index');
Route::get('/validarUser/{email}', [LoginController::class, 'validarUser'])->name('validar.user.index');
// Route::post('/loginLocalUser', [LoginController::class, 'loginLocalUser'])->name('loginLocalUser.user.index');
Route::post('/loginLocalUser', [LoginController::class, 'loginLocalUser'])->name('loginLocalUser.user.index');
Route::get('user', [LoginController::class, 'indexHome'])->name('loginUser.proceso.index');
// Route::get('/viewTokenUser', [LoginController::class, 'viewToken'])->name('aloginUser.proceso.index');

Route::group(['prefix'=>'cley','middleware'=>['jwt.user.consume','jwt.user.service']],function () {
    // Route::middleware(['jwt.user.consume'])->group(function () {
Route::get('/viewTokenUser', [LoginController::class, 'viewToken'])->name('aloginUser.proceso.index');
    
});