<x-layout>
    <x-slot:title>Expenses</x-slot>
    <x-slot:actions>
        <div x-data="{ open: false, aiSuggestion: '', analyzing: false }">
            <button @click="open = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Expense
            </button>

            <div x-show="open" style="display: none;" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 overflow-y-auto" x-transition.opacity>
                <div @click.outside="open = false" class="bg-white rounded-lg shadow-xl w-full max-w-lg my-8">
                    <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="px-6 py-4 border-b border-slate-200">
                            <h3 class="font-bold text-lg text-slate-900">Record Expense</h3>
                        </div>
                        <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Date *</label>
                                <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Category *</label>
                                <select name="category" required class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    <option value="">Select category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}">{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Amount (Rp) *</label>
                                <input type="number" name="amount" required min="1" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="0">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Responsible Member *</label>
                                <select name="member_id" required class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    <option value="">Select member</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                                <textarea name="description" rows="2" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Expense details..."></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Proof Image (Optional)</label>
                                <input type="file" name="proof_image" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                    @change="
                                        if ($event.target.files.length) {
                                            analyzing = true;
                                            const formData = new FormData();
                                            formData.append('image', $event.target.files[0]);
                                            fetch('{{ route('expenses.analyzeImage') }}', {
                                                method: 'POST',
                                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                                body: formData
                                            })
                                            .then(r => r.json())
                                            .then(data => { aiSuggestion = data.suggestion || data.error; analyzing = false; })
                                            .catch(() => { aiSuggestion = 'Analysis failed.'; analyzing = false; });
                                        }
                                    ">
                            </div>
                            <div x-show="analyzing || aiSuggestion" class="bg-blue-50 border border-blue-200 rounded-md p-3">
                                <div class="text-xs font-medium text-blue-700 mb-1">🤖 AI Suggestion</div>
                                <div x-show="analyzing" class="text-sm text-blue-600">Analyzing image...</div>
                                <div x-show="!analyzing && aiSuggestion" x-text="aiSuggestion" class="text-sm text-slate-700 whitespace-pre-wrap"></div>
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-3 rounded-b-lg">
                            <button type="button" @click="open = false; aiSuggestion = ''" class="px-4 py-2 bg-white border border-slate-300 rounded-md text-slate-700 hover:bg-slate-50 text-sm font-medium">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-md">{{ session('success') }}</div>
    @endif

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm">
        <div class="p-4 border-b border-slate-200">
            <form method="GET" class="flex gap-4">
                <select name="category" onchange="this.form.submit()" class="rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    <option value="all" {{ $category === 'all' ? 'selected' : '' }}>All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Responsible</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Invoice</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($expenses as $expense)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">{{ $expense->date->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $expense->category }}</td>
                            <td class="px-6 py-4 text-sm text-slate-500 max-w-xs truncate">{{ $expense->description ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $expense->member->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-500">-Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('invoice.show', $expense->invoice_number) }}" class="text-blue-600 hover:underline">{{ $expense->invoice_number }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="inline" onsubmit="return confirm('Delete this expense?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-red-600">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-400">No expenses recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layout>
