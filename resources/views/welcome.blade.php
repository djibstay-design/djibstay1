@extends('layouts.app')
@section('title', \App\Models\SiteSetting::get('app_name','DjibStay').' — Les meilleurs hôtels de Djibouti')

@push('styles')
<style>
/* ══ HERO ══ */
.hero-section {
    position:relative;
    height:680px;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
}
.hero-bg {
    position:absolute;
    inset:0;
    background-size:cover;
    background-position:center;
    transform:scale(1.05);
    transition:transform 8s ease;
}
.hero-bg.loaded { transform:scale(1); }
.hero-overlay {
    position:absolute;
    inset:0;
    background:linear-gradient(160deg,rgba(0,30,80,0.55) 0%,rgba(0,80,160,0.40) 100%);
}
.hero-content {
    position:relative;
    z-index:2;
    text-align:center;
    color:#fff;
    width:100%;
    padding:0 20px;
}
.hero-title {
    font-size:clamp(28px,5vw,54px);
    font-weight:900;
    line-height:1.1;
    margin-bottom:12px;
    text-shadow:0 3px 20px rgba(0,0,0,0.35);
    letter-spacing:-1px;
}
.hero-title span { color:#febb02; }
.hero-sub {
    font-size:clamp(14px,2vw,18px);
    color:rgba(255,255,255,0.92);
    margin-bottom:32px;
    font-weight:500;
}
.hero-sub span {
    background:rgba(255,255,255,0.18);
    border-radius:20px;
    padding:5px 14px;
    margin:0 4px;
    font-size:13px;
    font-weight:600;
    backdrop-filter:blur(4px);
    border:1px solid rgba(255,255,255,0.2);
}

/* ══ SEARCH BOX ══ */
.search-box {
    background:#fff;
    border-radius:16px;
    padding:10px;
    display:flex;
    flex-wrap:wrap;
    gap:6px;
    max-width:960px;
    margin:0 auto;
    box-shadow:0 20px 60px rgba(0,0,0,0.30);
}
.search-field {
    flex:1;
    min-width:160px;
    display:flex;
    flex-direction:column;
    padding:10px 14px;
    border-right:1px solid #e8f0fe;
    cursor:pointer;
    border-radius:10px;
    transition:background .2s;
}
.search-field:hover { background:#f8faff; }
.search-field:last-of-type { border-right:none; }
.search-field label {
    font-size:10px;
    font-weight:800;
    color:#003580;
    text-transform:uppercase;
    letter-spacing:.5px;
    margin-bottom:4px;
    display:flex;
    align-items:center;
    gap:5px;
}
.search-field input, .search-field select {
    border:none;
    outline:none;
    font-size:15px;
    font-weight:600;
    color:#1e293b;
    background:transparent;
    padding:0;
    width:100%;
}
.search-field input::placeholder { color:#94a3b8; font-weight:400; }
.search-btn {
    background:linear-gradient(135deg,#003580,#0071c2);
    color:#fff;
    border:none;
    border-radius:10px;
    padding:16px 32px;
    font-weight:800;
    font-size:16px;
    cursor:pointer;
    transition:all .2s;
    white-space:nowrap;
    display:flex;
    align-items:center;
    gap:8px;
    align-self:stretch;
    box-shadow:0 4px 16px rgba(0,53,128,0.3);
}
.search-btn:hover { background:linear-gradient(135deg,#0071c2,#003580); transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,53,128,0.4); }
@media(max-width:768px){
    .hero-section { height:auto; padding:80px 0 40px; }
    .search-box { border-radius:12px; }
    .search-field { min-width:calc(50% - 6px); border-right:none; border-bottom:1px solid #f1f5f9; }
    .search-btn { width:100%; justify-content:center; }
}

/* ══ STATS BAR ══ */
.stats-bar { background:#003580; padding:16px 0; }
.stats-bar-inner { display:flex; align-items:center; justify-content:center; flex-wrap:wrap; }
.stat-item { display:flex; align-items:center; gap:10px; padding:8px 32px; border-right:1px solid rgba(255,255,255,0.15); color:#fff; }
.stat-item:last-child { border-right:none; }
.stat-item .num { font-size:20px; font-weight:900; color:#febb02; line-height:1; }
.stat-item .lbl { font-size:11px; color:rgba(255,255,255,0.8); font-weight:600; margin-top:2px; }
@media(max-width:600px){ .stat-item { padding:6px 14px; } .stat-item .num { font-size:16px; } }

/* ══ SECTION TITLES ══ */
.section-title { font-size:clamp(22px,3vw,30px); font-weight:900; color:#003580; margin-bottom:6px; }
.section-sub   { font-size:15px; color:#64748b; margin-bottom:32px; }

/* ══ HOTEL CARDS ══ */
.hotel-card {
    background:#fff;
    border-radius:14px;
    border:1px solid #e2e8f0;
    box-shadow:0 2px 12px rgba(0,53,128,0.08);
    overflow:hidden;
    transition:all .25s;
    height:100%;
    display:flex;
    flex-direction:column;
    text-decoration:none;
    color:inherit;
}
.hotel-card:hover {
    transform:translateY(-6px);
    box-shadow:0 16px 48px rgba(0,53,128,0.18);
    border-color:#0071c2;
    color:inherit;
    text-decoration:none;
}
.hotel-card-img {
    position:relative;
    height:220px;
    overflow:hidden;
    background:#0f172a;
}
.hotel-card-img img {
    width:100%;
    height:100%;
    object-fit:cover;
    transition:transform .5s;
    display:block;
}
.hotel-card:hover .hotel-card-img img { transform:scale(1.08); }
.hotel-card-badge {
    position:absolute;
    top:12px;
    left:12px;
    background:#003580;
    color:#fff;
    font-size:11px;
    font-weight:800;
    padding:4px 12px;
    border-radius:20px;
    backdrop-filter:blur(4px);
}
.hotel-card-badge.new {
    background:#febb02;
    color:#003580;
}
.hotel-card-score {
    position:absolute;
    top:12px;
    right:12px;
    background:#febb02;
    color:#003580;
    font-size:13px;
    font-weight:900;
    padding:5px 10px;
    border-radius:8px;
    display:flex;
    align-items:center;
    gap:3px;
}
.hotel-card-body { padding:16px 18px; flex:1; display:flex; flex-direction:column; }
.hotel-card-name { font-size:16px; font-weight:800; color:#1e293b; margin-bottom:5px; line-height:1.3; }
.hotel-card-loc  { font-size:12px; color:#64748b; margin-bottom:10px; display:flex; align-items:center; gap:4px; }
.hotel-card-equip { display:flex; flex-wrap:wrap; gap:5px; margin-bottom:12px; }
.hotel-card-equip span { font-size:10px; background:#f1f5f9; color:#475569; padding:3px 8px; border-radius:5px; font-weight:600; }
.hotel-card-footer { display:flex; align-items:flex-end; justify-content:space-between; margin-top:auto; padding-top:12px; border-top:1px solid #f1f5f9; }
.hotel-card-price .label { font-size:10px; color:#94a3b8; }
.hotel-card-price .amount { font-size:20px; font-weight:900; color:#003580; line-height:1; }
.hotel-card-price .unit   { font-size:11px; color:#64748b; }
.btn-voir {
    background:#003580;
    color:#fff;
    padding:9px 16px;
    border-radius:8px;
    font-size:12px;
    font-weight:700;
    text-decoration:none;
    transition:all .2s;
    white-space:nowrap;
}
.btn-voir:hover { background:#0071c2; color:#fff; transform:translateX(2px); }

/* ══ AVANTAGES ══ */
.avantage-card {
    background:#fff;
    border-radius:14px;
    border:1px solid #e2e8f0;
    padding:28px 22px;
    text-align:center;
    box-shadow:0 2px 12px rgba(0,53,128,0.07);
    transition:all .25s;
    height:100%;
}
.avantage-card:hover { transform:translateY(-5px); box-shadow:0 12px 36px rgba(0,53,128,0.15); border-color:#0071c2; }
.avantage-icon {
    width:72px;
    height:72px;
    background:linear-gradient(135deg,#003580,#0071c2);
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 16px;
    font-size:30px;
    color:#fff;
    box-shadow:0 6px 20px rgba(0,53,128,0.28);
}

/* ══ AVIS ══ */
.avis-card {
    background:#fff;
    border-radius:14px;
    border:1px solid #e2e8f0;
    padding:24px;
    box-shadow:0 2px 10px rgba(0,53,128,0.07);
    height:100%;
    display:flex;
    flex-direction:column;
    transition:all .25s;
}
.avis-card:hover { transform:translateY(-4px); box-shadow:0 10px 32px rgba(0,53,128,0.13); }
.avis-stars { display:flex; gap:3px; margin-bottom:12px; }
.avis-stars i { color:#febb02; font-size:15px; }
.avis-stars i.empty { color:#e2e8f0; }
.avis-global {
    background:linear-gradient(135deg,#003580,#0071c2);
    border-radius:16px;
    padding:32px;
    text-align:center;
    color:#fff;
    margin-bottom:32px;
}
.avis-global .big-note { font-size:64px; font-weight:900; color:#febb02; line-height:1; }
.avis-global .note-label { font-size:14px; color:rgba(255,255,255,0.8); margin-top:4px; }

/* ══ STEPS ══ */
.step-card { text-align:center; padding:24px 20px; }
.step-num {
    width:56px;
    height:56px;
    background:linear-gradient(135deg,#003580,#0071c2);
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:22px;
    font-weight:900;
    color:#fff;
    margin:0 auto 16px;
    box-shadow:0 6px 20px rgba(0,53,128,0.28);
}

/* ══ CTA ══ */
.cta-section {
    background:linear-gradient(135deg,#003580,#0071c2);
    padding:80px 0;
    text-align:center;
    color:#fff;
    position:relative;
    overflow:hidden;
}
.cta-section::before {
    content:'';
    position:absolute;
    top:-40%;left:-10%;
    width:500px;height:500px;
    background:rgba(255,255,255,0.04);
    border-radius:50%;
}
.cta-section::after {
    content:'';
    position:absolute;
    bottom:-30%;right:-5%;
    width:400px;height:400px;
    background:rgba(255,255,255,0.04);
    border-radius:50%;
}
</style>
@endpush

@section('content')
@php
    $appName    = \App\Models\SiteSetting::get('app_name','DjibStay');
    $slogan     = \App\Models\SiteSetting::get('app_slogan','Réservez les meilleurs hôtels à Djibouti');
    $logoPath   = \App\Models\SiteSetting::get('app_logo','');
    $aboutText  = \App\Models\SiteSetting::get('about_text','');

    $hotels     = \App\Models\Hotel::with(['images','mainImage','typesChambre','avis'])
                    ->withCount('avis')
                    ->latest()->take(6)->get();
    $totalHotels= \App\Models\Hotel::count();
    $totalResas = \App\Models\Reservation::where('statut','CONFIRMEE')->count();
    $totalAvis  = \App\Models\Avis::count();
    $avgRating  = round(\App\Models\Avis::avg('note') ?? 0, 1);
    $recentAvis = \App\Models\Avis::with('hotel')->latest()->take(3)->get();

    $hotelImageMap = [
        'sheraton'    =>'sheraton.jpeg',
        'kempinski'   =>'kempinski.jpeg',
        'ayla'        =>'ayla.jpg',
        'escale'      =>'escale.jpg',
        'waafi'       =>'waafi.jpg',
        'best western'=>'best western.jpeg',
        'europe'      =>'hotel europe.jpg',
        'gadileh'     =>'gadileh.jpg',
        'accacia'     =>'accacia-hotel.jpg',
    ];
@endphp

{{-- ══ HERO ══ --}}
<section class="hero-section">
    <div class="hero-bg" id="heroBg"></div>
    <div class="hero-overlay"></div>
    <div class="hero-content">

        <h1 class="hero-title">
            Trouvez votre hôtel idéal<br>
            <span>à Djibouti</span>
        </h1>
        <p class="hero-sub">
            <span>🏨 {{ $totalHotels }} hôtels</span>
            <span>💳 Paiement en {{ \App\Models\SiteSetting::get('app_devise','DJF') }}, $, £</span>
            <span>⚡ Réservation en 2 min</span>
            <span>🎧 Support 24/7</span>
        </p>

        {{-- SEARCH BOX --}}
        <form method="GET" action="{{ route('hotels.index') }}">
            <div class="search-box">
                <div class="search-field">
                    <label><i class="bi bi-geo-alt-fill" style="color:#003580;"></i>Destination</label>
                    <input type="text" name="q" placeholder="Nom d'hôtel ou ville..." value="{{ request('q') }}">
                </div>
                <div class="search-field">
                    <label><i class="bi bi-calendar-event" style="color:#003580;"></i>Arrivée</label>
                    <input type="date" name="check_in" value="{{ request('check_in') }}" min="{{ date('Y-m-d') }}">
                </div>
                <div class="search-field">
                    <label><i class="bi bi-calendar-check" style="color:#003580;"></i>Départ</label>
                    <input type="date" name="check_out" value="{{ request('check_out') }}" min="{{ date('Y-m-d',strtotime('+1 day')) }}">
                </div>
                <div class="search-field" style="min-width:120px;">
                    <label><i class="bi bi-people-fill" style="color:#003580;"></i>Personnes</label>
                    <select name="personnes">
                        @for($i=1;$i<=8;$i++)
                        <option value="{{ $i }}" {{ request('personnes')==$i?'selected':'' }}>
                            {{ $i }} {{ $i===1?'adulte':'adultes' }}
                        </option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="search-btn">
                    <i class="bi bi-search"></i> Rechercher
                </button>
            </div>
        </form>
    </div>
</section>

{{-- ══ STATS BAR ══ --}}
<div class="stats-bar">
    <div class="container">
        <div class="stats-bar-inner">
            <div class="stat-item">
                <i class="bi bi-building" style="font-size:22px;color:#febb02;"></i>
                <div><div class="num">{{ $totalHotels }}+</div><div class="lbl">Hôtels partenaires</div></div>
            </div>
            <div class="stat-item">
                <i class="bi bi-calendar-check" style="font-size:22px;color:#febb02;"></i>
                <div><div class="num">{{ $totalResas }}+</div><div class="lbl">Réservations confirmées</div></div>
            </div>
            <div class="stat-item">
                <i class="bi bi-star-fill" style="font-size:22px;color:#febb02;"></i>
                <div><div class="num">{{ $avgRating > 0 ? $avgRating : '4.8' }}/5</div><div class="lbl">Note moyenne</div></div>
            </div>
            <div class="stat-item">
                <i class="bi bi-phone" style="font-size:22px;color:#febb02;"></i>
                <div><div class="num">{{ \App\Models\SiteSetting::get('app_devise','DJF') }}</div><div class="lbl">Paiement local accepté</div></div>
            </div>
            <div class="stat-item">
                <i class="bi bi-headset" style="font-size:22px;color:#febb02;"></i>
                <div><div class="num">24/7</div><div class="lbl">Support client</div></div>
            </div>
        </div>
    </div>
</div>

{{-- ══ HÔTELS EN VEDETTE ══ --}}
<section class="py-5" style="background:#f2f6fc;">
    <div class="container" style="max-width:1320px;">
        <div class="d-flex align-items-end justify-content-between flex-wrap gap-3 mb-4">
            <div>
                <h2 class="section-title mb-1">🏨 Hôtels en vedette</h2>
                <p class="section-sub mb-0">Les établissements les mieux notés à Djibouti</p>
            </div>
            <a href="{{ route('hotels.index') }}"
               style="background:#003580;color:#fff;padding:11px 22px;border-radius:9px;text-decoration:none;font-weight:700;font-size:13px;display:inline-flex;align-items:center;gap:7px;white-space:nowrap;">
                Voir tous les hôtels <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            @forelse($hotels as $hotel)
            @php
                $mainImg  = $hotel->mainImage ?? $hotel->images->first();
                $nomL     = strtolower($hotel->nom);
                $fallback = asset('images/ayla.jpg');
                foreach($hotelImageMap as $k=>$v){ if(str_contains($nomL,$k)){ $fallback=asset('images/'.$v); break; } }
                $imgUrl   = $mainImg ? $mainImg->url : $fallback;
                $avgNote  = round($hotel->avis->avg('note') ?? 0, 1);
                $minPrice = $hotel->typesChambre->min('prix_par_nuit');
                $nbDispo  = $hotel->typesChambre->sum(fn($t) => $t->chambres->where('etat','DISPONIBLE')->count());
                $isNew    = $hotel->created_at->diffInDays(now()) <= 30;
            @endphp
            <div class="col-sm-6 col-lg-4">
                <a href="{{ route('hotels.show',$hotel) }}" class="hotel-card">
                    <div class="hotel-card-img">
                        <img src="{{ $imgUrl }}" alt="{{ $hotel->nom }}" loading="lazy"
                             onerror="this.src='{{ $fallback }}'">
                        @if($isNew)
                            <div class="hotel-card-badge new">✨ Nouveau</div>
                        @elseif($avgNote >= 4.5)
                            <div class="hotel-card-badge">⭐ Excellent</div>
                        @elseif($avgNote >= 4)
                            <div class="hotel-card-badge">👍 Très bien</div>
                        @endif
                        @if($avgNote > 0)
                        <div class="hotel-card-score">
                            <i class="bi bi-star-fill" style="font-size:11px;"></i>
                            {{ number_format($avgNote,1) }}
                        </div>
                        @endif
                    </div>
                    <div class="hotel-card-body">
                        <div class="hotel-card-name">{{ $hotel->nom }}</div>
                        <div class="hotel-card-loc">
                            <i class="bi bi-geo-alt-fill" style="color:#0071c2;flex-shrink:0;"></i>
                            {{ $hotel->ville ?? 'Djibouti-Ville' }}
                            @if($hotel->adresse) · {{ Str::limit($hotel->adresse,30) }}@endif
                        </div>
                        <div class="hotel-card-equip">
                            @if($hotel->typesChambre->contains('has_wifi',true))
                            <span><i class="bi bi-wifi me-1"></i>WiFi</span>
                            @endif
                            @if($hotel->typesChambre->contains('has_climatisation',true))
                            <span><i class="bi bi-snow2 me-1"></i>Clim</span>
                            @endif
                            @if($hotel->typesChambre->contains('has_minibar',true))
                            <span><i class="bi bi-cup-straw me-1"></i>Minibar</span>
                            @endif
                            <span><i class="bi bi-door-open me-1"></i>{{ $hotel->typesChambre->count() }} types</span>
                            @if($nbDispo > 0)
                            <span style="background:#dcfce7;color:#14532d;">
                                <i class="bi bi-check-circle me-1"></i>{{ $nbDispo }} dispo
                            </span>
                            @endif
                        </div>
                        <div class="hotel-card-footer">
                            <div class="hotel-card-price">
                                <div class="label">À partir de</div>
                                <div class="amount">{{ $minPrice ? number_format($minPrice,0,',',' ') : '—' }}</div>
                                <div class="unit">{{ \App\Models\SiteSetting::get('app_devise','DJF') }} / nuit</div>
                            </div>
                            <span class="btn-voir">Voir →</span>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div style="font-size:56px;margin-bottom:12px;">🏨</div>
                <p style="color:#64748b;font-size:16px;">Aucun hôtel disponible pour le moment.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ══ COMMENT ÇA MARCHE ══ --}}
<section class="py-5 bg-white">
    <div class="container" style="max-width:1100px;">
        <div class="text-center mb-5">
            <h2 class="section-title">⚡ Comment ça marche ?</h2>
            <p class="section-sub">Réservez votre hôtel en 3 étapes simples</p>
        </div>
        <div class="row g-4 position-relative">
            @foreach([
                ['🔍','Recherchez','Entrez votre destination, vos dates et le nombre de personnes.'],
                ['🏨','Choisissez','Comparez les hôtels, consultez les photos et les avis clients.'],
                ['✅','Réservez','Remplissez le formulaire et payez l\'acompte en ligne en '. \App\Models\SiteSetting::get('app_devise','DJF') .'.'],
            ] as $idx => [$icon,$title,$desc])
            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-num">{{ $idx+1 }}</div>
                    <div style="font-size:32px;margin-bottom:12px;">{{ $icon }}</div>
                    <div style="font-size:17px;font-weight:800;color:#003580;margin-bottom:8px;">{{ $title }}</div>
                    <div style="font-size:14px;color:#64748b;line-height:1.75;">{{ $desc }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ POURQUOI NOUS CHOISIR ══ --}}
<section class="py-5" style="background:#f2f6fc;">
    <div class="container" style="max-width:1320px;">
        <div class="text-center mb-5">
            <h2 class="section-title">💡 Pourquoi choisir {{ $appName }} ?</h2>
            <p class="section-sub">La plateforme de confiance pour vos réservations d'hôtels</p>
        </div>
        <div class="row g-4">
            @foreach([
                ['bi-shield-check','Paiement sécurisé','Acompte en ligne via Waafi, D-Money ou carte bancaire. Vos données sont chiffrées et protégées.'],
                ['bi-geo-alt-fill','100% Local','Équipe locale, hôtels vérifiés, paiement en '. \App\Models\SiteSetting::get('app_devise','DJF') .'. Nous connaissons le marché local.'],
                ['bi-lightning-charge','Réservation rapide','En moins de 2 minutes, trouvez et réservez votre chambre. Interface simple et intuitive.'],
                ['bi-headset','Support 24/7','Notre équipe est disponible à tout moment pour vous aider et répondre à vos questions.'],
            ] as [$icon,$title,$desc])
            <div class="col-sm-6 col-lg-3">
                <div class="avantage-card">
                    <div class="avantage-icon"><i class="bi {{ $icon }}"></i></div>
                    <div style="font-size:16px;font-weight:800;color:#003580;margin-bottom:8px;">{{ $title }}</div>
                    <div style="font-size:13px;color:#64748b;line-height:1.75;">{{ $desc }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ AVIS CLIENTS ══ --}}
@if($recentAvis->count() > 0)
<section class="py-5 bg-white">
    <div class="container" style="max-width:1100px;">
        <div class="text-center mb-4">
            <h2 class="section-title">⭐ Ce que disent nos clients</h2>
            <p class="section-sub">{{ $totalAvis }} avis vérifiés</p>
        </div>

        {{-- Note globale en grand --}}
        <div class="avis-global mb-5">
            <div class="big-note">{{ $avgRating > 0 ? $avgRating : '4.8' }}</div>
            <div style="display:flex;justify-content:center;gap:4px;margin:10px 0 6px;">
                @for($i=1;$i<=5;$i++)
                <i class="bi bi-star-fill" style="color:#febb02;font-size:22px;"></i>
                @endfor
            </div>
            <div class="note-label">Note moyenne sur {{ $totalAvis }} avis vérifiés</div>
        </div>

        <div class="row g-4">
            @foreach($recentAvis as $avis)
            <div class="col-md-4">
                <div class="avis-card">
                    <div class="avis-stars">
                        @for($i=1;$i<=5;$i++)
                        <i class="bi bi-star-fill {{ $i<=$avis->note?'':'empty' }}"></i>
                        @endfor
                    </div>
                    @if($avis->commentaire)
                    <p style="font-size:14px;color:#475569;line-height:1.8;flex:1;margin-bottom:16px;font-style:italic;">
                        "{{ Str::limit($avis->commentaire,160) }}"
                    </p>
                    @endif
                    <div style="display:flex;align-items:center;gap:10px;margin-top:auto;padding-top:14px;border-top:1px solid #f1f5f9;">
                        <div style="width:42px;height:42px;background:linear-gradient(135deg,#003580,#0071c2);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;font-weight:800;flex-shrink:0;">
                            {{ strtoupper(substr($avis->nom_client,0,1)) }}
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:700;color:#1e293b;">{{ $avis->nom_client }}</div>
                            <div style="font-size:11px;color:#94a3b8;">
                                {{ $avis->hotel->nom ?? '' }}
                                @if($avis->date_avis) · {{ $avis->date_avis->format('M Y') }}@endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ══ À PROPOS ══ --}}
@if($aboutText)
<section class="py-5" style="background:#f2f6fc;">
    <div class="container" style="max-width:900px;text-align:center;">
        <h2 class="section-title">🏢 À propos de {{ $appName }}</h2>
        <p style="font-size:15px;color:#475569;line-height:1.85;margin-bottom:24px;">{{ $aboutText }}</p>
        <a href="{{ route('pages.about') }}"
           style="background:#003580;color:#fff;padding:12px 28px;border-radius:9px;text-decoration:none;font-weight:700;font-size:14px;">
            En savoir plus →
        </a>
    </div>
</section>
@endif

{{-- ══ CTA ══ --}}
<section class="cta-section">
    <div class="container" style="max-width:700px;position:relative;z-index:2;">
        <h2 style="font-size:clamp(24px,4vw,40px);font-weight:900;color:#fff;margin-bottom:14px;">
            Prêt à réserver votre séjour ?
        </h2>
        <p style="color:rgba(255,255,255,0.85);font-size:16px;margin-bottom:32px;">
            {{ $slogan }}
        </p>
        <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('hotels.index') }}"
               style="background:#febb02;color:#003580;font-weight:800;font-size:16px;padding:16px 40px;border-radius:12px;text-decoration:none;display:inline-flex;align-items:center;gap:8px;box-shadow:0 6px 24px rgba(0,0,0,0.25);">
                <i class="bi bi-search"></i> Explorer les hôtels
            </a>
            <a href="{{ route('reservations.status') }}"
               style="background:rgba(255,255,255,0.15);color:#fff;font-weight:700;font-size:16px;padding:16px 32px;border-radius:12px;text-decoration:none;border:2px solid rgba(255,255,255,0.35);display:inline-flex;align-items:center;gap:8px;">
                <i class="bi bi-search-heart"></i> Suivre ma réservation
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
window.addEventListener('load', function() {
    const bg = document.getElementById('heroBg');
    if (bg) {
        bg.style.backgroundImage = "url('https://images.unsplash.com/photo-1582719508461-905c673771fd?q=80&w=1920&auto=format&fit=crop')";
        setTimeout(() => bg.classList.add('loaded'), 100);
    }
});

const checkIn  = document.querySelector('input[name="check_in"]');
const checkOut = document.querySelector('input[name="check_out"]');
if (checkIn && checkOut) {
    checkIn.addEventListener('change', function() {
        const next = new Date(this.value);
        next.setDate(next.getDate() + 1);
        checkOut.min = next.toISOString().split('T')[0];
        if (checkOut.value && checkOut.value <= this.value) {
            checkOut.value = next.toISOString().split('T')[0];
        }
    });
}
</script>
@endpush