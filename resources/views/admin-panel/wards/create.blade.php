@extends('layouts.app')

@section('title', 'Dodaj oddział')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">
    <a href="{{ route('admin.wards.index') }}" class="btn-outline text-sm inline-flex items-center"><i class="fas fa-arrow-left mr-1"></i>Powrót</a>

    <div class="card"><div class="card-header bg-indigo-600 text-white"><i class="fas fa-plus mr-2"></i>Dodaj nowy oddział</div>
        <div class="card-body">
            <form action="{{ route('admin.wards.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div><label class="block text-sm font-medium mb-1">Nazwa oddziału *</label><input type="text" name="name" class="input" value="{{ old('name') }}" required>@error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                    <div><label class="block text-sm font-medium mb-1">Adres e-mail *</label><input type="email" name="email" class="input" value="{{ old('email') }}" required><p class="text-xs text-gray-500 mt-1">Oddział otrzyma potwierdzenia zamówień na ten adres.</p>@error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                    <div><label class="block text-sm font-medium mb-1">Osoba kontaktowa (opcjonalnie)</label><input type="text" name="contact_person" class="input" value="{{ old('contact_person') }}">@error('contact_person')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                    <div><label class="block text-sm font-medium mb-1">Hasło *</label><input type="text" name="password" class="input" value="1234" required><p class="text-xs text-gray-500 mt-1">Min. 4 znaki. Domyślnie: 1234</p>@error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                    <div class="flex justify-between pt-2"><a href="{{ route('admin.wards.index') }}" class="btn-secondary"><i class="fas fa-times mr-1"></i>Anuluj</a><button type="submit" class="btn-primary"><i class="fas fa-save mr-1"></i>Zapisz oddział</button></div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection