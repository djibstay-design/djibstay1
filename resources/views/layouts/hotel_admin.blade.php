<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Mon Hôtel') — DjibStay</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        :root {
            --sidebar-w: 260px;
            --topbar-h:  60px;
            --blue:      #003580;
            --blue-light:#0071c2;
            --yellow:    #febb02;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:#f2f6fc; color:#1a1a2e; min-height:100vh; }

        /* ══ SIDEBAR ══ */
        .ha-sidebar {
            position: fixed; top:0; left:0;
            width: var(--sidebar-w); height:100vh;
            background: #003580;
            display: flex; flex-direction:column;
            z-index: 1000; overflow-y:auto;
            scrollbar-width:thin;
            scrollbar-color:rgba(255,255,255,0.2) transparent;
            transition: transform .3s ease;
        }
        .ha-sidebar::-webkit-scrollbar { width:4px; }
        .ha-sidebar::-webkit-scrollbar-thumb { background:rgba(255,255,255,0.2); border-radius:2px; }

        /* Logo */
        .ha-logo {
            padding:18px 20px 14px;
            border-bottom:1px solid rgba(255,255,255,0.1);
            display:flex; align-items:center; gap:10px;
            text-decoration:none; flex-shrink:0;
        }
        .ha-logo .icon {
            width:38px; height:38px; background:#febb02;
            border-radius:10px; display:flex;
            align-items:center; justify-content:center;
            font-size:20px; flex-shrink:0;
        }
        .ha-logo .name  { font-size:17px; font-weight:900; color:#fff; line-height:1; }
        .ha-logo .sub   { font-size:10px; color:rgba(255,255,255,0.6); text-transform:uppercase; letter-spacing:.5px; margin-top:2px; }

        /* Hotel info */
        .ha-hotel-info {
            padding:12px 20px;
            border-bottom:1px solid rgba(255,255,255,0.1);
            flex-shrink:0;
        }
        .ha-hotel-info .hotel-name {
            font-size:13px; font-weight:800; color:#fff;
            white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
        }
        .ha-hotel-info .hotel-city {
            font-size:11px; color:rgba(255,255,255,0.6); margin-top:2px;
            display:flex; align-items:center; gap:4px;
        }
        .ha-hotel-score {
            display:inline-flex; align-items:center; gap:4px;
            background:#febb02; color:#003580;
            font-size:11px; font-weight:800;
            padding:2px 8px; border-radius:10px; margin-top:6px;
        }

        /* User */
        .ha-user {
            padding:12px 20px;
            border-bottom:1px solid rgba(255,255,255,0.1);
            display:flex; align-items:center; gap:10px; flex-shrink:0;
        }
        .ha-avatar {
            width:34px; height:34px;
            background:rgba(255,255,255,0.15);
            border-radius:50%; display:flex;
            align-items:center; justify-content:center;
            font-size:14px; font-weight:800; color:#fff; flex-shrink:0;
        }
        .ha-user-name  { font-size:13px; font-weight:700; color:#fff; line-height:1.2; }
        .ha-user-role  { font-size:10px; color:rgba(255,255,255,0.6); text-transform:uppercase; }

        /* Nav */
        .ha-nav { padding:10px 0; flex:1; }
        .ha-section {
            padding:8px 20px 4px;
            font-size:10px; font-weight:800;
            color:rgba(255,255,255,0.4);
            text-transform:uppercase; letter-spacing:.8px;
        }
        .ha-link {
            display:flex; align-items:center; gap:10px;
            padding:9px 20px; color:rgba(255,255,255,0.78);
            text-decoration:none; font-size:13px; font-weight:500;
            transition:all .2s; border-left:3px solid transparent;
        }
        .ha-link i { font-size:16px; width:20px; text-align:center; flex-shrink:0; }
        .ha-link:hover { background:rgba(255,255,255,0.08); color:#fff; border-left-color:rgba(255,255,255,0.3); }
        .ha-link.active { background:rgba(255,255,255,0.12); color:#fff; border-left-color:#febb02; font-weight:700; }
        .ha-link .badge-count {
            margin-left:auto; background:#febb02; color:#003580;
            font-size:10px; font-weight:800; padding:1px 7px; border-radius:10px;
        }

        /* Footer */
        .ha-footer {
            padding:14px 20px;
            border-top:1px solid rgba(255,255,255,0.1); flex-shrink:0;
        }
        .ha-footer .back-link {
            display:flex; align-items:center; gap:8px;
            color:rgba(255,255,255,0.6); text-decoration:none;
            font-size:12px; font-weight:600; margin-bottom:8px;
            transition:color .2s;
        }
        .ha-footer .back-link:hover { color:#fff; }
        .ha-footer form button {
            width:100%; background:rgba(255,255,255,0.08);
            color:rgba(255,255,255,0.8);
            border:1px solid rgba(255,255,255,0.15);
            border-radius:8px; padding:9px 14px;
            font-size:13px; font-weight:600;
            cursor:pointer; transition:all .2s;
            display:flex; align-items:center; gap:8px;
        }
        .ha-footer form button:hover { background:rgba(255,59,48,0.25); color:#fff; }

        /* ══ TOPBAR ══ */
        .ha-topbar {
            position:fixed; top:0; left:var(--sidebar-w); right:0;
            height:var(--topbar-h); background:#fff;
            border-bottom:1px solid #e2e8f0;
            display:flex; align-items:center;
            justify-content:space-between; padding:0 24px;
            z-index:900; box-shadow:0 1px 4px rgba(0,53,128,0.06);
        }
        .ha-topbar .title  { font-size:15px; font-weight:800; color:#003580; }
        .ha-topbar .breadcrumb { font-size:12px; color:#94a3b8; }
        .ha-toggle {
            display:none; background:none; border:none;
            font-size:22px; color:#003580; cursor:pointer; padding:4px;
        }

        /* ══ MAIN ══ */
        .ha-main {
            margin-left:var(--sidebar-w);
            margin-top:var(--topbar-h);
            padding:28px 28px 48px;
            min-height:calc(100vh - var(--topbar-h));
        }
        .ha-alert { margin-bottom:20px; }

        /* ══ OVERLAY ══ */
        .ha-overlay {
            display:none; position:fixed; inset:0;
            background:rgba(0,0,0,0.5); z-index:999;
        }

        /* ══ RESPONSIVE ══ */
        @media(max-width:991px){
            .ha-sidebar { transform:translateX(-100%); }
            .ha-sidebar.open { transform:translateX(0); }
            .ha-topbar { left:0; }
            .ha-main { margin-left:0; }
            .ha-toggle { display:flex; align-items:center; }
            .ha-overlay.show { display:block; }
        }

        /* ══ UTILITAIRES ══ */
        .page-title { font-size:22px; font-weight:900; color:#1e293b; margin:0 0 4px; }
        .page-sub   { font-size:13px; color:#64748b; }
        .card-admin {
            background:#fff; border-radius:14px;
            border:1px solid #e2e8f0;
            box-shadow:0 2px 12px rgba(0,53,128,0.07);
        }
        .btn-ha-primary {
            background:#003580; color:#fff; border:none;
            border-radius:8px; font-weight:700; font-size:14px;
            padding:10px 20px; cursor:pointer; transition:all .2s;
            text-decoration:none; display:inline-flex;
            align-items:center; gap:7px;
        }
        .btn-ha-primary:hover { background:#0071c2; color:#fff; }
        .btn-ha-yellow {
            background:#febb02; color:#003580; border:none;
            border-radius:8px; font-weight:800; font-size:14px;
            padding:10px 20px; cursor:pointer; transition:all .2s;
            text-decoration:none; display:inline-flex;
            align-items:center; gap:7px;
        }
        .btn-ha-yellow:hover { background:#f5a623; color:#003580; }
        .btn-ha-danger {
            background:#fee2e2; color:#991b1b; border:none;
            border-radius:8px; font-weight:700; font-size:13px;
            padding:8px 16px; cursor:pointer; transition:all .2s;
            text-decoration:none; display:inline-flex;
            align-items:center; gap:6px;
        }
        .btn-ha-danger:hover { background:#fecaca; }
        .btn-ha-outline {
            background:#fff; color:#003580;
            border:2px solid #003580;
            border-radius:8px; font-weight:700; font-size:13px;
            padding:8px 16px; cursor:pointer; transition:all .2s;
            text-decoration:none; display:inline-flex;
            align-items:center; gap:6px;
        }
        .btn-ha-outline:hover { background:#003580; color:#fff; }
        .badge-confirmee  { background:#dcfce7; color:#14532d; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:700; }
        .badge-en_attente { background:#fef3c7; color:#92400e; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:700; }
        .badge-annulee    { background:#fee2e2; color:#991b1b; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:700; }
        .ha-table { width:100%; border-collapse:collapse; font-size:13px; }
        .ha-table th { background:#f8fafc; padding:10px 14px; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:.5px; border-bottom:2px solid #e2e8f0; text-align:left; }
        .ha-table td { padding:12px 14px; border-bottom:1px solid #f1f5f9; color:#1e293b; vertical-align:middle; }
        .ha-table tr:last-child td { border-bottom:none; }
        .ha-table tr:hover td { background:#f8fafc; }
        .form-ha label { font-size:12px; font-weight:700; color:#003580; text-transform:uppercase; letter-spacing:.4px; margin-bottom:6px; display:block; }
        .form-ha .form-control, .form-ha .form-select { border:2px solid #e2e8f0; border-radius:8px; padding:10px 13px; font-size:14px; }
        .form-ha .form-control:focus, .form-ha .form-select:focus { border-color:#0071c2; box-shadow:0 0 0 3px rgba(0,113,194,0.1); }
    </style>
</head>
<body>

{{-- SIDEBAR --}}
<aside class="ha-sidebar" id="haSidebar">

    {{-- Logo --}}
    {{-- Logo --}}
@php
    $haAppName = \App\Models\SiteSetting::get('app_name','DjibStay');
    $haLogo    = \App\Models\SiteSetting::get('app_logo','');
@endphp
<a href="{{ route('hoteladmin.dashboard') }}" class="ha-logo">
    @if($haLogo)
        <img src="{{ asset('storage/'.$haLogo) }}" alt="{{ $haAppName }}"
             style="height:38px;width:38px;object-fit:contain;border-radius:10px;background:#fff;padding:3px;flex-shrink:0;">
    @else
        <div class="icon">🏨</div>
    @endif
    <div>
        <div class="name">{{ $haAppName }}</div>
        <div class="sub">Espace Hôtel</div>
    </div>
</a>

    {{-- Info hôtel --}}
    @php
        $user = auth()->user();
        $sidebarHotel = \App\Models\Hotel::where(fn($q) => $q->where('user_id',$user->id)->orWhere('admin_id',$user->id))->first();
        $sidebarAvg   = $sidebarHotel ? round(\App\Models\Avis::where('hotel_id',$sidebarHotel->id)->avg('note') ?? 0, 1) : 0;
        $pendingCount = $sidebarHotel ? \App\Models\Reservation::whereHas('chambre.typeChambre', fn($q) => $q->where('hotel_id',$sidebarHotel->id))->where('statut','EN_ATTENTE')->count() : 0;
    @endphp
    @if($sidebarHotel)
    <div class="ha-hotel-info">
        <div class="hotel-name">{{ $sidebarHotel->nom }}</div>
        <div class="hotel-city"><i class="bi bi-geo-alt-fill" style="color:#febb02;"></i>{{ $sidebarHotel->ville ?? 'Djibouti' }}</div>
        @if($sidebarAvg > 0)
        <div class="ha-hotel-score"><i class="bi bi-star-fill"></i>{{ number_format($sidebarAvg,1) }} / 5</div>
        @endif
    </div>
    @endif

    {{-- User --}}
    <div class="ha-user">
        <div class="ha-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
        <div>
            <div class="ha-user-name">{{ auth()->user()->prenom ?? auth()->user()->name }}</div>
            <div class="ha-user-role">Admin Hôtel</div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="ha-nav">

        <div class="ha-section">Général</div>
        <a href="{{ route('hoteladmin.dashboard') }}"
           class="ha-link {{ request()->routeIs('hoteladmin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="ha-section">Mon Hôtel</div>
        <a href="{{ route('hoteladmin.hotel.edit') }}"
           class="ha-link {{ request()->routeIs('hoteladmin.hotel.*') ? 'active' : '' }}">
            <i class="bi bi-building"></i> Informations
        </a>
        <a href="{{ route('hoteladmin.photos.index') }}"
           class="ha-link {{ request()->routeIs('hoteladmin.photos.*') ? 'active' : '' }}">
            <i class="bi bi-images"></i> Photos
        </a>
        <a href="{{ route('hoteladmin.types-chambre.index') }}"
           class="ha-link {{ request()->routeIs('hoteladmin.types-chambre.*') ? 'active' : '' }}">
            <i class="bi bi-grid"></i> Types de chambre
        </a>
        <a href="{{ route('hoteladmin.chambres.index') }}"
           class="ha-link {{ request()->routeIs('hoteladmin.chambres.*') ? 'active' : '' }}">
            <i class="bi bi-door-open"></i> Chambres
        </a>

        <div class="ha-section">Réservations</div>
        <a href="{{ route('hoteladmin.reservations.index') }}"
           class="ha-link {{ request()->routeIs('hoteladmin.reservations.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i> Toutes
            @if($pendingCount > 0)
                <span class="badge-count">{{ $pendingCount }}</span>
            @endif
        </a>
        <a href="{{ route('hoteladmin.reservations.index', ['statut'=>'EN_ATTENTE']) }}"
           class="ha-link">
            <i class="bi bi-hourglass-split"></i> En attente
            @if($pendingCount > 0)
                <span class="badge-count">{{ $pendingCount }}</span>
            @endif
        </a>

        <div class="ha-section">Clients</div>
        <a href="{{ route('hoteladmin.avis.index') }}"
           class="ha-link {{ request()->routeIs('hoteladmin.avis.*') ? 'active' : '' }}">
            <i class="bi bi-star"></i> Avis clients
        </a>

    </nav>

    {{-- Footer --}}
    <div class="ha-footer">
        <a href="{{ route('home') }}" class="back-link">
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
<div class="ha-overlay" id="haOverlay"></div>

{{-- TOPBAR --}}
<header class="ha-topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="ha-toggle" id="haToggle"><i class="bi bi-list"></i></button>
        <div>
            <div class="title">@yield('page_title', 'Dashboard')</div>
            <div class="breadcrumb">
                <a href="{{ route('hoteladmin.dashboard') }}" style="color:#94a3b8;text-decoration:none;">Mon Hôtel</a>
                <span> › </span>
                <span>@yield('page_title', 'Dashboard')</span>
            </div>
        </div>
    </div>
    <div class="d-flex align-items-center gap-3">
        <span style="font-size:12px;color:#64748b;">
            <i class="bi bi-clock me-1"></i>{{ now()->locale('fr')->translatedFormat('d M Y') }}
        </span>
        <a href="{{ route('hotels.show', $sidebarHotel) }}" target="_blank"
           style="background:#003580;color:#fff;padding:7px 14px;border-radius:7px;text-decoration:none;font-size:12px;font-weight:700;">
            <i class="bi bi-eye me-1"></i> Mon hôtel
        </a>
    </div>
</header>

{{-- MAIN --}}
<main class="ha-main">

    @if(session('success'))
    <div class="ha-alert">
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="ha-alert">
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="ha-alert">
        <div class="alert alert-danger alert-dismissible fade show">
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
    const sidebar = document.getElementById('haSidebar');
    const overlay = document.getElementById('haOverlay');
    const toggle  = document.getElementById('haToggle');
    toggle.addEventListener('click', () => { sidebar.classList.toggle('open'); overlay.classList.toggle('show'); });
    overlay.addEventListener('click', () => { sidebar.classList.remove('open'); overlay.classList.remove('show'); });
</script>
@stack('scripts')
</body>
</html>