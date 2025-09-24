<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('admin.dashboard.stats', 60, function () {
            $totalProducts = Product::count();
            $totalInvoices = Invoice::count();
            $paidCount     = Invoice::where('status', 'PAID')->count();
            $pendingCount  = Invoice::whereIn('status', ['UNPAID', 'pending_local'])->count();

            return [
                ['label' => 'Total Produk',         'value' => $totalProducts, 'href' => '/admin/products'],
                ['label' => 'Total Invoice',        'value' => $totalInvoices, 'href' => '/admin/invoices'],
                ['label' => 'Transaksi Berhasil',   'value' => $paidCount,     'href' => '/admin/invoices?status=PAID'],
                ['label' => 'Transaksi Pending',    'value' => $pendingCount,  'href' => '/admin/invoices?status=UNPAID'],
            ];
        });

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
        ]);
    }
}
