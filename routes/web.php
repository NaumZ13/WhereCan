<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Route::resource('/products', ProductController::class);
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/search-products', [ProductController::class, 'search'])->name('products.search');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    
    // Route::get('/dashboard', function () {
    //     return Inertia::render('Clients/Dashboard');
    // })->name('clients.dashboard');
});

Route::middleware(['client'])->prefix('client')->group(function () {
    Route::get('/dashboard', [ClientController::class, 'index'])->name('clients.dashboard');
});