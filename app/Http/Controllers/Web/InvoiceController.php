<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $statusIn = strtoupper(trim((string) $request->query('status', '')));
        $method = strtoupper(trim((string) $request->query('method', '')));
        $from = $request->query('from');
        $to = $request->query('to');

        $statusMap = [
            'PENDING' => ['UNPAID', 'pending_local', 'PENDING'],
            'UNPAID' => ['UNPAID'],
            'PAID' => ['PAID'],
            'FAILED' => ['FAILED'],
            'EXPIRED' => ['EXPIRED'],
        ];
        $statusList = $statusIn !== '' ? $statusMap[$statusIn] ?? [$statusIn] : [];

        $fromDate = $this->toDate($from);
        $toDate = $this->toDate($to);
        if ($fromDate && $toDate && $fromDate > $toDate) {
            [$fromDate, $toDate] = [$toDate, $fromDate];
        }

        $invoices = Invoice::query()
            ->with('product')
            ->when($q !== '', function ($qb) use ($q) {
                $qb->where(function ($w) use ($q) {
                    $w->where('tripay_reference', 'like', "%{$q}%")
                        ->orWhere('merchant_ref', 'like', "%{$q}%")
                        ->orWhere('buyer_email', 'like', "%{$q}%")
                        ->orWhere('buyer_phone', 'like', "%{$q}%")
                        ->orWhereHas('product', function ($p) use ($q) {
                            $p->where('name', 'like', "%{$q}%")->orWhere('sku', 'like', "%{$q}%");
                        });
                });
            })
            ->when(!empty($statusList), fn($qb) => $qb->whereIn('status', $statusList))
            ->when($method !== '', fn($qb) => $qb->where('payment_method', $method))
            ->when($fromDate, fn($qb) => $qb->whereDate('created_at', '>=', $fromDate))
            ->when($toDate, fn($qb) => $qb->whereDate('created_at', '<=', $toDate))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Invoices/Index', [
            'invoices' => $invoices,
        ]);
    }

    private function toDate(?string $val): ?string
    {
        if (!$val) {
            return null;
        }
        try {
            return \Illuminate\Support\Carbon::createFromFormat('Y-m-d', $val)->toDateString();
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function my(Request $request)
    {
        $user = $request->user();

        $invoices = Invoice::with('product')
            ->where(function ($q) use ($user) {
                $q->where('buyer_user_id', $user->id)            // primary link
                    ->orWhere('buyer_email', $user->email);        // fallback utk data lama
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Invoices/My', [
            'invoices' => $invoices,
        ]);
    }
}
