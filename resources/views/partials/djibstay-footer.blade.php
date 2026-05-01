@php
    $appName   = \App\Models\SiteSetting::get('app_name','DjibStay');
    $logoPath  = \App\Models\SiteSetting::get('app_logo','');
    $aboutText = \App\Models\SiteSetting::get('about_text','');
    $facebook  = \App\Models\SiteSetting::get('social_facebook','');
    $instagram = \App\Models\SiteSetting::get('social_instagram','');
    $twitter   = \App\Models\SiteSetting::get('social_twitter','');
    $telephone = \App\Models\SiteSetting::get('contact_telephone','+253 77 00 00 00');
    $email     = \App\Models\SiteSetting::get('contact_email','contact@djibstay.dj');
    $copyright = \App\Models\SiteSetting::get('footer_copyright','© 2026 DjibStay');
    $adresse   = \App\Models\SiteSetting::get('contact_adresse','');
@endphp

<style>
.site-footer { background:#0f1729; color:#fff; padding:48px 0 0; }
.footer-logo { font-size:22px; font-weight:900; color:#fff; margin-bottom:10px; }
.footer-logo span { color:#febb02; }
.footer-desc { font-size:13px; color:rgba(255,255,255,0.65); line-height:1.8; }
.footer-title { font-size:13px; font-weight:800; color:#fff; text-transform:uppercase; letter-spacing:.5px; margin-bottom:14px; }
.footer-link { display:block; font-size:13px; color:rgba(255,255,255,0.65); text-decoration:none; margin-bottom:8px; transition:color .2s; }
.footer-link:hover { color:#febb02; }
.footer-social { display:flex; gap:10px; margin-top:14px; }
.footer-social a { width:36px; height:36px; background:rgba(255,255,255,0.1); border-radius:8px; display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,0.75); font-size:16px; text-decoration:none; transition:all .2s; }
.footer-social a:hover { background:#febb02; color:#003580; }
.footer-bottom { border-top:1px solid rgba(255,255,255,0.08); padding:16px 0; margin-top:36px; text-align:center; font-size:12px; color:rgba(255,255,255,0.45); }
</style>

<footer class="site-footer">
    <div class="container" style="max-width:1320px;">
        <div class="row g-4 pb-4">

            {{-- Colonne logo + description + réseaux --}}
            <div class="col-lg-4 col-md-6">
                @if($logoPath)
                    <img src="{{ asset('storage/'.$logoPath) }}" alt="{{ $appName }}"
     style="height:44px;object-fit:contain;margin-bottom:12px;">
                @else
                    <div class="footer-logo">🏨 {{ $appName }}</div>
                @endif
                <p class="footer-desc">
                    {{ $aboutText ? Str::limit($aboutText,150) : 'La plateforme de référence pour la réservation d\'hôtels.' }}
                </p>
                <div class="footer-social">
                    @if($facebook)<a href="{{ $facebook }}" target="_blank"><i class="bi bi-facebook"></i></a>@endif
                    @if($instagram)<a href="{{ $instagram }}" target="_blank"><i class="bi bi-instagram"></i></a>@endif
                    @if($twitter)<a href="{{ $twitter }}" target="_blank"><i class="bi bi-twitter-x"></i></a>@endif
                    @if($telephone)<a href="https://wa.me/{{ preg_replace('/\D/','',$telephone) }}" target="_blank"><i class="bi bi-whatsapp"></i></a>@endif
                </div>
            </div>

            {{-- Colonne Navigation --}}
            <div class="col-lg-2 col-md-6 col-6">
                <div class="footer-title">Navigation</div>
                <a href="{{ route('home') }}" class="footer-link">Accueil</a>
                <a href="{{ route('hotels.index') }}" class="footer-link">Hôtels</a>
                <a href="{{ route('pages.about') }}" class="footer-link">À propos</a>
                <a href="{{ route('pages.contact') }}" class="footer-link">Contact</a>
            </div>

            {{-- Colonne Mon compte --}}
            <div class="col-lg-2 col-md-6 col-6">
                <div class="footer-title">Mon compte</div>
                @auth
                    <a href="{{ route('client.compte') }}" class="footer-link">Mon espace</a>
                    <a href="{{ route('client.reservations') }}" class="footer-link">Mes réservations</a>
                @else
                    <a href="{{ route('login') }}" class="footer-link">Se connecter</a>
                    @if(\App\Models\SiteSetting::get('inscription_active', '1') == '1')
                        <a href="{{ route('register') }}" class="footer-link">S'inscrire</a>
                    @endif
                @endauth
                <a href="{{ route('reservations.status') }}" class="footer-link">Suivi réservation</a>
            </div>

            {{-- Colonne Contact --}}
            <div class="col-lg-4 col-md-6">
                <div class="footer-title">Contact</div>
                @if($telephone)
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;font-size:13px;color:rgba(255,255,255,0.65);">
                    <i class="bi bi-telephone-fill" style="color:#febb02;flex-shrink:0;"></i>{{ $telephone }}
                </div>
                @endif
                @if($email)
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;font-size:13px;color:rgba(255,255,255,0.65);">
                    <i class="bi bi-envelope-fill" style="color:#febb02;flex-shrink:0;"></i>{{ $email }}
                </div>
                @endif
                @if($adresse)
                <div style="display:flex;align-items:flex-start;gap:8px;margin-bottom:8px;font-size:13px;color:rgba(255,255,255,0.65);">
                    <i class="bi bi-geo-alt-fill" style="color:#febb02;flex-shrink:0;margin-top:2px;"></i>{{ $adresse }}
                </div>
                @endif
                <div style="margin-top:14px;">
                    <a href="{{ route('pages.contact') }}"
                       style="background:rgba(254,187,2,0.15);color:#febb02;border:1px solid rgba(254,187,2,0.3);padding:8px 16px;border-radius:7px;text-decoration:none;font-size:12px;font-weight:700;display:inline-flex;align-items:center;gap:6px;">
                        <i class="bi bi-headset"></i> Nous contacter
                    </a>
                </div>
            </div>

        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">{{ $copyright }}</div>
    </div>
</footer>