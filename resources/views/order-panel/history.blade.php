@extends('layouts.app')

@section('title', 'Historia zamówień')

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <div class="flex justify-between items-center">
        <div><h1 class="text-2xl font-bold text-gray-800 dark:text-white"><i class="fas fa-history mr-2 text-indigo-500"></i>Historia zamówień</h1><p class="text-gray-500 dark:text-gray-400 text-sm">{{ $ward->name }}</p></div>
        <a href="{{ route('order.dashboard') }}" class="btn-outline text-sm"><i class="fas fa-arrow-left mr-1"></i>Powrót</a>
    </div>

    <div class="card"><div class="card-body p-0">
        @if($orders->count())
            @php $currentDate = null; @endphp
            @foreach($orders as $order)
                @php $orderDate = \Carbon\Carbon::parse($order->order_date)->format('d.m.Y'); @endphp
                @if($currentDate != $orderDate)
                    @if($currentDate) </div></div> @endif
                    <div class="border-b border-gray-200 dark:border-gray-700"><div class="bg-gray-50 dark:bg-gray-900 px-4 py-2"><span class="font-medium">{{ $orderDate }}</span></div><div class="px-4">
                @endif
                <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700 last:border-0"><span class="text-sm">{{ $order->diet->name }}</span><span class="font-semibold">{{ $order->quantity }}</span></div>
                @php $currentDate = $orderDate; @endphp
            @endforeach
            </div></div>
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">{{ $orders->links() }}</div>
        @else
            <div class="text-center py-8"><i class="fas fa-inbox text-4xl text-gray-400 mb-3 block"></i><p class="text-gray-500">Brak historii zamówień</p><a href="{{ route('order.form') }}" class="btn-primary inline-block mt-3 text-sm"><i class="fas fa-plus-circle mr-1"></i>Złóż pierwsze zamówienie</a></div>
        @endif
    </div></div>
</div>
@endsection