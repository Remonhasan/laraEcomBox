<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Admin\CategoryController;

// Admin Routes Start

Route::get('{locale}/' . config('app.admin_route_prefix'), [LanguageController::class,'setLocale']);

// Admin Auth Routes Start
Route::group(
    ['prefix' => Request::segment(1)],
    function () {

        // Admin Register
        Route::get('/admin-register', [AdminController::class, 'registerForm'])->name('register.form');
        Route::post('/register/create', [AdminController::class, 'register'])->name('admin.register');

        // Admin Login
        Route::get('/admin-login', [AdminController::class, 'loginForm'])->name('login.form');
        Route::post('/login/owner', [AdminController::class, 'login'])->name('admin.login');
    });

    // Admin Auth Routes End 

Route::prefix(Request::segment(1) . config('app.admin_route_prefix'))->middleware(['locale', 'admin'])->group(
    function () {

        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        // Logout
        Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');

        Route::prefix('category')->group(function () {
            Route::get('/list',[CategoryController::class,'index'])->name('category.list');
            Route::get('/create',[CategoryController::class,'create'])->name('category.create');
        });
    });

// Admin Routes End