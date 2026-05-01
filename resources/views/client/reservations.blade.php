@extends('layouts.app')

@section('title', 'Mes réservations — DjibStay')

@push('styles')
<style>
    .client-hero {
        background: linear-gradient(135deg, #003580 0%, #0071c2 100%);
        padding: 40px 0; color: #fff;
    }
    .client-hero h1 { font-size: clamp(22px,3vw,32px); font-weight: 900; }

    .client-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 24px; align-items: start;
    }
    @media(max-width:991px){ .client-layout { grid-template-columns: 1fr; } }

    .profile-card {
        background: #fff; border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 12px rgba(0,53,128,0.08);
        overflow: hidden; position: sticky; top: 80px;
    }
    .profile-card-top {
        background: linear-gradient(135deg, #003580, #0071c2);
        padding: 24px 20px; text-align: center;
    }
    .profile-avatar {
        width: 64px; height: 64px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 26px; font-weight: 900; color: #fff;
        margin: 0 auto 10px;
        border: 3px solid rgba(255,255,255,0.4);
    }
    .profile-name  { font-size: 15px; font-weight: 800; color: #fff; }
    .profile-email { font-size: 11px; color: rgba(255,255,255,0.75); margin-top: 2px; }
    .profile-nav   { padding: 10px 0; }
    .profile-nav-link {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 20px; color: #475569;
        text-decoration: none; font-size: 13px; font-weight: 500;
        transition: all .2s; border-left: 3px solid transparent;
    }
    .profile-nav-link:hover  { background: #f0f7ff; color: #003580; border-left-color: #0071c2; }
    .profile-nav-link.active { background: #f0f7ff; color: #003580; border-left-color: #003580; font-weight: 700; }
    .profile-nav-link i { font-size: 15px; width: 18px; text-align: center; }

    /* Reservation cards */
    .resa-card {
        background: #fff; border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(0,53,128,0.07);
        overflow: hidden; margin-bottom: 16px;
        transition: all .25s;
    }
    .resa-card:hover { box-shadow: 0 6px 24px rgba(0,53,128,0.13); transform: translateY(-2px); }
    .resa-card-header {
        background: #f8fafc; padding: 12px 18px;
        border-bottom: 1px solid #f1f5f9;
        display: flex; align-items: center;
        justify-content: space-between; flex-wrap: wrap; gap: 8px;
    }
    .resa-code { font-family: monospace; font-size: 13px; font-weight: 800; color: #003580; }
    .resa-card-body {
        padding: 16px 18px;
        display: grid; grid-template-columns: 1fr 1fr 1fr auto;
        gap: 16px; align-items: center;
    }
    @media(max-width:768px){ .resa-card-body { grid-template-columns: 1fr 1fr; } }
    @media(max-width:480px){ .resa-card-body { grid-template-columns: 1fr; } }

    .resa-info-block .lbl { font-size: 11px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: .4px; }
    .resa-info-block .val { font-size: 14px; font-weight: 700; color: #1e293b; margin-top: 2px; }
    .resa-info-block .val.price { color: #003580; font-size: 16px; }

    .badge-s { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; }
    .badge-s.confirmee  { background: #dcfce7; color: #14532d; }
    .badge-s.en_attente { background: #fef3c7; color: #92400e; }
    .badge-s.annulee    { background: #fee2e2; color: #991b1b; }

    .btn-resa {
        padding: 8px 16px; border-radius: 7px;
        font-weight: 700; font-size: 12px;
        text-decoration: none; display: inline-flex;
        align-items: center; gap: 5px; transition: all .2s;
        white-space: nowrap;
    }
    .btn-resa-primary { background: #003580; color: #fff; }
    .btn-resa-primary:hover { background: #0071c2; color: #fff; }
    .btn-resa-outline { background: #fff; color: #003580; border: 2px solid #003580; }
    .btn-resa-outline:hover { background: #003580; color: #fff; }

    .empty-state { text-align: center; padding: 60px 20px; }
    .empty-state .icon { font-size: 56px; margin-bottom: 14px; }
</style>
@endpush

@section('content')

<section class="client-hero">
    <div class="container" style="max-width:1200px;">
        <h1><i class="bi bi-calendar-check me-2"></i>Mes réservations</h1>
        <p style="color:rgba(255,255,255,0.82);font-size:14px;margin-top:6px;">
            {{ $reservations->total() }} réservation(s) au total
        </p>
    </div>
</section>

<div class="container py-4" style="max-width:1200px;">
    <div class="client-layout">

        {{-- SIDEBAR --}}
        <aside>
            <div class="profile-card">
                <div class="profile-card-top">
                    <div class="profile-avatar">
                        {{ strtoupper(substr($user->prenom ?? $user->name, 0, 1)) }}
                    </div>
                    <div class="profile-name">{{ $user->prenom }} {{ $user->name }}</div>
                    <div class="profile-email">{{ $user->email }}</div>
                </div>
                <nav class="profile-nav">
                    <a href="{{ route('client.compte') }}" class="profile-nav-link">
                        <i class="bi bi-house"></i> Mon compte
                    </a>
                    <a href="{{ route('client.reservations') }}" class="profile-nav-link active">
                        <i class="bi bi-calendar-check"></i> Mes réservations
                    </a>
                    <a href="{{ route('reservations.status') }}" class="profile-nav-link">
                        <i class="bi bi-search"></i> Suivi réservation
                    </a>
                    <a href="{{ route('hotels.index') }}" class="profile-nav-link">
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

        {{-- LISTE RÉSERVATIONS --}}
        <div>
            @forelse($reservations as $res)
                @php
                    $hotel = $res->chambre->typeChambre->hotel ?? null;
                    $nuits = $res->date_debut->diffInDays($res->date_fin);
                    $deposit = $res->hasPaidDeposit();
                @endphp
                <div class="resa-card">
                    <div class="resa-card-header">
                        <div>
                            <span class="resa-code">{{ $res->code_reservation }}</span>
                            <span style="font-size:12px;color:#94a3b8;margin-left:8px;">
                                Réservé le {{ $res->date_reservation->format('d/m/Y') }}
                            </span>
                        </div>
                        <span class="badge-s {{ strtolower($res->statut) }}">
                            @if($res->statut === 'CONFIRMEE') ✅ Confirmée
                            @elseif($res->statut === 'EN_ATTENTE') ⏳ En attente
                            @else ❌ Annulée
                            @endif
                        </span>
                    </div>
                    <div class="resa-card-body">
                        <div class="resa-info-block">
                            <div class="lbl"><i class="bi bi-building me-1"></i>Hôtel</div>
                            <div class="val">{{ $hotel->nom ?? '—' }}</div>
                            <div style="font-size:12px;color:#64748b;margin-top:2px;">
                                {{ $res->chambre->typeChambre->nom_type ?? '' }}
                                · Chambre N° {{ $res->chambre->numero }}
                            </div>
                        </div>
                        <div class="resa-info-block">
                            <div class="lbl"><i class="bi bi-calendar me-1"></i>Séjour</div>
                            <div class="val">{{ $res->date_debut->format('d/m/Y') }}</div>
                            <div style="font-size:12px;color:#64748b;margin-top:2px;">
                                au {{ $res->date_fin->format('d/m/Y') }} · {{ $nuits }} nuit(s)
                            </div>
                        </div>
                        <div class="resa-info-block">
                            <div class="lbl"><i class="bi bi-cash me-1"></i>Montant</div>
                            <div class="val price">{{ number_format($res->montant_total, 0, ',', ' ') }} DJF</div>
                            <div style="font-size:12px;margin-top:2px;">
                                @if($deposit)
                                    <span style="color:#16a34a;font-weight:700;">✅ Acompte payé</span>
                                @else
                                    <span style="color:#dc2626;font-weight:700;">⏳ Acompte requis</span>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ route('reservations.status') }}?code={{ $res->code_reservation }}"
                               class="btn-resa btn-resa-primary">
                                <i class="bi bi-eye"></i> Détails
                            </a>
                            @if(!$deposit)
                                <a href="{{ route('reservations.payment.show', $res) }}"
                                   class="btn-resa btn-resa-outline">
                                    <i class="bi bi-credit-card"></i> Payer
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="icon">🏨</div>
                    <h3 style="color:#003580;font-weight:800;margin-bottom:8px;">
                        Aucune réservation
                    </h3>
                    <p style="color:#64748b;font-size:14px;margin-bottom:20px;">
                        Vous n'avez pas encore effectué de réservation.
                    </p>
                    <a href="{{ route('hotels.index') }}"
                       style="background:#003580;color:#fff;padding:12px 28px;
                              border-radius:8px;text-decoration:none;font-weight:700;font-size:14px;">
                        <i class="bi bi-search me-2"></i>Explorer les hôtels
                    </a>
                </div>
            @endforelse

            @if($reservations->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $reservations->links() }}
                </div>
            @endif
        </div>

    </div>
</div>

@endsection