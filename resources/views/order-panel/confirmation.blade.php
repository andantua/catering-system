@extends('layouts.app')

@section('title', 'Potwierdzenie zamówienia')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body p-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 48px;"></i>
                    <h4 class="mt-3">Zamówienie złożone!</h4>
                    <p class="small text-muted">Potwierdzenie wysłano na adres:<br>{{ $ward->email }}</p>
                    
                    <div class="table-responsive mt-3">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr class="small">
                                    <th>Dieta</th>
                                    <th>Ilość</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td class="small">{{ $order->diet->name }}</td>
                                    <td class="small text-center">{{ $order->quantity }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr class="small fw-bold">
                                    <td>RAZEM</td>
                                    <td class="text-center">{{ $orders->sum('quantity') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <a href="{{ route('order.dashboard') }}" class="btn btn-primary btn-sm mt-3">
                        <i class="fas fa-home me-1"></i>Strona główna
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection