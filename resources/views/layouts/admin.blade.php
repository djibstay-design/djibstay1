<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — DjibStay</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        :root {
            --blue:       #003580;
            --blue-light: #0071c2;
            --yellow:     #febb02;
            --sidebar-w:  260px;
            --topbar-h:   60px;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior:smooth; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f2f6fc;
            color: #1a1a2e;
            min-height: 100vh;
        }

        /* ══════════════════════════════
           SIDEBAR
        ══════════════════════════════ */
        .admin-sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--blue);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform .3s ease;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.2) transparent;
        }
        .admin-sidebar::-webkit-scrollbar { width: 4px; }
        .admin-sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 2px; }

        /* Logo */
        .sidebar-logo {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex; align-items: center; gap: 10px;
            text-decoration: none; flex-shrink: 0;
        }
        .sidebar-logo .logo-icon {
            width: 38px; height: 38px;
            background: var(--yellow);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; flex-shrink: 0;
        }
        .sidebar-logo .logo-text {
            font-size: 18px; font-weight: 900;
            color: #fff; line-height: 1;
        }
        .sidebar-logo .logo-sub {
            font-size: 10px; color: rgba(255,255,255,0.6);
            text-transform: uppercase; letter-spacing: .5px;
            margin-top: 2px;
        }

        /* User info */
        .sidebar-user {
            padding: 14px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex; align-items: center; gap: 10px;
            flex-shrink: 0;
        }
        .sidebar-user .avatar {
            width: 36px; height: 36px;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; font-weight: 800; color: #fff;
            flex-shrink: 0;
        }
        .sidebar-user .user-name {
            font-size: 13px; font-weight: 700; color: #fff; line-height: 1.2;
        }
        .sidebar-user .user-role {
            font-size: 10px; color: rgba(255,255,255,0.6);
            text-transform: uppercase; letter-spacing: .4px;
        }
        .sidebar-user .role-badge {
            margin-left: auto;
            background: var(--yellow);
            color: var(--blue);
            font-size: 9px; font-weight: 800;
            padding: 2px 7px; border-radius: 10px;
            text-transform: uppercase; letter-spacing: .3px;
            flex-shrink: 0;
        }

        /* Nav */
        .sidebar-nav { padding: 12px 0; flex: 1; }
        .sidebar-section {
            padding: 8px 20px 4px;
            font-size: 10px; font-weight: 800;
            color: rgba(255,255,255,0.4);
            text-transform: uppercase; letter-spacing: .8px;
        }
        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 20px;
            color: rgba(255,255,255,0.78);
            text-decoration: none;
            font-size: 13px; font-weight: 500;
            transition: all .2s;
            border-left: 3px solid transparent;
            position: relative;
        }
        .sidebar-link i { font-size: 16px; width: 20px; text-align: center; flex-shrink: 0; }
        .sidebar-link:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
            border-left-color: rgba(255,255,255,0.3);
        }
        .sidebar-link.active {
            background: rgba(255,255,255,0.12);
            color: #fff;
            border-left-color: var(--yellow);
            font-weight: 700;
        }
        .sidebar-link .badge-count {
            margin-left: auto;
            background: var(--yellow);
            color: var(--blue);
            font-size: 10px; font-weight: 800;
            padding: 1px 7px; border-radius: 10px;
        }

        /* Logout */
        .sidebar-footer {
            padding: 14px 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            flex-shrink: 0;
        }
        .sidebar-footer form button {
            width: 100%;
            background: rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.8);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 8px;
            padding: 9px 14px;
            font-size: 13px; font-weight: 600;
            cursor: pointer; transition: all .2s;
            display: flex; align-items: center; gap: 8px;
        }
        .sidebar-footer form button:hover {
            background: rgba(255,59,48,0.25);
            border-color: rgba(255,59,48,0.4);
            color: #fff;
        }
        .sidebar-footer .back-site {
            display: flex; align-items: center; gap: 8px;
            color: rgba(255,255,255,0.6);
            text-decoration: none; font-size: 12px;
            font-weight: 600; margin-bottom: 8px;
            transition: color .2s;
        }
        .sidebar-footer .back-site:hover { color: #fff; }

        /* ══════════════════════════════
           TOPBAR
        ══════════════════════════════ */
        .admin-topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 900;
            box-shadow: 0 1px 4px rgba(0,53,128,0.06);
        }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .topbar-title { font-size: 15px; font-weight: 800; color: #003580; }
        .topbar-breadcrumb {
            font-size: 12px; color: #94a3b8;
            display: flex; align-items: center; gap: 4px;
        }
        .topbar-right { display: flex; align-items: center; gap: 12px; }

        /* Burger mobile */
        .sidebar-toggle {
            display: none;
            background: none; border: none;
            font-size: 22px; color: #003580;
            cursor: pointer; padding: 4px;
        }

        /* ══════════════════════════════
           MAIN CONTENT
        ══════════════════════════════ */
        .admin-main {
            margin-left: var(--sidebar-w);
            margin-top: var(--topbar-h);
            padding: 28px 28px 48px;
            min-height: calc(100vh - var(--topbar-h));
        }

        /* ── ALERTS ── */
        .admin-alert { margin-bottom: 20px; }

        /* ── OVERLAY mobile ── */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        /* ══════════════════════════════
           RESPONSIVE
        ══════════════════════════════ */
        @media(max-width:991px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar.open { transform: translateX(0); }
            .admin-topbar { left: 0; }
            .admin-main { margin-left: 0; }
            .sidebar-toggle { display: flex; align-items: center; }
            .sidebar-overlay.show { display: block; }
        }
    </style>
</head>
<body>

{{-- ══════════════════════════════
     SIDEBAR
══════════════════════════════ --}}
<aside class="admin-sidebar" id="adminSidebar">

    {{-- Logo --}}
    {{-- Logo --}}
@php
    $sidebarAppName = \App\Models\SiteSetting::get('app_name','DjibStay');
    $sidebarLogo    = \App\Models\SiteSetting::get('app_logo','');
@endphp
<a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
    @if($sidebarLogo)
        <img src="{{ asset('storage/'.$sidebarLogo) }}" alt="{{ $sidebarAppName }}"
             style="height:38px;width:38px;object-fit:contain;border-radius:10px;background:#fff;padding:3px;flex-shrink:0;">
    @else
        <div class="logo-icon">🏨</div>
    @endif
    <div>
        <div class="logo-text">{{ $sidebarAppName }}</div>
        <div class="logo-sub">Administration</div>
    </div>
</a>

    {{-- User --}}
    <div class="sidebar-user">
        <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
        <div>
            <div class="user-name">{{ auth()->user()->name }}</div>
            <div class="user-role">{{ auth()->user()->role }}</div>
        </div>
        <span class="role-badge">
            {{ auth()->user()->role === 'SUPER_ADMIN' ? 'Super' : 'Admin' }}
        </span>
    </div>

    {{-- Nav --}}
    <nav class="sidebar-nav">

        {{-- GÉNÉRAL --}}
        <div class="sidebar-section">Général</div>
        <a href="{{ route('admin.dashboard') }}"
           class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        {{-- HÔTELS --}}
        <div class="sidebar-section">Hôtels</div>
        <a href="{{ route('admin.hotels.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.hotels.*') ? 'active' : '' }}">
            <i class="bi bi-building"></i> Hôtels
        </a>
        <a href="{{ route('admin.types-chambre.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.types-chambre.*') ? 'active' : '' }}">
            <i class="bi bi-grid"></i> Types de chambre
        </a>
        <a href="{{ route('admin.chambres.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.chambres.*') ? 'active' : '' }}">
            <i class="bi bi-door-open"></i> Chambres
        </a>
        <a href="{{ route('admin.images.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.images.*') ? 'active' : '' }}">
            <i class="bi bi-images"></i> Galerie photos
        </a>

        {{-- RÉSERVATIONS --}}
        <div class="sidebar-section">Réservations</div>
        <a href="{{ route('admin.reservations.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i> Réservations
            @php
                $pending = \App\Models\Reservation::where('statut','EN_ATTENTE')->count();
            @endphp
            @if($pending > 0)
                <span class="badge-count">{{ $pending }}</span>
            @endif
        </a>
        <a href="{{ route('admin.avis.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.avis.*') ? 'active' : '' }}">
            <i class="bi bi-star"></i> Avis clients
        </a>
        {{-- PARTENAIRES --}}
@if(auth()->user()->role === 'SUPER_ADMIN')
<div class="sidebar-section">Partenaires</div>
<a href="{{ route('admin.partenaires.index') }}"
   class="sidebar-link {{ request()->routeIs('admin.partenaires.*') ? 'active' : '' }}">
    <i class="bi bi-building-add"></i> Espace Partenaire
    @php $newDemandes = \App\Models\DemandePartenaire::where('statut','en_attente')->count(); @endphp
    @if($newDemandes > 0)
        <span class="badge-count">{{ $newDemandes }}</span>
    @endif
</a>
{{-- BOÎTE MAIL --}}
@if(auth()->user()->role === 'SUPER_ADMIN')
<a href="{{ route('admin.boite-mail.index') }}"
   class="sidebar-link {{ request()->routeIs('admin.boite-mail.*') ? 'active' : '' }}">
    <i class="bi bi-envelope"></i> Boîte Mail
    @php $nonLus = \App\Models\ContactMessage::where('lu', false)->where('archive', false)->count(); @endphp
    @if($nonLus > 0)
        <span class="badge-count">{{ $nonLus }}</span>
    @endif
</a>
@endif
@endif

        {{-- SUPER ADMIN ONLY --}}
        @if(auth()->user()->role === 'SUPER_ADMIN')
        <div class="sidebar-section">Administration</div>
        <a href="{{ route('admin.payment-methods.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.payment-methods.*') ? 'active' : '' }}">
            <i class="bi bi-credit-card"></i> Types de paiement
        </a>
        <a href="{{ route('admin.users.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Utilisateurs
        </a>
        <a href="{{ route('admin.settings.edit') }}"
           class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i> Paramètres
        </a>
        @endif

    </nav>

    {{-- Footer --}}
    <div class="sidebar-footer">
        <a href="{{ route('home') }}" class="back-site">
            <i class="bi bi-arrow-left-circle"></i> Voir le site public
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">
                <i class="bi bi-box-arrow-left"></i> Déconnexion
            </button>
        </form>
    </div>

</aside>

{{-- Overlay mobile --}}
<div class="sidebar-overlay" id="sidebarOverlay"></div>

{{-- ══════════════════════════════
     TOPBAR
══════════════════════════════ --}}
<header class="admin-topbar">
    <div class="topbar-left">
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>
        <div>
            <div class="topbar-title">@yield('page_title', 'Dashboard')</div>
            <div class="topbar-breadcrumb">
                <a href="{{ route('admin.dashboard') }}" style="color:#94a3b8;text-decoration:none;">Admin</a>
                @hasSection('page_title')
                    <span>›</span>
                    <span>@yield('page_title')</span>
                @endif
            </div>
        </div>
    </div>
    <div class="topbar-right">
        <span style="font-size:12px;color:#64748b;">
            <i class="bi bi-clock me-1"></i>
            {{ now()->locale('fr')->translatedFormat('d M Y') }}
        </span>
        <a href="{{ route('home') }}"
           style="background:#003580;color:#fff;padding:7px 14px;border-radius:7px;text-decoration:none;font-size:12px;font-weight:700;">
            <i class="bi bi-eye me-1"></i> Site public
        </a>
    </div>
</header>

{{-- ══════════════════════════════
     MAIN CONTENT
══════════════════════════════ --}}
<main class="admin-main">

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="admin-alert">
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="admin-alert">
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('error') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="admin-alert">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Erreurs :</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @yield('content')

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle sidebar mobile
    const sidebar  = document.getElementById('adminSidebar');
    const overlay  = document.getElementById('sidebarOverlay');
    const toggle   = document.getElementById('sidebarToggle');

    toggle.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('show');
    });
    overlay.addEventListener('click', () => {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    });
</script>
@stack('scripts')
</body>
</html>