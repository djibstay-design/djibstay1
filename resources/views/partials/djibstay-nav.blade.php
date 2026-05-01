<nav class="djibstay-nav navbar navbar-expand-lg" style="background:#003580;">
    <div class="djibstay-nav-inner w-100">
        <div class="d-flex align-items-center justify-content-between w-100 py-2">

            {{-- Logo --}}
            @php
    $appName  = \App\Models\SiteSetting::get('app_name', 'DjibStay');
    $logoPath = \App\Models\SiteSetting::get('app_logo', '');
    $slogan   = \App\Models\SiteSetting::get('app_slogan', 'Réservation Hôtels');
@endphp

<a href="{{ route('home') }}" class="text-decoration-none d-flex align-items-center gap-2">
    @if($logoPath)
        <img
            <img
    src="{{ asset('storage/'.$logoPath) }}"
    alt="{{ $appName }}"
    style="height:52px;width:52px;object-fit:contain;border-radius:10px;background:#fff;padding:5px;flex-shrink:0;">
    @else
        <span style="background:#fff;border-radius:8px;width:36px;height:36px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#003580" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </span>
    @endif
    <div>
        <span style="color:#febb02;font-weight:800;font-size:20px;">{{ $appName }}</span>
        <div style="font-size:9.5px;color:rgba(255,255,255,0.7);text-transform:uppercase;letter-spacing:.6px;">
            {{ $slogan }}
        </div>
    </div>
</a>

            {{-- Burger mobile --}}
            <button class="navbar-toggler border-0 ms-auto me-2" type="button"
                    data-bs-toggle="collapse" data-bs-target="#djibNav"
                    style="color:#fff;">
                <span style="font-size:24px;">☰</span>
            </button>

            {{-- Links --}}
            <div class="collapse navbar-collapse" id="djibNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-1">

                    <li class="nav-item">
                        <a href="{{ route('home') }}"
                           class="nav-link text-white fw-500 px-3 py-2 rounded"
                           style="{{ request()->routeIs('home') ? 'background:rgba(255,255,255,0.15);font-weight:700;' : '' }}">
                            Accueil
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('hotels.index') }}"
                           class="nav-link text-white fw-500 px-3 py-2 rounded"
                           style="{{ request()->routeIs('hotels.*') ? 'background:rgba(255,255,255,0.15);font-weight:700;' : '' }}">
                            Hôtels
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('pages.about') }}"
                           class="nav-link text-white fw-500 px-3 py-2 rounded"
                           style="{{ request()->routeIs('pages.about') ? 'background:rgba(255,255,255,0.15);font-weight:700;' : '' }}">
                            À propos
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('pages.contact') }}"
                           class="nav-link text-white fw-500 px-3 py-2 rounded"
                           style="{{ request()->routeIs('pages.contact') ? 'background:rgba(255,255,255,0.15);font-weight:700;' : '' }}">
                            Contact
                        </a>
                    </li>

                    {{-- Séparateur --}}
                    <li class="nav-item d-none d-lg-block">
                        <span style="color:rgba(255,255,255,0.3);font-size:20px;padding:0 4px;">|</span>
                    </li>

                    {{-- Auth --}}
                    @auth
                        @if(in_array(auth()->user()->role, ['SUPER_ADMIN', 'ADMIN']))
                            <li class="nav-item">
                                <a href="{{ route('admin.dashboard') }}"
                                   class="nav-link px-3 py-2 rounded fw-600"
                                   style="background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.3);">
                                    <i class="bi bi-speedometer2 me-1"></i> Admin
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('client.compte') }}"
                                   class="nav-link px-3 py-2 rounded fw-600"
                                   style="background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.3);">
                                    <i class="bi bi-person-circle me-1"></i>
                                    {{ auth()->user()->prenom ?? auth()->user()->name }}
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="btn btn-sm px-3 py-2 rounded fw-600"
                                        style="background:rgba(255,255,255,0.12);color:#fff;border:1px solid rgba(255,255,255,0.3);">
                                    <i class="bi bi-box-arrow-left me-1"></i> Déconnexion
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}"
                               class="nav-link px-3 py-2 rounded fw-600"
                               style="background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.35);">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Connexion
                            </a>
                        </li>
                        @if(\App\Models\SiteSetting::get('inscription_active', '1') == '1')
                        <li class="nav-item">
                            <a href="{{ route('register') }}"
                               class="nav-link px-4 py-2 rounded fw-700"
                               style="background:#febb02;color:#003580!important;font-weight:800;">
                                <i class="bi bi-person-plus me-1"></i> S'inscrire
                            </a>
                        </li>
                        @endif
                    @endauth

                </ul>
            </div>

        </div>
    </div>
</nav>