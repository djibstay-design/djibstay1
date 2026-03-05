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
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Inter', sans-serif; background: #f8fafc; color: #1e293b; }
        .admin-sidebar { position: fixed; left: 0; top: 0; width: 260px; height: 100vh; background: #003580; z-index: 100; overflow-y: auto; }
        .admin-sidebar .logo { padding: 24px; display: flex; align-items: center; gap: 12px; color: #fff; font-weight: 700; font-size: 18px; text-decoration: none; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .admin-sidebar .logo-icon { width: 40px; height: 40px; background: #fff; color: #003580; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 18px; }
        .admin-sidebar nav { padding: 16px 12px; }
        .admin-sidebar .nav-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; color: rgba(255,255,255,0.85); text-decoration: none; border-radius: 8px; margin-bottom: 4px; transition: all 0.2s; }
        .admin-sidebar .nav-item:hover { background: rgba(255,255,255,0.1); color: #fff; }
        .admin-sidebar .nav-item.active { background: rgba(255,255,255,0.15); color: #fff; }
        .admin-sidebar .nav-item svg { width: 20px; height: 20px; flex-shrink: 0; }
        .admin-sidebar .nav-footer { padding: 16px 12px; border-top: 1px solid rgba(255,255,255,0.1); }
        .admin-main { margin-left: 260px; min-height: 100vh; display: flex; flex-direction: column; }
        .admin-header { position: sticky; top: 0; z-index: 50; background: #fff; border-bottom: 1px solid #e2e8f0; padding: 16px 24px; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
        .admin-content { flex: 1; padding: 24px; overflow-y: auto; }
    </style>
</head>
<body>
    {{-- Sidebar fixe --}}
    <aside class="admin-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="logo">
            <div class="logo-icon">D</div>
            <span>DjiboutiStay</span>
        </a>
        <nav>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.reservations.index') }}" class="nav-item {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Réservations
            </a>
            <a href="{{ route('admin.chambres.index') }}" class="nav-item {{ request()->routeIs('admin.chambres.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Chambres
            </a>
            <a href="{{ route('admin.hotels.index') }}" class="nav-item {{ request()->routeIs('admin.hotels.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Hôtels
            </a>
            <a href="{{ route('admin.types-chambre.index') }}" class="nav-item {{ request()->routeIs('admin.types-chambre.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Types chambre
            </a>
            <a href="{{ route('admin.avis.index') }}" class="nav-item {{ request()->routeIs('admin.avis.index') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                Avis
            </a>
            @if (auth()->user()->role === 'SUPER_ADMIN')
                <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Utilisateurs
                </a>
            @endif
        </nav>
        <div class="nav-footer">
            <a href="{{ route('home') }}" class="nav-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Voir le site
            </a>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="nav-item w-full text-left border-0 bg-transparent cursor-pointer p-0" style="color: rgba(255,255,255,0.85);">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Déconnexion
                </button>
            </form>
        </div>
    </aside>

    {{-- Zone principale --}}
    <div class="admin-main">
        @if(View::hasSection('header'))
            @yield('header')
        @else
            <header class="admin-header">
                <h1 class="text-xl font-bold text-slate-800">@yield('title', 'Admin')</h1>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold" style="background:#003580">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                    <div>
                        <p class="font-semibold text-slate-800 text-sm">{{ auth()->user()->prenom ?? '' }} {{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-slate-500">{{ auth()->user()->role === 'SUPER_ADMIN' ? 'Super Admin' : 'Admin' }}</p>
                    </div>
                </div>
            </header>
        @endif

        @if (session('success') || session('error') || (isset($errors) && $errors->any()))
            <div class="px-6 py-2 bg-white border-b">
                @if (session('success'))<div class="bg-green-50 text-green-800 px-4 py-2 rounded text-sm">{{ session('success') }}</div>@endif
                @if (session('error'))<div class="bg-red-50 text-red-800 px-4 py-2 rounded text-sm">{{ session('error') }}</div>@endif
                @if (isset($errors) && $errors->any())<div class="bg-red-50 text-red-800 px-4 py-2 rounded text-sm"><ul class="list-disc list-inside">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
            </div>
        @endif

        <main class="admin-content">
            @yield('content')
        </main>
    </div>
</body>
</html>
