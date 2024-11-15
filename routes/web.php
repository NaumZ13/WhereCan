<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientProductsController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Models\Product;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegisterClient' => Route::has('client.register'),
        'canRegister' => Route::has('register'),
    ]);
});

// Route::resource('/products', ProductController::class);
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/fetch-products', [ProductController::class, 'fetchFilteredProducts'])->name('products.search');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Route::get('/dashboard', function () {
    //     return Inertia::render('Clients/Dashboard');
    // })->name('clients.dashboard');

    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('review.store');
    Route::put('/products/reviews/{review}', [ReviewController::class, 'update'])->name('review.update');
    Route::delete('/products/reviews/{review}', [ReviewController::class, 'destroy'])->name('review.destroy');

    Route::post('/likes/{type}/{id}', [LikeController::class, 'store'])->name('likes.store');
    Route::delete('/likes/{type}/{id}', [LikeController::class, 'destroy'])->name('likes.destroy');
});

Route::middleware(['auth','client'])->prefix('client')->group(function () {
    Route::get('/dashboard', [ClientController::class, 'index'])->name('client.dashboard');

    Route::get('/products/getProducts', [ClientProductsController::class, 'getProducts'])->name('client.products.getProducts');
    Route::get('/products', [ClientProductsController::class, 'index'])->name('client.products.index');
    Route::get('/products/create', [ClientProductsController::class, 'create'])->name('client.products.create')->can('create', Product::class);
    Route::post('/products', [ClientProductsController::class, 'store'])->name('client.products.store');
    Route::get('/products/{product}', [ClientProductsController::class, 'edit'])->name('client.products.edit');
    Route::put('/products/{product}', [ClientProductsController::class, 'update'])->name('client.products.update');
    Route::delete('/products/{product}', [ClientProductsController::class, 'destroy'])->name('client.products.destroy');
    Route::put('/products/{product}/unpublish', [ClientProductsController::class, 'unpublish'])->name('client.products.unpublish');
    Route::put('/products/{product}/publish', [ClientProductsController::class, 'publish'])->name('client.products.publish');
});


require __DIR__.'/fortify.php';