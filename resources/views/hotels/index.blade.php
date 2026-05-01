@extends('layouts.app')

@section('title', 'Hôtels à Djibouti — DjibStay')

@push('styles')
<style>
    /* ── HERO ── */
    .hotels-hero {
        background: linear-gradient(135deg, #003580 0%, #0071c2 100%);
        padding: 40px 0 32px;
        color: #fff;
    }
    .hotels-hero h1 { font-size:clamp(22px,4vw,36px); font-weight:900; }
    .hotels-hero p  { color:rgba(255,255,255,0.82); font-size:15px; }

    /* ── LAYOUT ── */
    .hotels-layout { display:grid; grid-template-columns:280px 1fr; gap:28px; align-items:start; }
    @media(max-width:991px){ .hotels-layout { grid-template-columns:1fr; } }

    /* ── SIDEBAR FILTRES ── */
    .filter-sidebar {
        background:#fff;
        border-radius:12px;
        border:1px solid #e2e8f0;
        box-shadow:0 2px 12px rgba(0,53,128,0.07);
        padding:24px;
        position:sticky;
        top:80px;
    }
    .filter-sidebar h5 {
        font-size:15px; font-weight:800; color:#003580;
        border-bottom:2px solid #e2e8f0; padding-bottom:10px; margin-bottom:16px;
    }
    .filter-group { margin-bottom:20px; }
    .filter-group label {
        font-size:12px; font-weight:700; color:#64748b;
        text-transform:uppercase; letter-spacing:.5px; margin-bottom:7px; display:block;
    }
    .filter-sidebar .form-control,
    .filter-sidebar .form-select {
        border:2px solid #e2e8f0; border-radius:8px;
        font-size:13px; padding:8px 12px; color:#1a1a2e;
    }
    .filter-sidebar .form-control:focus,
    .filter-sidebar .form-select:focus {
        border-color:#0071c2; box-shadow:0 0 0 3px rgba(0,113,194,0.1);
    }
    .btn-filter {
        background:#003580; color:#fff; border:none;
        border-radius:8px; font-weight:700; font-size:14px;
        padding:10px 0; width:100%; transition:background .2s;
    }
    .btn-filter:hover { background:#0071c2; }
    .btn-reset {
        background:#f1f5f9; color:#64748b; border:none;
        border-radius:8px; font-weight:600; font-size:13px;
        padding:8px 0; width:100%; margin-top:8px; transition:background .2s;
    }
    .btn-reset:hover { background:#e2e8f0; color:#003580; }

    /* ── SORT BAR ── */
    .sort-bar {
        background:#fff; border-radius:10px;
        border:1px solid #e2e8f0; padding:12px 18px;
        display:flex; align-items:center; gap:12px;
        flex-wrap:wrap; margin-bottom:20px;
        box-shadow:0 1px 4px rgba(0,53,128,0.06);
    }
    .sort-bar span { font-size:14px; color:#64748b; font-weight:500; }
    .sort-bar select { border:2px solid #e2e8f0; border-radius:7px; font-size:13px; padding:6px 12px; color:#003580; font-weight:600; }

    /* ── HOTEL CARD ── */
    .hotel-card {
        background:#fff; border-radius:12px;
        border:1px solid #e2e8f0;
        box-shadow:0 2px 10px rgba(0,53,128,0.07);
        overflow:hidden; transition:all .25s;
        display:flex; flex-direction:column;
        height:100%;
    }
    .hotel-card:hover {
        box-shadow:0 10px 36px rgba(0,53,128,0.15);
        transform:translateY(-4px);
    }
    .hotel-card-img {
        height:195px; width:100%;
        object-fit:cover; display:block;
    }
    .hotel-card-body { padding:16px; flex:1; display:flex; flex-direction:column; }
    .hotel-card-title {
        font-size:15px; font-weight:700; color:#1e293b;
        white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
        margin-bottom:3px;
    }
    .hotel-card-city { font-size:13px; color:#64748b; margin-bottom:8px; }
    .hotel-card-city i { color:#0071c2; }
    .badge-rating {
        background:#003580; color:#fff;
        font-weight:700; font-size:12px;
        padding:4px 9px; border-radius:6px;
        display:inline-flex; align-items:center; gap:4px;
    }
    .equip-badge {
        font-size:11px; color:#0071c2;
        background:#e8f0f8; padding:2px 8px;
        border-radius:4px; font-weight:500;
    }
    .hotel-card-price {
        margin-top:auto; padding-top:10px;
        border-top:1px solid #f1f5f9;
        display:flex; justify-content:space-between; align-items:center;
    }
    .hotel-card-price strong { font-size:17px; font-weight:800; color:#003580; }
    .btn-voir {
        background:#003580; color:#fff; border:none;
        border-radius:7px; font-size:13px; font-weight:600;
        padding:7px 16px; text-decoration:none; transition:background .2s;
    }
    .btn-voir:hover { background:#0071c2; color:#fff; }

    /* ── EMPTY STATE ── */
    .empty-state { text-align:center; padding:60px 20px; }
    .empty-state .icon { font-size:64px; margin-bottom:16px; }

    /* ── PAGINATION ── */
    .page-link { color:#003580; }
    .page-item.active .page-link { background:#003580; border-color:#003580; }
</style>
@endpush

@section('content')

{{-- HERO --}}
<section class="hotels-hero">
    <div class="container" style="max-width:1320px;">
        <h1 class="mb-2">
            <i class="bi bi-building me-2"></i>
            @if(request('city'))
                Hôtels à {{ request('city') }}
            @else
                Tous les hôtels à Djibouti
            @endif
        </h1>
        <p class="mb-0">
            {{ $hotels->total() }} hôtel(s) disponible(s)
            @if(request('check_in') && request('check_out'))
                · Du {{ \Carbon\Carbon::parse(request('check_in'))->format('d/m/Y') }}
                au {{ \Carbon\Carbon::parse(request('check_out'))->format('d/m/Y') }}
            @endif
        </p>
    </div>
</section>

{{-- CONTENU --}}
<div class="container py-4" style="max-width:1320px;">
    <div class="hotels-layout">

        {{-- ── SIDEBAR FILTRES ── --}}
        <aside>
            <div class="filter-sidebar">
                <h5><i class="bi bi-funnel me-2"></i>Filtres</h5>
                <form method="GET" action="{{ route('hotels.index') }}">

                    {{-- Conserver dates/voyageurs si déjà renseignés --}}
                    @foreach(['check_in','check_out','adults','children','rooms'] as $param)
                        @if(request($param))
                            <input type="hidden" name="{{ $param }}" value="{{ request($param) }}">
                        @endif
                    @endforeach

                    <div class="filter-group">
                        <label>Recherche</label>
                        <input type="text" name="search" class="form-control"
                               placeholder="Nom d'hôtel..."
                               value="{{ request('search') }}">
                    </div>

                    <div class="filter-group">
                        <label>Ville</label>
                        <select name="city" class="form-select">
                            <option value="">Toutes les villes</option>
                            @foreach(\App\Models\Hotel::whereNotNull('ville')->distinct()->orderBy('ville')->pluck('ville') as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Prix min / nuit (DJF)</label>
                        <input type="number" name="min_price" class="form-control"
                               placeholder="Ex: 5000"
                               value="{{ request('min_price') }}" min="0">
                    </div>

                    <div class="filter-group">
                        <label>Prix max / nuit (DJF)</label>
                        <input type="number" name="max_price" class="form-control"
                               placeholder="Ex: 50000"
                               value="{{ request('max_price') }}" min="0">
                    </div>

                    <div class="filter-group">
                        <label>Note minimale</label>
                        <select name="min_rating" class="form-select">
                            <option value="">Toutes les notes</option>
                            <option value="4" {{ request('min_rating') == '4' ? 'selected' : '' }}>⭐ 4+ Très bien</option>
                            <option value="3" {{ request('min_rating') == '3' ? 'selected' : '' }}>⭐ 3+ Bien</option>
                            <option value="2" {{ request('min_rating') == '2' ? 'selected' : '' }}>⭐ 2+ Correct</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-filter">
                        <i class="bi bi-search me-1"></i> Appliquer
                    </button>
                    <a href="{{ route('hotels.index') }}" class="btn-reset d-block text-center text-decoration-none">
                        <i class="bi bi-x-circle me-1"></i> Réinitialiser
                    </a>
                </form>
            </div>
        </aside>

        {{-- ── LISTE HÔTELS ── --}}
        <div>
            {{-- Sort bar --}}
            <div class="sort-bar">
                <span>Trier par :</span>
                <form method="GET" action="{{ route('hotels.index') }}" id="sortForm">
                    @foreach(request()->except('sort') as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <select name="sort" class="form-select d-inline-block w-auto"
                            onchange="document.getElementById('sortForm').submit()">
                        <option value="recommended" {{ request('sort','recommended') == 'recommended' ? 'selected' : '' }}>
                            ⭐ Recommandés
                        </option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                            💰 Prix croissant
                        </option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                            💰 Prix décroissant
                        </option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>
                            🏆 Mieux notés
                        </option>
                    </select>
                </form>
                <span class="ms-auto" style="font-size:13px;color:#94a3b8;">
                    {{ $hotels->firstItem() ?? 0 }}–{{ $hotels->lastItem() ?? 0 }}
                    sur {{ $hotels->total() }} résultat(s)
                </span>
            </div>

            {{-- Résultats --}}
            @if($hotels->isEmpty())
                <div class="empty-state">
                    <div class="icon">🏨</div>
                    <h3 style="color:#003580;font-weight:700;">Aucun hôtel trouvé</h3>
                    <p class="text-muted mb-4">Essayez d'autres filtres ou une autre période.</p>
                    <a href="{{ route('hotels.index') }}" class="btn-voir px-4 py-2">
                        Réinitialiser les filtres
                    </a>
                </div>
            @else
                <div class="row g-4">
                    @foreach($hotels as $hotel)
                        @php
                            $imgFile = null;
                            $nomLower = strtolower($hotel->nom);
                            foreach($hotelImageMap as $key => $file) {
                                if(str_contains($nomLower, $key)) { $imgFile = $file; break; }
                            }
                            $mainImg = $hotel->mainImage ?? null;
                            if (!$imgFile && !$mainImg) {
                                $imgFile = $hotelImagesFallback[$hotel->id % count($hotelImagesFallback)];
                            }
                            $imgUrl = $mainImg
                                ? $mainImg->url
                                : asset('images/' . $imgFile);
                            $minPrice = $hotel->typesChambre->min('prix_par_nuit');
                            $rating   = round($hotel->avis_avg_note ?? 0, 1);
                            $avisCount = $hotel->avis->count();
                        @endphp
                        <div class="col-sm-6 col-xl-4">
                            <div class="hotel-card">
                                <a href="{{ route('hotels.show', $hotel) }}">
                                    <img src="{{ $imgUrl }}"
                                         alt="{{ $hotel->nom }}"
                                         class="hotel-card-img"
                                         loading="lazy"
                                         onerror="this.src='{{ asset('images/ayla.jpg') }}'">
                                </a>
                                <div class="hotel-card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h3 class="hotel-card-title flex-1" title="{{ $hotel->nom }}">
                                            {{ $hotel->nom }}
                                        </h3>
                                        @if($rating > 0)
                                            <span class="badge-rating ms-2 flex-shrink-0">
                                                <i class="bi bi-star-fill" style="font-size:10px;"></i>
                                                {{ number_format($rating,1) }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="hotel-card-city">
                                        <i class="bi bi-geo-alt-fill"></i>
                                        {{ $hotel->ville ?? 'Djibouti' }}
                                        @if($avisCount > 0)
                                            · <span style="color:#94a3b8;">{{ $avisCount }} avis</span>
                                        @endif
                                    </p>
                                    @php
                                        $hasWifi = $hotel->typesChambre->contains('has_wifi', true);
                                        $hasAC   = $hotel->typesChambre->contains('has_climatisation', true);
                                        $hasMini = $hotel->typesChambre->contains('has_minibar', true);
                                    @endphp
                                    <div class="d-flex gap-2 flex-wrap mb-2">
                                        @if($hasWifi)
                                            <span class="equip-badge"><i class="bi bi-wifi"></i> WiFi</span>
                                        @endif
                                        @if($hasAC)
                                            <span class="equip-badge"><i class="bi bi-snow2"></i> Clim</span>
                                        @endif
                                        @if($hasMini)
                                            <span class="equip-badge"><i class="bi bi-cup-straw"></i> Minibar</span>
                                        @endif
                                    </div>
                                    <div class="hotel-card-price">
                                        <div>
                                            @if($minPrice)
                                                <div style="font-size:11px;color:#94a3b8;">À partir de</div>
                                                <strong>{{ number_format($minPrice,0,',',' ') }} DJF</strong>
                                                <span style="font-size:12px;color:#94a3b8;">/nuit</span>
                                            @else
                                                <span style="font-size:13px;color:#94a3b8;">Prix sur demande</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('hotels.show', $hotel) }}" class="btn-voir">
                                            Voir <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($hotels->hasPages())
                    <div class="d-flex justify-content-center mt-5">
                        {{ $hotels->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>
</div>

@endsection