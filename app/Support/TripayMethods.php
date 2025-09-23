<?php

namespace App\Support;

final class TripayMethods
{
    public const ALLOWED = [
        'QRIS', 'BNIVA', 'MANDIRIVA', 'BRIVA', 'DANA', 'OVO', 'SHOPEEPAY',
    ];

    public static function codes(): array
    {
        return self::ALLOWED;
    }
}
