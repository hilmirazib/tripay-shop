<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'product_id',
        'tripay_reference',
        'merchant_ref',
        'buyer_user_id',
        'buyer_email',
        'buyer_phone',
        'payment_method',
        'amount',
        'status',
        'checkout_url',
        'raw_response'
    ];

    protected $casts = [
        'raw_response' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_user_id');
    }
}
