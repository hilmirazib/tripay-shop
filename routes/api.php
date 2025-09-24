<?php
use App\Http\Controllers\Web\CheckoutController;
Route::post('/payment/callback', [CheckoutController::class, 'callback']);
