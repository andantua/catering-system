@extends('layouts.app')

@section('title', 'Zarządzanie oddziałami')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-hospital"></i> Oddziały</h4>
            <a href="{{ route('admin.wards.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Dodaj oddział
            </a>
        </div>
        <div class="card-body">
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        32
                            <th>ID</th>
                            <th>Nazwa oddziału</th>
                            <th>E-mail</th>
                            <th>Osoba kontaktowa</th>
                            <th>Hasło</th>
                            <th width="150">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wards as $ward)
                        <tr>
                            <td>{{ $ward->id }}</td>
                            <td><strong>{{ $ward->name }}</strong></td>
                            <td>{{ $ward->email }}</td>
                            <td>{{ $ward->contact_person ?? '-' }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-secondary" 
                                        onclick="showPassword('{{ $ward->id }}')">
                                    <i class="fas fa-eye"></i> Pokaż
                                </button>
                                <span id="password-{{ $ward->id }}" style="display: none;">
                                    (hasło ustawione)
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.wards.edit', $ward->id) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.wards.destroy', $ward->id) }}" 
                                      method="POST" 
                                      style="display: inline-block;"
                                      onsubmit="return confirm('Czy na pewno usunąć oddział {{ $ward->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Brak oddziałów</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle"></i>
                <strong>Domyślne hasło dla nowych oddziałów:</strong> ustawiane podczas tworzenia.
                Oddziały logują się na stronie: <code>/order/login</code>
            </div>
        </div>
    </div>
</div>

<script>
function showPassword(wardId) {
    alert('Hasło jest zaszyfrowane w bazie danych. Aby zmienić hasło, użyj opcji edycji.');
}
</script>
@endsection