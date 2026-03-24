@extends('layouts.app')

@section('title', 'Weryfikacja kodu')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-lock me-2"></i>Weryfikacja</h4>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">
                    Witaj <strong>{{ $ward->name }}</strong>!<br>
                    Wprowadź 6-cyfrowy kod który otrzymałeś w e-mailu.
                </p>
                
                <form action="{{ route('order.verify') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token_id" value="{{ session('order_token_temp') }}">
                    
                    <div class="mb-4">
                        <label for="code" class="form-label">Kod weryfikacyjny</label>
                        <input type="text" class="form-control form-control-lg text-center" 
                               id="code" name="code" 
                               placeholder="000000"
                               maxlength="6"
                               pattern="[0-9]{6}"
                               required>
                        <div class="form-text">Wprowadź 6-cyfrowy kod z e-maila.</div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-check"></i> Zweryfikuj
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Automatyczne przejście do następnego pola po wpisaniu 6 cyfr
    document.getElementById('code').addEventListener('input', function(e) {
        if (this.value.length === 6) {
            this.form.submit();
        }
    });
</script>
@endpush
@endsection