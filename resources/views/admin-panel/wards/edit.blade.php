@extends('layouts.app')

@section('title', 'Edytuj oddział')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">
    <a href="{{ route('admin.wards.index') }}" class="btn-outline text-sm inline-flex items-center"><i class="fas fa-arrow-left mr-1"></i>Powrót</a>

    <div class="card"><div class="card-header bg-indigo-600 text-white"><i class="fas fa-edit mr-2"></i>Edytuj oddział: {{ $ward->name }}</div>
        <div class="card-body">
            <form action="{{ route('admin.wards.update', $ward->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="space-y-4">
                    <div><label class="block text-sm font-medium mb-1">Nazwa oddziału *</label><input type="text" name="name" class="input" value="{{ old('name', $ward->name) }}" required>@error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                    <div><label class="block text-sm font-medium mb-1">Adres e-mail *</label><input type="email" name="email" class="input" value="{{ old('email', $ward->email) }}" required><p class="text-xs text-gray-500 mt-1">Oddział otrzyma potwierdzenia zamówień na ten adres.</p>@error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                    <div><label class="block text-sm font-medium mb-1">Osoba kontaktowa</label><input type="text" name="contact_person" class="input" value="{{ old('contact_person', $ward->contact_person) }}">@error('contact_person')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                    <div><label class="block text-sm font-medium mb-1">Nowe hasło (opcjonalnie)</label><input type="text" name="password" class="input" placeholder="Pozostaw puste, aby nie zmieniać"><p class="text-xs text-gray-500 mt-1">Wpisz nowe hasło tylko jeśli chcesz je zmienić.</p>@error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                    <div class="flex justify-between pt-2"><a href="{{ route('admin.wards.index') }}" class="btn-secondary"><i class="fas fa-times mr-1"></i>Anuluj</a><button type="submit" class="btn-primary"><i class="fas fa-save mr-1"></i>Zapisz zmiany</button></div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection