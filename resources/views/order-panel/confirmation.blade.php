@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4>Potwierdzenie zamówienia</h4>
                </div>
                <div class="card-body text-center">
                    
                    <i class="fas fa-check-circle" style="font-size: 64px; color: green;"></i>
                    
                    <h3 class="mt-3">Zamówienie zostało złożone!</h3>
                    
                    <p>Potwierdzenie wysłano na adres: <strong>{{ $ward->email }}</strong></p>
                    
                    <table class="table table-bordered mt-4">
                        <thead>
                            32
                                <th>Dieta</th>
                                <th>Ilość</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->diet->name }}</td>
                                <td class="text-center">{{ $order->quantity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-active">
                                <th>RAZEM</th>
                                <th class="text-center">{{ $orders->sum('quantity') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <a href="{{ route('order.login') }}" class="btn btn-primary">
                        Strona główna
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection