<!DOCTYPE html>
<html lang="pl" x-data="themeSwitcher()" x-init="initTheme()" :class="{ 'dark': isDark }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'System Cateringowy')</title>
    
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        
        .timer-success { background: linear-gradient(135deg, #10b981, #34d399); color: white; }
        .timer-warning { background: linear-gradient(135deg, #f59e0b, #fbbf24); color: white; }
        .timer-danger-pulse { background: linear-gradient(135deg, #ef4444, #f87171); color: white; animation: pulse 1s infinite; }
        .timer-blocked { background: linear-gradient(135deg, #6b7280, #9ca3af); color: white; }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.9; transform: scale(1.02); }
        }
        
        .animate-pulse-slow {
            animation: btnPulse 1.2s infinite;
        }
        
        @keyframes btnPulse {
            0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            50% { opacity: 0.95; box-shadow: 0 0 0 6px rgba(239, 68, 68, 0.2); }
        }
        
        * {
            transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 font-sans antialiased">
    
    <nav class="bg-indigo-700 dark:bg-indigo-900 text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-2 text-lg font-semibold hover:text-indigo-200 transition">
                    <i class="fas fa-utensils"></i>
                    <span>System Cateringowy</span>
                </a>
                <div class="flex items-center gap-4">
                    @auth
                        <span class="text-sm opacity-90"><i class="fas fa-user-circle mr-1"></i>{{ Auth::user()->name }}</span>
                        @if(Auth::user()->email === 'admin@catering.com')
                        <a href="{{ route('admin.dashboard') }}" class="text-sm hover:text-indigo-200 transition">
                            <i class="fas fa-chart-line mr-1"></i>Panel Admina
                        </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm hover:text-indigo-200 transition">
                                <i class="fas fa-sign-out-alt mr-1"></i>Wyloguj
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm hover:text-indigo-200 transition">
                            <i class="fas fa-sign-in-alt mr-1"></i>Logowanie
                        </a>
                    @endauth
                    <button @click="toggleTheme()" class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 transition flex items-center justify-center">
                        <i class="fas fa-moon text-sm" :class="{ 'hidden': isDark }"></i>
                        <i class="fas fa-sun text-sm" :class="{ 'hidden': !isDark }"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    
    <main class="container mx-auto px-4 py-6">
        @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border-l-4 border-emerald-500 text-emerald-700 dark:text-emerald-300 p-3 rounded-lg mb-4 flex items-center gap-2 shadow-sm">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-3 rounded-lg mb-4 flex items-center gap-2 shadow-sm">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif
        @if(session('warning'))
        <div class="bg-amber-50 dark:bg-amber-900/30 border-l-4 border-amber-500 text-amber-700 dark:text-amber-300 p-3 rounded-lg mb-4 flex items-center gap-2 shadow-sm">
            <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
        </div>
        @endif
        @if(session('info'))
        <div class="bg-sky-50 dark:bg-sky-900/30 border-l-4 border-sky-500 text-sky-700 dark:text-sky-300 p-3 rounded-lg mb-4 flex items-center gap-2 shadow-sm">
            <i class="fas fa-info-circle"></i> {{ session('info') }}
        </div>
        @endif
        
        @yield('content')
    </main>
    
    <footer class="border-t border-gray-200 dark:border-gray-800 mt-8 py-4 text-center text-gray-500 dark:text-gray-400 text-sm">
        &copy; {{ date('Y') }} System Cateringowy
    </footer>
    
    <script>
        function themeSwitcher() {
            return {
                isDark: false,
                initTheme() {
                    const saved = localStorage.getItem('theme');
                    if (saved === 'dark') {
                        this.isDark = true;
                    } else if (saved === 'light') {
                        this.isDark = false;
                    } else {
                        this.isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    }
                    this.applyTheme();
                },
                toggleTheme() {
                    this.isDark = !this.isDark;
                    this.applyTheme();
                    localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
                },
                applyTheme() {
                    if (this.isDark) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            }
        }
    </script>
    @stack('scripts')
</body>
</html>