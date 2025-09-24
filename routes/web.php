<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\InvoiceController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Web\CheckoutController;

//Homepage publik (list produk)
Route::get('/', [ProductController::class, 'publicIndex'])->name('home');

// checkout
Route::get('/checkout/start', [CheckoutController::class, 'start'])->name('checkout.start'); 
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');  

// payment
Route::get('/payment/return', [CheckoutController::class, 'return'])->name('tripay.return');


Route::get('/welcome', function () {
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

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /**
     * ADMIN AREA
     * Proteksi level role:admin; aksi CRUD juga tetap dijaga oleh Policies & permission middleware di controller
     */
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', fn () => Inertia::render('Admin/Dashboard'))->name('dashboard');

        Route::resource('products', ProductController::class)->except(['show']);

        Route::get('invoices', [InvoiceController::class, 'index'])
            ->name('invoices.index')
            ->middleware('permission:invoice.read');
    });

    Route::get('/my-invoices', [InvoiceController::class, 'my'])
        ->name('invoices.my')
        ->middleware('permission:invoice.read');
});

require __DIR__ . '/auth.php';
