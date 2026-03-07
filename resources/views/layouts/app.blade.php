<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name')) - Gestion Réservation</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Navbar toujours bleu foncé, visible sur tout le site */
        .navbar-site {
            background-color: #003580 !important;
            color: #ffffff !important;
            min-height: 56px !important;
            display: block !important;
        }
        .navbar-site a { color: #ffffff !important; }
        .navbar-site a:hover { color: #003580 !important; background-color: #ffffff !important; }
        .navbar-site button { color: #ffffff !important; }
        .navbar-site .bg-white { background-color: #ffffff !important; color: #003580 !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] min-h-screen">
    {{-- Navbar : bleu foncé visible --}}
    <nav class="navbar-site text-white shadow-lg border-b-2 border-white/10" style="background-color: #003580;">
        <div class="max-w-6xl mx-auto px-4 py-3.5 flex items-center justify-between">
            <a href="{{ route('home') }}" class="font-bold text-lg text-white hover:text-white transition-colors flex items-center gap-2.5">
                <span class="w-9 h-9 rounded-lg bg-white flex items-center justify-center text-[#003580]">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </span>
                Réservation Hôtel
            </a>
            <div class="flex items-center gap-1 sm:gap-2">
                <a href="{{ route('hotels.index') }}" class="text-sm font-semibold text-white hover:bg-white hover:text-[#003580] px-3 py-2 rounded-lg transition-colors">Hôtels</a>
                <a href="{{ route('reservations.status') }}" class="text-sm font-semibold text-white hover:bg-white hover:text-[#003580] px-3 py-2 rounded-lg transition-colors">Suivi réservation</a>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-white px-3 py-2 rounded-lg border-2 border-white hover:bg-white hover:text-[#003580] transition-colors">Admin</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-white hover:bg-red-400 px-3 py-2 rounded-lg transition-colors">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-[#003580] bg-white hover:bg-gray-100 px-4 py-2 rounded-lg transition-colors">Connexion Admin</a>
                @endauth
            </div>
        </div>
    </nav>

    @if (session('success'))
        <div class="max-w-6xl mx-auto px-4 py-2">
            <div class="bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 px-4 py-2 rounded">{{ session('success') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="max-w-6xl mx-auto px-4 py-2">
            <div class="bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 px-4 py-2 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <main class="max-w-6xl mx-auto px-4 py-8">
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>
