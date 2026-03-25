@extends('layouts.app')

@section('title', 'Panel Oddziału')

@section('content')
<div class="container py-3">
    <div class="row">
        <div class="col-12">
            
            {{-- Nagłówek --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-0 fw-bold">
                        <i class="fas fa-building text-primary me-2"></i>{{ $ward->name }}
                    </h4>
                    <small class="text-muted">Panel zamówień cateringowych</small>
                </div>
                <form action="{{ route('order.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-sign-out-alt me-1"></i>Wyloguj
                    </button>
                </form>
            </div>
            
            {{-- Karty statusu --}}
            <div class="row g-2 mb-3">
                <div class="col-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body py-2">
                            <small class="text-muted">Dzisiejsze zamówienie</small>
                            @if($hasSubmittedToday)
                                <h5 class="mb-0 fw-bold text-success">{{ $todayTotal }}</h5>
                                <small class="text-success">✔️ Zatwierdzone</small>
                            @else
                                <h5 class="mb-0 fw-bold text-warning">Brak</h5>
                                <small class="text-muted">Nie złożono</small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body py-2">
                            <small class="text-muted">Łącznie posiłków</small>
                            <h5 class="mb-0 fw-bold">{{ $stats['total_orders'] }}</h5>
                            <small>w {{ $stats['total_days'] }} dniach</small>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body py-2">
                            <small class="text-muted">Średnia dzienna</small>
                            <h5 class="mb-0 fw-bold">{{ $stats['avg_per_day'] }}</h5>
                            <small>posiłków/dzień</small>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Przyciski akcji --}}
            <div class="row g-2 mb-3">
                <div class="col-6">
                    <a href="{{ route('order.form') }}" class="btn btn-primary w-100 py-2">
                        <i class="fas fa-plus-circle me-1"></i>Nowe zamówienie
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('order.history') }}" class="btn btn-outline-info w-100 py-2">
                        <i class="fas fa-history me-1"></i>Historia
                    </a>
                </div>
            </div>
            
            {{-- Ostatnie zamówienia --}}
            @if($recentOrders->count() > 0)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-2">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-clock text-primary me-1"></i>Ostatnie zamówienia</h6>
                </div>
                <div class="card-body p-0">
                    @foreach($recentOrders as $date => $orders)
                        <div class="border-bottom">
                            <div class="bg-light px-3 py-1">
                                <small class="fw-bold">{{ \Carbon\Carbon::parse($date)->format('d.m.Y') }}</small>
                                <span class="badge bg-primary float-end">{{ $orders->sum('quantity') }}</span>
                            </div>
                            <div class="px-3 py-1">
                                @foreach($orders as $order)
                                <div class="d-flex justify-content-between small py-1">
                                    <span>{{ $order->diet->name }}</span>
                                    <span class="fw-bold">{{ $order->quantity }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection