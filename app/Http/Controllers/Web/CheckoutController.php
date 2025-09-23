<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Product;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function start(Request $request)
    {
        $productId = (int) $request->query('product_id');
        $product = Product::findOrFail($productId);

        $methods = [
            ['code' => 'QRIS', 'name' => 'QRIS'],
            ['code' => 'BNIVA', 'name' => 'BNI Virtual Account'],
            ['code' => 'MANDIRIVA', 'name' => 'Mandiri VA'],
            ['code' => 'DANA', 'name' => 'DANA'],
            ['code' => 'OVO', 'name' => 'OVO'],
            ['code' => 'SHOPEEPAY', 'name' => 'ShopeePay'],
        ];

        return Inertia::render('Checkout/Methods', [
            'product' => $product,
            'methods' => $methods,
        ]);
    }

    public function store(CheckoutRequest $request)
    {
        $product = Product::findOrFail($request->integer('product_id'));

        $invoice = Invoice::create([
            'product_id'     => $product->id,
            'buyer_email'    => $request->string('buyer_email'),
            'buyer_phone'    => $request->string('buyer_phone'),
            'payment_method' => $request->string('payment_method'),
            'amount'         => $product->price,
            'status'         => 'pending_local',
        ]);

        return redirect()
            ->route('home')
            ->with('success', 'Draft invoice dibuat. Lanjutkan pembayaran di langkah berikutnya.');
    }
}
