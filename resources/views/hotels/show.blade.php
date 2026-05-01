@extends('layouts.app')

@section('title', $hotel->nom . ' — DjibStay')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css"/>
<style>
/* ── HERO ── */
.hotel-hero {
    background: #ffffff;
    padding: 24px 0 20px;
    color: #1e293b;
}
.hotel-hero h1 { font-size:clamp(20px,4vw,32px); font-weight:900; margin-bottom:6px; }
.hotel-meta { display:flex; flex-wrap:wrap; gap:14px; margin-top:10px; }
.hotel-meta-item { display:flex; align-items:center; gap:6px; font-size:13px; color:#475569; }
.hotel-meta-item i { color:#febb02; }
.rating-badge { background:#febb02; color:#003580; font-weight:900; font-size:16px; padding:8px 14px; border-radius:10px; display:inline-flex; align-items:center; gap:6px; }

/* ══════════════════════════════════════════
   GALERIE — STYLE BOOKING.COM EXACT
   Rangée 1 : grande photo + 2 miniatures droite
   Rangée 2 : 5 petites photos
══════════════════════════════════════════ */
.gallery-wrap {
    margin-top: 20px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

/* ── RANGÉE HAUTE ── */
.gallery-row-top {
    display: grid;
    grid-template-columns: 1.55fr 1fr;
    grid-template-rows: 1fr 1fr;
    gap: 4px;
    height: 220px;
}

.gallery-main {
    grid-row: 1 / 3;
    grid-column: 1;
    position: relative;
    overflow: hidden;
    border-radius: 8px 0 0 8px;
    cursor: pointer;
    background: #0f172a;
}

.gallery-side {
    position: relative;
    overflow: hidden;
    cursor: pointer;
    background: #0f172a;
}
.gallery-side:first-of-type { border-radius: 0 8px 0 0; }
.gallery-side:last-of-type  { border-radius: 0 0 8px 0; }

/* ── RANGÉE BASSE ── */
.gallery-row-bottom {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 4px;
    height: 75px;
}

.gallery-small {
    position: relative;
    overflow: hidden;
    cursor: pointer;
    background: #0f172a;
    border-radius: 4px;
}
.gallery-small:first-child { border-radius: 0 0 0 8px; }
.gallery-small:last-child  { border-radius: 0 0 8px 0; }

/* ── IMAGES ── */
.gallery-main img,
.gallery-side img,
.gallery-small img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.4s cubic-bezier(0.25,0.46,0.45,0.94);
}
.gallery-main:hover img  { transform: scale(1.04); }
.gallery-side:hover img  { transform: scale(1.06); }
.gallery-small:hover img { transform: scale(1.07); }

/* Overlay hover léger */
.gallery-main::after,
.gallery-side::after,
.gallery-small::after {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0);
    transition: background .25s;
    pointer-events: none;
}
.gallery-main:hover::after,
.gallery-side:hover::after,
.gallery-small:hover::after { background: rgba(0,0,0,0.12); }

/* Bouton "+X autres photos" */
.gallery-more-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.48);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #fff;
    text-decoration: none;
    gap: 2px;
    backdrop-filter: blur(1px);
    transition: background .25s;
    z-index: 2;
    border-radius: inherit;
}
.gallery-more-overlay:hover { background: rgba(0,0,0,0.64); color: #fff; }
.gallery-more-overlay .more-num  { font-size: 22px; font-weight: 900; line-height: 1; text-decoration: underline; }
.gallery-more-overlay .more-text { font-size: 12px; font-weight: 600; }

/* Bouton flottant "Voir toutes les photos" sur la grande image */
.gallery-all-btn {
    position: absolute;
    bottom: 12px;
    right: 12px;
    background: rgba(255,255,255,0.92);
    color: #003580;
    border: none;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 700;
    padding: 7px 13px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    z-index: 3;
    transition: all .2s;
    text-decoration: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.18);
}
.gallery-all-btn:hover { background: #fff; color: #003580; transform: translateY(-1px); }

.gallery-hidden { display: none; }

/* Mobile */
@media (max-width: 768px) {
    .gallery-row-top {
        grid-template-columns: 1fr 1fr;
        grid-template-rows: 180px 130px;
        height: auto;
    }
    .gallery-main {
        grid-column: 1 / 3;
        grid-row: 1;
        border-radius: 8px 8px 0 0;
    }
    .gallery-side { border-radius: 0; }
    .gallery-row-bottom { display: none; }
    .gallery-all-btn { display: none; }
}

/* ── LAYOUT ── */
.hotel-layout { display:grid; grid-template-columns:1fr 320px; gap:24px; align-items:start; }
@media(max-width:991px){ .hotel-layout { grid-template-columns:1fr; } }

/* ── SECTION CARDS ── */
.section-card { background:#fff; border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,53,128,0.07); overflow:hidden; margin-bottom:20px; }
.section-card-header { padding:14px 22px; border-bottom:1px solid #f1f5f9; background:#f8fafc; display:flex; align-items:center; gap:10px; }
.section-card-header h2 { font-size:15px; font-weight:800; color:#003580; margin:0; }
.section-card-body { padding:20px 22px; }

/* ── ÉQUIPEMENTS ── */
.equip-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(150px,1fr)); gap:10px; }
.equip-item { display:flex; align-items:center; gap:8px; background:#f8fafc; border-radius:8px; padding:10px 12px; font-size:13px; color:#1e293b; font-weight:500; border:1px solid #e2e8f0; }
.equip-item i { color:#0071c2; font-size:16px; flex-shrink:0; }

/* ── CHAMBRE ── */
.room-card { border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; margin-bottom:20px; box-shadow:0 2px 10px rgba(0,53,128,0.07); transition:box-shadow .25s; }
.room-card:hover { box-shadow:0 8px 28px rgba(0,53,128,0.14); }
.room-layout { display:grid; grid-template-columns:260px 1fr 200px; align-items:stretch; }
@media(max-width:900px){ .room-layout { grid-template-columns:1fr; } }
.room-gallery { position:relative; background:#0f172a; min-height:200px; }
.room-slides { width:100%; height:100%; position:relative; }
.room-slide { position:absolute; inset:0; opacity:0; transition:opacity .35s; }
.room-slide.active { opacity:1; }
.room-slide img { width:100%; height:100%; object-fit:cover; min-height:200px; display:block; }
.room-nav { position:absolute; top:50%; transform:translateY(-50%); width:32px; height:32px; border-radius:50%; background:rgba(255,255,255,0.92); border:none; color:#003580; font-size:16px; cursor:pointer; z-index:5; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 8px rgba(0,0,0,0.2); transition:all .2s; }
.room-nav:hover { background:#fff; transform:translateY(-50%) scale(1.08); }
.room-nav.prev { left:8px; }
.room-nav.next { right:8px; }
.room-dots { position:absolute; bottom:10px; left:0; right:0; display:flex; justify-content:center; gap:5px; z-index:5; }
.room-dot { width:6px; height:6px; border-radius:50%; background:rgba(255,255,255,0.5); border:none; cursor:pointer; padding:0; transition:all .2s; }
.room-dot.active { background:#fff; transform:scale(1.3); }
.room-thumbs { display:flex; gap:3px; padding:5px; background:#020617; overflow-x:auto; }
.room-thumb { flex:0 0 56px; height:40px; border-radius:3px; overflow:hidden; border:2px solid transparent; cursor:pointer; padding:0; transition:border-color .2s; }
.room-thumb.active { border-color:#0071c2; }
.room-thumb img { width:100%; height:100%; object-fit:cover; display:block; }
.room-info { padding:16px 20px; background:#fff; }
.room-name { font-size:16px; font-weight:800; color:#1e293b; margin-bottom:6px; }
.room-badges { display:flex; flex-wrap:wrap; gap:7px; margin-bottom:12px; }
.room-badge { display:inline-flex; align-items:center; gap:5px; padding:5px 10px; border:1px solid #d1d5db; border-radius:4px; font-size:12px; color:#374151; font-weight:500; }
.room-badge i { color:#6b7280; }
.room-feature { font-size:13px; color:#4b5563; margin:4px 0; display:flex; align-items:flex-start; gap:6px; }
.room-feature i { color:#003580; margin-top:2px; flex-shrink:0; }
.room-available { display:inline-flex; align-items:center; gap:5px; font-size:13px; color:#059669; font-weight:600; margin-top:8px; }
.room-price-col { padding:16px; border-left:1px solid #e2e8f0; background:#f8fafc; display:flex; flex-direction:column; align-items:stretch; }
@media(max-width:900px){ .room-price-col { border-left:none; border-top:1px solid #e2e8f0; } }
.price-label { font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:.4px; }
.price-value { font-size:24px; font-weight:900; color:#003580; line-height:1; margin:4px 0; }
.price-unit  { font-size:12px; color:#64748b; }
.btn-reserver { background:linear-gradient(135deg,#003580,#0071c2); color:#fff; border:none; border-radius:8px; font-weight:800; font-size:14px; padding:12px; text-align:center; cursor:pointer; transition:all .2s; text-decoration:none; display:block; margin-top:10px; box-shadow:0 4px 14px rgba(0,53,128,0.25); }
.btn-reserver:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(0,53,128,0.35); color:#fff; }
.btn-reserver.disabled { background:#94a3b8; cursor:not-allowed; box-shadow:none; transform:none; }
.dispo-count { font-size:12px; color:#dc2626; font-weight:700; margin-top:6px; text-align:center; }
.acompte-note { background:#fef3c7; border-radius:6px; padding:8px 10px; font-size:11px; color:#92400e; font-weight:600; margin-top:8px; }

/* ── AVIS ── */
.avis-score-big { background:#003580; color:#fff; font-size:34px; font-weight:900; padding:12px 16px; border-radius:12px; line-height:1; flex-shrink:0; }
.avis-star { color:#febb02; font-size:16px; }
.avis-star.empty { color:#e2e8f0; }
.avis-card { background:#f8fafc; border-radius:10px; border:1px solid #e2e8f0; padding:14px; margin-bottom:10px; }

/* ── SIDEBAR ── */
.sidebar-sticky { position:sticky; top:80px; }
.sidebar-book { background:#fff; border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 4px 20px rgba(0,53,128,0.12); overflow:hidden; margin-bottom:16px; }
.sidebar-book-header { background:linear-gradient(135deg,#003580,#0071c2); color:#fff; padding:16px 20px; }
.sidebar-book-header h3 { font-size:14px; font-weight:800; margin:0; }
.sidebar-book-body { padding:16px 20px; }

/* ── STAR RATING ── */
.star-rating { display:flex; gap:4px; margin-bottom:12px; flex-direction:row-reverse; justify-content:flex-end; }
.star-rating input { display:none; }
.star-rating label { font-size:26px; color:#e2e8f0; cursor:pointer; transition:color .15s; }
.star-rating input:checked ~ label, .star-rating label:hover, .star-rating label:hover ~ label { color:#febb02; }
</style>
@endpush

@section('content')

{{-- ══ HERO ══ --}}
<section class="hotel-hero">
    <div class="container" style="max-width:1320px;">
        <nav style="font-size:13px;color:#64748b;margin-bottom:10px;">
            <a href="{{ route('home') }}" style="color:#64748b;text-decoration:none;">Accueil</a>
            <span class="mx-2">›</span>
            <a href="{{ route('hotels.index') }}" style="color:#64748b;text-decoration:none;">Hôtels</a>
            <span class="mx-2">›</span>
            <span style="color:#1e293b;font-weight:600;">{{ $hotel->nom }}</span>
        </nav>

        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
            <div>
                <h1>{{ $hotel->nom }}</h1>
                <div class="hotel-meta">
                    @if($hotel->ville)
                    <div class="hotel-meta-item">
                        <i class="bi bi-geo-alt-fill"></i>
                        {{ $hotel->adresse ? $hotel->adresse.', ' : '' }}{{ $hotel->ville }}
                    </div>
                    @endif
                    <div class="hotel-meta-item">
                        <i class="bi bi-star-fill"></i>
                        {{ $hotel->avis->count() }} avis clients
                    </div>
                    <div class="hotel-meta-item">
                        <i class="bi bi-door-open"></i>
                        {{ $hotel->typesChambre->sum(fn($t) => $t->chambres->count()) }} chambres
                    </div>
                </div>
            </div>
            @php $avgRating = round($hotel->avis->avg('note') ?? 0, 1); @endphp
            @if($avgRating > 0)
            <div class="rating-badge">
                <i class="bi bi-star-fill"></i>
                {{ number_format($avgRating,1) }}
                <span style="font-size:12px;font-weight:600;">/5</span>
            </div>
            @endif
        </div>

        {{-- ══ GALERIE BOOKING.COM STYLE ══ --}}
        @php
            $allImages = $hotel->images;
            $hotelImageMap = [
                'sheraton'     => 'sheraton.jpeg',
                'kempinski'    => 'kempinski.jpeg',
                'ayla'         => 'ayla.jpg',
                'escale'       => 'escale.jpg',
                'waafi'        => 'waafi.jpg',
                'best western' => 'best western.jpeg',
                'europe'       => 'hotel europe.jpg',
                'gadileh'      => 'gadileh.jpg',
                'accacia'      => 'accacia-hotel.jpg',
            ];
            $fallbackImg = asset('images/ayla.jpg');
            $nomLower    = strtolower($hotel->nom);
            foreach ($hotelImageMap as $k => $v) {
                if (str_contains($nomLower, $k)) { $fallbackImg = asset('images/'.$v); break; }
            }

            // Image principale
            $mainImg = $allImages->firstWhere('is_main', true) ?? $allImages->first();
            $mainUrl = $mainImg ? $mainImg->url : $fallbackImg;

            // Toutes les autres images (sans la principale)
            $otherImgs   = $allImages->filter(fn($i) => $i->id !== optional($mainImg)->id)->values();
            $totalPhotos = $allImages->count();

            // Rangée haute droite : photos 1 et 2
            $sideTop1 = $otherImgs[0] ?? null;
            $sideTop2 = $otherImgs[1] ?? null;

            // Rangée basse : photos 3 à 7 (5 photos)
            $bottomImgs = $otherImgs->slice(2, 5)->values();

            // Nombre de photos restantes après les 8 affichées (1 main + 2 side + 5 bottom)
            $remaining = max(0, $totalPhotos - 8);
        @endphp

        <div class="gallery-wrap">

            {{-- ── RANGÉE HAUTE : grande + 2 miniatures droite ── --}}
            <div class="gallery-row-top">

                {{-- Grande photo principale --}}
                <div class="gallery-main">
                    <a href="{{ $mainUrl }}"
                       class="glightbox"
                       data-gallery="hotel-gallery"
                       data-title="{{ $hotel->nom }}">
                        <img src="{{ $mainUrl }}"
                             alt="{{ $hotel->nom }}"
                             loading="lazy"
                             onerror="this.src='{{ $fallbackImg }}'">
                    </a>
                    @if($totalPhotos > 1)
                    <a href="#" class="gallery-all-btn"
                       onclick="document.querySelector('.glightbox[data-gallery=hotel-gallery]').click();return false;">
                        <i class="bi bi-images"></i> Voir les {{ $totalPhotos }} photos
                    </a>
                    @endif
                </div>

                {{-- Miniature droite haut --}}
                <div class="gallery-side" style="border-radius:0 8px 0 0;">
                    @php $url1 = $sideTop1 ? $sideTop1->url : $fallbackImg; @endphp
                    <a href="{{ $url1 }}"
                       class="glightbox"
                       data-gallery="hotel-gallery"
                       data-title="{{ $hotel->nom }}">
                        <img src="{{ $url1 }}"
                             alt="{{ $hotel->nom }}"
                             loading="lazy"
                             onerror="this.src='{{ $fallbackImg }}'">
                    </a>
                </div>

                {{-- Miniature droite bas --}}
                <div class="gallery-side" style="border-radius:0 0 8px 0;">
                    @php $url2 = $sideTop2 ? $sideTop2->url : $fallbackImg; @endphp
                    <a href="{{ $url2 }}"
                       class="glightbox"
                       data-gallery="hotel-gallery"
                       data-title="{{ $hotel->nom }}">
                        <img src="{{ $url2 }}"
                             alt="{{ $hotel->nom }}"
                             loading="lazy"
                             onerror="this.src='{{ $fallbackImg }}'">
                    </a>
                </div>

            </div>

            {{-- ── RANGÉE BASSE : 5 petites photos ── --}}
            <div class="gallery-row-bottom">
                @for($i = 0; $i < 5; $i++)
                    @php
                        $bImg   = $bottomImgs[$i] ?? null;
                        $bUrl   = $bImg ? $bImg->url : $fallbackImg;
                        $isLast = ($i === 4) && ($remaining > 0);
                    @endphp
                    <div class="gallery-small">
                        @if($bImg)
                        <a href="{{ $bUrl }}"
                           class="glightbox"
                           data-gallery="hotel-gallery"
                           data-title="{{ $hotel->nom }}">
                            <img src="{{ $bUrl }}"
                                 alt="{{ $hotel->nom }}"
                                 loading="lazy"
                                 onerror="this.src='{{ $fallbackImg }}'">
                        </a>
                        @else
                        <img src="{{ $fallbackImg }}"
                             alt="{{ $hotel->nom }}"
                             style="width:100%;height:100%;object-fit:cover;display:block;opacity:0.4;">
                        @endif
                        @if($isLast)
                        <a href="#" class="gallery-more-overlay"
                           onclick="document.querySelector('.glightbox[data-gallery=hotel-gallery]').click();return false;">
                            <span class="more-num">{{ $remaining }} autres photos</span>
                        </a>
                        @endif
                    </div>
                @endfor
            </div>

            {{-- Photos cachées pour le lightbox (à partir de la 9ème) --}}
            @foreach($allImages->skip(8) as $img)
            <a href="{{ $img->url }}"
               class="glightbox gallery-hidden"
               data-gallery="hotel-gallery"></a>
            @endforeach

        </div>
    </div>
</section>

{{-- ══ CONTENU ══ --}}
<div class="container py-4" style="max-width:1320px;">
    <div class="hotel-layout">

        {{-- COLONNE PRINCIPALE --}}
        <div>

            {{-- Description --}}
            @if($hotel->description)
            <div class="section-card">
                <div class="section-card-header">
                    <i class="bi bi-info-circle text-primary fs-5"></i>
                    <h2>À propos de cet hôtel</h2>
                </div>
                <div class="section-card-body">
                    <p style="font-size:15px;color:#475569;line-height:1.8;margin:0;">{{ $hotel->description }}</p>
                </div>
            </div>
            @endif

            {{-- Équipements --}}
            <div class="section-card">
                <div class="section-card-header">
                    <i class="bi bi-patch-check text-primary fs-5"></i>
                    <h2>Équipements & services</h2>
                </div>
                <div class="section-card-body">
                    <div class="equip-grid">
                        @if($hotel->typesChambre->contains('has_wifi',true))
                        <div class="equip-item"><i class="bi bi-wifi"></i> WiFi gratuit</div>
                        @endif
                        @if($hotel->typesChambre->contains('has_climatisation',true))
                        <div class="equip-item"><i class="bi bi-snow2"></i> Climatisation</div>
                        @endif
                        @if($hotel->typesChambre->contains('has_minibar',true))
                        <div class="equip-item"><i class="bi bi-cup-straw"></i> Minibar</div>
                        @endif
                        <div class="equip-item"><i class="bi bi-shield-check"></i> Sécurité 24h</div>
                        <div class="equip-item"><i class="bi bi-car-front"></i> Parking</div>
                        <div class="equip-item"><i class="bi bi-telephone"></i> Réception 24h</div>
                        <div class="equip-item"><i class="bi bi-bag-check"></i> Bagagerie</div>
                        <div class="equip-item"><i class="bi bi-credit-card"></i> Paiement mobile</div>
                    </div>
                </div>
            </div>

            {{-- ══ CHAMBRES ══ --}}
            <div class="section-card" id="chambres">
                <div class="section-card-header">
                    <i class="bi bi-door-open text-primary fs-5"></i>
                    <h2>Choisissez votre chambre</h2>
                </div>
                <div class="section-card-body" style="padding:14px;">

                    @forelse($hotel->typesChambre as $type)
                    @php
                        $dateIn  = request('check_in');
                        $dateOut = request('check_out');
                        if ($dateIn && $dateOut) {
                            $nbDispos = $type->calculerDisponibilite($dateIn, $dateOut);
                        } else {
                            $nbDispos = $type->chambres->where('etat','DISPONIBLE')->count();
                        }
                        $chambresDispos = $type->chambres->where('etat','DISPONIBLE');
                        $firstChambre   = $chambresDispos->first();
                        $roomImgs       = $type->images;
                    @endphp

                    <div class="room-card">
                        <div class="room-layout">

                            {{-- Carousel --}}
                            <div class="room-gallery">
                                @if($roomImgs->count() > 0)
                                    <div class="room-slides" id="slides-{{ $type->id }}">
                                        @foreach($roomImgs as $idx => $img)
                                        <div class="room-slide {{ $idx===0?'active':'' }}">
                                            <img src="{{ $img->url }}"
                                                 alt="{{ $type->nom_type }}"
                                                 loading="lazy"
                                                 onerror="this.src='{{ $fallbackImg }}'">
                                        </div>
                                        @endforeach
                                    </div>
                                    @if($roomImgs->count() > 1)
                                    <button class="room-nav prev" onclick="slideRoom({{ $type->id }},-1)">‹</button>
                                    <button class="room-nav next" onclick="slideRoom({{ $type->id }},1)">›</button>
                                    <div class="room-dots" id="dots-{{ $type->id }}">
                                        @foreach($roomImgs as $idx => $img)
                                        <button class="room-dot {{ $idx===0?'active':'' }}"
                                                onclick="goSlide({{ $type->id }},{{ $idx }})"></button>
                                        @endforeach
                                    </div>
                                    <div class="room-thumbs">
                                        @foreach($roomImgs as $idx => $img)
                                        <button class="room-thumb {{ $idx===0?'active':'' }}"
                                                id="thumb-{{ $type->id }}-{{ $idx }}"
                                                onclick="goSlide({{ $type->id }},{{ $idx }})">
                                            <img src="{{ $img->url }}" alt="" loading="lazy"
                                                 onerror="this.src='{{ $fallbackImg }}'">
                                        </button>
                                        @endforeach
                                    </div>
                                    @endif
                                @else
                                    <div style="width:100%;min-height:200px;background:#1e293b;display:flex;align-items:center;justify-content:center;">
                                        <div style="text-align:center;color:rgba(255,255,255,0.4);">
                                            <i class="bi bi-image" style="font-size:36px;"></i>
                                            <div style="font-size:12px;margin-top:6px;">Aucune photo</div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Infos chambre --}}
                            <div class="room-info">
                                <div class="room-name">{{ $type->nom_type }}</div>
                                <div class="room-badges">
                                    <div class="room-badge"><i class="bi bi-people"></i>{{ $type->capacite }} pers.</div>
                                    @if($type->superficie_m2)
                                    <div class="room-badge"><i class="bi bi-aspect-ratio"></i>{{ $type->superficie_m2 }} m²</div>
                                    @endif
                                    @if($type->has_wifi)<div class="room-badge"><i class="bi bi-wifi"></i>WiFi</div>@endif
                                    @if($type->has_climatisation)<div class="room-badge"><i class="bi bi-snow2"></i>Clim</div>@endif
                                    @if($type->has_minibar)<div class="room-badge"><i class="bi bi-cup-straw"></i>Minibar</div>@endif
                                </div>
                                @if($type->lit_description)
                                <div class="room-feature"><i class="bi bi-moon-stars"></i>{{ $type->lit_description }}</div>
                                @endif
                                @if($type->description)
                                <div class="room-feature"><i class="bi bi-info-circle"></i>{{ Str::limit($type->description,120) }}</div>
                                @endif
                                @php $sdb = $type->equipementsSalleBainList(); @endphp
                                @if(count($sdb) > 0)
                                <div class="room-feature"><i class="bi bi-droplet"></i><span>{{ implode(', ',array_slice($sdb,0,3)) }}</span></div>
                                @endif
                                <div class="room-available">
                                    @if($nbDispos > 0)
                                        <i class="bi bi-check-circle-fill text-success"></i>{{ $nbDispos }} chambre(s) disponible(s)
                                    @else
                                        <i class="bi bi-x-circle-fill text-danger"></i><span style="color:#dc2626;">Complet</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Prix --}}
                            <div class="room-price-col">
                                <div class="price-label">Prix par nuit</div>
                                <div class="price-value">{{ number_format($type->prix_par_nuit,0,',',' ') }}</div>
                                <div class="price-unit">DJF / nuit</div>
                                @if(request('check_in') && request('check_out'))
                                @php $nuits = \Carbon\Carbon::parse(request('check_in'))->diffInDays(\Carbon\Carbon::parse(request('check_out'))); @endphp
                                <div style="font-size:12px;color:#64748b;margin-top:4px;">
                                    {{ $nuits }} nuits = <strong>{{ number_format($type->prix_par_nuit*$nuits,0,',',' ') }} DJF</strong>
                                </div>
                                @endif
                                @if($nbDispos > 0 && $firstChambre)
                                    <a href="{{ route('reservations.create',['chambre_id'=>$firstChambre->id,'check_in'=>request('check_in'),'check_out'=>request('check_out')]) }}"
                                       class="btn-reserver">
                                        <i class="bi bi-calendar-plus me-1"></i> Réserver
                                    </a>
                                    @if($nbDispos <= 3)
                                    <div class="dispo-count">🔥 Plus que {{ $nbDispos }} dispo !</div>
                                    @endif
                                @else
                                    <div class="btn-reserver disabled">Indisponible</div>
                                @endif
                                <div class="acompte-note"><i class="bi bi-info-circle me-1"></i>Acompte 30% à la réservation</div>
                            </div>

                        </div>
                    </div>
                    @empty
                    <div style="text-align:center;padding:40px;color:#94a3b8;">
                        <i class="bi bi-door-open" style="font-size:40px;"></i>
                        <p style="margin-top:10px;">Aucune chambre disponible.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- ══ AVIS ══ --}}
            <div class="section-card" id="avis">
                <div class="section-card-header">
                    <i class="bi bi-star text-primary fs-5"></i>
                    <h2>Avis clients ({{ $hotel->avis->count() }})</h2>
                </div>
                <div class="section-card-body">
                    @if($hotel->avis->count() > 0)
                    @php $avg = round($hotel->avis->avg('note'),1); @endphp
                    <div class="d-flex align-items-center gap-4 mb-4 pb-3" style="border-bottom:1px solid #f1f5f9;">
                        <div class="avis-score-big">{{ number_format($avg,1) }}</div>
                        <div>
                            <div class="d-flex gap-1 mb-1">
                                @for($i=1;$i<=5;$i++)
                                <i class="bi bi-star-fill avis-star {{ $i<=round($avg)?'':'empty' }}"></i>
                                @endfor
                            </div>
                            <div style="font-size:14px;font-weight:700;color:#003580;">
                                @if($avg>=4.5) Exceptionnel
                                @elseif($avg>=4) Très bien
                                @elseif($avg>=3) Bien
                                @else Correct
                                @endif
                            </div>
                            <div style="font-size:13px;color:#64748b;">{{ $hotel->avis->count() }} avis vérifiés</div>
                        </div>
                    </div>
                    @foreach($hotel->avis->take(5) as $avis)
                    <div class="avis-card">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div style="font-weight:700;color:#1e293b;font-size:14px;">
                                <i class="bi bi-person-circle me-1 text-primary"></i>{{ $avis->nom_client }}
                            </div>
                            <div style="font-size:12px;color:#94a3b8;">{{ $avis->date_avis?->format('d/m/Y') }}</div>
                        </div>
                        <div class="d-flex gap-1 mb-2">
                            @for($i=1;$i<=5;$i++)
                            <i class="bi bi-star-fill" style="font-size:12px;color:{{ $i<=$avis->note?'#febb02':'#e2e8f0' }};"></i>
                            @endfor
                        </div>
                        @if($avis->commentaire)
                        <p style="font-size:14px;color:#475569;line-height:1.65;margin:0;">{{ $avis->commentaire }}</p>
                        @endif
                        @if($avis->reponse_admin)
                        <div style="background:#e8f0f8;border-left:3px solid #003580;border-radius:0 8px 8px 0;padding:10px 14px;margin-top:10px;">
                            <div style="font-size:11px;font-weight:800;color:#003580;margin-bottom:3px;">🏨 Réponse de l'hôtel</div>
                            <p style="font-size:13px;color:#1e293b;margin:0;">{{ $avis->reponse_admin }}</p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                    @else
                    <div style="text-align:center;padding:24px;color:#94a3b8;">
                        <i class="bi bi-star" style="font-size:32px;"></i>
                        <p style="margin-top:8px;">Aucun avis pour le moment.</p>
                    </div>
                    @endif

                    {{-- Formulaire avis --}}
                    <div style="background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;padding:20px;margin-top:16px;">
                        <h4 style="font-size:15px;font-weight:800;color:#003580;margin-bottom:14px;">
                            <i class="bi bi-pencil-square me-2"></i>Laisser un avis
                        </h4>
                        @if(session('success'))
                        <div class="alert alert-success mb-3">{{ session('success') }}</div>
                        @endif
                        <form method="POST" action="{{ route('avis.store') }}">
                            @csrf
                            <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <input type="text" name="nom_client" class="form-control"
                                           placeholder="Votre nom *"
                                           value="{{ old('nom_client', auth()->user()?->name) }}" required>
                                </div>
                                <div class="col-sm-6">
                                    <input type="email" name="email_client" class="form-control"
                                           placeholder="Votre email *"
                                           value="{{ old('email_client', auth()->user()?->email) }}" required>
                                </div>
                                <div class="col-12">
                                    <label style="font-size:13px;font-weight:700;color:#003580;margin-bottom:6px;display:block;">Note *</label>
                                    <div class="star-rating">
                                        @for($i=5;$i>=1;$i--)
                                        <input type="radio" name="note" id="star{{ $i }}" value="{{ $i }}"
                                               {{ old('note')==$i?'checked':'' }}>
                                        <label for="star{{ $i }}">★</label>
                                        @endfor
                                    </div>
                                </div>
                                <div class="col-12">
                                    <textarea name="commentaire" class="form-control" rows="3"
                                              placeholder="Partagez votre expérience...">{{ old('commentaire') }}</textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit"
                                            style="background:#003580;color:#fff;border:none;border-radius:8px;padding:10px 24px;font-weight:700;font-size:14px;cursor:pointer;">
                                        <i class="bi bi-send me-2"></i>Publier mon avis
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── SIDEBAR ── --}}
        <div class="sidebar-sticky">
            <div class="sidebar-book">
                <div class="sidebar-book-header">
                    <h3><i class="bi bi-calendar-check me-2"></i>Réserver cet hôtel</h3>
                </div>
                <div class="sidebar-book-body">
                    @php $minPrice = $hotel->typesChambre->min('prix_par_nuit'); @endphp
                    @if($minPrice)
                    <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.4px;">À partir de</div>
                    <div style="font-size:26px;font-weight:900;color:#003580;line-height:1;">{{ number_format($minPrice,0,',',' ') }}</div>
                    <div style="font-size:12px;color:#64748b;margin-bottom:14px;">DJF / nuit</div>
                    @endif
                    <form method="GET" action="{{ request()->url() }}">
                        <div class="mb-2">
                            <label style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.4px;">Arrivée</label>
                            <input type="date" name="check_in" class="form-control form-control-sm mt-1"
                                   value="{{ request('check_in') }}" min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="mb-3">
                            <label style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.4px;">Départ</label>
                            <input type="date" name="check_out" class="form-control form-control-sm mt-1"
                                   value="{{ request('check_out') }}"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        </div>
                        <button type="submit"
                                style="background:#febb02;color:#003580;border:none;border-radius:8px;font-weight:800;font-size:15px;padding:12px;width:100%;cursor:pointer;">
                            <i class="bi bi-search me-2"></i>Voir les disponibilités
                        </button>
                    </form>
                    <div style="text-align:center;margin-top:10px;">
                        <a href="#chambres" style="font-size:13px;color:#0071c2;font-weight:600;text-decoration:none;">
                            <i class="bi bi-arrow-down me-1"></i>Voir toutes les chambres
                        </a>
                    </div>
                </div>
            </div>

            {{-- Infos pratiques --}}
            <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 10px rgba(0,53,128,0.07);padding:18px 20px;margin-bottom:16px;">
                <h4 style="font-size:14px;font-weight:800;color:#003580;margin-bottom:12px;">
                    <i class="bi bi-info-circle me-2"></i>Infos pratiques
                </h4>
                @if($hotel->adresse || $hotel->ville)
                <div style="display:flex;align-items:flex-start;gap:10px;font-size:13px;color:#475569;margin-bottom:10px;">
                    <i class="bi bi-geo-alt-fill" style="color:#0071c2;flex-shrink:0;margin-top:2px;"></i>
                    <span>{{ $hotel->adresse ?? '' }} {{ $hotel->ville ?? 'Djibouti' }}</span>
                </div>
                @endif
                <div style="display:flex;align-items:flex-start;gap:10px;font-size:13px;color:#475569;margin-bottom:10px;">
                    <i class="bi bi-shield-check" style="color:#0071c2;flex-shrink:0;margin-top:2px;"></i>
                    <span>Annulation jusqu'à 48h avant</span>
                </div>
                <div style="display:flex;align-items:flex-start;gap:10px;font-size:13px;color:#475569;margin-bottom:10px;">
                    <i class="bi bi-credit-card" style="color:#0071c2;flex-shrink:0;margin-top:2px;"></i>
                    <span>Acompte 30% en ligne, solde à l'hôtel</span>
                </div>
                <div style="display:flex;align-items:flex-start;gap:10px;font-size:13px;color:#475569;">
                    <i class="bi bi-telephone" style="color:#0071c2;flex-shrink:0;margin-top:2px;"></i>
                    <span>{{ \App\Models\SiteSetting::get('contact_telephone','+253 77 00 00 00') }}</span>
                </div>
            </div>

            {{-- Partager --}}
            <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;padding:16px 20px;text-align:center;">
                <h4 style="font-size:14px;font-weight:800;color:#003580;margin-bottom:10px;">
                    <i class="bi bi-share me-2"></i>Partager
                </h4>
                <div style="display:flex;gap:8px;justify-content:center;">
                    <a href="https://wa.me/?text={{ urlencode($hotel->nom.' sur DjibStay : '.url()->current()) }}"
                       target="_blank"
                       style="background:#25d366;color:#fff;padding:8px 14px;border-radius:7px;text-decoration:none;font-size:12px;font-weight:700;">
                        <i class="bi bi-whatsapp me-1"></i>WhatsApp
                    </a>
                    <button onclick="navigator.clipboard.writeText('{{ url()->current() }}');this.textContent='✅ Copié !';setTimeout(()=>this.innerHTML='<i class=\'bi bi-link-45deg me-1\'></i>Copier',2000)"
                            style="background:#003580;color:#fff;padding:8px 14px;border-radius:7px;border:none;font-size:12px;font-weight:700;cursor:pointer;">
                        <i class="bi bi-link-45deg me-1"></i>Copier
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<script>
GLightbox({ selector: '.glightbox', touchNavigation: true, loop: true });

const roomStates = {};
function initRoom(id, total) { roomStates[id] = { current: 0, total }; }
function slideRoom(id, dir) {
    const s = roomStates[id];
    if (!s) return;
    s.current = (s.current + dir + s.total) % s.total;
    goSlide(id, s.current);
}
function goSlide(id, idx) {
    const s = roomStates[id];
    if (!s) return;
    s.current = idx;
    document.querySelectorAll(`#slides-${id} .room-slide`).forEach((el,i) => el.classList.toggle('active', i===idx));
    document.querySelectorAll(`#dots-${id} .room-dot`).forEach((el,i)  => el.classList.toggle('active', i===idx));
    document.querySelectorAll(`[id^="thumb-${id}-"]`).forEach((el,i)   => el.classList.toggle('active', i===idx));
}
@foreach($hotel->typesChambre as $type)
initRoom({{ $type->id }}, {{ max(1, $type->images->count()) }});
@endforeach

setInterval(() => {
    Object.keys(roomStates).forEach(id => {
        if (roomStates[id].total > 1) slideRoom(parseInt(id), 1);
    });
}, 4000);
</script>
@endpush