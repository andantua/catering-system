@extends('layouts.app')

@section('title', 'Potwierdzenie zamówienia')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12">
    <div class="max-w-md w-full">
        <div class="card text-center"><div class="card-body p-8">
            <i class="fas fa-check-circle text-6xl text-emerald-500 mb-4"></i>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Zamówienie złożone!</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-4">Potwierdzenie wysłano na adres:<br><strong>{{ $ward->email }}</strong></p>
            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 mb-6">
                <table class="w-full"><thead class="text-xs text-gray-500 uppercase"><tr><th class="text-left">Dieta</th><th class="text-right">Ilość</th></tr></thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">@foreach($orders as $order)<tr><td class="py-1 text-left">{{ $order->diet->name }}</td><td class="py-1 text-right font-semibold">{{ $order->quantity }}</td></tr>@endforeach</tbody>
                <tfoot class="border-t border-gray-200 dark:border-gray-700 pt-2"><tr><td class="pt-2 font-bold">RAZEM</td><td class="pt-2 text-right font-bold">{{ $orders->sum('quantity') }}</td></tr></tfoot>
                </table>
            </div>
            <a href="{{ route('order.dashboard') }}" class="btn-primary inline-block"><i class="fas fa-home mr-1"></i>Strona główna</a>
        </div></div>
    </div>
</div>
@endsection