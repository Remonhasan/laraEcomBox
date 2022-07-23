<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubCategoryController;

// Admin Routes Start

Route::get('{locale}/' . config('app.admin_route_prefix'), [LanguageController::class, 'setLocale']);

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

         // SLIDER ROUTE START
         Route::prefix('slider')->group(function () {
            Route::get('/list', [SliderController::class, 'index'])->name('slider.list');
            Route::get('/create', [SliderController::class, 'create'])->name('slider.create');
            Route::post('/store', [SliderController::class, 'store'])->name('slider.store');
            Route::get('/edit/{sliderId?}', [SliderController::class, 'edit'])->name('slider.edit');
            Route::put('/update/{sliderId?}', [SliderController::class, 'update'])->name('slider.update');
            Route::delete('/delete/{sliderId?}', [SliderController::class, 'delete'])->name('slider.delete');
        });
        // SLIDER ROUTE END

        // CATEGORY ROUTE START
        Route::prefix('category')->group(function () {
            Route::get('/list', [CategoryController::class, 'index'])->name('category.list');
            Route::get('/create', [CategoryController::class, 'create'])->name('category.create');
            Route::post('/store', [CategoryController::class, 'store'])->name('category.store');
            Route::get('/edit/{categoryId?}', [CategoryController::class, 'edit'])->name('category.edit');
            Route::put('/update/{categoryId?}', [CategoryController::class, 'update'])->name('category.update');
            Route::delete('/delete/{categoryId?}', [CategoryController::class, 'delete'])->name('category.delete');
        });
        // CATEGORY ROUTE END

        // SUBCATEGORY ROUTE START
        Route::prefix('subcategory')->group(function () {
            Route::get('/list', [SubCategoryController::class, 'index'])->name('subcategory.list');
            Route::get('/create', [SubCategoryController::class, 'create'])->name('subcategory.create');
            Route::post('/store', [SubCategoryController::class, 'store'])->name('subcategory.store');
            Route::get('/edit/{subcategoryId?}', [SubCategoryController::class, 'edit'])->name('subcategory.edit');
            Route::put('/update/{subcategoryId?}', [SubCategoryController::class, 'update'])->name('subcategory.update');
            Route::delete('/delete/{subcategoryId?}', [SubCategoryController::class, 'delete'])->name('subcategory.delete');
        });
        // SUBCATEGORY ROUTE END

        // PROFUCT ROUTE START
        Route::prefix('product')->group(function () {
            Route::get('/list', [ProductController::class, 'index'])->name('product.list');
            Route::get('/create', [ProductController::class, 'create'])->name('product.create');
            Route::post('/store', [ProductController::class, 'store'])->name('product.store');
            Route::get('/edit/{productId?}', [ProductController::class, 'edit'])->name('product.edit');
            Route::put('/update/{productId?}', [ProductController::class, 'update'])->name('product.update');
            Route::delete('/delete/{productId?}', [ProductController::class, 'delete'])->name('product.delete');
        });
        // PROFUCT ROUTE START
    });

// Admin Routes End
