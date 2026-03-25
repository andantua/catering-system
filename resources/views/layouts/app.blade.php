<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'System Cateringowy')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
    body {
        background-color: #f5f5f5;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    /* Kompaktowe style dla całej aplikacji */
    .container, .container-fluid {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
    
    .card {
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: none;
    }
    
    .card-header {
        background-color: white;
        border-bottom: 1px solid #e9ecef;
        padding: 0.6rem 1rem;
    }
    
    .card-body {
        padding: 0.8rem;
    }
    
    .table-sm td, .table-sm th {
        padding: 0.4rem 0.6rem;
        font-size: 0.85rem;
    }
    
    .btn-sm {
        padding: 0.2rem 0.6rem;
        font-size: 0.8rem;
    }
    
    .form-control-sm {
        font-size: 0.85rem;
        padding: 0.2rem 0.5rem;
    }
    
    .badge {
        padding: 0.3rem 0.6rem;
        font-weight: 500;
    }
    
    /* Kompaktowe karty statystyk */
    .stat-card {
        transition: transform 0.1s;
        cursor: default;
    }
    .stat-card:hover {
        transform: translateY(-2px);
    }
    
    /* Timer style */
    .timer-success { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; }
    .timer-warning { background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); color: #212529; }
    .timer-danger-pulse { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white; animation: pulse 1s infinite; }
    .timer-blocked { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); color: white; }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }
    
    footer {
        margin-top: 2rem;
        padding: 1rem 0;
        text-align: center;
        color: #6c757d;
        border-top: 1px solid #dee2e6;
        font-size: 0.8rem;
    }
    
    /* Alerty kompaktowe */
    .alert {
        padding: 0.6rem 1rem;
        margin-bottom: 0.8rem;
        font-size: 0.85rem;
    }
    
    /* Formularze kompaktowe */
    .form-label {
        font-size: 0.85rem;
        margin-bottom: 0.2rem;
    }
    
    .mb-3 {
        margin-bottom: 0.8rem !important;
    }
    
    .mt-4 {
        margin-top: 1.2rem !important;
    }
    
    .py-4 {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
    }
</style>
    
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-utensils me-2"></i> System Cateringowy
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <span class="nav-link">Witaj, {{ Auth::user()->name }}</span>
                        </li>
                        @if(Auth::user()->email === 'admin@catering.com')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-chart-line me-1"></i> Panel Admina
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link">
                                    <i class="fas fa-sign-out-alt me-1"></i> Wyloguj
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> Logowanie
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    
    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} System Cateringowy. Wszelkie prawa zastrzeżone.</p>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>