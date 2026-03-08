<x-layout>
    <x-slot:title>Dashboard</x-slot>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Balance Card -->
        <div class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm">
            <div class="text-sm text-slate-500 font-medium">Total Balance</div>
            <div class="font-bold text-2xl mt-1 {{ $totalBalance >= 0 ? 'text-slate-900' : 'text-red-600' }}">
                Rp {{ number_format($totalBalance, 0, ',', '.') }}
            </div>
            <div class="text-sm mt-2 text-slate-500">Current available funds</div>
        </div>

        <!-- Income Card -->
        <div class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm">
            <div class="text-sm text-slate-500 font-medium">Total Income</div>
            <div class="font-bold text-2xl mt-1 text-green-600">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
            <div class="text-sm mt-2 text-slate-500">All time</div>
        </div>

        <!-- Expense Card -->
        <div class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm">
            <div class="text-sm text-slate-500 font-medium">Total Expenses</div>
            <div class="font-bold text-2xl mt-1 text-red-500">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</div>
            <div class="text-sm mt-2 text-slate-500">All time</div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-900 mb-4">Recent Transactions</h2>
        @if($recentTransactions->isEmpty())
            <div class="text-center py-8 text-slate-400">No transactions yet. Add income or expenses to get started.</div>
        @else
            <div class="space-y-4">
                @foreach($recentTransactions as $tx)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center {{ $tx['type'] === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-50 text-red-500' }}">
                                @if($tx['type'] === 'income')
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19V5M5 12l7 7 7-7"/></svg>
                                @else
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12l7-7 7 7"/></svg>
                                @endif
                            </div>
                            <div>
                                <div class="text-sm font-medium text-slate-900">{{ $tx['source'] }}</div>
                                <div class="text-xs text-slate-500">{{ $tx['date']->format('d M Y') }}</div>
                            </div>
                        </div>
                        <div class="text-sm font-bold {{ $tx['type'] === 'income' ? 'text-green-600' : 'text-red-500' }}">
                            {{ $tx['type'] === 'income' ? '+' : '-' }}Rp {{ number_format($tx['amount'], 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layout>
