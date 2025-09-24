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
        $invoices = Invoice::with('product')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Invoices/Index', [
            'invoices' => $invoices,
        ]);
    }

    public function my(Request $request)
    {
        $email = $request->user()->email;
        $invoices = Invoice::with('product')
            ->where('buyer_email', $email)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Invoices/My', [
            'invoices' => $invoices,
        ]);
    }
}
