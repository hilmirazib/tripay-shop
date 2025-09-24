<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Product;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\TripayService;

class CheckoutController extends Controller
{
    public function start(Request $request)
    {
        $productId = (int) $request->query('product_id');
        $product = Product::findOrFail($productId);

        $methods = [
            ['code' => 'QRIS', 'name' => 'QRIS'],
            ['code' => 'BNIVA', 'name' => 'BNI Virtual Account'],
            ['code' => 'BRIVA', 'name' => 'BRI Virtual Account'],
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

    public function store(CheckoutRequest $request, TripayService $tripay)
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

        $merchantRef = 'INV-'.now()->format('YmdHis').'-'.$invoice->id;

        $payload = [
            'method'         => (string)$request->string('payment_method'), 
            'merchant_ref'   => $merchantRef,
            'amount'         => (int)$product->price,
            'customer_name'  => 'Customer '.$invoice->id,
            'customer_email' => (string)$request->string('buyer_email'),
            'customer_phone' => (string)$request->string('buyer_phone'),
            'order_items'    => [[
                'sku'         => $product->sku,
                'name'        => $product->name,
                'price'       => (int)$product->price,
                'quantity'    => 1,
            ]],
            'callback_url'   => route('tripay.callback'),
            'return_url'     => route('tripay.return'),
            'expired_time'   => now()->addDay()->timestamp,
        ];

        $result = $tripay->createTransaction($payload);

        if (!($result['http_ok'] ?? false) || !($result['success'] ?? false)) {
            $invoice->update([
                'status' => 'failed_create',
                'raw_response' => $result['body'] ?? null,
                'merchant_ref' => $merchantRef,
            ]);
            return back()->withErrors(['checkout' => 'Gagal membuat transaksi (HTTP '.$result['status'].'): '.json_encode($result['body'])]);
        }

        if (!($result['success'] ?? false)) {
            $invoice->update([
                'status' => 'failed_create',
                'raw_response' => $result['body'] ?? null,
                'merchant_ref' => $merchantRef,
            ]);
            return back()->withErrors(['checkout' => $result['message'] ?? 'Gagal membuat transaksi TriPay']);
        }

        $data = $result['data'] ?? [];
        $invoice->update([
            'tripay_reference' => $data['reference'] ?? null,
            'merchant_ref'     => $data['merchant_ref'] ?? $merchantRef,
            'checkout_url'     => $data['checkout_url'] ?? null,
            'status'           => $data['status'] ?? 'UNPAID', // TriPay mengembalikan "UNPAID"
            'raw_response'     => $result['body'] ?? null,
        ]);

        if (!empty($data['checkout_url'])) {
            return Inertia::location($data['checkout_url']); // <<<
        }

        return redirect()->route('home')->with('success', 'Transaksi dibuat, reference: '.$invoice->tripay_reference);
    }

    public function return(Request $request)
    {
        return Inertia::render('Checkout/Return', [
            'message' => 'Anda kembali dari halaman pembayaran. Status transaksi akan diperbarui setelah callback.',
        ]);
    }

    public function callback(Request $request)
    {
        $payload = $request->all();
        $signature = $payload['signature'] ?? null;
        $merchantRef = $payload['merchant_ref'] ?? null;
        $amount = $payload['amount'] ?? null;
        $status = $payload['status'] ?? null;

        // 1) Verify signature
        $expected = hash_hmac(
            'sha256',
            config('tripay.merchant_code') . $merchantRef . $amount,
            config('tripay.private_key')
        );

        if ($signature !== $expected) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $invoice = Invoice::where('merchant_ref', $merchantRef)->first();
        if ($invoice) {
            $invoice->update([
                'status' => $status, 
                'raw_response' => $payload,
            ]);
        }

        return response()->json(['success' => true]);
    }
}
