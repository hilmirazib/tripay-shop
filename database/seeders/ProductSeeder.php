<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['sku' => 'SKU-QRIS-001', 'name' => 'Voucher QRIS 10K', 'price' => 10000, 'reference' => 'QRIS-10K'],
            ['sku' => 'SKU-BRIVA-002', 'name' => 'Topup BRIVA 25K', 'price' => 25000, 'reference' => 'BRIVA-25K'],
            ['sku' => 'SKU-DANA-003', 'name' => 'Saldo DANA 50K', 'price' => 50000, 'reference' => 'DANA-50K'],
        ];
        foreach ($items as $it) {
            Product::firstOrCreate(['sku' => $it['sku']], $it);
        }
    }
}
