<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Expense;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('expense.member')->latest()->get();
        return view('invoice.index', compact('invoices'));
    }

    public function show($id)
    {
        $invoice = Invoice::where('id', $id)
            ->orWhere('invoice_number', $id)
            ->with('expense.member')
            ->firstOrFail();
        return view('invoice.show', compact('invoice'));
    }

    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load('expense.member');
        
        // Return printable HTML view - user can use browser's Print to PDF
        return view('invoice.pdf', compact('invoice'));
    }
}
