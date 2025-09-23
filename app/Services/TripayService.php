<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TripayService
{
    public function __construct()
    {
        $this->base = config('tripay.base');
        $this->apiKey = config('tripay.api_key');
        $this->privateKey = config('tripay.private_key');
        $this->merchantCode = config('tripay.merchant_code');
    }

    /**
     * Create transaction to TriPay
     * @param array $payload
     * Required keys: method, merchant_ref, amount, customer_name, customer_email, customer_phone, order_items[], callback_url, return_url, expired_time
     */
    public function createTransaction(array $payload): array
    {
        // Signature HMAC-SHA256: hash_hmac('sha256', merchantCode + merchantRef + amount, privateKey)
        $signature = hash_hmac(
            'sha256',
            $this->merchantCode . $payload['merchant_ref'] . $payload['amount'],
            $this->privateKey
        );

        $data = array_merge($payload, [
            'signature' => $signature,
        ]);

        $url = rtrim($this->base, '/') . config('tripay.create_path');

        $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])
            ->asForm() 
            ->post($url, $data);

        if (!$response->ok()) {
            return [
                'http_ok' => false,
                'status' => $response->status(),
                'body' => $response->json(),
            ];
        }

        $json = $response->json();
        return [
            'http_ok' => true,
            'body' => $json,
            'success' => (bool)($json['success'] ?? false),
            'data' => $json['data'] ?? null,
            'message' => $json['message'] ?? null,
        ];
    }
}
