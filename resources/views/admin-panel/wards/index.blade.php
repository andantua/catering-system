@extends('layouts.app')

@section('title', 'Zarządzanie oddziałami')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center"><div><h1 class="text-2xl font-bold text-gray-800 dark:text-white"><i class="fas fa-hospital mr-2 text-indigo-500"></i>Oddziały</h1><p class="text-gray-500 dark:text-gray-400 text-sm">Zarządzanie oddziałami szpitalnymi</p></div><a href="{{ route('admin.wards.create') }}" class="btn-primary text-sm"><i class="fas fa-plus mr-1"></i>Dodaj oddział</a></div>

    @if(session('success'))<div class="bg-emerald-50 dark:bg-emerald-900/30 border-l-4 border-emerald-500 text-emerald-700 dark:text-emerald-300 p-3 rounded"><i class="fas fa-check-circle mr-1"></i> {{ session('success') }}</div>@endif
    @if(session('error'))<div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-3 rounded"><i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}</div>@endif

    <div class="card"><div class="card-body p-0 overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-900"><tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"><th class="px-4 py-3">ID</th><th class="px-4 py-3">Nazwa</th><th class="px-4 py-3">E-mail</th><th class="px-4 py-3">Kontakt</th><th class="px-4 py-3 text-right">Akcje</th></tr></thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($wards as $ward)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900"><td class="px-4 py-2 text-sm">{{ $ward->id }}</td><td class="px-4 py-2 font-medium">{{ $ward->name }}</td><td class="px-4 py-2 text-sm">{{ $ward->email }}</td><td class="px-4 py-2 text-sm">{{ $ward->contact_person ?? '-' }}</td><td class="px-4 py-2 text-right"><a href="{{ route('admin.wards.edit', $ward->id) }}" class="text-indigo-500 hover:text-indigo-600 mr-2"><i class="fas fa-edit"></i></a><form action="{{ route('admin.wards.destroy', $ward->id) }}" method="POST" class="inline">@csrf @method('DELETE')<button type="submit" class="text-red-500 hover:text-red-600" onclick="return confirm('Usunąć {{ $ward->name }}?')"><i class="fas fa-trash"></i></button></form></td></tr>
                @empty
                <tr><td colspan="5" class="text-center py-6 text-gray-500">Brak oddziałów</td></tr>
                @endforelse
            </tbody>
        </table>
    </div></div>

    <div class="bg-sky-50 dark:bg-sky-900/30 border-l-4 border-sky-500 p-3 rounded text-sm"><i class="fas fa-info-circle mr-1"></i> Oddziały logują się na stronie: <code class="bg-sky-100 dark:bg-sky-800 px-1 rounded">/order/login</code> z hasłem ustawionym podczas tworzenia.</div>
</div>
@endsection