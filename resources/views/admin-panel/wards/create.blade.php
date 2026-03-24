@extends('layouts.app')

@section('title', 'Dodaj oddział')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0"><i class="fas fa-plus"></i> Dodaj nowy oddział</h4>
        </div>
        <div class="card-body">
            
            <form action="{{ route('admin.wards.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="name" class="form-label">Nazwa oddziału *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Adres e-mail *</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email') }}" required>
                    <div class="form-text">Oddział otrzyma potwierdzenia zamówień na ten adres.</div>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="contact_person" class="form-label">Osoba kontaktowa (opcjonalnie)</label>
                    <input type="text" class="form-control @error('contact_person') is-invalid @enderror" 
                           id="contact_person" name="contact_person" value="{{ old('contact_person') }}">
                    @error('contact_person')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Hasło *</label>
                    <input type="text" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" value="1234" required>
                    <div class="form-text">Min. 4 znaki. Domyślnie: 1234 (zmień przy pierwszym logowaniu).</div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.wards.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Anuluj
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Zapisz oddział
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection