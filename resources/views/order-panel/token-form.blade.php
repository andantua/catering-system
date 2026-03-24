@extends('layouts.app')

@section('title', 'Zamówienie posiłków')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-utensils me-2"></i>Zamówienie posiłków</h4>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">
                    Wprowadź link który otrzymałeś w e-mailu, lub skorzystaj z bezpośredniego linku.
                </p>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Link do zamówienia otrzymasz e-mailem każdego dnia przed 15:00.
                </div>
                
                <form action="{{ route('order.token.form') }}" method="GET">
                    <div class="mb-3">
                        <label for="token" class="form-label">Wprowadź link lub token:</label>
                        <input type="text" class="form-control" id="token" name="token" 
                               placeholder="np. abc123def456...">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-arrow-right"></i> Kontynuuj
                    </button>
                </form>
                
                <hr class="my-4">
                
                <p class="text-center text-muted small">
                    Nie masz linku? Skontaktuj się z działem żywienia.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection