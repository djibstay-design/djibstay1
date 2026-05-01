@extends('layouts.app')
@section('title', \App\Models\SiteSetting::get('app_name','DjibStay').' — À propos')

@push('styles')
<style>
.about-hero {
    position: relative;
    overflow: hidden;
    padding: 60px 0;
    color: #fff;
    text-align: center;
    background: url('https://images.unsplash.com/photo-1582719508461-905c673771fd?q=80&w=1920&auto=format&fit=crop') center/cover no-repeat;
}
.about-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(160deg, rgba(0,30,80,0.62) 0%, rgba(0,80,160,0.48) 100%);
}
.about-hero .container { position: relative; z-index: 2; }
.about-hero h1 { font-size:clamp(26px,5vw,44px); font-weight:900; }
.about-hero p  { color:rgba(255,255,255,0.82); font-size:16px; max-width:600px; margin:12px auto 0; }
.value-card { background:#fff; border-radius:12px; border:1px solid #e2e8f0; padding:26px 22px; text-align:center; box-shadow:0 2px 12px rgba(0,53,128,0.07); transition:all .25s; height:100%; }
.value-card:hover { transform:translateY(-4px); box-shadow:0 10px 32px rgba(0,53,128,0.13); }
.value-icon { width:60px; height:60px; background:linear-gradient(135deg,#003580,#0071c2); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 14px; font-size:26px; color:#fff; }
.team-card { background:#fff; border-radius:12px; border:1px solid #e2e8f0; padding:24px 20px; text-align:center; box-shadow:0 2px 10px rgba(0,53,128,0.07); transition:all .25s; }
.team-card:hover { transform:translateY(-4px); box-shadow:0 10px 32px rgba(0,53,128,0.13); }
.team-avatar { width:72px; height:72px; background:linear-gradient(135deg,#003580,#0071c2); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 12px; font-size:28px; color:#fff; font-weight:800; }
</style>
@endpush

@section('content')
@php
    $appName  = \App\Models\SiteSetting::get('app_name','DjibStay');
    $aboutText= \App\Models\SiteSetting::get('about_text','DjibStay est né d\'un constat simple : réserver un hôtel à Djibouti était trop compliqué.');
    $facebook = \App\Models\SiteSetting::get('social_facebook','');
    $instagram= \App\Models\SiteSetting::get('social_instagram','');
    $twitter  = \App\Models\SiteSetting::get('social_twitter','');
    $logoPath = \App\Models\SiteSetting::get('app_logo','');
@endphp

{{-- HERO --}}
<section class="about-hero">
   <div class="container">
    <div style="
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-bottom: 28px;
    ">
        @if($logoPath)
            <img
                src="{{ asset('storage/'.$logoPath) }}"
                alt="{{ $appName }}"
                style="
                    height: 60px;
                    max-width: 220px;
                    object-fit: contain;
                    display: block;
                    margin-bottom: 22px;
                "
            >
        @else
            <div style="font-size: 48px; margin-bottom: 22px; line-height: 1;">🏨</div>
        @endif

        <h1 style="
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
            text-align: center;
        ">À propos de {{ $appName }}</h1>

        <p style="
            color: rgba(255,255,255,0.78);
            font-size: 15px;
            margin: 0;
            text-align: center;
            max-width: 340px;
            line-height: 1.6;
        ">La plateforme djiboutienne de référence pour la réservation d'hôtels.</p>
    </div>
</div>
</section>

{{-- NOTRE HISTOIRE --}}
<section class="py-5 bg-white">
    <div class="container" style="max-width:1000px;">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 style="font-size:30px;font-weight:900;color:#003580;margin-bottom:14px;">Notre histoire</h2>
                <p style="color:#475569;line-height:1.8;font-size:15px;">{{ $aboutText }}</p>
                <div class="d-flex gap-3 mt-4 flex-wrap">
                    <a href="{{ route('hotels.index') }}" style="background:#003580;color:#fff;padding:11px 26px;border-radius:8px;text-decoration:none;font-weight:700;font-size:14px;display:inline-flex;align-items:center;gap:7px;">
                        <i class="bi bi-search"></i> Explorer les hôtels
                    </a>
                    <a href="{{ route('pages.contact') }}" style="background:#f1f5f9;color:#003580;padding:11px 26px;border-radius:8px;text-decoration:none;font-weight:700;font-size:14px;display:inline-flex;align-items:center;gap:7px;">
                        <i class="bi bi-envelope"></i> Nous contacter
                    </a>
                </div>
                @if($facebook || $instagram || $twitter)
                <div class="d-flex gap-3 mt-3">
                    @if($facebook)<a href="{{ $facebook }}" target="_blank" style="color:#1877f2;font-size:22px;"><i class="bi bi-facebook"></i></a>@endif
                    @if($instagram)<a href="{{ $instagram }}" target="_blank" style="color:#e1306c;font-size:22px;"><i class="bi bi-instagram"></i></a>@endif
                    @if($twitter)<a href="{{ $twitter }}" target="_blank" style="color:#1da1f2;font-size:22px;"><i class="bi bi-twitter-x"></i></a>@endif
                </div>
                @endif
            </div>
            <div class="col-lg-6">
                <div style="background:linear-gradient(135deg,#003580,#0071c2);border-radius:14px;padding:32px;">
                    <div class="row g-3">
                        <div class="col-6" style="text-align:center;">
                            <div style="font-size:36px;font-weight:900;color:#febb02;line-height:1;">{{ \App\Models\Hotel::count() }}+</div>
                            <div style="font-size:13px;color:rgba(255,255,255,0.8);margin-top:4px;">Hôtels partenaires</div>
                        </div>
                        <div class="col-6" style="text-align:center;">
                            <div style="font-size:36px;font-weight:900;color:#febb02;line-height:1;">{{ \App\Models\Reservation::count() }}+</div>
                            <div style="font-size:13px;color:rgba(255,255,255,0.8);margin-top:4px;">Réservations</div>
                        </div>
                        <div class="col-6" style="text-align:center;">
                            <div style="font-size:36px;font-weight:900;color:#febb02;line-height:1;">4.8</div>
                            <div style="font-size:13px;color:rgba(255,255,255,0.8);margin-top:4px;">Note moyenne</div>
                        </div>
                        <div class="col-6" style="text-align:center;">
                            <div style="font-size:36px;font-weight:900;color:#febb02;line-height:1;">24/7</div>
                            <div style="font-size:13px;color:rgba(255,255,255,0.8);margin-top:4px;">Support client</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- VALEURS --}}
<section class="py-5" style="background:#f2f6fc;">
    <div class="container" style="max-width:1320px;">
        <div class="text-center mb-5">
            <h2 style="font-size:28px;font-weight:900;color:#003580;">Nos valeurs</h2>
            <p style="color:#64748b;font-size:15px;">Ce qui guide chacune de nos décisions</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="value-card">
                    <div class="value-icon"><i class="bi bi-shield-check"></i></div>
                    <div style="font-weight:700;color:#003580;font-size:15px;margin-bottom:8px;">Confiance</div>
                    <div style="font-size:13px;color:#64748b;line-height:1.65;">Chaque hôtel vérifié, chaque paiement sécurisé.</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="value-card">
                    <div class="value-icon"><i class="bi bi-lightning-charge"></i></div>
                    <div style="font-weight:700;color:#003580;font-size:15px;margin-bottom:8px;">Simplicité</div>
                    <div style="font-size:13px;color:#64748b;line-height:1.65;">Interface intuitive et réservation rapide.</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="value-card">
                    <div class="value-icon"><i class="bi bi-people"></i></div>
                    <div style="font-weight:700;color:#003580;font-size:15px;margin-bottom:8px;">Local</div>
                    <div style="font-size:13px;color:#64748b;line-height:1.65;">Équipe djiboutienne, solutions de paiement locales.</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="value-card">
                    <div class="value-icon"><i class="bi bi-graph-up"></i></div>
                    <div style="font-weight:700;color:#003580;font-size:15px;margin-bottom:8px;">Innovation</div>
                    <div style="font-size:13px;color:#64748b;line-height:1.65;">Amélioration continue pour la meilleure expérience.</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-5" style="background:linear-gradient(135deg,#003580,#0071c2);">
    <div class="container text-center" style="max-width:650px;">
        <h2 style="font-size:28px;font-weight:900;color:#fff;margin-bottom:12px;">Prêt à découvrir Djibouti ?</h2>
        <p style="color:rgba(255,255,255,0.82);font-size:16px;margin-bottom:24px;">Trouvez l'hôtel parfait et réservez en quelques minutes.</p>
        <a href="{{ route('hotels.index') }}" style="background:#febb02;color:#003580;font-weight:800;font-size:16px;padding:14px 40px;border-radius:10px;text-decoration:none;display:inline-block;">
            <i class="bi bi-search me-2"></i>Voir les hôtels
        </a>
    </div>
</section>
@endsection