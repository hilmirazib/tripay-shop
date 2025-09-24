<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\InvoiceController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\DashboardController;

Route::get('/', [ProductController::class, 'publicIndex'])->name('home');

// payment
Route::post('/payment/callback', [CheckoutController::class, 'callback'])->name('tripay.callback');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('products', ProductController::class)->except(['show']);

        Route::get('invoices', [InvoiceController::class, 'index'])
            ->name('invoices.index')
            ->middleware('permission:invoice.read');
    });

    // checkout
    Route::get('/checkout/start', [CheckoutController::class, 'start'])->name('checkout.start');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // payment
    Route::get('/payment/return', [CheckoutController::class, 'return'])->name('tripay.return');

    Route::get('/my-invoices', [InvoiceController::class, 'my'])
        ->name('invoices.my')
        ->middleware('permission:invoice.read');
});

require __DIR__ . '/auth.php';
