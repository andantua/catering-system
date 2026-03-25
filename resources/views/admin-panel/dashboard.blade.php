@extends('layouts.app')

@section('title', 'Panel Administratora')

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Dashboard</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Zarządzanie zamówieniami i oddziałami</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.wards.index') }}" class="btn-outline text-sm"><i class="fas fa-hospital-user mr-1"></i>Oddziały</a>
            <a href="{{ route('admin.print.kitchen') }}" class="btn-success text-sm" target="_blank"><i class="fas fa-print mr-1"></i>Drukuj</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data zamówień:</label>
                    <input type="date" name="date" value="{{ request('date', today()->format('Y-m-d')) }}" class="input w-auto">
                </div>
                <div>
                    <button type="submit" class="btn-primary"><i class="fas fa-eye mr-1"></i>Pokaż</button>
                </div>
                @if(request('date') && request('date') != today()->format('Y-m-d'))
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn-secondary"><i class="fas fa-times mr-1"></i>Wyczyść</a>
                </div>
                @endif
            </form>
        </div>
    </div>

    <div x-data="liveTimer()" x-init="startTimer()" 
         class="rounded-xl p-4 text-center transition-all shadow-md"
         :class="isClosed ? 'timer-blocked' : (minutes < 30 ? 'timer-danger-pulse' : (minutes < 120 ? 'timer-warning' : 'timer-success'))">
        <template x-if="isClosed">
            <div>
                <i class="fas fa-ban text-3xl mb-2 block"></i>
                <span class="font-bold text-lg">ZAMÓWIENIA ZAMKNIĘTE</span>
                <p class="text-sm opacity-90 mt-1">Zamówienia przyjmowane są do godziny 18:00</p>
            </div>
        </template>
        <template x-if="!isClosed">
            <div>
                <div class="flex items-center justify-center gap-4">
                    <div class="text-center"><div class="text-3xl font-bold" x-text="hours"></div><div class="text-xs uppercase tracking-wider">godzin</div></div>
                    <div class="text-3xl font-bold">:</div>
                    <div class="text-center"><div class="text-3xl font-bold" x-text="minutes"></div><div class="text-xs uppercase tracking-wider">minut</div></div>
                    <div class="text-3xl font-bold">:</div>
                    <div class="text-center"><div class="text-3xl font-bold" x-text="seconds"></div><div class="text-xs uppercase tracking-wider">sekund</div></div>
                </div>
                <p class="text-sm opacity-90 mt-2">Termin składania zamówień: 18:00</p>
            </div>
        </template>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card"><div class="flex justify-between items-start"><div><p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Łącznie posiłków</p><p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $totalOrders }}</p></div><div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center"><i class="fas fa-utensils text-indigo-600 dark:text-indigo-400"></i></div></div></div>
        <div class="stat-card"><div class="flex justify-between items-start"><div><p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Oddziały z zamówieniem</p><p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ $submittedCount }} / {{ $totalWards }}</p></div><div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center"><i class="fas fa-hospital text-emerald-600 dark:text-emerald-400"></i></div></div></div>
        <div class="stat-card"><div class="flex justify-between items-start"><div><p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Brak zamówienia</p><p class="text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $wardsNotSubmitted->count() }}</p></div><div class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center"><i class="fas fa-clock text-amber-600 dark:text-amber-400"></i></div></div></div>
        <div class="stat-card"><div class="flex justify-between items-start"><div><p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Średnia na oddział</p><p class="text-3xl font-bold text-sky-600 dark:text-sky-400">{{ $submittedCount > 0 ? round($totalOrders / $submittedCount) : 0 }}</p></div><div class="w-10 h-10 rounded-full bg-sky-100 dark:bg-sky-900/50 flex items-center justify-center"><i class="fas fa-chart-line text-sky-600 dark:text-sky-400"></i></div></div></div>
    </div>

    @if($wardsNotSubmitted->count() > 0)
    <div>
        <form action="{{ route('admin.remind') }}" method="POST">
            @csrf
            <button type="submit" class="btn-warning w-full flex items-center justify-center gap-2"><i class="fas fa-bell"></i><span>Przypomnij wszystkim ({{ $wardsNotSubmitted->count() }} oddziały bez zamówienia)</span></button>
        </form>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-chart-pie mr-2 text-indigo-500"></i>Zestawienie według diet</div>
            <div class="card-body">
                @if($summaryByDiet->count())
                    <div class="space-y-3">
                        @foreach($summaryByDiet as $diet => $quantity)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-700 dark:text-gray-300">{{ $diet }}</span>
                                <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $quantity }}</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                                <div class="bg-indigo-500 h-2 rounded-full transition-all duration-500" style="width: {{ $totalOrders > 0 ? ($quantity / $totalOrders) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                        <div class="pt-3 border-t border-gray-200 dark:border-gray-700 flex justify-between font-semibold">
                            <span>SUMA</span>
                            <span>{{ $summaryByDiet->sum() }}</span>
                        </div>
                    </div>
                @else
                    <p class="text-center text-gray-500 py-4">Brak zamówień na dziś</p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="fas fa-bell mr-2 text-amber-500"></i>Oddziały bez zamówienia</div>
            <div class="card-body">
                @if($wardsNotSubmitted->count())
                    <div class="space-y-2">
                        @foreach($wardsNotSubmitted as $ward)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700 last:border-0">
                            <div class="flex items-center gap-2"><i class="fas fa-building text-gray-400 text-sm"></i><span class="text-gray-700 dark:text-gray-300">{{ $ward->name }}</span></div>
                            <form action="{{ route('admin.remind.single', $ward->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-amber-500 hover:text-amber-600 text-sm flex items-center gap-1 transition"><i class="fas fa-envelope"></i><span>Przypomnij</span></button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <form action="{{ route('admin.remind') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-warning w-full text-sm"><i class="fas fa-envelope-open-text mr-1"></i>Wyślij przypomnienia do wszystkich ({{ $wardsNotSubmitted->count() }})</button>
                        </form>
                    </div>
                @else
                    <div class="text-center py-4"><i class="fas fa-check-circle text-3xl text-emerald-500 mb-2 block"></i><p class="text-emerald-600 dark:text-emerald-400">Wszystkie oddziały złożyły zamówienie!</p></div>
                @endif
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><i class="fas fa-list-check mr-2 text-indigo-500"></i>Zamówienia na dziś</div>
        <div class="card-body p-0 overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-4 py-3">Oddział</th><th class="px-4 py-3">Dieta</th><th class="px-4 py-3 text-right">Ilość</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($orders->sortBy('ward.name') as $order)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900"><td class="px-4 py-2 text-sm">{{ $order->ward->name }}</td><td class="px-4 py-2 text-sm">{{ $order->diet->name }}</td><td class="px-4 py-2 text-sm text-right font-semibold">{{ $order->quantity }}</td></tr>
                    @empty
                    <tr><td colspan="3" class="text-center py-6 text-gray-500">Brak zamówień na dziś</td></tr>
                    @endforelse
                </tbody>
                @if($orders->count())
                <tfoot class="bg-gray-50 dark:bg-gray-900 font-semibold"><tr><td colspan="2" class="px-4 py-2 text-right">RAZEM:</td><td class="px-4 py-2 text-right">{{ $orders->sum('quantity') }}</td></tr></tfoot>
                @endif
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header flex justify-between items-center">
            <span><i class="fas fa-table-list mr-2 text-indigo-500"></i>Szczegółowe zestawienie oddziałów</span>
            <a href="{{ route('admin.print.kitchen') }}" class="btn-success text-sm py-1 px-3" target="_blank"><i class="fas fa-print mr-1"></i>Drukuj</a>
        </div>
        <div class="card-body space-y-6">
            @forelse($summaryByWard as $wardName => $data)
            <div>
                <div class="flex flex-wrap justify-between items-center mb-3">
                    <div class="flex items-center gap-2"><i class="fas fa-building text-indigo-500"></i><h4 class="font-bold">{{ $wardName }}</h4><span class="badge badge-primary">{{ $data['total'] }} posiłków</span></div>
                    <a href="{{ route('admin.print.ward', $wardName) }}" class="text-sm text-indigo-500 hover:text-indigo-600" target="_blank"><i class="fas fa-print mr-1"></i>Drukuj</a>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                    @foreach($data['details'] as $diet => $qty)
                    <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-900 rounded-lg px-3 py-2"><span class="text-sm">{{ $diet }}</span><span class="font-semibold text-indigo-600">{{ $qty }}</span></div>
                    @endforeach
                </div>
            </div>
            @if(!$loop->last)<hr class="border-gray-200 dark:border-gray-700">@endif
            @empty
            <p class="text-center text-gray-500 py-4">Brak zamówień na dziś</p>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
function liveTimer() {
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