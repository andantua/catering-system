@extends('layouts.app')

@section('title', 'Panel Oddziału')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            
            {{-- Nagłówek --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 mb-0 fw-bold">
                        <i class="fas fa-building text-primary me-2"></i>{{ $ward->name }}
                    </h1>
                    <p class="text-muted mt-1">Panel zamówień cateringowych</p>
                </div>
                <div>
                    <form action="{{ route('order.logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="fas fa-sign-out-alt me-2"></i>Wyloguj
                        </button>
                    </form>
                </div>
            </div>
            
            {{-- Karty statusu --}}
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon me-3" style="background: rgba(13,110,253,0.1); color: #0d6efd;">
                                    <i class="fas fa-calendar-day fa-2x"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-0 small">Dzisiejsze zamówienie</p>
                                    @if($hasSubmittedToday)
                                        <h3 class="mb-0 fw-bold text-success">{{ $todayTotal }}</h3>
                                        <small class="text-success">✔️ Zatwierdzone</small>
                                    @else
                                        <h3 class="mb-0 fw-bold text-warning">Brak</h3>
                                        <small class="text-muted">Nie złożono jeszcze</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon me-3" style="background: rgba(25,135,84,0.1); color: #198754;">
                                    <i class="fas fa-chart-line fa-2x"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-0 small">Łączna liczba posiłków</p>
                                    <h3 class="mb-0 fw-bold">{{ $stats['total_orders'] }}</h3>
                                    <small>w {{ $stats['total_days'] }} dniach</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon me-3" style="background: rgba(255,193,7,0.1); color: #ffc107;">
                                    <i class="fas fa-chart-simple fa-2x"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-0 small">Średnia dzienna</p>
                                    <h3 class="mb-0 fw-bold">{{ $stats['avg_per_day'] }}</h3>
                                    <small>posiłków/dzień</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Akcje --}}
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-4">
                <a href="{{ route('order.form') }}" class="btn btn-primary btn-lg px-5">
                    <i class="fas fa-plus-circle me-2"></i>Nowe zamówienie
                </a>
                <p class="text-muted mt-3 mb-0 small">
                    <i class="fas fa-calendar-alt me-1"></i>Zamów na dziś lub na kolejne dni
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-4">
                <a href="{{ route('order.history') }}" class="btn btn-outline-info btn-lg px-5">
                    <i class="fas fa-history me-2"></i>Historia zamówień
                </a>
                <p class="text-muted mt-3 mb-0 small">
                    <i class="fas fa-chart-line me-1"></i>Przeglądaj swoje poprzednie zamówienia
                </p>
            </div>
        </div>
    </div>
</div>
            
            {{-- Ostatnie zamówienia --}}
            @if($recentOrders->count() > 0)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-clock text-primary me-2"></i>Ostatnie zamówienia
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($recentOrders as $date => $orders)
                        <div class="mb-4">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-calendar-alt text-muted"></i>
                                <strong>{{ \Carbon\Carbon::parse($date)->format('d.m.Y') }}</strong>
                                <span class="badge bg-primary rounded-pill">{{ $orders->sum('quantity') }} posiłków</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Dieta</th>
                                            <th class="text-end">Ilość</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                        <tr>
                                            <td>{{ $order->diet->name }}</td>
                                            <td class="text-end">{{ $order->quantity }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .stat-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
        border: none;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(13,110,253,0.3);
    }
    
    .btn-outline-info:hover {
        transform: translateY(-2px);
    }
</style>
@endpush
@endsection