@extends('layouts.app')

@section('title', 'Panel Administratora')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            
            {{-- Nagłówek z przyciskami --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-0 fw-bold">
                        <i class="fas fa-chart-line text-primary me-2"></i>Dashboard
                    </h4>
                    <small class="text-muted">Zarządzanie zamówieniami</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.wards.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-hospital-user me-1"></i>Oddziały
                    </a>
                    <a href="{{ route('admin.print.kitchen') }}" class="btn btn-sm btn-success" target="_blank">
                        <i class="fas fa-print me-1"></i>Drukuj
                    </a>
                </div>
            </div>
            
            {{-- Filtr daty i timer w jednym wierszu --}}
            <div class="row g-2 mb-3">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body py-2">
                            <form method="GET" action="{{ route('admin.dashboard') }}" class="row g-2 align-items-center">
                                <div class="col-auto">
                                    <label class="small text-muted">Data:</label>
                                </div>
                                <div class="col">
                                    <input type="date" name="date" value="{{ request('date', today()->format('Y-m-d')) }}" class="form-control form-control-sm">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @if(request('date') && request('date') != today()->format('Y-m-d'))
                                <div class="col-auto">
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm timer-{{ $timerClass }}">
                        <div class="card-body py-2 text-center">
                            @if($isAfterDeadline)
                                <span class="small">⛔ ZAMKNIĘTE (do {{ $deadline }})</span>
                            @else
                                <span class="small fw-bold">
                                    ⏰ Do zamknięcia: 
                                    <span class="fw-bold">{{ floor($minutesRemaining / 60) }}h {{ $minutesRemaining % 60 }}min</span>
                                    ({{ $deadline }})
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Karty statystyk w kompaktowej formie --}}
            <div class="row g-2 mb-3">
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm stat-card">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Posiłki</small>
                                    <h5 class="mb-0 fw-bold">{{ $totalOrders }}</h5>
                                </div>
                                <i class="fas fa-utensils text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm stat-card">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Oddziały</small>
                                    <h5 class="mb-0 fw-bold">{{ $submittedCount }} / {{ $totalWards }}</h5>
                                </div>
                                <i class="fas fa-hospital text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm stat-card">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Brak zamówienia</small>
                                    <h5 class="mb-0 fw-bold text-warning">{{ $wardsNotSubmitted->count() }}</h5>
                                </div>
                                <i class="fas fa-clock text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm stat-card">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Średnia</small>
                                    <h5 class="mb-0 fw-bold">{{ $submittedCount > 0 ? round($totalOrders / $submittedCount) : 0 }}</h5>
                                </div>
                                <i class="fas fa-chart-line text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Szybka akcja przypomnienia --}}
            @if($wardsNotSubmitted->count() > 0)
            <div class="mb-3">
                <form action="{{ route('admin.remind') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-warning w-100">
                        <i class="fas fa-bell me-1"></i>Przypomnij ({{ $wardsNotSubmitted->count() }} oddziały)
                    </button>
                </form>
            </div>
            @endif
            
            {{-- Zestawienie diet (kompaktowe) --}}
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-2">
                            <h6 class="mb-0 fw-bold"><i class="fas fa-chart-pie text-primary me-1"></i>Diety</h6>
                        </div>
                        <div class="card-body py-2">
                            @if($summaryByDiet->count() > 0)
                                @foreach($summaryByDiet as $diet => $quantity)
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small>{{ $diet }}</small>
                                    <small class="fw-bold">{{ $quantity }}</small>
                                </div>
                                @endforeach
                                <hr class="my-1">
                                <div class="d-flex justify-content-between fw-bold">
                                    <small>SUMA</small>
                                    <small>{{ $summaryByDiet->sum() }}</small>
                                </div>
                            @else
                                <div class="text-center py-2 text-muted small">Brak zamówień</div>
                            @endif
                        </div>
                    </div>
                </div>
                
                {{-- Oddziały bez zamówienia (kompaktowe) --}}
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-2">
                            <h6 class="mb-0 fw-bold"><i class="fas fa-bell text-warning me-1"></i>Bez zamówienia</h6>
                        </div>
                        <div class="card-body py-2">
                            @if($wardsNotSubmitted->count() > 0)
                                @foreach($wardsNotSubmitted as $ward)
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small>{{ $ward->name }}</small>
                                    <form action="{{ route('admin.remind.single', $ward->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-link text-warning p-0">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                    </form>
                                </div>
                                @endforeach
                            @else
                                <div class="text-center py-2 text-success small">✓ Wszystkie oddziały</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Lista zamówień (kompaktowa) --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-2">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-list-check text-primary me-1"></i>Zamówienia</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                32
                                    <th class="ps-3">Oddział</th>
                                    <th>Dieta</th>
                                    <th class="text-end pe-3">Ilość</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders->sortBy('ward.name') as $order)
                                <tr>
                                    <td class="ps-3"><small>{{ $order->ward->name }}</small></td>
                                    <td><small>{{ $order->diet->name }}</small></td>
                                    <td class="text-end pe-3"><small>{{ $order->quantity }}</small></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-3 text-muted small">Brak zamówień</td>
                                </tr>
                                @endforelse
                            </tbody>
                            @if($orders->count() > 0)
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="2" class="ps-3 fw-bold">RAZEM</td>
                                    <td class="text-end pe-3 fw-bold">{{ $orders->sum('quantity') }}</td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            
            {{-- Szczegółowe zestawienie oddziałów (kompaktowe) --}}
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white border-0 py-2 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-table-list text-primary me-1"></i>Oddziały</h6>
                    <a href="{{ route('admin.print.kitchen') }}" class="btn btn-sm btn-outline-success" target="_blank">
                        <i class="fas fa-print"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    @forelse($summaryByWard as $wardName => $data)
                        <div class="border-bottom">
                            <div class="d-flex justify-content-between align-items-center px-3 py-2 bg-light">
                                <span class="fw-bold small">{{ $wardName }}</span>
                                <span class="badge bg-primary">{{ $data['total'] }}</span>
                            </div>
                            <div class="px-3 py-1">
                                @foreach($data['details'] as $diet => $qty)
                                <div class="d-flex justify-content-between small py-1">
                                    <span>{{ $diet }}</span>
                                    <span class="fw-bold">{{ $qty }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-3 text-muted small">Brak zamówień</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .stat-card {
        transition: transform 0.1s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
    }
    .table-sm td, .table-sm th {
        padding: 0.4rem;
    }
    .timer-success { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; }
    .timer-warning { background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); color: #212529; }
    .timer-danger-pulse { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white; animation: pulse 1s infinite; }
    .timer-blocked { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); color: white; }
    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.7; } 100% { opacity: 1; } }
</style>
@endpush
@endsection