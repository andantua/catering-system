@extends('layouts.app')

@section('title', 'Logowanie - Panel Oddziału')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-primary text-white text-center">
                <h4><i class="fas fa-hospital-user"></i> Logowanie Oddziału</h4>
            </div>
            <div class="card-body">
                
                <form method="POST" action="{{ route('order.login.post') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Adres e-mail oddziału</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Hasło</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-sign-in-alt"></i> Zaloguj
                    </button>
                </form>
                
                <hr class="my-3">
                
                <div class="alert alert-info small">
                    <strong>Dane testowe:</strong><br>
                    Email: kardiologia@szpital.pl<br>
                    Hasło: 1234
                </div>
            </div>
        </div>
    </div>
</div>
@endsection