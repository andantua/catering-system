@extends('layouts.app')

@section('title', 'Formularz zamówienia')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">
    <a href="{{ route('order.dashboard') }}" class="btn-outline text-sm inline-flex items-center"><i class="fas fa-arrow-left mr-1"></i>Powrót</a>

    <div x-data="orderTimer()" x-init="startTimer()" class="rounded-xl p-4 text-center transition-all shadow-md"
         :class="isClosed ? 'timer-blocked' : (minutes < 30 ? 'timer-danger-pulse' : (minutes < 120 ? 'timer-warning' : 'timer-success'))">
        <template x-if="isClosed"><div><i class="fas fa-ban text-3xl mb-2 block"></i><span class="font-bold text-lg">ZAMÓWIENIA ZAMKNIĘTE</span><p class="text-sm opacity-90 mt-1">Zamówienia przyjmowane są do godziny 18:00</p></div></template>
        <template x-if="!isClosed"><div><div class="flex items-center justify-center gap-4"><div class="text-center"><div class="text-3xl font-bold" x-text="hours"></div><div class="text-xs uppercase">godzin</div></div><div class="text-3xl font-bold">:</div><div class="text-center"><div class="text-3xl font-bold" x-text="minutes"></div><div class="text-xs uppercase">minut</div></div><div class="text-3xl font-bold">:</div><div class="text-center"><div class="text-3xl font-bold" x-text="seconds"></div><div class="text-xs uppercase">sekund</div></div></div><p class="text-sm opacity-90 mt-2">Termin składania zamówień: 18:00</p></div></template>
    </div>

    <div class="card">
        <div class="card-header bg-indigo-600 text-white"><i class="fas fa-clipboard-list mr-2"></i>Formularz zamówienia</div>
        <div class="card-body">
            @if(!$canOrder)<div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-3 rounded mb-4 text-sm"><i class="fas fa-ban mr-1"></i> Nie można złożyć zamówienia na wybraną datę.</div>@endif

            <form x-data="orderForm()" x-init="init()" action="{{ route('order.submit') }}" method="POST">
                @csrf
                <div class="mb-4"><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data zamówienia:</label><input type="date" name="order_date" id="order_date" value="{{ $orderDate }}" min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" max="{{ $maxOrderDate }}" class="input w-auto"></div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-900"><tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"><th class="px-3 py-2">Dieta</th><th class="px-3 py-2 text-center w-32">Ilość</th></tr></thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($diets as $diet)
                            <tr><td class="px-3 py-2 text-sm">{{ $diet->name }}</td><td class="px-3 py-2 text-center"><input type="number" name="quantities[{{ $diet->id }}]" x-model="quantities[{{ $diet->id }}]" min="0" max="999" class="input text-center w-20 inline-block"></td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <button type="submit" class="w-full py-3 rounded-lg font-medium transition-all duration-200 flex items-center justify-center gap-2 shadow-md mt-4"
                        :class="isClosed ? 'bg-gray-500 cursor-not-allowed' : (minutes < 30 && minutes > 0 ? 'bg-red-600 hover:bg-red-700 animate-pulse-slow' : 'bg-emerald-600 hover:bg-emerald-700')"
                        {{ !$canOrder ? 'disabled' : '' }} :disabled="isClosed">
                    <i class="fas fa-check-circle"></i> <span x-text="isClosed ? 'ZAMKNIĘTE' : (minutes < 30 && minutes > 0 ? 'ZŁÓŻ ZAMÓWIENIE (ostatnia szansa!)' : 'ZŁÓŻ ZAMÓWIENIE')"></span>
                </button>

                <div x-show="saved" x-transition class="text-sm text-emerald-600 dark:text-emerald-400 mt-2 text-center"><i class="fas fa-check-circle"></i> <span x-text="saveMessage"></span></div>
            </form>
        </div>
    </div>
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

function orderForm() {
    return {
        quantities: {},
        saved: false,
        saveMessage: '',
        autoSaveTimer: null,
        init() {
            @foreach($diets as $diet)
                this.quantities[{{ $diet->id }}] = {{ $quantities[$diet->id] ?? 0 }};
            @endforeach
            this.autoSaveTimer = setInterval(() => this.autoSave(), 30000);
        },
        autoSave() {
            const formData = new FormData();
            for (const [id, val] of Object.entries(this.quantities)) formData.append(`quantities[${id}]`, val);
            fetch('{{ route("order.save-draft") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                body: formData
            }).then(r => r.json()).then(data => {
                if (data.success) { this.saved = true; this.saveMessage = 'Wersja robocza zapisana o ' + new Date().toLocaleTimeString(); setTimeout(() => this.saved = false, 3000); }
            }).catch(e => console.error(e));
        }
    }
}
document.getElementById('order_date').addEventListener('change', function() { window.location.href = '{{ route('order.form') }}?date=' + this.value; });
</script>
@endpush
@endsection