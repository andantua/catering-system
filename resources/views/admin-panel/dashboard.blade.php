@extends('layouts.app')

@section('title', 'Panel Administratora')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            
            {{-- Nagłówek z przyciskami --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 mb-0 fw-bold">
                        <i class="fas fa-chart-line text-primary me-2"></i>Dashboard
                    </h1>
                    <p class="text-muted mt-1">Zarządzanie zamówieniami i oddziałami</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.wards.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-hospital-user me-2"></i>Zarządzaj oddziałami
                    </a>
                    <button onclick="window.print()" class="btn btn-outline-secondary">
                        <i class="fas fa-print me-2"></i>Drukuj
                    </button>
                </div>
            </div>
            
            {{-- Timer w nowoczesnym stylu --}}
            <div class="card mb-4 border-0 shadow-sm timer-card timer-{{ $timerClass }}">
                <div class="card-body text-center py-4">
                    @if($isAfterDeadline)
                        <div class="d-flex align-items-center justify-content-center gap-3">
                            <i class="fas fa-ban fa-3x"></i>
                            <div>
                                <h3 class="mb-0 fw-bold">ZAMÓWIENIA ZAMKNIĘTE</h3>
                                <small class="opacity-75">Zamówienia przyjmowane są do godziny {{ $deadline }}</small>
                            </div>
                        </div>
                    @else
                        <div class="d-flex align-items-center justify-content-center gap-4">
                            <div class="timer-circle">
                                <span class="timer-number">{{ floor($minutesRemaining / 60) }}</span>
                                <span class="timer-label">godz.</span>
                            </div>
                            <div class="timer-circle">
                                <span class="timer-number">{{ $minutesRemaining % 60 }}</span>
                                <span class="timer-label">min.</span>
                            </div>
                            <div class="text-start">
                                <h4 class="mb-0 fw-bold">Pozostało do zamknięcia</h4>
                                <small class="opacity-75">Termin składania zamówień: {{ $deadline }}</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            {{-- Karty statystyk w nowoczesnym stylu --}}
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100 stat-card stat-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1 small">Łącznie posiłków</p>
                                    <h2 class="mb-0 fw-bold">{{ $totalOrders }}</h2>
                                </div>
                                <div class="stat-icon">
                                    <i class="fas fa-utensils fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100 stat-card stat-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1 small">Oddziały z zamówieniem</p>
                                    <h2 class="mb-0 fw-bold">{{ $submittedCount }} / {{ $totalWards }}</h2>
                                </div>
                                <div class="stat-icon">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100 stat-card stat-warning">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1 small">Brak zamówienia</p>
                                    <h2 class="mb-0 fw-bold">{{ $wardsNotSubmitted->count() }}</h2>
                                </div>
                                <div class="stat-icon">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100 stat-card stat-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1 small">Średnia na oddział</p>
                                    <h2 class="mb-0 fw-bold">{{ $submittedCount > 0 ? round($totalOrders / $submittedCount) : 0 }}</h2>
                                </div>
                                <div class="stat-icon">
                                    <i class="fas fa-chart-simple fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-4 mb-4">
                {{-- Zestawienie według diet --}}
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 pt-4 pb-0">
                            <h5 class="fw-bold mb-0">
                                <i class="fas fa-chart-pie text-primary me-2"></i>Zestawienie według diet
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($summaryByDiet->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Dieta</th>
                                                <th class="text-end">Ilość</th>
                                                <th class="text-end" style="width: 100px">%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $total = $summaryByDiet->sum(); @endphp
                                            @foreach($summaryByDiet as $diet => $quantity)
                                            <tr>
                                                <td class="fw-medium">{{ $diet }}</td>
                                                <td class="text-end fw-bold">{{ $quantity }}</td>
                                                <td class="text-end text-muted">
                                                    {{ $total > 0 ? round(($quantity / $total) * 100, 1) : 0 }}%
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <th class="fw-bold">SUMA</th>
                                                <th class="text-end fw-bold">{{ $total }}</th>
                                                <th class="text-end">100%</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-chart-line fa-3x mb-3 opacity-50"></i>
                                    <p>Brak zamówień na dziś</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                {{-- Oddziały bez zamówienia --}}
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 pt-4 pb-0">
                            <h5 class="fw-bold mb-0">
                                <i class="fas fa-bell text-warning me-2"></i>Oddziały bez zamówienia
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($wardsNotSubmitted->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($wardsNotSubmitted as $ward)
                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                        <div>
                                            <i class="fas fa-hospital me-2 text-muted"></i>
                                            <span class="fw-medium">{{ $ward->name }}</span>
                                        </div>
                                        <form action="{{ route('admin.remind.single', $ward->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-envelope me-1"></i>Przypomnij
                                            </button>
                                        </form>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="mt-4">
                                    <form action="{{ route('admin.remind') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-warning w-100">
                                            <i class="fas fa-envelope-open-text me-2"></i>
                                            Wyślij przypomnienia do wszystkich ({{ $wardsNotSubmitted->count() }})
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                    <h5 class="text-success">Wszystkie oddziały złożyły zamówienie!</h5>
                                    <p class="text-muted">Świetna robota!</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Szczegółowe zestawienie oddziałów --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-table-list text-primary me-2"></i>Szczegółowe zestawienie oddziałów
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($summaryByWard as $wardName => $data)
                        <div class="mb-4">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <i class="fas fa-building fa-lg text-primary"></i>
                                <h4 class="mb-0 fw-bold">{{ $wardName }}</h4>
                                <span class="badge bg-primary rounded-pill px-3 py-2">łącznie: {{ $data['total'] }}</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Dieta</th>
                                            <th class="text-end" style="width: 100px">Ilość</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data['details'] as $diet => $qty)
                                        <tr>
                                            <td>{{ $diet }}</td>
                                            <td class="text-end fw-medium">{{ $qty }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if(!$loop->last)
                            <hr class="my-4">
                        @endif
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                            <p>Brak zamówień na dziś</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Nowoczesne style */
    .stat-card {
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: default;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        opacity: 0.7;
    }
    
    .stat-primary .stat-icon { color: #0d6efd; background: rgba(13,110,253,0.1); }
    .stat-success .stat-icon { color: #198754; background: rgba(25,135,84,0.1); }
    .stat-warning .stat-icon { color: #ffc107; background: rgba(255,193,7,0.1); }
    .stat-info .stat-icon { color: #0dcaf0; background: rgba(13,202,240,0.1); }
    
    /* Timer style */
    .timer-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(5px);
    }
    
    .timer-number {
        font-size: 2rem;
        font-weight: bold;
        line-height: 1;
    }
    
    .timer-label {
        font-size: 0.75rem;
        opacity: 0.9;
    }
    
    .timer-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }
    
    .timer-warning {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        color: #212529;
    }
    
    .timer-danger-pulse {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        animation: pulse-glow 1s infinite;
    }
    
    .timer-blocked {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
    }
    
    @keyframes pulse-glow {
        0% { box-shadow: 0 0 0 0 rgba(220,53,69,0.7); }
        70% { box-shadow: 0 0 0 15px rgba(220,53,69,0); }
        100% { box-shadow: 0 0 0 0 rgba(220,53,69,0); }
    }
    
    /* Responsywność */
    @media (max-width: 768px) {
        .timer-circle {
            width: 60px;
            height: 60px;
        }
        .timer-number {
            font-size: 1.5rem;
        }
        .stat-card {
            margin-bottom: 1rem;
        }
    }
    
    @media print {
        .navbar, .btn, form, .alert, .card-header .float-right, .stat-card:hover {
            display: none !important;
        }
        .card {
            break-inside: avoid;
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
        body {
            background: white;
        }
        .timer-card {
            background: #f8f9fa !important;
            color: black !important;
        }
    }
</style>
@endpush
@endsection