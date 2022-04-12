<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('admin')->group(function () {

     // Register
     Route::get('/register', [AdminController::class, 'registerForm'])->name('register.form');
     Route::post('/register/create', [AdminController::class, 'register'])->name('admin.register');
    
    // Login
    Route::get('/login', [AdminController::class, 'loginForm'])->name('login.form');
    Route::post('/login/owner', [AdminController::class, 'login'])->name('admin.login');

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard')->middleware('admin');
    // Logout
    Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout')->middleware('admin');

});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';
