@extends('layouts.app')

@section('title', 'Historia zamówień')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            
            {{-- Nagłówek --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 mb-0 fw-bold">
                        <i class="fas fa-history text-primary me-2"></i>Historia zamówień
                    </h1>
                    <p class="text-muted mt-1">{{ $ward->name }}</p>
                </div>
                <div>
                    <a href="{{ route('order.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Powrót do dashboardu
                    </a>
                </div>
            </div>
            
            {{-- Lista zamówień --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @if($orders->count() > 0)
                        @foreach($orders as $order)
                        <div class="mb-4 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                                    <strong>{{ \Carbon\Carbon::parse($order->order_date)->format('d.m.Y (l)') }}</strong>
                                </div>
                                <span class="badge bg-success">Zatwierdzone</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Dieta</th>
                                            <th class="text-end">Ilość</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $dailyTotal = 0; @endphp
                                        @foreach($orders as $orderItem)
                                        <tr>
                                            <td>{{ $orderItem->diet->name }}</td>
                                            <td class="text-end">{{ $orderItem->quantity }}</td>
                                        </tr>
                                        @php $dailyTotal += $orderItem->quantity; @endphp
                                        @endforeach
                                        <tr class="table-active">
                                            <th>RAZEM</th>
                                            <th class="text-end">{{ $dailyTotal }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endforeach
                        
                        {{-- Paginacja --}}
                        <div class="mt-4">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5>Brak historii zamówień</h5>
                            <p class="text-muted">Nie złożono jeszcze żadnego zamówienia.</p>
                            <a href="{{ route('order.form') }}" class="btn btn-primary mt-2">
                                <i class="fas fa-clipboard-list me-2"></i>Złóż pierwsze zamówienie
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection