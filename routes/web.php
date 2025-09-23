<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\InvoiceController;
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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // admin
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', fn() => Inertia::render('Admin/Dashboard'))
            ->name('dashboard');

        // contoh CRUD products (lanjut M2)
        Route::resource('products', ProductController::class);

        // lihat semua invoice
        Route::get('invoices', [InvoiceController::class, 'index'])
            ->name('invoices.index')
            ->middleware('permission:invoice.read');
    });

    // customer invoice
    Route::get('/my-invoices', [InvoiceController::class, 'my'])
        ->name('invoices.my')
        ->middleware('permission:invoice.read');
});

require __DIR__ . '/auth.php';
