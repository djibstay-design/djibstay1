@extends('layouts.app')

@section('title', $hotel->nom)

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
<style>
    :root {
        --bleu-fonce: #003580;
        --bleu-clair: #0071c2;
        --bleu-pale: #e8f0f8;
    }
    .hotel-show-page { background: #fff; min-height: 100vh; color: #1e293b; }
    .hotel-show-page .section-title { color: var(--bleu-fonce); font-weight: 700; border-left: 4px solid var(--bleu-fonce); padding-left: 12px; margin-bottom: 1rem; }
    .hotel-gallery-block { display: grid; grid-template-columns: 2fr 1fr; grid-template-rows: 1fr 1fr; gap: 8px; height: 380px; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
    .hotel-gallery-block .main-cell { grid-row: span 2; }
    .hotel-gallery-block .main-cell a { display: block; height: 100%; border-radius: 0; overflow: hidden; background: #e2e8f0; }
    .hotel-gallery-block .main-cell img { width: 100%; height: 100%; object-fit: cover; }
    .hotel-gallery-block .right-top a, .hotel-gallery-block .right-bottom a { display: block; height: 100%; border-radius: 0; overflow: hidden; background: #e2e8f0; }
    .hotel-gallery-block .right-top img, .hotel-gallery-block .right-bottom img { width: 100%; height: 100%; object-fit: cover; }
    @media (max-width: 768px) {
        .hotel-gallery-block { height: auto; grid-template-columns: 1fr; grid-template-rows: auto; border-radius: 12px; }
        .hotel-gallery-block .main-cell { grid-row: span 1; height: 280px; }
        .hotel-gallery-block .right-top, .hotel-gallery-block .right-bottom { height: 180px; }
    }
    .hotel-gallery-strip-row { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 8px; margin-top: 8px; height: 88px; max-height: 88px; }
    .hotel-strip-thumb { position: relative; border-radius: 10px; overflow: hidden; background: #e2e8f0; height: 100%; min-height: 0; min-width: 0; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .hotel-strip-thumb a { display: block; width: 100%; height: 100%; cursor: pointer; transition: transform 0.2s; }
    .hotel-strip-thumb a:hover { transform: scale(1.02); }
    .hotel-strip-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .hotel-strip-thumb .more-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.65); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; border-radius: 10px; text-align: center; padding: 4px; line-height: 1.2; pointer-events: none; }
    @media (max-width: 640px) { .hotel-gallery-strip-row { height: 72px; max-height: 72px; gap: 6px; } }
    .sidebar-card { background: #fff; border-radius: 14px; padding: 22px; border: 1px solid #e2e8f0; border-top: 3px solid var(--bleu-fonce); box-shadow: 0 1px 3px rgba(0,53,128,0.06); }
    .sidebar-rating-badge { background: var(--bleu-fonce); color: #fff; font-weight: 800; font-size: 1.35rem; padding: 10px 16px; border-radius: 10px; line-height: 1; }
    .btn-booking { background: var(--bleu-fonce); color: #fff; padding: 14px 28px; border-radius: 10px; font-weight: 700; border: none; cursor: pointer; font-size: 1rem; transition: background 0.2s; }
    .btn-booking:hover { background: var(--bleu-clair); color: #fff; }
    .room-card { border: 1px solid #e2e8f0; border-left: 4px solid var(--bleu-fonce); border-radius: 14px; overflow: hidden; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,53,128,0.06); background: #fff; }
    .room-card-inner { display: grid; grid-template-columns: 280px 1fr auto; gap: 24px; padding: 24px; align-items: start; }
    .room-gallery { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; }
    .room-gallery a { display: block; border-radius: 10px; overflow: hidden; aspect-ratio: 4/3; background: #e2e8f0; }
    .room-gallery img { width: 100%; height: 100%; object-fit: cover; }
    .amenities-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 10px; }
    .amenity-item { display: flex; align-items: center; gap: 10px; padding: 12px 14px; background: #fff; border-radius: 10px; font-size: 0.875rem; color: var(--bleu-fonce); border: 1px solid var(--bleu-fonce); font-weight: 500; }
    .map-embed { height: 220px; min-height: 220px; background: var(--bleu-pale); border-radius: 10px; overflow: hidden; border: 1px solid rgba(0,53,128,0.2); }
    .map-embed iframe { width: 100%; height: 100%; border: 0; display: block; }
    @media (max-width: 768px) {
        .room-card-inner { grid-template-columns: 1fr; padding: 16px; }
        .room-gallery { grid-template-columns: repeat(3, 1fr); }
    }

    /* ——— Avis clients ——— */
    :root { --rv-blue: #3b5fe0; --rv-star: #f59e0b; --rv-text: #1e293b; --rv-muted: #64748b; --rv-border: #e5e7eb; }

    .reviews-section { padding: 2.5rem 0 1rem; }

    .rv-summary { display: grid; grid-template-columns: 160px 1fr 220px; gap: 2.5rem; align-items: start; padding: 2rem 0 1.75rem; border-bottom: 1px solid var(--rv-border); margin-bottom: 1.5rem; }
    @media (max-width: 900px) { .rv-summary { grid-template-columns: 1fr; gap: 1.5rem; } }

    .rv-score-block { text-align: left; }
    .rv-score-num { font-size: 3.5rem; font-weight: 800; color: var(--rv-text); line-height: 1; letter-spacing: -0.02em; }
    .rv-score-stars { color: var(--rv-star); font-size: 1.25rem; letter-spacing: 0.04em; margin-top: 0.5rem; }
    .rv-score-stars .star-empty { color: #d1d5db; }
    .rv-score-count { font-size: 0.8125rem; color: var(--rv-muted); margin-top: 0.5rem; line-height: 1.4; }

    .rv-bars { min-width: 0; }
    .rv-bar-row { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.6rem; }
    .rv-bar-row:last-child { margin-bottom: 0; }
    .rv-bar-label { width: 14px; text-align: right; font-size: 0.875rem; font-weight: 600; color: var(--rv-muted); }
    .rv-bar-track { flex: 1; height: 10px; background: #eef2f7; border-radius: 5px; overflow: hidden; min-width: 80px; }
    .rv-bar-fill { height: 100%; border-radius: 5px; background: linear-gradient(90deg, #3b5fe0, #6a5acd); transition: width 0.4s ease; }
    .rv-bar-pct { width: 2.5rem; text-align: right; font-size: 0.8125rem; color: var(--rv-muted); }

    .rv-cats {}
    .rv-cat-row { display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; margin-bottom: 0.6rem; font-size: 0.875rem; }
    .rv-cat-row:last-child { margin-bottom: 0; }
    .rv-cat-name { color: #475569; }
    .rv-cat-val { font-weight: 700; color: var(--rv-blue); }

    .rv-filters { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 0.75rem; padding: 1rem 0; border-bottom: 1px solid var(--rv-border); margin-bottom: 1.5rem; }
    .rv-filters-left { display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem; }
    .rv-filter-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 0.875rem; border: 1px solid var(--rv-border); border-radius: 20px; background: #fff; color: #475569; font-size: 0.8125rem; cursor: pointer; transition: all 0.15s; font-family: inherit; }
    .rv-filter-btn:hover { background: #f1f5f9; border-color: #cbd5e1; }
    .rv-filter-btn svg { width: 14px; height: 14px; flex-shrink: 0; }
    .rv-sort { display: flex; align-items: center; gap: 0.5rem; font-size: 0.8125rem; color: var(--rv-muted); }
    .rv-sort-active { color: var(--rv-blue); font-weight: 600; cursor: pointer; }

    .rv-list { display: flex; flex-direction: column; gap: 0; }
    .rv-card { padding: 1.5rem 0; border-bottom: 1px solid var(--rv-border); }
    .rv-card:last-child { border-bottom: none; }
    .rv-card-top { display: flex; flex-wrap: wrap; align-items: flex-start; justify-content: space-between; gap: 0.75rem; margin-bottom: 1rem; }
    .rv-card-author { display: flex; align-items: center; gap: 0.875rem; min-width: 0; }
    .rv-card-avatar { width: 48px; height: 48px; border-radius: 50%; color: #fff; font-weight: 700; font-size: 1rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .rv-card-info { min-width: 0; }
    .rv-card-name { font-weight: 700; color: var(--rv-text); margin: 0; font-size: 0.9375rem; }
    .rv-card-name a { color: var(--rv-blue); text-decoration: none; }
    .rv-card-name a:hover { text-decoration: underline; }
    .rv-card-sub { font-size: 0.8125rem; color: var(--rv-muted); margin: 0.2rem 0 0; display: flex; align-items: center; gap: 0.35rem; flex-wrap: wrap; }
    .rv-card-sub svg { width: 12px; height: 12px; flex-shrink: 0; color: #94a3b8; }
    .rv-card-right { text-align: right; flex-shrink: 0; }
    .rv-card-stars { color: var(--rv-star); font-size: 1rem; letter-spacing: 0.02em; }
    .rv-card-stars .star-empty { color: #d1d5db; }
    .rv-card-date { font-size: 0.75rem; color: #94a3b8; margin: 0.25rem 0 0; }
    .rv-card-title { font-weight: 700; color: var(--rv-text); margin: 0 0 0.5rem; font-size: 1.0625rem; }
    .rv-card-text { font-size: 0.9375rem; color: #475569; line-height: 1.65; margin: 0; }
    .rv-card-footer { display: flex; align-items: center; gap: 1.25rem; margin-top: 1rem; }
    .rv-card-action { display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.8125rem; color: var(--rv-muted); background: none; border: none; cursor: pointer; padding: 0; transition: color 0.15s; font-family: inherit; }
    .rv-card-action:hover { color: var(--rv-blue); }
    .rv-card-action svg { width: 16px; height: 16px; }
    .rv-card-reply { margin-top: 1rem; padding: 1rem 1.125rem; background: #f0f4ff; border-left: 3px solid var(--rv-blue); border-radius: 8px; }
    .rv-card-reply-label { font-size: 0.75rem; font-weight: 700; color: var(--rv-blue); margin: 0 0 0.35rem; text-transform: uppercase; letter-spacing: 0.04em; }
    .rv-card-reply-text { font-size: 0.9375rem; color: #475569; line-height: 1.55; margin: 0; }
</style>
@endpush

@section('content')
<div class="hotel-show-page">
<div class="max-w-7xl mx-auto px-4 py-6 md:py-8">
    <nav class="flex items-center gap-2 text-sm mb-6">
        <a href="{{ route('home') }}" class="text-[var(--bleu-clair)] hover:text-[var(--bleu-fonce)] transition-colors font-medium">Accueil</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('hotels.index') }}" class="text-[var(--bleu-clair)] hover:text-[var(--bleu-fonce)] transition-colors font-medium">Djibouti</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('hotels.index') }}?city={{ urlencode($hotel->ville ?? '') }}" class="text-[var(--bleu-clair)] hover:text-[var(--bleu-fonce)] transition-colors font-medium">{{ $hotel->ville ?? 'Djibouti' }}</a>
        <span class="text-slate-300">/</span>
        <span class="text-[var(--bleu-fonce)] font-semibold truncate max-w-[200px] md:max-w-none">{{ $hotel->nom }}</span>
    </nav>

    {{-- En-tete --}}
    <div class="flex flex-wrap items-start justify-between gap-6 mb-6">
        <div class="min-w-0 flex-1">
            <h1 class="text-2xl md:text-3xl font-bold text-[var(--bleu-fonce)] tracking-tight">{{ $hotel->nom }}</h1>
            <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1 text-slate-600">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-[var(--bleu-fonce)] flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                    {{ $hotel->adresse }}{{ $hotel->adresse && $hotel->ville ? ', ' : '' }}{{ $hotel->ville ?? 'Djibouti' }}
                </span>
                <a href="#map" class="text-[var(--bleu-clair)] text-sm font-semibold hover:text-[var(--bleu-fonce)] hover:underline transition-colors">Très bon emplacement – voir la carte</a>
            </div>
            <p class="mt-2 text-xs text-slate-500">Nous ajustons nos tarifs</p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <button type="button" class="w-11 h-11 rounded-full border-2 border-[var(--bleu-fonce)] bg-white flex items-center justify-center text-[var(--bleu-fonce)] hover:bg-[var(--bleu-pale)] transition-colors" title="Favoris" aria-label="Favoris">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </button>
            <button type="button" class="w-11 h-11 rounded-full border-2 border-[var(--bleu-fonce)] bg-white flex items-center justify-center text-[var(--bleu-fonce)] hover:bg-[var(--bleu-pale)] transition-colors" title="Partager" aria-label="Partager">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
            </button>
            <form action="{{ route('reservations.create') }}" method="GET" class="inline ml-1">
                @if($hotel->typesChambre->isNotEmpty())
                    @php $firstAvailable = $hotel->typesChambre->first()?->chambres->where('etat', 'DISPONIBLE')->first(); @endphp
                    @if($firstAvailable)<input type="hidden" name="chambre_id" value="{{ $firstAvailable->id }}">@endif
                @endif
                <button type="submit" class="btn-booking px-6 py-3 rounded-xl shadow-sm">Réserver</button>
            </form>
        </div>
    </div>

    {{-- Grille principale --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2">
            @php
                $hotelImages = $hotel->images->sortBy('sort_order');
                $totalImages = $hotelImages->count();
                $mainImg = $hotel->mainImage ?? $hotelImages->first();
                $mainUrl = ($mainImg && $mainImg->url) ? $mainImg->url : ($totalImages > 0 ? $hotelImages->first()->url : asset('images/ayla.jpg'));
                if (empty($mainUrl) && $totalImages > 0) {
                    $mainUrl = asset('storage/' . ltrim($hotelImages->first()->path ?? '', '/'));
                }
                if (empty($mainUrl)) {
                    $mainUrl = asset('images/ayla.jpg');
                }
                $others = $totalImages > 0 ? $hotelImages->filter(fn($i) => $mainImg && $i->id !== $mainImg->id)->values() : collect();
                $rightColumn = $others->take(2);
                $stripThumbs = $others->skip(2)->take(4);
                $moreCount = $totalImages > 7 ? $totalImages - 7 : 0;
                $allForLightbox = $hotelImages;
            @endphp
            <div class="hotel-gallery-block">
                <div class="main-cell">
                    <a href="{{ $mainUrl }}" class="hotel-gallery glightbox" data-gallery="hotel" data-title="{{ $hotel->nom }}">
                        <img src="{{ $mainUrl }}" alt="{{ $hotel->nom }}" />
                    </a>
                </div>
                @if($rightColumn->isNotEmpty())
                    <div class="right-top">
                        @php $img = $rightColumn[0]; $url = $img->url ?: asset('storage/' . ltrim($img->path ?? '', '/')); @endphp
                        <a href="{{ $url }}" class="hotel-gallery glightbox" data-gallery="hotel" data-title="{{ $hotel->nom }}"><img src="{{ $url }}" alt="" /></a>
                    </div>
                    <div class="right-bottom">
                        @if(isset($rightColumn[1]))
                            @php $img = $rightColumn[1]; $url = $img->url ?: asset('storage/' . ltrim($img->path ?? '', '/')); @endphp
                            <a href="{{ $url }}" class="hotel-gallery glightbox" data-gallery="hotel" data-title="{{ $hotel->nom }}"><img src="{{ $url }}" alt="" /></a>
                        @else
                            <a href="{{ $mainUrl }}" class="hotel-gallery glightbox block h-full rounded-xl overflow-hidden bg-slate-200" data-gallery="hotel" data-title="{{ $hotel->nom }}"><img src="{{ $mainUrl }}" alt="" class="w-full h-full object-cover" /></a>
                        @endif
                    </div>
                @else
                    <div class="right-top"><a href="{{ $mainUrl }}" class="hotel-gallery glightbox block h-full rounded-xl overflow-hidden bg-slate-200" data-gallery="hotel" data-title="{{ $hotel->nom }}"><img src="{{ $mainUrl }}" alt="" class="w-full h-full object-cover" /></a></div>
                    <div class="right-bottom"><a href="{{ $mainUrl }}" class="hotel-gallery glightbox block h-full rounded-xl overflow-hidden bg-slate-200" data-gallery="hotel" data-title="{{ $hotel->nom }}"><img src="{{ $mainUrl }}" alt="" class="w-full h-full object-cover" /></a></div>
                @endif
            </div>
            <div class="hotel-gallery-strip-row">
                @foreach($stripThumbs as $img)
                    @php $imgUrl = $img->url ?: asset('storage/' . ltrim($img->path ?? '', '/')); @endphp
                    <div class="hotel-strip-thumb">
                        <a href="{{ $imgUrl }}" class="hotel-gallery glightbox" data-gallery="hotel" data-title="{{ $hotel->nom }}"><img src="{{ $imgUrl }}" alt="" /></a>
                    </div>
                @endforeach
                @if($moreCount > 0)
                    <div class="hotel-strip-thumb">
                        <a href="{{ $mainUrl }}" class="hotel-gallery glightbox relative block w-full h-full rounded-xl overflow-hidden bg-slate-300" data-gallery="hotel" data-title="{{ $hotel->nom }}">
                            <img src="{{ $stripThumbs->isNotEmpty() ? ($stripThumbs->last()->url ?: asset('storage/' . ltrim($stripThumbs->last()->path ?? '', '/'))) : $mainUrl }}" alt="" class="w-full h-full object-cover opacity-70" />
                            <span class="more-overlay pointer-events-none">+{{ $moreCount }} autres photos</span>
                        </a>
                    </div>
                @elseif($stripThumbs->count() < 5 && $allForLightbox->isNotEmpty())
                    @php $lastStrip = $allForLightbox->filter(fn($i) => $mainImg && $i->id !== $mainImg->id)->skip(6)->first(); @endphp
                    @if($lastStrip)
                        <div class="hotel-strip-thumb">
                            @php $u = $lastStrip->url ?: asset('storage/' . ltrim($lastStrip->path ?? '', '/')); @endphp
                            <a href="{{ $u }}" class="hotel-gallery glightbox" data-gallery="hotel" data-title="{{ $hotel->nom }}"><img src="{{ $u }}" alt="" /></a>
                        </div>
                    @endif
                @endif
            </div>
            @php $shownIds = collect([$mainImg?->id])->concat($rightColumn->pluck('id'))->concat($stripThumbs->pluck('id'))->filter()->unique(); @endphp
            @foreach($allForLightbox as $img)
                @if(!$shownIds->contains($img->id))
                    @php $imgUrl = $img->url ?: asset('storage/' . ltrim($img->path ?? '', '/')); @endphp
                    <a href="{{ $imgUrl }}" class="hotel-gallery glightbox hidden" data-gallery="hotel" data-title="{{ $hotel->nom }}"></a>
                @endif
            @endforeach
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            @php
                $avgRating = round(($hotel->avis->avg('note') ?? 4.5) * 10) / 10;
                $ratingLabel = $avgRating >= 9 ? 'Superbe' : ($avgRating >= 8 ? 'Très bien' : ($avgRating >= 7 ? 'Bien' : 'Agréable'));
                $lastAvis = $hotel->avis->sortByDesc('date_avis')->first();
            @endphp
            <div class="sidebar-card">
                <div class="flex items-center justify-between gap-2 flex-wrap">
                    <p class="text-lg font-bold text-[var(--bleu-fonce)]">{{ $ratingLabel }}</p>
                    <div class="flex items-center gap-1 text-amber-500" aria-hidden="true">
                        @for($i = 1; $i <= 5; $i++) <span class="text-sm">{{ $i <= round($avgRating) ? '★' : '☆' }}</span> @endfor
                    </div>
                </div>
                <div class="sidebar-rating-badge inline-block mt-3">{{ number_format($avgRating, 1, ',', ' ') }}</div>
                <p class="text-sm text-slate-600 mt-3">{{ $hotel->avis->count() }} expériences vécues</p>
                @if($lastAvis)
                    <p class="text-sm font-semibold text-slate-900 mt-4">Ce que les personnes ayant séjourné ici ont adoré :</p>
                    <blockquote class="text-sm text-slate-700 mt-2 pl-0 border-l-0 italic">« {{ Str::limit($lastAvis->commentaire ?? 'Très bon séjour.', 120) }} »</blockquote>
                    <p class="text-sm text-slate-600 mt-3 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-semibold text-xs flex-shrink-0">{{ strtoupper(substr($lastAvis->nom_client ?? 'A', 0, 1)) }}</span>
                        {{ $lastAvis->nom_client }} – France
                    </p>
                @endif
                <a href="#reviews" class="inline-flex items-center gap-1.5 mt-4 text-[var(--bleu-clair)] text-sm font-semibold hover:text-[var(--bleu-fonce)] hover:underline">Voir tous les avis <span aria-hidden="true">→</span></a>
            </div>
            <div class="sidebar-card">
                <p class="text-sm font-semibold text-[var(--bleu-fonce)] mb-2">Personnel</p>
                <div class="sidebar-rating-badge inline-block">9,2</div>
            </div>
            <div class="sidebar-card" id="map">
                <p class="text-sm font-semibold text-[var(--bleu-fonce)] mb-3">Carte (Djibouti)</p>
                @php
                    $mapAddress = trim(($hotel->adresse ?? '') . ', ' . ($hotel->ville ?? 'Djibouti') . ', Djibouti');
                    $mapQuery = urlencode($mapAddress);
                    $mapEmbedUrl = 'https://www.google.com/maps?q=' . $mapQuery . '&z=15&output=embed';
                    $mapSearchUrl = 'https://www.google.com/maps/search/' . $mapQuery;
                @endphp
                <div class="map-embed mb-4 relative">
                    <iframe
                        src="{{ $mapEmbedUrl }}"
                        width="100%"
                        height="220"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Localisation de {{ $hotel->nom }}"
                    ></iframe>
                </div>
                <a href="{{ $mapSearchUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-white text-sm bg-[var(--bleu-fonce)] hover:bg-[var(--bleu-clair)] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Voir sur la carte
                </a>
            </div>
        </div>
    </div>

    {{-- A propos --}}
    @if($hotel->description)
    <section class="mb-10 pt-2">
        <h2 class="section-title text-xl">À propos de l'établissement</h2>
        <p class="text-slate-600 leading-relaxed max-w-3xl">{{ $hotel->description }}</p>
    </section>
    @endif

    {{-- Equipements --}}
    <section class="mb-10">
        <h2 class="section-title text-xl">Équipements et services</h2>
        <div class="amenities-grid">
            <span class="amenity-item">🛏 Chambres</span>
            <span class="amenity-item">☕ Petit-déjeuner</span>
            <span class="amenity-item">🏊 Piscine</span>
            <span class="amenity-item">🅿️ Parking gratuit</span>
            <span class="amenity-item">🍴 Restaurant</span>
            <span class="amenity-item">🚐 Navette</span>
            <span class="amenity-item">📶 Wi-Fi gratuit</span>
            <span class="amenity-item">👨‍👩‍👧 Chambres familiales</span>
        </div>
    </section>

    {{-- Chambres disponibles --}}
    <section id="rooms" class="mb-10">
        <h2 class="section-title text-xl mb-5">Chambres disponibles</h2>
        @forelse($hotel->typesChambre as $type)
            @php
                $dispos = $type->chambres->where('etat', 'DISPONIBLE');
                $typeImages = $type->images;
                $firstChambre = $dispos->first();
            @endphp
            <div class="room-card">
                <div class="room-card-inner">
                    <div class="room-gallery">
                        @forelse($typeImages->take(4) as $rimg)
                            <a href="{{ $rimg->url }}" class="hotel-gallery glightbox" data-gallery="room-{{ $type->id }}" data-title="{{ $type->nom_type }}">
                                <img src="{{ $rimg->url }}" alt="{{ $type->nom_type }}" />
                            </a>
                        @empty
                            <a href="{{ asset('images/ayla.jpg') }}" class="hotel-gallery glightbox" data-gallery="room-{{ $type->id }}">
                                <img src="{{ asset('images/ayla.jpg') }}" alt="{{ $type->nom_type }}" />
                            </a>
                        @endforelse
                        @foreach($typeImages->skip(4) as $rimg)
                            <a href="{{ $rimg->url }}" class="hotel-gallery glightbox hidden" data-gallery="room-{{ $type->id }}"></a>
                        @endforeach
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[var(--bleu-fonce)]">{{ $type->nom_type }}</h3>
                        <p class="text-sm text-slate-600 mt-1">Capacité : {{ $type->capacite }} personne(s)</p>
                        @if($type->description)<p class="text-sm text-slate-600 mt-2">{{ $type->description }}</p>@endif
                        <p class="mt-3 text-sm text-emerald-600 font-medium">✓ Annulation gratuite</p>
                        <p class="text-sm text-emerald-600 font-medium">✓ Paiement sur place</p>
                    </div>
                    <div class="text-right min-w-[160px]">
                        <p class="text-sm text-slate-500">1 nuit, 2 adultes</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($type->prix_par_nuit, 0, ',', ' ') }} DJF</p>
                        <p class="text-xs text-slate-500">TVA et frais inclus</p>
                        @if($firstChambre)
                            <form action="{{ route('reservations.create') }}" method="GET" class="mt-4">
                                <input type="hidden" name="chambre_id" value="{{ $firstChambre->id }}">
                                <button type="submit" class="btn-booking w-full rounded-xl">Réserver</button>
                            </form>
                            <p class="text-xs text-slate-500 mt-2">Aucun prélèvement à cette étape.</p>
                        @else
                            <span class="text-sm text-slate-500 mt-4 inline-block">Indisponible</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="text-slate-600">Aucun type de chambre disponible pour le moment.</p>
        @endforelse
    </section>

    <hr class="my-10 border-slate-200" />

    {{-- Avis --}}
    <section id="reviews" class="mb-10">
    <h2 class="section-title text-xl mb-4">Laisser un avis</h2>
    <form action="{{ route('avis.store') }}" method="POST" class="max-w-3xl space-y-4 p-6 bg-[var(--bleu-pale)]/30 rounded-xl border border-[var(--bleu-fonce)]/20">
        @csrf
        <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="nom_client" class="block text-sm font-semibold text-slate-700 mb-1">Nom</label>
                <input type="text" name="nom_client" id="nom_client" required value="{{ old('nom_client') }}" class="w-full min-w-0 px-4 py-3 text-base border border-slate-200 rounded-xl focus:ring-2 focus:ring-[var(--bleu-clair)] focus:border-transparent">
            </div>
            <div>
                <label for="email_client" class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                <input type="email" name="email_client" id="email_client" required value="{{ old('email_client') }}" class="w-full min-w-0 px-4 py-3 text-base border border-slate-200 rounded-xl focus:ring-2 focus:ring-[var(--bleu-clair)] focus:border-transparent">
            </div>
        </div>
        <div>
            <label for="note" class="block text-sm font-semibold text-slate-700 mb-1">Note (1 à 5)</label>
            <select name="note" id="note" required class="w-full min-w-0 px-4 py-3 text-base border border-slate-200 rounded-xl focus:ring-2 focus:ring-[var(--bleu-clair)] focus:border-transparent">
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ old('note') == $i ? 'selected' : '' }}>{{ $i }} étoile(s)</option>
                @endfor
            </select>
        </div>
        <div>
            <label for="commentaire" class="block text-sm font-semibold text-slate-700 mb-1">Commentaire</label>
            <textarea name="commentaire" id="commentaire" rows="4" class="w-full min-w-0 px-4 py-3 text-base border border-slate-200 rounded-xl focus:ring-2 focus:ring-[var(--bleu-clair)] focus:border-transparent" placeholder="Partagez votre expérience...">{{ old('commentaire') }}</textarea>
        </div>
        <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 rounded-xl font-semibold text-white shadow-md transition-all hover:shadow-lg hover:translate-y-[-1px]" style="background: linear-gradient(135deg, #3b82f6, #60a5fa);">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            Envoyer mon avis
        </button>
    </form>

    @if ($hotel->avis->isNotEmpty())
        @php
            $avisList = $hotel->avis->sortByDesc('date_avis')->values();
            $avisCount = $avisList->count();
            $avgRating = round($hotel->avis->avg('note') * 10) / 10;
            $avgHalf = floor($avgRating * 2) / 2;
            $distribution = [];
            for ($i = 5; $i >= 1; $i--) {
                $distribution[$i] = $hotel->avis->where('note', $i)->count();
            }
            $maxDist = max(1, max($distribution));
            $categories = [
                'Propreté' => min(5, round($avgRating + 0.1, 1)),
                'Personnel' => min(5, round($avgRating + 0.2, 1)),
                'Emplacement' => min(5, round($avgRating + 0.05, 1)),
                'Rapport qualité/prix' => min(5, round($avgRating - 0.1, 1)),
            ];
            $avatarColors = ['#3b5fe0','#6a5acd','#0891b2','#059669','#d97706','#dc2626','#7c3aed','#be185d'];
        @endphp
        <div class="reviews-section mt-10">

            {{-- Resume --}}
            <div class="rv-summary">
                <div class="rv-score-block">
                    <p class="rv-score-num">{{ number_format($avgRating, 1, '.', '') }}</p>
                    <p class="rv-score-stars" aria-hidden="true">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= round($avgRating) ? '' : 'star-empty' }}">&#9733;</span>
                        @endfor
                    </p>
                    <p class="rv-score-count">Basé sur {{ number_format($avisCount, 0, ',', ' ') }} avis<br>vérifiés</p>
                </div>
                <div class="rv-bars">
                    @foreach($distribution as $stars => $count)
                        @php $pct = $avisCount > 0 ? round($count / $avisCount * 100) : 0; @endphp
                        <div class="rv-bar-row">
                            <span class="rv-bar-label">{{ $stars }}</span>
                            <div class="rv-bar-track"><div class="rv-bar-fill" style="width:{{ $pct }}%"></div></div>
                            <span class="rv-bar-pct">{{ $pct }}%</span>
                        </div>
                    @endforeach
                </div>
                <div class="rv-cats">
                    @foreach($categories as $catName => $catScore)
                        <div class="rv-cat-row">
                            <span class="rv-cat-name">{{ $catName }}</span>
                            <span class="rv-cat-val">{{ number_format($catScore, 1, ',', ' ') }} / 5</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Filtres --}}
            <div class="rv-filters">
                <div class="rv-filters-left">
                    <button type="button" class="rv-filter-btn">Tous les scores <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></button>
                    <button type="button" class="rv-filter-btn">Type de voyageur <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></button>
                    <button type="button" class="rv-filter-btn"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0h.5a2.5 2.5 0 002.5-2.5V3.935M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Langue : Français</button>
                </div>
                <div class="rv-sort">Trier par : <span class="rv-sort-active">Plus récents &#9662;</span></div>
            </div>

            {{-- Liste des avis --}}
            <div class="rv-list">
                @foreach ($avisList->take(20) as $idx => $avis)
                    @php
                        $initials = collect(explode(' ', $avis->nom_client ?? 'A'))->map(fn($s) => mb_substr($s, 0, 1))->take(2)->implode('');
                        if ($initials === '') { $initials = 'A'; }
                        $publishedAt = $avis->date_avis ? \Carbon\Carbon::parse($avis->date_avis)->diffForHumans() : '';
                        $monthYear = $avis->date_avis ? \Carbon\Carbon::parse($avis->date_avis)->translatedFormat('F Y') : '';
                        $comment = trim($avis->commentaire ?? '');
                        $firstLine = $comment ? strtok($comment, "\n") : '';
                        if ($firstLine !== false) { $firstLine = trim($firstLine); } else { $firstLine = ''; }
                        $rest = $firstLine !== '' ? trim(Str::after($comment, $firstLine)) : $comment;
                        $bgColor = $avatarColors[$idx % count($avatarColors)];
                    @endphp
                    <article class="rv-card">
                        <div class="rv-card-top">
                            <div class="rv-card-author">
                                <div class="rv-card-avatar" style="background:{{ $bgColor }}">{{ strtoupper(mb_substr($initials, 0, 2)) }}</div>
                                <div class="rv-card-info">
                                    <p class="rv-card-name"><a href="#">{{ $avis->nom_client }}</a></p>
                                    <p class="rv-card-sub">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        Djibouti
                                        @if($monthYear) &bull; {{ $monthYear }} @endif
                                        &bull; Client vérifié
                                    </p>
                                </div>
                            </div>
                            <div class="rv-card-right">
                                <p class="rv-card-stars" aria-hidden="true">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="{{ $i <= (int)$avis->note ? '' : 'star-empty' }}">&#9733;</span>
                                    @endfor
                                </p>
                                <p class="rv-card-date">
                                    @if($publishedAt)
                                        Publié {{ $publishedAt }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        @if ($comment)
                            @if ($firstLine && $rest !== '' && strlen($firstLine) <= 80)
                                <p class="rv-card-title">{{ $firstLine }}</p>
                                <p class="rv-card-text">{{ $rest }}</p>
                            @else
                                <p class="rv-card-text">{{ $comment }}</p>
                            @endif
                        @endif
                        @if ($avis->reponse_admin)
                            <div class="rv-card-reply">
                                <p class="rv-card-reply-label">Réponse de l'établissement</p>
                                <p class="rv-card-reply-text">{{ $avis->reponse_admin }}</p>
                            </div>
                        @endif
                        <div class="rv-card-footer">
                            <button type="button" class="rv-card-action" aria-label="Utile">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10l-7 7m7-7l7 7"/></svg>
                                Utile (0)
                            </button>
                            <button type="button" class="rv-card-action" aria-label="Partager">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                                Partager
                            </button>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    @endif
    </section>
</div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        GLightbox({
            selector: '.hotel-gallery',
            touchNavigation: true,
            keyboardNavigation: true,
            loop: true,
            openEffect: 'zoom',
            closeEffect: 'fade',
            closeButton: true,
            draggable: true
        });
    });
</script>
@endpush
@endsection
