@extends('layouts.app')

@section('title', 'Logowanie - Panel Oddziału')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h5 class="mb-0"><i class="fas fa-hospital-user me-2"></i>Logowanie Oddziału</h5>
                </div>
                <div class="card-body p-4">
                    
                    <form method="POST" action="{{ route('order.login.post') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label small fw-bold">Adres e-mail oddziału</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label small fw-bold">Hasło</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="fas fa-sign-in-alt me-2"></i>Zaloguj
                        </button>
                    </form>
                    
                    <hr class="my-3">
                    
                    <div class="alert alert-info small mb-0 py-2">
                        <strong>Dane testowe:</strong><br>
                        Email: kardiologia@szpital.pl / Hasło: 1234
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection