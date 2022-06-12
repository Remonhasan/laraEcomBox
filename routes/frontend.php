<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Frontend\FrontendController;
use Illuminate\Support\Facades\Request;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
 */


Route::get('/', [LanguageController::class, 'baseURL']);

// Uses for setting up the language.
Route::get('{locale}/' . config('app.frontend_route_prefix'),[LanguageController::class, 'setLocale'] );

Route::get('/{lang?}', [FrontEndController::class, 'home'])->name('frontend.home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';
