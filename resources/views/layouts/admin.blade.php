<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') - DjiboutiStay</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .admin-sidebar { background: #1a2434; }
        .admin-sidebar .nav-link { color: #94a3b8; transition: all 0.2s; }
        .admin-sidebar .nav-link:hover { background: rgba(255,255,255,0.1); color: #fff; }
        .admin-sidebar .nav-link.active { background: rgba(59,130,246,0.2); color: #fff; border-left: 3px solid #3b82f6; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen flex font-sans" style="font-family: 'Inter', sans-serif;">
    <aside class="admin-sidebar w-64 shrink-0 min-h-screen flex flex-col">
        <a href="{{ route('admin.dashboard') }}" class="p-5 flex items-center gap-2 font-bold text-white text-lg">
            <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center text-white text-xl font-black">D</div>
            <span>DjiboutiStay</span>
        </a>
        <nav class="flex-1 p-3 space-y-0.5">
            <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.reservations.index') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Réservations
            </a>
            <a href="{{ route('admin.chambres.index') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.chambres.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Chambres
            </a>
            <a href="{{ route('admin.hotels.index') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.hotels.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Hôtels
            </a>
            <a href="{{ route('admin.types-chambre.index') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.types-chambre.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Types chambre
            </a>
            <a href="{{ route('admin.avis.index') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.avis.index') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                Avis
            </a>
            @if (auth()->user()->role === 'SUPER_ADMIN')
                <a href="{{ route('admin.users.index') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Utilisateurs
                </a>
            @endif
        </nav>
        <div class="p-3 border-t border-gray-700">
            <a href="{{ route('home') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Voir le site
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button type="submit" class="nav-link w-full flex items-center gap-3 px-4 py-3 rounded-lg text-red-400 hover:text-red-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Déconnexion
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0">
        @if (session('success'))
            <div class="px-6 py-2 bg-green-50">
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded">{{ session('success') }}</div>
            </div>
        @endif
        @if (session('error'))
            <div class="px-6 py-2 bg-red-50">
                <div class="bg-red-100 text-red-800 px-4 py-2 rounded">{{ session('error') }}</div>
            </div>
        @endif
        @if ($errors->any())
            <div class="px-6 py-2 bg-red-50">
                <div class="bg-red-100 text-red-800 px-4 py-2 rounded">
                    <ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            </div>
        @endif

        @yield('header')

        <main class="flex-1 p-6 overflow-auto">
            @yield('content')
        </main>
    </div>
</body>
</html>
