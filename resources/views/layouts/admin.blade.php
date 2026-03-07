<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') - DjibStay</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Inter', sans-serif; background: #f9fafb; color: #1e293b; }

        /* ── Sidebar (style Hotel Management Dashboard - fond sombre) ── */
        .admin-sidebar {
            position: fixed; left: 0; top: 0; width: 260px; height: 100vh;
            background: #141b2d;
            border-right: 1px solid rgba(255,255,255,0.06);
            z-index: 100; overflow-y: auto; display: flex; flex-direction: column;
        }
        .admin-sidebar .logo-wrap { padding: 20px 24px 12px; }
        .admin-sidebar .logo-label {
            font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.12em;
            color: rgba(148,163,184,0.6); margin-bottom: 16px; padding: 0 4px;
        }
        .admin-sidebar .logo {
            display: flex; align-items: center; gap: 12px;
            color: #fff; font-weight: 700; font-size: 20px; text-decoration: none;
            letter-spacing: -0.3px;
        }
        .admin-sidebar .logo-icon {
            width: 36px; height: 36px; background: #2196f3; color: #fff;
            border-radius: 8px; display: flex; align-items: center; justify-content: center;
        }
        .admin-sidebar nav { padding: 8px 12px; flex: 1; }
        .admin-sidebar .nav-section-label {
            font-size: 10px; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.1em; color: rgba(148,163,184,0.5);
            padding: 16px 16px 8px; margin-top: 4px;
        }
        .admin-sidebar .nav-item {
            display: flex; align-items: center; gap: 12px; padding: 10px 16px;
            color: #94a3b8; text-decoration: none; border-radius: 8px;
            margin-bottom: 2px; transition: all 0.2s; font-size: 14px; font-weight: 500;
            border-left: 3px solid transparent;
        }
        .admin-sidebar .nav-item:hover { background: #1e293b; color: #e2e8f0; }
        .admin-sidebar .nav-item.active {
            background: #2b3447; color: #fff; font-weight: 600;
            border-left-color: #2196f3;
        }
        .admin-sidebar .nav-item svg { width: 20px; height: 20px; flex-shrink: 0; }
        .admin-sidebar .nav-footer {
            padding: 16px 12px; border-top: 1px solid rgba(255,255,255,0.06);
        }
        .admin-sidebar .nav-footer .nav-item { color: #94a3b8; }

        /* ── Main ── */
        .admin-main { margin-left: 260px; min-height: 100vh; display: flex; flex-direction: column; }
        .admin-header {
            position: sticky; top: 0; z-index: 50; background: #fff;
            border-bottom: 1px solid #e5e7eb; padding: 12px 24px;
            display: flex; align-items: center; justify-content: space-between; flex-shrink: 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .admin-content { flex: 1; padding: 24px; overflow-y: auto; background: #f8fafc; }

        /* ── Responsive ── */
        @media (max-width: 1024px) {
            .admin-sidebar { width: 220px; }
            .admin-main { margin-left: 220px; }
        }
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%); transition: transform 0.3s;
                width: 260px;
            }
            .admin-sidebar.open { transform: translateX(0); }
            .admin-main { margin-left: 0; }
            .admin-content { padding: 16px; }
            .admin-header { padding: 12px 16px; }
        }

        /* Modales suppression / succès */
        .admin-modal-backdrop {
            position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); z-index: 1000;
            display: flex; align-items: center; justify-content: center; padding: 16px;
            opacity: 0; visibility: hidden; transition: opacity 0.2s, visibility 0.2s;
        }
        .admin-modal-backdrop.show { opacity: 1; visibility: visible; }
        .admin-modal-box {
            background: #fff; border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            max-width: 400px; width: 100%; text-align: center; padding: 28px 24px;
            transform: scale(0.95); transition: transform 0.2s;
        }
        .admin-modal-backdrop.show .admin-modal-box { transform: scale(1); }
        .admin-modal-icon-warn {
            width: 56px; height: 56px; margin: 0 auto 16px;
            border-radius: 50%; border: 3px solid #f59e0b; color: #f59e0b;
            display: flex; align-items: center; justify-content: center; font-size: 28px; font-weight: 700;
        }
        .admin-modal-icon-ok {
            width: 56px; height: 56px; margin: 0 auto 16px;
            border-radius: 50%; background: #10b981; color: #fff;
            display: flex; align-items: center; justify-content: center;
        }
        .admin-modal-title { font-size: 1.125rem; font-weight: 700; color: #1e293b; margin-bottom: 6px; }
        .admin-modal-text { font-size: 0.875rem; color: #64748b; margin-bottom: 20px; }
        .admin-modal-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .admin-modal-btn-confirm { background: #003580; color: #fff; border: 0; padding: 10px 20px; border-radius: 10px; font-weight: 600; cursor: pointer; }
        .admin-modal-btn-cancel { background: #fff; color: #dc2626; border: 2px solid #dc2626; padding: 10px 20px; border-radius: 10px; font-weight: 600; cursor: pointer; }
        .admin-modal-btn-ok { background: #003580; color: #fff; border: 0; padding: 10px 24px; border-radius: 10px; font-weight: 600; cursor: pointer; }

        /* ── Tables CRUD (style pro : bordures, boutons actions) ── */
        .crud-table-wrap { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.06); overflow: hidden; }
        .crud-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
        .crud-table th, .crud-table td { padding: 14px 16px; text-align: left; border: 1px solid #e2e8f0; background: #fff; }
        .crud-table thead th { background: #fff; font-weight: 700; color: #1e293b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; }
        .crud-table tbody tr { transition: background 0.15s; }
        .crud-table tbody tr:hover { background: #f8fafc; }
        .crud-table tbody tr.crud-row-warning { background: #fef2f2; }
        .crud-table tbody tr.crud-row-warning:hover { background: #fee2e2; }
        .crud-table .crud-actions { display: flex; align-items: center; justify-content: flex-end; gap: 8px; }
        .crud-table .crud-btn { width: 36px; height: 36px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; flex-shrink: 0; }
        .crud-table .crud-btn-view { color: #475569; background: transparent; }
        .crud-table .crud-btn-view:hover { background: #f1f5f9; color: #1e293b; }
        .crud-table .crud-btn-edit { color: #2563eb; background: transparent; }
        .crud-table .crud-btn-edit:hover { background: #eff6ff; color: #1d4ed8; }
        .crud-table .crud-btn-delete { color: #dc2626; background: #fee2e2; border: none; cursor: pointer; }
        .crud-table .crud-btn-delete:hover { background: #fecaca; color: #b91c1c; }
        .crud-table .crud-btn-delete svg { width: 18px; height: 18px; }
        .crud-table .crud-btn-view svg, .crud-table .crud-btn-edit svg { width: 18px; height: 18px; }
        .crud-table .crud-badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .crud-table .crud-badge-success { background: #dcfce7; color: #166534; }
        .crud-table .crud-badge-warning { background: #fef3c7; color: #b45309; }
        .crud-table .crud-badge-danger { background: #fee2e2; color: #b91c1c; }
        .crud-table .crud-badge-info { background: #dbeafe; color: #1d4ed8; }
        .crud-table .crud-badge-neutral { background: #f1f5f9; color: #475569; }
        .crud-pagination { padding: 16px 24px; border-top: 1px solid #e2e8f0; background: #fff; }

        /* Barre recherche + filtre CRUD */
        .crud-toolbar { display: flex; flex-wrap: wrap; align-items: center; justify-content: flex-end; gap: 10px; margin-bottom: 16px; }
        .crud-toolbar form { display: flex; flex-wrap: wrap; align-items: center; gap: 10px; }
        .crud-search-wrap { position: relative; }
        .crud-search-wrap svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: #94a3b8; pointer-events: none; }
        .crud-search { padding: 8px 12px 8px 40px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem; min-width: 200px; background: #fff; }
        .crud-search:focus { outline: none; border-color: #2196f3; box-shadow: 0 0 0 2px rgba(33,150,243,0.15); }
        .crud-filter { padding: 8px 32px 8px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem; background: #fff; color: #475569; cursor: pointer; }
        .crud-toolbar .crud-btn-submit { padding: 8px 16px; background: #2196f3; color: #fff; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; }
        .crud-toolbar .crud-btn-submit:hover { background: #1976d2; }
        .crud-toolbar .crud-btn-reset { padding: 8px 16px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem; cursor: pointer; }
        .crud-toolbar .crud-btn-reset:hover { background: #e2e8f0; }
    </style>
    @stack('styles')
</head>
<body>
    {{-- Sidebar fixe (style Hotel Management Dashboard) --}}
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="logo-wrap">
            <p class="logo-label">Admin Dashboard</p>
            <a href="{{ route('admin.dashboard') }}" class="logo">
                <div class="logo-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
                <span>DjibStay</span>
            </a>
        </div>
        <nav>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.reservations.index') }}" class="nav-item {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Reservation
            </a>
            <a href="{{ route('admin.chambres.index') }}" class="nav-item {{ request()->routeIs('admin.chambres.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v11a2 2 0 002 2h14a2 2 0 002-2V7"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12H3M3 12V9a2 2 0 012-2h2a2 2 0 012 2v3m12 0V9a2 2 0 00-2-2h-2a2 2 0 00-2 2v3"/><path stroke-linecap="round" stroke-width="2" d="M1 20h22"/></svg>
                Chambres
            </a>
            @if (auth()->user()->role === 'SUPER_ADMIN')
                <a href="{{ route('admin.hotels.index') }}" class="nav-item {{ request()->routeIs('admin.hotels.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Hôtels
                </a>
            @endif
            <a href="{{ route('admin.images.index') }}" class="nav-item {{ request()->routeIs('admin.images.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Gérer les images
            </a>

            <div class="nav-section-label">Reports</div>

            <a href="{{ route('admin.types-chambre.index') }}" class="nav-item {{ request()->routeIs('admin.types-chambre.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Types de chambre
            </a>
            <a href="#" class="nav-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Reports
            </a>
            <a href="{{ route('admin.avis.index') }}" class="nav-item {{ request()->routeIs('admin.avis.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                Avis
            </a>
            <a href="#" class="nav-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                Invoices
            </a>

            <div class="nav-section-label">Preferences</div>

            <a href="#" class="nav-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Settings
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
                <button type="submit" class="nav-item w-full text-left border-0 bg-transparent cursor-pointer p-0" style="color: #94a3b8; font-size:14px; font-weight:500;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Déconnexion
                </button>
            </form>
        </div>
    </aside>

    {{-- Mobile toggle --}}
    <button onclick="document.getElementById('adminSidebar').classList.toggle('open')" class="fixed top-3 left-3 z-[200] p-2 bg-white rounded-lg shadow-md md:hidden">
        <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>

    {{-- Zone principale --}}
    <div class="admin-main">
        @if(View::hasSection('header'))
            @yield('header')
        @else
            <header class="admin-header">
                <div class="flex items-center gap-4 flex-1">
                    <h1 class="text-xl font-bold text-slate-800 whitespace-nowrap">@yield('title', 'Admin')</h1>
                    <div class="relative flex-1 max-w-md mx-4 hidden sm:block">
                        <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" placeholder="Rechercher réservation, chambre..." class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl bg-slate-50 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all placeholder:text-slate-400">
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" class="p-2 rounded-lg hover:bg-slate-100 text-slate-500 transition-colors" title="Notifications">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </button>
                    <button type="button" class="p-2 rounded-lg hover:bg-slate-100 text-slate-500 transition-colors" title="Messages">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </button>
                    <div class="flex items-center gap-3 pl-3 border-l border-slate-200">
                        <div class="w-10 h-10 rounded-full bg-[#2196f3] flex items-center justify-center text-white font-semibold text-sm">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="hidden sm:block">
                            <p class="font-semibold text-slate-800 text-sm leading-tight">{{ auth()->user()->prenom ?? '' }} {{ auth()->user()->name ?? 'Admin' }}</p>
                            <p class="text-xs text-slate-500">{{ auth()->user()->role === 'SUPER_ADMIN' ? 'Super Admin' : 'Admin' }}</p>
                        </div>
                    </div>
                </div>
            </header>
        @endif

        @if (session('error') || (isset($errors) && $errors->any()))
            <div class="px-6 py-2 bg-white border-b">
                @if (session('error'))<div class="bg-red-50 text-red-800 px-4 py-2 rounded text-sm">{{ session('error') }}</div>@endif
                @if (isset($errors) && $errors->any())<div class="bg-red-50 text-red-800 px-4 py-2 rounded text-sm"><ul class="list-disc list-inside">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
            </div>
        @endif

        <main class="admin-content">
            @yield('content')
        </main>
    </div>

    {{-- Modale confirmation suppression --}}
    <div class="admin-modal-backdrop" id="deleteConfirmModal" aria-hidden="true">
        <div class="admin-modal-box" role="dialog" aria-modal="true" aria-labelledby="deleteConfirmTitle">
            <div class="admin-modal-icon-warn" aria-hidden="true">!</div>
            <h2 id="deleteConfirmTitle" class="admin-modal-title">Vous allez supprimer cet élément</h2>
            <p class="admin-modal-text">Cette action est irréversible.</p>
            <form id="deleteConfirmForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="admin-modal-actions">
                    <button type="submit" class="admin-modal-btn-confirm">Oui, j'en suis sûr&nbsp;!</button>
                    <button type="button" class="admin-modal-btn-cancel" id="deleteConfirmCancel">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modale succès (après suppression / action) --}}
    @if (session('success'))
    <div class="admin-modal-backdrop show" id="successModal" aria-hidden="true">
        <div class="admin-modal-box">
            <div class="admin-modal-icon-ok">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h2 class="admin-modal-title">Opération réalisée avec succès</h2>
            <p class="admin-modal-text">{{ session('success') }}</p>
            <button type="button" class="admin-modal-btn-ok" id="successModalOk">OK</button>
        </div>
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    (function() {
        var deleteModal = document.getElementById('deleteConfirmModal');
        var deleteForm = document.getElementById('deleteConfirmForm');
        var deleteTitle = document.getElementById('deleteConfirmTitle');
        var deleteCancel = document.getElementById('deleteConfirmCancel');
        if (deleteModal && deleteForm) {
            document.querySelectorAll('[data-delete-url]').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var url = this.getAttribute('data-delete-url');
                    var label = this.getAttribute('data-delete-label') || 'cet élément';
                    deleteForm.action = url;
                    deleteTitle.textContent = 'Vous allez supprimer ' + label;
                    deleteModal.classList.add('show');
                    deleteModal.setAttribute('aria-hidden', 'false');
                });
            });
            function closeDeleteModal() {
                deleteModal.classList.remove('show');
                deleteModal.setAttribute('aria-hidden', 'true');
            }
            deleteCancel.addEventListener('click', closeDeleteModal);
            deleteModal.addEventListener('click', function(e) {
                if (e.target === deleteModal) closeDeleteModal();
            });
        }
        var successModal = document.getElementById('successModal');
        if (successModal) {
            document.getElementById('successModalOk').addEventListener('click', function() {
                successModal.classList.remove('show');
            });
            successModal.addEventListener('click', function(e) {
                if (e.target === successModal) successModal.classList.remove('show');
            });
        }
    })();
    </script>
    @stack('scripts')
</body>
</html>
