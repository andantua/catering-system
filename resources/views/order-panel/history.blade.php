@extends('layouts.app')

@section('title', 'Historia zamówień')

@section('content')
<div class="container py-3">
    <div class="row">
        <div class="col-12">
            
            {{-- Nagłówek --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-0 fw-bold">
                        <i class="fas fa-history text-primary me-2"></i>Historia zamówień
                    </h4>
                    <small class="text-muted">{{ $ward->name }}</small>
                </div>
                <a href="{{ route('order.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Powrót
                </a>
            </div>
            
            {{-- Lista zamówień --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    @if($orders->count() > 0)
                        @php $currentDate = null; @endphp
                        @foreach($orders as $order)
                            @php
                                $orderDate = \Carbon\Carbon::parse($order->order_date)->format('d.m.Y');
                            @endphp
                            @if($currentDate != $orderDate)
                                @if($currentDate) </div> @endif
                                <div class="border-bottom">
                                    <div class="bg-light px-3 py-2">
                                        <strong class="small">{{ $orderDate }}</strong>
                                    </div>
                                    <div class="px-3">
                            @endif
                            <div class="d-flex justify-content-between small py-2 border-bottom">
                                <span>{{ $order->diet->name }}</span>
                                <span class="fw-bold">{{ $order->quantity }}</span>
                            </div>
                            @php $currentDate = $orderDate; @endphp
                        @endforeach
                        </div></div>
                        
                        {{-- Paginacja --}}
                        <div class="px-3 py-2 border-top">
                            {{ $orders->links('pagination::bootstrap-5') }}
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2 opacity-50"></i>
                            <p class="small">Brak historii zamówień</p>
                            <a href="{{ route('order.form') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus-circle me-1"></i>Złóż pierwsze zamówienie
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection