<x-layout>
    <x-slot:title>Members</x-slot>
    <x-slot:actions>
        <div x-data="{ open: false }">
            <button @click="open = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Member
            </button>

            <!-- Add Modal -->
            <div x-show="open" style="display: none;" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" x-transition.opacity>
                <div @click.outside="open = false" class="bg-white rounded-lg shadow-xl w-full max-w-md">
                    <form action="{{ route('members.store') }}" method="POST">
                        @csrf
                        <div class="px-6 py-4 border-b border-slate-200">
                            <h3 class="font-bold text-lg text-slate-900">Add New Member</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Full Name *</label>
                                <input type="text" name="name" required class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="e.g. John Doe">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Role (Optional)</label>
                                <input type="text" name="role" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="e.g. Treasurer">
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-3 rounded-b-lg">
                            <button type="button" @click="open = false" class="px-4 py-2 bg-white border border-slate-300 rounded-md text-slate-700 hover:bg-slate-50 text-sm font-medium">Cancel</button>
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
                <select name="filter" onchange="this.form.submit()" class="rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>All Status</option>
                    <option value="active" {{ $filter === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $filter === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($members as $member)
                        <tr x-data="{ editing: false }">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-900">{{ $member->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $member->role ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $member->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ ucfirst($member->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $member->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <button @click="editing = true" class="text-slate-400 hover:text-blue-600">Edit</button>
                                <form action="{{ route('members.toggleStatus', $member) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-slate-400 hover:text-{{ $member->status === 'active' ? 'red' : 'green' }}-600">
                                        {{ $member->status === 'active' ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                
                                <!-- Edit Modal -->
                                <div x-show="editing" style="display: none;" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" x-transition.opacity>
                                    <div @click.outside="editing = false" class="bg-white rounded-lg shadow-xl w-full max-w-md">
                                        <form action="{{ route('members.update', $member) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="px-6 py-4 border-b border-slate-200">
                                                <h3 class="font-bold text-lg text-slate-900">Edit Member</h3>
                                            </div>
                                            <div class="p-6 space-y-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700 mb-1">Full Name *</label>
                                                    <input type="text" name="name" value="{{ $member->name }}" required class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700 mb-1">Role</label>
                                                    <input type="text" name="role" value="{{ $member->role }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                                </div>
                                            </div>
                                            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-3 rounded-b-lg">
                                                <button type="button" @click="editing = false" class="px-4 py-2 bg-white border border-slate-300 rounded-md text-slate-700 hover:bg-slate-50 text-sm font-medium">Cancel</button>
                                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">No members found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layout>
