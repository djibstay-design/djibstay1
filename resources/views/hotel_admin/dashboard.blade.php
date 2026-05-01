@extends('layouts.hotel_admin')
@section('page_title', 'Dashboard')
@section('title', 'Dashboard — Mon Hôtel')

@push('styles')
<style>
    /* ── BASE ── */
    .db-wrap { background:#f2f6fc; padding:0; }

    /* ── BANNER ── */
    .hotel-banner {
        background: linear-gradient(135deg,#003580 0%,#0071c2 100%);
        border-radius:16px; padding:24px 28px; color:#fff;
        display:flex; align-items:center; justify-content:space-between;
        flex-wrap:wrap; gap:16px; margin-bottom:24px;
        box-shadow:0 4px 20px rgba(0,53,128,0.25);
    }
    .hotel-banner h2 { font-size:22px; font-weight:900; margin:0 0 4px; }
    .hotel-banner p  { color:rgba(255,255,255,0.8); font-size:13px; margin:0; }
    .hotel-meta { display:flex; gap:18px; flex-wrap:wrap; margin-top:10px; }
    .hotel-meta-item { display:flex; align-items:center; gap:6px; font-size:13px; color:rgba(255,255,255,0.88); }
    .hotel-meta-item i { color:#febb02; }
    .hotel-score-box { background:rgba(255,255,255,0.15); border-radius:12px; padding:16px 24px; text-align:center; flex-shrink:0; }
    .hotel-score-box .num { font-size:38px; font-weight:900; color:#febb02; line-height:1; }
    .hotel-score-box .lbl { font-size:10px; color:rgba(255,255,255,0.72); text-transform:uppercase; letter-spacing:.6px; margin-top:3px; }

    /* ── KPI GRID ── */
    .kpi-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px; }
    @media(max-width:1100px){ .kpi-grid { grid-template-columns:repeat(2,1fr); } }
    @media(max-width:580px) { .kpi-grid { grid-template-columns:1fr; } }
    .kpi-card {
        background:#fff; border-radius:14px; border:1px solid #e2e8f0;
        padding:20px 22px; display:flex; align-items:flex-start; gap:14px;
        box-shadow:0 2px 10px rgba(0,53,128,0.07); transition:all .25s;
    }
    .kpi-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(0,53,128,0.13); border-color:#0071c2; }
    .kpi-icon { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0; }
    .kpi-icon.blue   { background:#dbeafe; }
    .kpi-icon.green  { background:#dcfce7; }
    .kpi-icon.yellow { background:#fef3c7; }
    .kpi-icon.purple { background:#ede9fe; }
    .kpi-icon.red    { background:#fee2e2; }
    .kpi-icon.teal   { background:#ccfbf1; }
    .kpi-body { flex:1; }
    .kpi-label { font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px; }
    .kpi-value { font-size:26px; font-weight:900; color:#1e293b; line-height:1; }
    .kpi-value.sm { font-size:18px; }
    .kpi-sub { font-size:11px; color:#94a3b8; margin-top:5px; }
    .kpi-badge {
        display:inline-flex; align-items:center; gap:3px;
        font-size:11px; font-weight:700; padding:2px 8px;
        border-radius:10px; margin-top:5px;
    }
    .kpi-badge.up   { background:#dcfce7; color:#16a34a; }
    .kpi-badge.down { background:#fee2e2; color:#dc2626; }
    .kpi-badge.flat { background:#f1f5f9; color:#64748b; }

    /* ── DASH CARDS ── */
    .dash-card {
        background:#fff; border-radius:14px; border:1px solid #e2e8f0;
        box-shadow:0 2px 10px rgba(0,53,128,0.07); overflow:hidden; margin-bottom:20px;
    }
    .dash-card-header {
        padding:14px 20px; border-bottom:1px solid #f1f5f9;
        display:flex; align-items:center; justify-content:space-between; background:#f8fafc;
    }
    .dash-card-header h3 { font-size:14px; font-weight:800; color:#003580; margin:0; }
    .dash-card-header a  { font-size:12px; font-weight:600; color:#0071c2; text-decoration:none; }
    .dash-card-body { padding:18px 20px; }

    /* ── CHARTS ── */
    .chart-bars { display:flex; align-items:flex-end; gap:8px; height:110px; }
    .chart-bar-wrap { flex:1; display:flex; flex-direction:column; align-items:center; gap:4px; height:100%; justify-content:flex-end; }
    .chart-bar { width:100%; border-radius:6px 6px 0 0; background:linear-gradient(180deg,#0071c2,#003580); min-height:4px; transition:height .4s; }
    .chart-bar.yellow { background:linear-gradient(180deg,#febb02,#f5a623); }
    .chart-bar.green  { background:linear-gradient(180deg,#22c55e,#16a34a); }
    .chart-bar-label { font-size:10px; color:#94a3b8; font-weight:600; }
    .chart-bar-val   { font-size:10px; color:#003580; font-weight:700; }

    /* ── OCCUPATION ── */
    .occ-wrap { display:flex; align-items:center; gap:20px; flex-wrap:wrap; }
    .occ-ring { position:relative; width:110px; height:110px; flex-shrink:0; }
    .occ-ring svg { transform:rotate(-90deg); }
    .occ-center { position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; }
    .occ-percent { font-size:20px; font-weight:900; color:#003580; line-height:1; }
    .occ-sub { font-size:10px; color:#64748b; font-weight:600; }
    .occ-legend { flex:1; }
    .occ-legend-item { display:flex; align-items:center; gap:7px; font-size:12px; color:#475569; margin-bottom:7px; }
    .occ-dot { width:10px; height:10px; border-radius:3px; flex-shrink:0; }

    /* ── TABLE ── */
    .db-table { width:100%; border-collapse:collapse; font-size:13px; }
    .db-table th { background:#f8fafc; padding:10px 14px; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:.5px; border-bottom:2px solid #e2e8f0; text-align:left; }
    .db-table td { padding:11px 14px; border-bottom:1px solid #f1f5f9; color:#1e293b; vertical-align:middle; }
    .db-table tr:last-child td { border-bottom:none; }
    .db-table tr:hover td { background:#f8fafc; }

    /* ── BADGES ── */
    .bdg { padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; white-space:nowrap; }
    .bdg.confirmee  { background:#dcfce7; color:#14532d; }
    .bdg.en_attente { background:#fef3c7; color:#92400e; }
    .bdg.annulee    { background:#fee2e2; color:#991b1b; }

    /* ── QUICK ACTIONS ── */
    .qa-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:10px; }
    .qa-btn { background:#fff; border:2px solid #e2e8f0; border-radius:10px; padding:14px; text-decoration:none; color:#003580; font-weight:700; font-size:12px; display:flex; align-items:center; gap:9px; transition:all .2s; }
    .qa-btn:hover { border-color:#003580; background:#f0f7ff; color:#003580; }
    .qa-icon { width:32px; height:32px; border-radius:7px; display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0; }

    /* ── CHECKINS ── */
    .checkin-row { padding:12px 18px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:12px; }
    .checkin-row:last-child { border-bottom:none; }
    .checkin-date { background:#003580; color:#fff; border-radius:8px; padding:6px 10px; text-align:center; flex-shrink:0; min-width:44px; }
    .checkin-date .day { font-size:16px; font-weight:900; line-height:1; }
    .checkin-date .mon { font-size:9px; text-transform:uppercase; color:rgba(255,255,255,0.75); }

    /* ── PROGRESS BARS ── */
    .progress-bar-wrap { margin-bottom:12px; }
    .progress-bar-label { display:flex; justify-content:space-between; font-size:13px; margin-bottom:5px; }
    .progress-bar-label .name  { font-weight:600; color:#1e293b; }
    .progress-bar-label .value { font-weight:700; color:#003580; }
    .progress-bar-bg { height:8px; background:#f1f5f9; border-radius:4px; }
    .progress-bar-fill { height:8px; border-radius:4px; transition:width .6s; }

    /* ── ETAT CHAMBRES ── */
    .etat-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; }
    .etat-card { border-radius:10px; padding:14px; text-align:center; }
    .etat-card .num { font-size:28px; font-weight:900; line-height:1; }
    .etat-card .lbl { font-size:11px; font-weight:700; margin-top:4px; text-transform:uppercase; letter-spacing:.4px; }
</style>
@endpush

@section('content')
<div class="db-wrap">

@if(!$monHotel)
    <div style="text-align:center;padding:80px 20px;">
        <div style="font-size:64px;margin-bottom:16px;">🏨</div>
        <h2 style="color:#003580;font-weight:800;">Aucun hôtel assigné</h2>
        <p style="color:#64748b;">Contactez le super administrateur.</p>
    </div>
@else

{{-- ══ BANNER ══ --}}
<div class="hotel-banner">
    <div>
        <h2>{{ $monHotel->nom }}</h2>
        <p>{{ $monHotel->adresse ?? '' }} {{ $monHotel->ville ?? 'Djibouti' }}</p>
        <div class="hotel-meta">
            <div class="hotel-meta-item"><i class="bi bi-door-open"></i>{{ $totalRooms }} chambre(s)</div>
            <div class="hotel-meta-item"><i class="bi bi-grid"></i>{{ $monHotel->types_chambre_count }} types</div>
            <div class="hotel-meta-item"><i class="bi bi-chat-square-text"></i>{{ $reviewsCount }} avis</div>
            <div class="hotel-meta-item"><i class="bi bi-check-circle"></i>{{ $reservationsByStatus['CONFIRMEE'] }} confirmées</div>
            <div class="hotel-meta-item"><i class="bi bi-hourglass-split"></i>{{ $reservationsByStatus['EN_ATTENTE'] }} en attente</div>
        </div>
    </div>
    <div class="hotel-score-box">
        <div class="num">{{ $avgRating > 0 ? number_format($avgRating,1) : '—' }}</div>
        <div class="lbl">⭐ Note moyenne</div>
        <div style="font-size:11px;color:rgba(255,255,255,0.6);margin-top:4px;">{{ $reviewsCount }} avis</div>
    </div>
</div>

{{-- ══ KPI ROW 1 ══ --}}
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-icon blue">💰</div>
        <div class="kpi-body">
            <div class="kpi-label">Revenus totaux</div>
            <div class="kpi-value sm">{{ number_format($totalRevenue,0,',',' ') }} <span style="font-size:12px;color:#64748b;">DJF</span></div>
            <div class="kpi-sub">Réservations confirmées</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon green">📅</div>
        <div class="kpi-body">
            <div class="kpi-label">Réservations (7j)</div>
            <div class="kpi-value">{{ $newBookings }}</div>
            <div class="kpi-sub">Cette semaine</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon red">⏳</div>
        <div class="kpi-body">
            <div class="kpi-label">En attente</div>
            <div class="kpi-value" style="{{ $enAttente > 0 ? 'color:#f59e0b;' : '' }}">{{ $enAttente }}</div>
            @if($enAttente > 0)
            <a href="{{ route('hoteladmin.reservations.index',['statut'=>'EN_ATTENTE']) }}"
               style="font-size:11px;color:#0071c2;font-weight:700;text-decoration:none;">
                → Traiter maintenant
            </a>
            @else
            <div class="kpi-sub">Aucune en attente ✅</div>
            @endif
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon purple">⭐</div>
        <div class="kpi-body">
            <div class="kpi-label">Note moyenne</div>
            <div class="kpi-value">{{ $avgRating > 0 ? number_format($avgRating,1) : '—' }}<span style="font-size:13px;color:#64748b;">/5</span></div>
            <div class="kpi-sub">{{ $reviewsCount }} avis clients</div>
        </div>
    </div>
</div>

{{-- ══ KPI ROW 2 ══ --}}
<div class="kpi-grid" style="margin-bottom:24px;">
    <div class="kpi-card">
        <div class="kpi-icon teal">🛏️</div>
        <div class="kpi-body">
            <div class="kpi-label">Chambres totales</div>
            <div class="kpi-value">{{ $totalRooms }}</div>
            <div class="kpi-sub">{{ $available }} disponibles</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon green">✅</div>
        <div class="kpi-body">
            <div class="kpi-label">Confirmées</div>
            <div class="kpi-value">{{ $reservationsByStatus['CONFIRMEE'] }}</div>
            <div class="kpi-sub">Total réservations</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon yellow">🏷️</div>
        <div class="kpi-body">
            <div class="kpi-label">Types de chambre</div>
            <div class="kpi-value">{{ $monHotel->types_chambre_count }}</div>
            <div class="kpi-sub">Catégories disponibles</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon red">❌</div>
        <div class="kpi-body">
            <div class="kpi-label">Annulées</div>
            <div class="kpi-value">{{ $reservationsByStatus['ANNULEE'] }}</div>
            <div class="kpi-sub">Total annulations</div>
        </div>
    </div>
</div>

{{-- ══ GRAPHIQUES ══ --}}
<div class="row g-4 mb-0">

    {{-- Revenus --}}
    <div class="col-lg-5">
        <div class="dash-card">
            <div class="dash-card-header"><h3>💰 Revenus — 6 mois</h3></div>
            <div class="dash-card-body">
                @php $maxRev = max(array_column($revenueData,'amount')) ?: 1; @endphp
                <div class="chart-bars">
                    @foreach($revenueData as $r)
                        @php
                            $h   = max(4, round(($r['amount']/$maxRev)*100));
                            $lbl = $r['amount'] > 0 ? number_format($r['amount']/1000,0).'k' : '';
                        @endphp
                        <div class="chart-bar-wrap">
                            <div class="chart-bar-val">{{ $lbl }}</div>
                            <div class="chart-bar" style="height:{{ $h }}px;"></div>
                            <div class="chart-bar-label">{{ $r['month'] }}</div>
                        </div>
                    @endforeach
                </div>
                <div style="margin-top:12px;padding-top:12px;border-top:1px solid #f1f5f9;display:flex;justify-content:space-between;font-size:12px;color:#64748b;">
                    <span>Total confirmé</span>
                    <strong style="color:#003580;">{{ number_format($totalRevenue,0,',',' ') }} DJF</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- Réservations 7j --}}
    <div class="col-lg-4">
        <div class="dash-card">
            <div class="dash-card-header"><h3>📅 Réservations — 7 jours</h3></div>
            <div class="dash-card-body">
                @php $maxG = max(array_column($guestsData,'count')) ?: 1; @endphp
                <div class="chart-bars">
                    @foreach($guestsData as $g)
                        @php $h = max(4, round(($g['count']/$maxG)*100)); @endphp
                        <div class="chart-bar-wrap">
                            <div class="chart-bar-val">{{ $g['count'] ?: '' }}</div>
                            <div class="chart-bar yellow" style="height:{{ $h }}px;"></div>
                            <div class="chart-bar-label">{{ $g['day'] }}</div>
                        </div>
                    @endforeach
                </div>
                <div style="margin-top:12px;padding-top:12px;border-top:1px solid #f1f5f9;display:flex;justify-content:space-between;font-size:12px;color:#64748b;">
                    <span>Cette semaine</span>
                    <strong style="color:#003580;">{{ $newBookings }} réservation(s)</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- Occupation ── --}}
    <div class="col-lg-3">
        <div class="dash-card">
            <div class="dash-card-header"><h3>🏨 Occupation</h3></div>
            <div class="dash-card-body">
                @php $r=46; $circ=2*M_PI*$r; $dash=($occupancyPercent/100)*$circ; @endphp
                <div class="occ-wrap">
                    <div class="occ-ring">
                        <svg width="110" height="110" viewBox="0 0 110 110">
                            <circle cx="55" cy="55" r="{{ $r }}" fill="none" stroke="#f1f5f9" stroke-width="11"/>
                            <circle cx="55" cy="55" r="{{ $r }}" fill="none" stroke="#0071c2" stroke-width="11"
                                    stroke-dasharray="{{ $dash }} {{ $circ }}" stroke-linecap="round"/>
                        </svg>
                        <div class="occ-center">
                            <div class="occ-percent">{{ $occupancyPercent }}%</div>
                            <div class="occ-sub">Occupé</div>
                        </div>
                    </div>
                    <div class="occ-legend">
                        <div class="occ-legend-item"><div class="occ-dot" style="background:#0071c2;"></div>Occupées : <strong>{{ $occupied }}</strong></div>
                        <div class="occ-legend-item"><div class="occ-dot" style="background:#22c55e;"></div>Libres : <strong>{{ $available }}</strong></div>
                        <div class="occ-legend-item"><div class="occ-dot" style="background:#febb02;"></div>Réservées : <strong>{{ $reserved }}</strong></div>
                        <div class="occ-legend-item"><div class="occ-dot" style="background:#f87171;"></div>Maintenance : <strong>{{ $maintenance }}</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ LIGNE 2 ══ --}}
<div class="row g-4">

    {{-- Réservations récentes --}}
    <div class="col-lg-8">
        <div class="dash-card">
            <div class="dash-card-header">
                <h3>🕐 Réservations récentes</h3>
                <a href="{{ route('hoteladmin.reservations.index') }}">Voir tout →</a>
            </div>
            <div style="overflow-x:auto;">
                <table class="db-table">
                    <thead>
                        <tr><th>Client</th><th>Chambre</th><th>Arrivée</th><th>Nuits</th><th>Montant</th><th>Statut</th></tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivity as $res)
                        <tr>
                            <td>
                                <div style="font-weight:700;">{{ $res->prenom_client }} {{ $res->nom_client }}</div>
                                <div style="font-size:11px;color:#94a3b8;">{{ $res->email_client }}</div>
                            </td>
                            <td style="font-size:12px;">
                                {{ $res->chambre->typeChambre->nom_type ?? '—' }}<br>
                                <span style="color:#94a3b8;">N° {{ $res->chambre->numero }}</span>
                            </td>
                            <td style="font-weight:700;color:#003580;">{{ $res->date_debut->format('d/m/Y') }}</td>
                            <td style="text-align:center;">{{ $res->date_debut->diffInDays($res->date_fin) }}</td>
                            <td style="font-weight:700;color:#003580;">{{ number_format($res->montant_total,0,',',' ') }} DJF</td>
                            <td>
                                <span class="bdg {{ strtolower($res->statut) }}">
                                    @if($res->statut==='CONFIRMEE') ✅ Confirmée
                                    @elseif($res->statut==='EN_ATTENTE') ⏳ En attente
                                    @else ❌ Annulée
                                    @endif
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align:center;padding:32px;color:#94a3b8;">
                                <div style="font-size:32px;margin-bottom:8px;">📋</div>
                                Aucune réservation pour le moment
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- COLONNE DROITE --}}
    <div class="col-lg-4">

        {{-- Actions rapides --}}
        <div class="dash-card mb-4">
            <div class="dash-card-header"><h3>⚡ Actions rapides</h3></div>
            <div class="dash-card-body">
                <div class="qa-grid">
                    <a href="{{ route('hoteladmin.reservations.index',['statut'=>'EN_ATTENTE']) }}" class="qa-btn">
                        <div class="qa-icon" style="background:#fef3c7;">⏳</div>
                        <div>En attente<br><span style="color:#f59e0b;font-size:11px;">{{ $enAttente }} à traiter</span></div>
                    </a>
                    <a href="{{ route('hoteladmin.chambres.index') }}" class="qa-btn">
                        <div class="qa-icon" style="background:#dbeafe;">🛏️</div>Chambres
                    </a>
                    <a href="{{ route('hoteladmin.types-chambre.index') }}" class="qa-btn">
                        <div class="qa-icon" style="background:#ede9fe;">🏷️</div>Types chambre
                    </a>
                    <a href="{{ route('hoteladmin.photos.index') }}" class="qa-btn">
                        <div class="qa-icon" style="background:#dcfce7;">🖼️</div>Photos
                    </a>
                    <a href="{{ route('hoteladmin.avis.index') }}" class="qa-btn">
                        <div class="qa-icon" style="background:#fef3c7;">⭐</div>Avis clients
                    </a>
                    <a href="{{ route('hoteladmin.hotel.edit') }}" class="qa-btn">
                        <div class="qa-icon" style="background:#fee2e2;">✏️</div>Mon hôtel
                    </a>
                </div>
            </div>
        </div>

        {{-- Statuts réservations --}}
        <div class="dash-card mb-4">
            <div class="dash-card-header"><h3>📊 Statuts réservations</h3></div>
            <div class="dash-card-body">
                @php
                    $totalRes = array_sum($reservationsByStatus) ?: 1;
                    $bars = [
                        ['label'=>'Confirmées',  'value'=>$reservationsByStatus['CONFIRMEE'],  'color'=>'#22c55e', 'bg'=>'#dcfce7'],
                        ['label'=>'En attente',  'value'=>$reservationsByStatus['EN_ATTENTE'], 'color'=>'#f59e0b', 'bg'=>'#fef3c7'],
                        ['label'=>'Annulées',    'value'=>$reservationsByStatus['ANNULEE'],    'color'=>'#f87171', 'bg'=>'#fee2e2'],
                    ];
                @endphp
                @foreach($bars as $bar)
                <div class="progress-bar-wrap">
                    <div class="progress-bar-label">
                        <span class="name">{{ $bar['label'] }}</span>
                        <span class="value">{{ $bar['value'] }}</span>
                    </div>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill"
                             style="width:{{ round(($bar['value']/$totalRes)*100) }}%;background:{{ $bar['color'] }};"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

{{-- ══ LIGNE 3 ══ --}}
<div class="row g-4">

    {{-- État chambres par type --}}
    <div class="col-lg-5">
        <div class="dash-card">
            <div class="dash-card-header">
                <h3>🛏️ État des chambres</h3>
                <a href="{{ route('hoteladmin.chambres.index') }}">Gérer →</a>
            </div>
            <div class="dash-card-body">
                {{-- Résumé --}}
                <div class="etat-grid mb-4">
                    <div class="etat-card" style="background:#dcfce7;">
                        <div class="num" style="color:#16a34a;">{{ $available }}</div>
                        <div class="lbl" style="color:#16a34a;">Disponibles</div>
                    </div>
                    <div class="etat-card" style="background:#fee2e2;">
                        <div class="num" style="color:#dc2626;">{{ $occupied }}</div>
                        <div class="lbl" style="color:#dc2626;">Occupées</div>
                    </div>
                    <div class="etat-card" style="background:#fef3c7;">
                        <div class="num" style="color:#f59e0b;">{{ $maintenance }}</div>
                        <div class="lbl" style="color:#f59e0b;">Maintenance</div>
                    </div>
                </div>

                {{-- Par type --}}
                @php $types = $monHotel->typesChambre()->with('chambres')->get(); @endphp
                @foreach($types as $type)
                @php
                    $total  = $type->chambres->count();
                    $dispo  = $type->chambres->where('etat','DISPONIBLE')->count();
                    $pct    = $total > 0 ? round(($dispo/$total)*100) : 0;
                @endphp
                <div style="margin-bottom:12px;">
                    <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
                        <span style="font-weight:600;color:#1e293b;">{{ $type->nom_type }}</span>
                        <span style="color:#64748b;">{{ $dispo }}/{{ $total }} dispo</span>
                    </div>
                    <div style="height:6px;background:#f1f5f9;border-radius:3px;">
                        <div style="height:6px;border-radius:3px;background:{{ $pct > 50 ? '#22c55e' : ($pct > 20 ? '#f59e0b' : '#f87171') }};width:{{ $pct }}%;"></div>
                    </div>
                    <div style="font-size:11px;color:#94a3b8;margin-top:3px;">
                        {{ number_format($type->prix_par_nuit,0,',',' ') }} DJF/nuit
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Prochains check-ins --}}
    <div class="col-lg-4">
        <div class="dash-card">
            <div class="dash-card-header">
                <h3>📅 Prochains check-ins</h3>
                <a href="{{ route('hoteladmin.reservations.index') }}">Voir →</a>
            </div>
            @forelse($upcomingCheckins as $res)
            <div class="checkin-row">
                <div class="checkin-date">
                    <div class="day">{{ $res->date_debut->format('d') }}</div>
                    <div class="mon">{{ $res->date_debut->format('M') }}</div>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:700;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $res->prenom_client }} {{ $res->nom_client }}
                    </div>
                    <div style="font-size:11px;color:#64748b;margin-top:2px;">
                        {{ $res->chambre->typeChambre->nom_type ?? '—' }}
                        · N° {{ $res->chambre->numero }}
                        · {{ $res->date_debut->diffInDays($res->date_fin) }} nuits
                    </div>
                </div>
                <span class="bdg {{ strtolower($res->statut) }}">
                    @if($res->statut==='CONFIRMEE') ✅
                    @elseif($res->statut==='EN_ATTENTE') ⏳
                    @else ❌
                    @endif
                </span>
            </div>
            @empty
            <div style="text-align:center;color:#94a3b8;padding:32px;font-size:13px;">
                <div style="font-size:36px;margin-bottom:8px;">📅</div>
                Aucun check-in prévu dans les 7 jours
            </div>
            @endforelse
        </div>
    </div>

    {{-- Avis récents --}}
    <div class="col-lg-3">
        <div class="dash-card">
            <div class="dash-card-header">
                <h3>⭐ Avis récents</h3>
                <a href="{{ route('hoteladmin.avis.index') }}">Voir →</a>
            </div>
            <div class="dash-card-body" style="padding:0;">
                @php
                    $recentAvis = \App\Models\Avis::where('hotel_id',$monHotel->id)
                        ->latest()->take(4)->get();
                @endphp
                @forelse($recentAvis as $av)
                <div style="padding:12px 16px;border-bottom:1px solid #f1f5f9;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                        <span style="font-size:13px;font-weight:700;color:#1e293b;">{{ $av->nom_client }}</span>
                        <div style="display:flex;gap:2px;">
                            @for($i=1;$i<=5;$i++)
                            <i class="bi bi-star-fill" style="font-size:10px;color:{{ $i<=$av->note?'#febb02':'#e2e8f0' }};"></i>
                            @endfor
                        </div>
                    </div>
                    @if($av->commentaire)
                    <p style="font-size:12px;color:#64748b;margin:0;line-height:1.5;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                        {{ $av->commentaire }}
                    </p>
                    @endif
                    @if(!$av->reponse_admin)
                    <a href="{{ route('hoteladmin.avis.index') }}"
                       style="font-size:11px;color:#0071c2;font-weight:700;text-decoration:none;margin-top:4px;display:block;">
                        ↩ Répondre
                    </a>
                    @else
                    <span style="font-size:11px;color:#16a34a;font-weight:600;">✅ Répondu</span>
                    @endif
                </div>
                @empty
                <div style="text-align:center;color:#94a3b8;padding:24px;font-size:13px;">
                    <div style="font-size:32px;margin-bottom:8px;">⭐</div>
                    Aucun avis pour le moment
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

@endif
</div>
@endsection