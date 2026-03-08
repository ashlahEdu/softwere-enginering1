@props(['active' => false])

<a {{ $attributes }} class="flex items-center gap-3 px-4 py-2 rounded-md font-medium transition-colors {{ $active ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
    {{ $slot }}
</a>
