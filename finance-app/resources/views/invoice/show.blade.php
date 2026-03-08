<x-layout>
    <x-slot:title>Invoice {{ $invoice->invoice_number }}</x-slot>
    <x-slot:actions>
        <div class="flex gap-2">
            <button onclick="window.print()" class="bg-white border border-slate-300 text-slate-700 px-4 py-2 rounded-md text-sm font-medium flex items-center gap-2 hover:bg-slate-50">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6z"/></svg>
                Print
            </button>
            <a href="{{ route('invoice.pdf', $invoice) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                Download PDF
            </a>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto bg-white border border-slate-200 rounded-lg shadow-sm p-8 print:shadow-none print:border-0">
        <!-- Header -->
        <div class="border-b border-slate-200 pb-6 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">INVOICE</h1>
                    <p class="text-slate-500 mt-1">{{ $invoice->invoice_number }}</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-slate-900">{{ $invoice->org_name }}</p>
                    <p class="text-sm text-slate-500 mt-1">{{ $invoice->expense->date->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="space-y-4 mb-8">
            <div class="flex justify-between py-2 border-b border-slate-100">
                <span class="text-slate-500">Category</span>
                <span class="font-medium text-slate-900">{{ $invoice->expense->category }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-slate-100">
                <span class="text-slate-500">Responsible</span>
                <span class="font-medium text-slate-900">{{ $invoice->expense->member->name }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-slate-100">
                <span class="text-slate-500">Description</span>
                <span class="font-medium text-slate-900 text-right max-w-xs">{{ $invoice->expense->description ?? '-' }}</span>
            </div>
        </div>

        <!-- Total -->
        <div class="bg-slate-50 rounded-lg p-4 flex justify-between items-center">
            <span class="text-lg font-semibold text-slate-700">Total Amount</span>
            <span class="text-2xl font-bold text-slate-900">Rp {{ number_format($invoice->expense->amount, 0, ',', '.') }}</span>
        </div>

        <!-- Signature -->
        <div class="mt-12 text-center">
            <div class="border-t border-slate-300 w-48 mx-auto pt-2">
                <p class="text-sm text-slate-500">Authorized Signature</p>
            </div>
        </div>
    </div>
</x-layout>
