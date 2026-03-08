<x-layout>
    <x-slot:title>Expenses</x-slot>
    <x-slot:actions>
        <div x-data="{ open: false }">
            <button @click="open = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center gap-2 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Expense
            </button>

            <!-- Modal -->
            <div x-show="open" style="display: none;" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" x-transition.opacity>
                <div @click.outside="open = false" class="bg-white rounded-lg shadow-xl w-full max-w-lg transform transition-all" x-transition.scale>
                    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                        <h3 class="font-bold text-lg text-slate-900">Record Expense</h3>
                        <button @click="open = false" class="text-slate-400 hover:text-slate-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </div>
                    <div class="p-6 space-y-4">
                         <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Date</label>
                            <input type="date" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Category</label>
                            <select class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option>Logistics</option>
                                <option>Events</option>
                                <option>Maintenance</option>
                                <option>Honorarium</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Amount (Rp)</label>
                            <input type="number" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Responsible Member</label>
                            <select class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option>Ahmad Fauzi</option>
                                <option>Siti Aminah</option>
                                <option>Budi Santoso</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                             <textarea class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" rows="3" placeholder="Expense details..."></textarea>
                        </div>
                         <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Proof of Transaction (Optional)</label>
                            <div class="border-2 border-dashed border-slate-300 p-4 rounded-md text-center cursor-pointer hover:bg-slate-50 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-auto mb-2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                <span class="text-sm text-slate-500">Click to upload image</span>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-3 rounded-b-lg">
                        <button @click="open = false" class="px-4 py-2 bg-white border border-slate-300 rounded-md text-slate-700 hover:bg-slate-50 text-sm font-medium">Cancel</button>
                        <button @click="open = false" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium">Save Expense</button>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm">
        <div class="p-4 border-b border-slate-200 flex flex-col sm:flex-row gap-4 justify-between items-center">
            <input type="date" class="w-full sm:max-w-[180px] rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
             <select class="w-full sm:max-w-[180px] rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                <option>All Categories</option>
                <option>Logistics</option>
                <option>Events</option>
                <option>Maintenance</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                         <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Title & Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Responsible</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    <!-- Row 1 -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">01 Feb 2026</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">Logistik</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-slate-900">Beli Beras</div>
                            <div class="text-xs text-slate-500">Pembelian beras 50kg untuk dapur</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 flex items-center gap-2">
                             <div class="w-5 h-5 bg-slate-200 rounded-full"></div>
                             Siti A.
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-500">-Rp 800.000</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button class="text-slate-400 hover:text-blue-600 mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <button class="text-slate-400 hover:text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-layout>
