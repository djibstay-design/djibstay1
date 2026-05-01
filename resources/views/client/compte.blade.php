@extends('layouts.app')

@section('title', 'Mon compte — DjibStay')

@push('styles')
<style>
    .client-hero {
        background: linear-gradient(135deg, #003580 0%, #0071c2 100%);
        padding: 40px 0;
        color: #fff;
    }
    .client-hero h1 { font-size: clamp(22px,3vw,32px); font-weight: 900; }
    .client-hero p  { color: rgba(255,255,255,0.82); font-size: 14px; margin-top: 6px; }

    .client-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 24px;
        align-items: start;
    }
    @media(max-width:991px){ .client-layout { grid-template-columns: 1fr; } }

    /* Sidebar profil */
    .profile-card {
        background: #fff; border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 12px rgba(0,53,128,0.08);
        overflow: hidden; position: sticky; top: 80px;
    }
    .profile-card-top {
        background: linear-gradient(135deg, #003580, #0071c2);
        padding: 28px 20px; text-align: center;
    }
    .profile-avatar {
        width: 72px; height: 72px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 28px; font-weight: 900; color: #fff;
        margin: 0 auto 12px;
        border: 3px solid rgba(255,255,255,0.4);
    }
    .profile-name { font-size: 16px; font-weight: 800; color: #fff; }
    .profile-email { font-size: 12px; color: rgba(255,255,255,0.75); margin-top: 2px; }
    .profile-badge {
        display: inline-block;
        background: #febb02; color: #003580;
        font-size: 10px; font-weight: 800;
        padding: 2px 10px; border-radius: 10px;
        margin-top: 8px; text-transform: uppercase;
    }
    .profile-nav { padding: 12px 0; }
    .profile-nav-link {
        display: flex; align-items: center; gap: 10px;
        padding: 11px 20px; color: #475569;
        text-decoration: none; font-size: 14px; font-weight: 500;
        transition: all .2s; border-left: 3px solid transparent;
    }
    .profile-nav-link:hover { background: #f0f7ff; color: #003580; border-left-color: #0071c2; }
    .profile-nav-link.active { background: #f0f7ff; color: #003580; border-left-color: #003580; font-weight: 700; }
    .profile-nav-link i { font-size: 16px; width: 20px; text-align: center; }

    /* Main content */
    .content-card {
        background: #fff; border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 12px rgba(0,53,128,0.08);
        overflow: hidden; margin-bottom: 20px;
    }
    .content-card-header {
        padding: 16px 22px; border-bottom: 1px solid #f1f5f9;
        background: #f8fafc;
        display: flex; align-items: center;
        justify-content: space-between; gap: 10px;
    }
    .content-card-header h3 { font-size: 15px; font-weight: 800; color: #003580; margin: 0; }
    .content-card-body { padding: 22px; }

    /* Stats */
    .stats-mini {
        display: grid; grid-template-columns: repeat(3,1fr); gap: 12px;
        margin-bottom: 20px;
    }
    @media(max-width:580px){ .stats-mini { grid-template-columns: 1fr; } }
    .stat-mini-card {
        background: #f8fafc; border-radius: 10px;
        border: 1px solid #e2e8f0; padding: 14px 16px;
        text-align: center;
    }
    .stat-mini-card .num {
        font-size: 24px; font-weight: 900;
        color: #003580; line-height: 1;
    }
    .stat-mini-card .lbl {
        font-size: 11px; color: #64748b;
        font-weight: 600; margin-top: 4px;
        text-transform: uppercase; letter-spacing: .4px;
    }

    /* Reservation mini card */
    .resa-mini {
        display: flex; align-items: center; gap: 14px;
        padding: 14px 0; border-bottom: 1px solid #f1f5f9;
    }
    .resa-mini:last-child { border-bottom: none; }
    .resa-date-box {
        background: #003580; color: #fff;
        border-radius: 8px; padding: 8px 12px;
        text-align: center; flex-shrink: 0; min-width: 48px;
    }
    .resa-date-box .day { font-size: 18px; font-weight: 900; line-height: 1; }
    .resa-date-box .mon { font-size: 10px; text-transform: uppercase; color: rgba(255,255,255,0.75); }
    .resa-info { flex: 1; min-width: 0; }
    .resa-hotel { font-size: 14px; font-weight: 700; color: #1e293b; }
    .resa-detail { font-size: 12px; color: #64748b; margin-top: 2px; }
    .resa-price { font-size: 14px; font-weight: 800; color: #003580; white-space: nowrap; }

    /* Badge statut */
    .badge-s { padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
    .badge-s.confirmee  { background: #dcfce7; color: #14532d; }
    .badge-s.en_attente { background: #fef3c7; color: #92400e; }
    .badge-s.annulee    { background: #fee2e2; color: #991b1b; }

    /* Infos profil */
    .info-row {
        display: flex; justify-content: space-between;
        align-items: center; padding: 12px 0;
        border-bottom: 1px solid #f1f5f9; font-size: 14px;
    }
    .info-row:last-child { border-bottom: none; }
    .info-row .lbl { color: #64748b; display: flex; align-items: center; gap: 7px; }
    .info-row .val { font-weight: 700; color: #1e293b; }

    .btn-djib {
        padding: 10px 22px; border-radius: 8px;
        font-weight: 700; font-size: 13px;
        text-decoration: none; border: none;
        cursor: pointer; transition: all .2s;
        display: inline-flex; align-items: center; gap: 7px;
    }
    .btn-primary-djib { background: #003580; color: #fff; }
    .btn-primary-djib:hover { background: #0071c2; color: #fff; }
    .btn-outline-djib { background: #fff; color: #003580; border: 2px solid #003580; }
    .btn-outline-djib:hover { background: #003580; color: #fff; }
</style>
@endpush

@section('content')

{{-- HERO --}}
<section class="client-hero">
    <div class="container" style="max-width:1200px;">
        <h1>
            <i class="bi bi-person-circle me-2"></i>
            Bonjour, {{ $user->prenom ?? $user->name }} 👋
        </h1>
        <p>Gérez vos réservations et votre profil depuis votre espace personnel.</p>
    </div>
</section>

<div class="container py-4" style="max-width:1200px;">
    <div class="client-layout">

        {{-- SIDEBAR PROFIL --}}
        <aside>
            <div class="profile-card">
                <div class="profile-card-top">
                    <div class="profile-avatar">
                        {{ strtoupper(substr($user->prenom ?? $user->name, 0, 1)) }}
                    </div>
                    <div class="profile-name">{{ $user->prenom }} {{ $user->name }}</div>
                    <div class="profile-email">{{ $user->email }}</div>
                    <span class="profile-badge">Client</span>
                </div>
                <nav class="profile-nav">
                    <a href="{{ route('client.compte') }}"
                       class="profile-nav-link active">
                        <i class="bi bi-house"></i> Mon compte
                    </a>
                    <a href="{{ route('client.reservations') }}"
                       class="profile-nav-link">
                        <i class="bi bi-calendar-check"></i> Mes réservations
                    </a>
                    <a href="{{ route('reservations.status') }}"
                       class="profile-nav-link">
                        <i class="bi bi-search"></i> Suivi réservation
                    </a>
                    <a href="{{ route('hotels.index') }}"
                       class="profile-nav-link">
                        <i class="bi bi-building"></i> Voir les hôtels
                    </a>
                    <div style="border-top:1px solid #f1f5f9;margin:8px 0;"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="profile-nav-link w-100 text-start"
                                style="background:none;border:none;cursor:pointer;color:#dc2626;">
                            <i class="bi bi-box-arrow-left" style="color:#dc2626;"></i>
                            Déconnexion
                        </button>
                    </form>
                </nav>
            </div>
        </aside>

        {{-- CONTENU PRINCIPAL --}}
        <div>

            {{-- Stats --}}
            @php
                $total      = $reservations->count();
                $confirmees = $reservations->where('statut','CONFIRMEE')->count();
                $attente    = $reservations->where('statut','EN_ATTENTE')->count();
            @endphp
            <div class="stats-mini">
                <div class="stat-mini-card">
                    <div class="num">{{ $total }}</div>
                    <div class="lbl">Réservations</div>
                </div>
                <div class="stat-mini-card">
                    <div class="num" style="color:#16a34a;">{{ $confirmees }}</div>
                    <div class="lbl">Confirmées</div>
                </div>
                <div class="stat-mini-card">
                    <div class="num" style="color:#92400e;">{{ $attente }}</div>
                    <div class="lbl">En attente</div>
                </div>
            </div>

            {{-- Dernières réservations --}}
            <div class="content-card">
                <div class="content-card-header">
                    <h3><i class="bi bi-calendar-check me-2"></i>Mes dernières réservations</h3>
                    <a href="{{ route('client.reservations') }}"
                       style="font-size:13px;color:#0071c2;font-weight:600;text-decoration:none;">
                        Voir tout →
                    </a>
                </div>
                <div class="content-card-body">
                    @forelse($reservations as $res)
                        @php
                            $hotel = $res->chambre->typeChambre->hotel ?? null;
                            $nuits = $res->date_debut->diffInDays($res->date_fin);
                        @endphp
                        <div class="resa-mini">
                            <div class="resa-date-box">
                                <div class="day">{{ $res->date_debut->format('d') }}</div>
                                <div class="mon">{{ $res->date_debut->format('M') }}</div>
                            </div>
                            <div class="resa-info">
                                <div class="resa-hotel">
                                    {{ $hotel->nom ?? 'Hôtel' }}
                                </div>
                                <div class="resa-detail">
                                    {{ $res->chambre->typeChambre->nom_type ?? '' }}
                                    · {{ $nuits }} nuit(s)
                                    · <span style="font-family:monospace;font-size:11px;">{{ $res->code_reservation }}</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="resa-price mb-1">
                                    {{ number_format($res->montant_total, 0, ',', ' ') }} DJF
                                </div>
                                <span class="badge-s {{ strtolower($res->statut) }}">
                                    @if($res->statut === 'CONFIRMEE') ✅ Confirmée
                                    @elseif($res->statut === 'EN_ATTENTE') ⏳ En attente
                                    @else ❌ Annulée
                                    @endif
                                </span>
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center;padding:32px;color:#94a3b8;">
                            <div style="font-size:48px;margin-bottom:12px;">🏨</div>
                            <div style="font-weight:700;color:#003580;font-size:15px;margin-bottom:6px;">
                                Aucune réservation pour le moment
                            </div>
                            <p style="font-size:13px;margin-bottom:16px;">
                                Explorez nos hôtels et faites votre première réservation !
                            </p>
                            <a href="{{ route('hotels.index') }}" class="btn-djib btn-primary-djib">
                                <i class="bi bi-search"></i> Voir les hôtels
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Informations profil --}}
            <div class="content-card">
                <div class="content-card-header">
                    <h3><i class="bi bi-person me-2"></i>Mes informations</h3>
                </div>
                <div class="content-card-body">
                    <div class="info-row">
                        <span class="lbl"><i class="bi bi-person"></i> Nom complet</span>
                        <span class="val">{{ $user->prenom }} {{ $user->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="lbl"><i class="bi bi-envelope"></i> Email</span>
                        <span class="val">{{ $user->email }}</span>
                    </div>
                    <div class="info-row">
                        <span class="lbl"><i class="bi bi-telephone"></i> Téléphone</span>
                        <span class="val">{{ $user->phone ?? 'Non renseigné' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="lbl"><i class="bi bi-calendar"></i> Membre depuis</span>
                        <span class="val">{{ $user->created_at->translatedFormat('d F Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- CTA --}}
            <div style="background:linear-gradient(135deg,#003580,#0071c2);border-radius:14px;padding:28px;text-align:center;">
                <div style="font-size:32px;margin-bottom:10px;">🏖️</div>
                <h3 style="color:#fff;font-weight:800;font-size:18px;margin-bottom:8px;">
                    Prêt pour votre prochain séjour ?
                </h3>
                <p style="color:rgba(255,255,255,0.8);font-size:14px;margin-bottom:18px;">
                    Découvrez nos hôtels disponibles et réservez en quelques clics.
                </p>
                <a href="{{ route('hotels.index') }}"
                   style="background:#febb02;color:#003580;padding:12px 28px;border-radius:8px;
                          text-decoration:none;font-weight:800;font-size:14px;display:inline-block;">
                    <i class="bi bi-search me-2"></i>Explorer les hôtels
                </a>
            </div>

        </div>
    </div>
</div>

@endsection