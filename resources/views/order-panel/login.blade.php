@extends('layouts.app')

@section('title', 'Logowanie - Panel Oddziału')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12">
    <div class="max-w-md w-full space-y-6">
        <div class="text-center"><i class="fas fa-utensils text-5xl text-indigo-500"></i><h2 class="mt-4 text-2xl font-bold text-gray-800 dark:text-white">Logowanie Oddziału</h2><p class="text-gray-500 dark:text-gray-400">System zamówień cateringowych</p></div>

        <div class="card"><div class="card-body">
            <form method="POST" action="{{ route('order.login.post') }}">
                @csrf
                <div class="mb-4"><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Adres e-mail oddziału</label><input type="email" name="email" class="input @error('email') border-red-500 @enderror" value="{{ old('email') }}" required autofocus>@error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                <div class="mb-6"><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hasło</label><input type="password" name="password" class="input @error('password') border-red-500 @enderror" required>@error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                <button type="submit" class="btn-primary w-full"><i class="fas fa-sign-in-alt mr-2"></i>Zaloguj</button>
            </form>
            <hr class="my-6 border-gray-200 dark:border-gray-700">
            <div class="bg-sky-50 dark:bg-sky-900/30 border-l-4 border-sky-500 p-3 rounded text-sm"><p class="font-medium text-sky-700 dark:text-sky-300">Dane testowe:</p><p class="text-sky-600 dark:text-sky-400">Email: kardiologia@szpital.pl</p><p class="text-sky-600 dark:text-sky-400">Hasło: 1234</p></div>
        </div></div>
    </div>
</div>
@endsection