@extends('layouts.app')

@section('title', 'Formularz zamówienia')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            {{-- Przycisk powrotu --}}
            <div class="mb-3">
                <a href="{{ route('order.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Powrót do dashboardu
                </a>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Formularz zamówienia</h4>
                </div>
                <div class="card-body">
                    
                    @if(!$canOrder)
                        <div class="alert alert-danger text-center">
                            <i class="fas fa-ban me-2"></i>
                            Nie można złożyć zamówienia na wybraną datę. Zamówienia na dziś przyjmowane są do godziny {{ $deadline }}.
                        </div>
                    @endif
                    
                    <form action="{{ route('order.submit') }}" method="POST" id="orderForm">
                        @csrf
                        
                        {{-- Wybór daty --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Data zamówienia:</label>
                            <input type="date" 
                                   name="order_date" 
                                   id="order_date"
                                   value="{{ $orderDate }}"
                                   min="{{ Carbon\Carbon::today()->format('Y-m-d') }}"
                                   max="{{ $maxOrderDate }}"
                                   class="form-control"
                                   style="width: auto; display: inline-block;">
                            <div class="form-text mt-1">
                                <i class="fas fa-info-circle"></i>
                                Zamówienia na dziś można składać do godziny {{ $deadline }}.
                                @if(Carbon\Carbon::now()->format('H:i') > $deadline)
                                    <span class="text-warning">Dziś nie można już zamówić – możesz zamówić na następne dni.</span>
                                @endif
                            </div>
                        </div>
                        
                        {{-- Tabela diet --}}
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    32
                                        <th>Dieta</th>
                                        <th width="150" class="text-center">Ilość</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($diets as $diet)
                                    <tr>
                                        <td>
                                            <strong>{{ $diet->name }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <input type="number" 
                                                   name="quantities[{{ $diet->id }}]" 
                                                   value="{{ $quantities[$diet->id] ?? 0 }}"
                                                   min="0" 
                                                   max="999"
                                                   class="form-control quantity-input text-center"
                                                   style="width: 100px; display: inline-block;">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-success btn-lg" {{ !$canOrder ? 'disabled' : '' }}>
                                <i class="fas fa-check-circle"></i> ZŁÓŻ ZAMÓWIENIE
                            </button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Przy zmianie daty – przeładuj formularz z nową datą
    document.getElementById('order_date').addEventListener('change', function() {
        const date = this.value;
        window.location.href = '{{ route('order.form') }}?date=' + date;
    });
</script>
@endpush

@push('styles')
<style>
    .quantity-input {
        width: 100px;
        display: inline-block;
    }
</style>
@endpush
@endsection