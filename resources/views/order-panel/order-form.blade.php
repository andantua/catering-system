@extends('layouts.app')

@section('title', 'Formularz zamówienia')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            {{-- Przycisk powrotu --}}
            <div class="mb-2">
                <a href="{{ route('order.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Powrót
                </a>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-2">
                    <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Formularz zamówienia</h5>
                </div>
                <div class="card-body">
                    
                    @if(!$canOrder)
                        <div class="alert alert-danger py-2 small">
                            <i class="fas fa-ban me-1"></i> Nie można złożyć zamówienia na wybraną datę.
                        </div>
                    @endif
                    
                    <form action="{{ route('order.submit') }}" method="POST">
                        @csrf
                        
                        {{-- Wybór daty --}}
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Data zamówienia:</label>
                            <input type="date" 
                                   name="order_date" 
                                   id="order_date"
                                   value="{{ $orderDate }}"
                                   min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                                   max="{{ $maxOrderDate }}"
                                   class="form-control form-control-sm"
                                   style="width: auto;">
                        </div>
                        
                        {{-- Tabela diet --}}
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr class="small">
                                        <th>Dieta</th>
                                        <th width="100" class="text-center">Ilość</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($diets as $diet)
                                    <tr>
                                        <td class="small">{{ $diet->name }}</td>
                                        <td class="text-center">
                                            <input type="number" 
                                                   name="quantities[{{ $diet->id }}]" 
                                                   value="{{ $quantities[$diet->id] ?? 0 }}"
                                                   min="0" 
                                                   max="999"
                                                   class="form-control form-control-sm text-center"
                                                   style="width: 80px; display: inline-block;">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100 py-2" {{ !$canOrder ? 'disabled' : '' }}>
                            <i class="fas fa-check-circle me-1"></i> ZŁÓŻ ZAMÓWIENIE
                        </button>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('order_date').addEventListener('change', function() {
        window.location.href = '{{ route('order.form') }}?date=' + this.value;
    });
</script>
@endpush
@endsection