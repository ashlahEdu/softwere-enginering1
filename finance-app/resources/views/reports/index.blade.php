<x-layout>
    <x-slot:title>Reports</x-slot>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm">
            <div class="text-sm text-slate-500 font-medium">Total Income</div>
            <div class="font-bold text-2xl mt-1 text-green-600">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm">
            <div class="text-sm text-slate-500 font-medium">Total Expenses</div>
            <div class="font-bold text-2xl mt-1 text-red-500">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm">
            <div class="text-sm text-slate-500 font-medium">Net Balance</div>
            <div class="font-bold text-2xl mt-1 {{ $totalBalance >= 0 ? 'text-slate-900' : 'text-red-600' }}">Rp {{ number_format($totalBalance, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Expense by Category -->
        <div class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Expense by Category</h2>
            @if($expenseByCategory->isEmpty())
                <p class="text-slate-400 text-center py-8">No expense data yet.</p>
            @else
                <div class="space-y-3">
                    @php $maxAmount = $expenseByCategory->max('total'); @endphp
                    @foreach($expenseByCategory as $cat)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-slate-700">{{ $cat->category }}</span>
                                <span class="text-slate-500">Rp {{ number_format($cat->total, 0, ',', '.') }}</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($cat->total / $maxAmount) * 100 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- AI Q&A Assistant -->
        <div class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm" x-data="{ question: '', answer: '', errorMsg: '', loading: false }">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">🤖 AI Financial Assistant</h2>
            <p class="text-sm text-slate-500 mb-4">Tanyakan apa saja tentang data keuangan. AI akan menjawab berdasarkan data aktual.</p>

            <div class="space-y-4">
                <textarea x-model="question" rows="3" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Contoh: Berapa total pengeluaran bulan ini? Siapa yang paling banyak pengeluarannya?"></textarea>

                <button
                    @click="
                        if (!question.trim()) return;
                        loading = true; answer = ''; errorMsg = '';
                        fetch('{{ route('reports.askAI') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ question })
                        })
                        .then(r => r.json())
                        .then(data => {
                            loading = false;
                            if (data.answer) { answer = data.answer; errorMsg = ''; }
                            else { errorMsg = data.error || 'Terjadi kesalahan tidak diketahui.'; answer = ''; }
                        })
                        .catch(err => { loading = false; errorMsg = 'Gagal terhubung ke server: ' + err.message; answer = ''; });
                    "
                    :disabled="loading"
                    class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors"
                >
                    <span x-show="!loading">Tanya AI</span>
                    <span x-show="loading">Sedang berpikir...</span>
                </button>

                <!-- Error Box -->
                <div x-show="errorMsg" class="bg-red-50 border border-red-200 rounded-md p-4">
                    <div class="text-xs font-medium text-red-600 mb-1">⚠️ Error:</div>
                    <div x-text="errorMsg" class="text-sm text-red-700 whitespace-pre-wrap"></div>
                </div>

                <!-- Answer Box -->
                <div x-show="answer" class="bg-slate-50 border border-slate-200 rounded-md p-4">
                    <div class="text-xs font-medium text-blue-600 mb-2">🤖 Jawaban AI:</div>
                    <div x-text="answer" class="text-sm text-slate-700 whitespace-pre-wrap"></div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
