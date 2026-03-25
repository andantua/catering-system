@extends('layouts.app')

@section('title', 'Panel Oddziału')

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white"><i class="fas fa-building mr-2 text-indigo-500"></i>{{ $ward->name }}</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Panel zamówień cateringowych</p>
        </div>
        <form action="{{ route('order.logout') }}" method="POST">@csrf<button type="submit" class="btn-outline text-sm"><i class="fas fa-sign-out-alt mr-1"></i>Wyloguj</button></form>
    </div>

    <div x-data="orderTimer()" x-init="startTimer()" class="rounded-xl p-4 text-center transition-all shadow-md"
         :class="isClosed ? 'timer-blocked' : (minutes < 30 ? 'timer-danger-pulse' : (minutes < 120 ? 'timer-warning' : 'timer-success'))">
        <template x-if="isClosed">
            <div><i class="fas fa-ban text-3xl mb-2 block"></i><span class="font-bold text-lg">ZAMÓWIENIA ZAMKNIĘTE</span><p class="text-sm opacity-90 mt-1">Zamówienia przyjmowane są do godziny 18:00</p></div>
        </template>
        <template x-if="!isClosed">
            <div><div class="flex items-center justify-center gap-4"><div class="text-center"><div class="text-3xl font-bold" x-text="hours"></div><div class="text-xs uppercase">godzin</div></div><div class="text-3xl font-bold">:</div><div class="text-center"><div class="text-3xl font-bold" x-text="minutes"></div><div class="text-xs uppercase">minut</div></div><div class="text-3xl font-bold">:</div><div class="text-center"><div class="text-3xl font-bold" x-text="seconds"></div><div class="text-xs uppercase">sekund</div></div></div><p class="text-sm opacity-90 mt-2">Termin składania zamówień: 18:00</p></div>
        </template>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="stat-card"><p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Dzisiejsze zamówienie</p>@if($hasSubmittedToday)<p class="text-2xl font-bold text-emerald-600">{{ $todayTotal }}</p><p class="text-xs text-emerald-600">✔️ Zatwierdzone</p>@else<p class="text-2xl font-bold text-amber-500">Brak</p><p class="text-xs text-gray-500">Nie złożono</p>@endif</div>
        <div class="stat-card"><p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Łącznie posiłków</p><p class="text-2xl font-bold">{{ $stats['total_orders'] }}</p><p class="text-xs text-gray-500">w {{ $stats['total_days'] }} dniach</p></div>
        <div class="stat-card"><p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Średnia dzienna</p><p class="text-2xl font-bold">{{ $stats['avg_per_day'] }}</p><p class="text-xs text-gray-500">posiłków/dzień</p></div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <a href="{{ route('order.form') }}" class="btn-primary text-center"><i class="fas fa-plus-circle mr-1"></i>Nowe zamówienie</a>
        <a href="{{ route('order.history') }}" class="btn-outline text-center"><i class="fas fa-history mr-1"></i>Historia zamówień</a>
    </div>

    @if($recentOrders->count())
    <div class="card">
        <div class="card-header"><i class="fas fa-clock mr-2 text-indigo-500"></i>Ostatnie zamówienia</div>
        <div class="card-body p-0">
            @foreach($recentOrders as $date => $orders)
            <div class="border-b border-gray-200 dark:border-gray-700 last:border-0">
                <div class="bg-gray-50 dark:bg-gray-900 px-4 py-2"><span class="font-medium">{{ \Carbon\Carbon::parse($date)->format('d.m.Y') }}</span><span class="badge badge-primary float-right">{{ $orders->sum('quantity') }} posiłków</span></div>
                <div class="px-4 py-2">@foreach($orders as $order)<div class="flex justify-between py-1"><span class="text-sm">{{ $order->diet->name }}</span><span class="font-semibold">{{ $order->quantity }}</span></div>@endforeach</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
function orderTimer() {
    return {
        hours: 0, minutes: 0, seconds: 0, isClosed: false,
        startTimer() { this.updateTimer(); setInterval(() => this.updateTimer(), 1000); },
        updateTimer() {
            const now = new Date(), deadline = new Date(); deadline.setHours(18,0,0,0);
            if (now >= deadline) { this.isClosed = true; return; }
            const diff = (deadline - now) / 1000;
            this.hours = Math.floor(diff / 3600);
            this.minutes = Math.floor((diff % 3600) / 60);
            this.seconds = Math.floor(diff % 60);
        }
    }
}
</script>
@endpush
@endsection