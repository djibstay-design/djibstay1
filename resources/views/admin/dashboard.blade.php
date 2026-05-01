@extends('layouts.admin')
@section('page_title', 'Dashboard')
@section('title', 'Dashboard Super Admin — DjibStay')

@push('styles')
<style>
    /* ANIMATIONS */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .fade-in-up { animation: fadeInUp 0.5s ease-out forwards; opacity: 0; }
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }

    .admin-wrap { background:#f2f6fc; min-height:100vh; padding:28px 0 48px; }
    .admin-inner { max-width:1400px; margin:0 auto; padding:0 24px; }

    .page-header { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px; margin-bottom:28px; }
    .page-header h1 { font-size:24px; font-weight:900; color:#003580; margin:0; }
    .page-header .sub { font-size:13px; color:#64748b; margin-top:3px; }
    .header-badge { background:linear-gradient(135deg,#003580,#0071c2); color:#fff; padding:6px 16px; border-radius:20px; font-size:12px; font-weight:800; }

    /* KPI */
    .kpi-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:20px; }
    @media(max-width:1100px){ .kpi-grid { grid-template-columns:repeat(2,1fr); } }
    @media(max-width:580px) { .kpi-grid { grid-template-columns:1fr; } }
    .kpi-card {
        background:#fff; border-radius:14px; border:1px solid #e2e8f0;
        padding:20px 22px; display:flex; align-items:flex-start; gap:14px;
        box-shadow:0 2px 10px rgba(0,53,128,0.07); transition:all .25s;
    }
    .kpi-card:hover { transform:translateY(-3px); border-color:#0071c2; box-shadow:0 8px 24px rgba(0,53,128,0.13); }
    .kpi-icon { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0; }
    .kpi-icon.blue   { background:#dbeafe; }
    .kpi-icon.green  { background:#dcfce7; }
    .kpi-icon.yellow { background:#fef3c7; }
    .kpi-icon.purple { background:#ede9fe; }
    .kpi-icon.red    { background:#fee2e2; }
    .kpi-body { flex:1; min-width:0; }
    .kpi-label { font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px; }
    .kpi-value { font-size:26px; font-weight:900; color:#1e293b; line-height:1; }
    .kpi-value.small { font-size:18px; }
    .kpi-change { font-size:11px; font-weight:700; margin-top:5px; display:flex; align-items:center; gap:4px; }
    .kpi-change.up   { color:#16a34a; }
    .kpi-change.down { color:#dc2626; }
    .kpi-change.flat { color:#64748b; }

    /* DASH CARDS */
    .dash-card { background:#fff; border-radius:14px; border:1px solid #e2e8f0; overflow:hidden; margin-bottom:20px; box-shadow:0 2px 10px rgba(0,53,128,0.07); }
    .dash-card-header { padding:14px 20px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between; background:#f8fafc; }
    .dash-card-header h3 { font-size:14px; font-weight:800; color:#003580; margin:0; }
    .dash-card-header a  { font-size:12px; font-weight:600; color:#0071c2; text-decoration:none; }
    .dash-card-header a:hover { color:#003580; }
    .dash-card-body { padding:18px 20px; }

    /* CHARTS */
    .chart-bars { display:flex; align-items:flex-end; gap:8px; height:110px; }
    .chart-bar-wrap { flex:1; display:flex; flex-direction:column; align-items:center; gap:4px; height:100%; justify-content:flex-end; }
    .chart-bar { width:100%; border-radius:5px 5px 0 0; background:linear-gradient(180deg,#0071c2,#003580); min-height:4px; }
    .chart-bar.yellow { background:linear-gradient(180deg,#febb02,#f5a623); }
    .chart-bar-label { font-size:10px; color:#94a3b8; font-weight:600; }
    .chart-bar-val   { font-size:10px; color:#003580; font-weight:700; }

    /* OCCUPATION */
    .occ-ring-wrap { display:flex; align-items:center; gap:20px; flex-wrap:wrap; }
    .occ-ring { position:relative; width:110px; height:110px; flex-shrink:0; }
    .occ-ring svg { transform:rotate(-90deg); }
    .occ-center { position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; }
    .occ-percent { font-size:20px; font-weight:900; color:#003580; line-height:1; }
    .occ-sub     { font-size:10px; color:#64748b; font-weight:600; }
    .occ-legend  { flex:1; }
    .occ-legend-item { display:flex; align-items:center; gap:8px; font-size:12px; color:#475569; margin-bottom:8px; }
    .occ-dot { width:10px; height:10px; border-radius:3px; flex-shrink:0; }

    /* TABLE */
    .dash-table { width:100%; border-collapse:collapse; font-size:13px; }
    .dash-table th { background:#f8fafc; padding:10px 14px; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:.5px; border-bottom:2px solid #e2e8f0; text-align:left; }
    .dash-table td { padding:11px 14px; border-bottom:1px solid #f1f5f9; color:#1e293b; vertical-align:middle; }
    .dash-table tr:last-child td { border-bottom:none; }
    .dash-table tr:hover td { background:#f8fafc; }

    /* BADGES */
    .badge-s { padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; white-space:nowrap; }
    .badge-s.confirmee  { background:#dcfce7; color:#14532d; }
    .badge-s.en_attente { background:#fef3c7; color:#92400e; }
    .badge-s.annulee    { background:#fee2e2; color:#991b1b; }

    /* QUICK ACTIONS */
    .quick-actions { display:grid; grid-template-columns:repeat(2,1fr); gap:10px; }
    .qa-btn { background:#fff; border:2px solid #e2e8f0; border-radius:10px; padding:13px 14px; text-decoration:none; color:#003580; font-weight:700; font-size:12px; display:flex; align-items:center; gap:9px; transition:all .2s; }
    .qa-btn:hover { border-color:#003580; background:#f0f7ff; color:#003580; }
    .qa-btn .qa-icon { width:32px; height:32px; border-radius:7px; display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0; }

    /* PERF */
    .perf-row { display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px solid #f1f5f9; }
    .perf-row:last-child { border-bottom:none; }
    .perf-rank { width:28px; height:28px; border-radius:50%; background:#003580; color:#fff; font-size:12px; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .perf-rank.gold   { background:#febb02; color:#003580; }
    .perf-rank.silver { background:#94a3b8; color:#fff; }
    .perf-rank.bronze { background:#cd7c2f; color:#fff; }
    .perf-name { flex:1; font-size:13px; font-weight:700; color:#1e293b; }
    .perf-bar-wrap { flex:1; }
    .perf-bar-bg   { height:6px; background:#f1f5f9; border-radius:3px; }
    .perf-bar-fill { height:6px; background:linear-gradient(90deg,#003580,#0071c2); border-radius:3px; }
    .perf-rev { font-size:12px; font-weight:800; color:#003580; white-space:nowrap; }

    /* PROGRESS BARS */
    .progress-bar-wrap { margin-bottom:12px; }
    .progress-bar-label { display:flex; justify-content:space-between; font-size:13px; margin-bottom:5px; }
    .progress-bar-label .name  { font-weight:600; color:#1e293b; }
    .progress-bar-label .value { font-weight:700; color:#003580; }
    .progress-bar-bg   { height:8px; background:#f1f5f9; border-radius:4px; }
    .progress-bar-fill { height:8px; border-radius:4px; transition:width .6s; }

    /* STAT HIGHLIGHT */
    .stat-highlight {
        background:linear-gradient(135deg,#003580,#0071c2);
        border-radius:12px; padding:16px;
        color:#fff; text-align:center;
    }
    .stat-highlight .num { font-size:28px; font-weight:900; color:#febb02; line-height:1; }
    .stat-highlight .lbl { font-size:11px; color:rgba(255,255,255,0.75); margin-top:4px; text-transform:uppercase; letter-spacing:.4px; }

    /* HOTEL MINI CARD */
    .hotel-mini { display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px solid #f1f5f9; }
    .hotel-mini:last-child { border-bottom:none; }
    .hotel-mini-avatar { width:40px; height:40px; background:linear-gradient(135deg,#003580,#0071c2); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; }
    .hotel-mini-name { font-size:13px; font-weight:700; color:#1e293b; }
    .hotel-mini-sub  { font-size:11px; color:#64748b; margin-top:2px; }
</style>
@endpush

@section('content')
<div class="admin-wrap">
<div class="admin-inner">

    {{-- HEADER --}}
    <div class="page-header">
        <div>
            <h1>🏆 Dashboard Super Admin</h1>
            <div class="sub">{{ now()->locale('fr')->translatedFormat('l d F Y') }}</div>
        </div>
        <span class="header-badge">SUPER ADMIN</span>
    </div>

    {{-- KPI ROW 1 --}}
    <div class="kpi-grid fade-in-up delay-1">
        <div class="kpi-card">
            <div class="kpi-icon blue">💰</div>
            <div class="kpi-body">
                <div class="kpi-label">Revenus totaux</div>
                <div class="kpi-value small">{{ number_format($totalRevenue,0,',',' ') }} <span style="font-size:12px;color:#64748b;">{{ \App\Models\SiteSetting::get('app_devise','DJF') }}</span></div>
                <div class="kpi-change {{ $revenueChange >= 0 ? 'up':'down' }}">
                    <i class="bi bi-arrow-{{ $revenueChange >= 0 ? 'up':'down' }}-circle-fill"></i>
                    {{ abs($revenueChange) }}% vs sem. préc.
                </div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon green">📅</div>
            <div class="kpi-body">
                <div class="kpi-label">Nouvelles réservations</div>
                <div class="kpi-value">{{ $newBookings }}</div>
                <div class="kpi-change {{ $bookingsChange >= 0 ? 'up':'down' }}">
                    <i class="bi bi-arrow-{{ $bookingsChange >= 0 ? 'up':'down' }}-circle-fill"></i>
                    {{ abs($bookingsChange) }}% cette semaine
                </div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon yellow">🏨</div>
            <div class="kpi-body">
                <div class="kpi-label">Hôtels partenaires</div>
                <div class="kpi-value">{{ $hotels->count() }}</div>
                <div class="kpi-change flat">
                    <i class="bi bi-building"></i>
                    {{ $hotels->sum('types_chambre_count') }} types de chambres
                </div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon purple">⭐</div>
            <div class="kpi-body">
                <div class="kpi-label">Note moyenne</div>
                <div class="kpi-value">{{ number_format($avgRating,1) }}<span style="font-size:13px;color:#64748b;">/5</span></div>
                <div class="kpi-change flat">
                    <i class="bi bi-chat-square-text"></i>
                    {{ $reviewsCount }} avis clients
                </div>
            </div>
        </div>
    </div>

    {{-- KPI ROW 2 --}}
    <div class="kpi-grid fade-in-up delay-2" style="margin-bottom:24px;">
        <div class="kpi-card">
            <div class="kpi-icon green">✅</div>
            <div class="kpi-body">
                <div class="kpi-label">Confirmées</div>
                <div class="kpi-value">{{ $reservationsByStatus['CONFIRMEE'] }}</div>
                <div class="kpi-change flat">
                    <i class="bi bi-check-circle"></i>
                    Réservations confirmées
                </div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon {{ $reservationsByStatus['EN_ATTENTE'] > 0 ? 'yellow':'green' }}">⏳</div>
            <div class="kpi-body">
                <div class="kpi-label">En attente</div>
                <div class="kpi-value" style="{{ $reservationsByStatus['EN_ATTENTE'] > 0 ? 'color:#f59e0b;' : '' }}">
                    {{ $reservationsByStatus['EN_ATTENTE'] }}
                </div>
                <div class="kpi-change flat">
                    <i class="bi bi-hourglass-split"></i>
                    À traiter
                </div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon yellow">🛏️</div>
            <div class="kpi-body">
                <div class="kpi-label">Chambres totales</div>
                <div class="kpi-value">{{ $totalRooms }}</div>
                <div class="kpi-change flat">
                    <i class="bi bi-door-open"></i>
                    {{ $available }} dispo · {{ $occupied }} occupées
                </div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon red">❌</div>
            <div class="kpi-body">
                <div class="kpi-label">Annulées</div>
                <div class="kpi-value">{{ $reservationsByStatus['ANNULEE'] }}</div>
                <div class="kpi-change flat">
                    <i class="bi bi-x-circle"></i>
                    Total annulations
                </div>
            </div>
        </div>
    </div>

    {{-- GRAPHIQUES --}}
    <div class="row g-4 mb-4 fade-in-up delay-3">
        <div class="col-lg-5">
            <div class="dash-card">
                <div class="dash-card-header"><h3>💰 Revenus — 8 mois</h3></div>
                <div class="dash-card-body">
                    <div id="revenueChart" style="min-height: 180px;"></div>
                    <div style="margin-top:12px;padding-top:10px;border-top:1px solid #f1f5f9;display:flex;justify-content:space-between;font-size:12px;color:#64748b;">
                        <span>Total confirmé</span>
                        <strong style="color:#003580;">{{ number_format($totalRevenue,0,',',' ') }} {{ \App\Models\SiteSetting::get('app_devise','DJF') }}</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="dash-card">
                <div class="dash-card-header"><h3>📅 Réservations — 7 jours</h3></div>
                <div class="dash-card-body">
                    <div id="bookingsChart" style="min-height: 180px;"></div>
                    <div style="margin-top:12px;padding-top:10px;border-top:1px solid #f1f5f9;display:flex;justify-content:space-between;font-size:12px;color:#64748b;">
                        <span>Cette semaine</span>
                        <strong style="color:#003580;">{{ $newBookings }} réservation(s)</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="dash-card">
                <div class="dash-card-header"><h3>🏨 Occupation</h3></div>
                <div class="dash-card-body">
                    <div class="occ-ring-wrap">
                        <div id="occupancyChart" style="width: 130px; height: 130px; margin: -10px;"></div>
                        <div class="occ-legend">
                            <div class="occ-legend-item"><div class="occ-dot" style="background:#0071c2;"></div>Occupées : <strong>{{ $occupied }}</strong></div>
                            <div class="occ-legend-item"><div class="occ-dot" style="background:#22c55e;"></div>Libres : <strong>{{ $available }}</strong></div>
                            <div class="occ-legend-item"><div class="occ-dot" style="background:#febb02;"></div>Réservées : <strong>{{ $reserved }}</strong></div>
                            <div class="occ-legend-item"><div class="occ-dot" style="background:#f87171;"></div>Maintenance : <strong>{{ $notReady }}</strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE + ACTIONS + STATUTS --}}
    <div class="row g-4 mb-4 fade-in-up delay-4">
        <div class="col-lg-8">
            <div class="dash-card">
                <div class="dash-card-header">
                    <h3>🕐 Activité récente</h3>
                    <a href="{{ route('admin.reservations.index') }}">Voir tout →</a>
                </div>
                <div style="overflow-x:auto;">
                    <table class="dash-table">
                        <thead>
                            <tr>
                                <th>Code</th><th>Client</th><th>Hôtel</th>
                                <th>Arrivée</th><th>Montant</th><th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentActivity as $r)
                            <tr>
                                <td><span style="font-family:monospace;font-size:11px;color:#003580;font-weight:700;">{{ $r->code_reservation }}</span></td>
                                <td>{{ $r->prenom_client }} {{ $r->nom_client }}</td>
                                <td style="font-size:12px;color:#64748b;">{{ $r->chambre->typeChambre->hotel->nom ?? '—' }}</td>
                                <td>{{ $r->date_debut->format('d/m/Y') }}</td>
                                <td style="font-weight:700;color:#003580;">{{ number_format($r->montant_total,0,',',' ') }} {{ \App\Models\SiteSetting::get('app_devise','DJF') }}</td>
                                <td>
                                    <span class="badge-s {{ strtolower($r->statut) }}">
                                        @if($r->statut==='CONFIRMEE') ✅ Confirmée
                                        @elseif($r->statut==='EN_ATTENTE') ⏳ En attente
                                        @else ❌ Annulée
                                        @endif
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" style="text-align:center;color:#94a3b8;padding:24px;">Aucune activité récente</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">

            {{-- Statuts réservations --}}
            <div class="dash-card mb-4">
                <div class="dash-card-header"><h3>📊 Statuts réservations</h3></div>
                <div class="dash-card-body">
                    @php
                        $totalRes = array_sum($reservationsByStatus) ?: 1;
                        $statBars = [
                            ['label'=>'Confirmées',  'value'=>$reservationsByStatus['CONFIRMEE'],  'color'=>'#22c55e'],
                            ['label'=>'En attente',  'value'=>$reservationsByStatus['EN_ATTENTE'], 'color'=>'#f59e0b'],
                            ['label'=>'Annulées',    'value'=>$reservationsByStatus['ANNULEE'],    'color'=>'#f87171'],
                        ];
                    @endphp
                    @foreach($statBars as $bar)
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

                    {{-- Stats highlights --}}
                    <div class="row g-2 mt-3">
                        <div class="col-6">
                            <div class="stat-highlight">
                                <div class="num">{{ $totalBooked ?? array_sum($reservationsByStatus) }}</div>
                                <div class="lbl">Total réservations</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-highlight">
                                <div class="num">{{ $hotels->count() }}</div>
                                <div class="lbl">Hôtels actifs</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions rapides --}}
            <div class="dash-card">
                <div class="dash-card-header"><h3>⚡ Actions rapides</h3></div>
                <div class="dash-card-body">
                    <div class="quick-actions">
                        <a href="{{ route('admin.hotels.create') }}" class="qa-btn">
                            <div class="qa-icon" style="background:#dbeafe;">🏨</div>Ajouter hôtel
                        </a>
                        <a href="{{ route('admin.reservations.index') }}" class="qa-btn">
                            <div class="qa-icon" style="background:#dcfce7;">📋</div>Réservations
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="qa-btn">
                            <div class="qa-icon" style="background:#ede9fe;">👥</div>Utilisateurs
                        </a>
                        <a href="{{ route('admin.settings.edit') }}" class="qa-btn">
                            <div class="qa-icon" style="background:#fef3c7;">⚙️</div>Paramètres
                        </a>
                        <a href="{{ route('admin.chambres.index') }}" class="qa-btn">
                            <div class="qa-icon" style="background:#fee2e2;">🛏️</div>Chambres
                        </a>
                        <a href="{{ route('admin.avis.index') }}" class="qa-btn">
                            <div class="qa-icon" style="background:#fef3c7;">⭐</div>Avis clients
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- PERF + HOTELS --}}
    <div class="row g-4">

        {{-- Performance par hôtel --}}
        <div class="col-lg-5">
            <div class="dash-card">
                <div class="dash-card-header">
                    <h3>🏆 Performance par hôtel</h3>
                    <a href="{{ route('admin.hotels.index') }}">Voir tout →</a>
                </div>
                <div class="dash-card-body">
                    @php $maxPerf = $hotelsPerformance->max('revenue') ?: 1; @endphp
                    @forelse($hotelsPerformance as $i => $hp)
                    <div class="perf-row">
                        <div class="perf-rank {{ $i===0?'gold':($i===1?'silver':($i===2?'bronze':'')) }}">{{ $i+1 }}</div>
                        <div class="perf-name">
                            {{ $hp['hotel']->nom }}
                            <div style="font-size:11px;color:#64748b;font-weight:500;">{{ $hp['reservations_count'] }} réservations</div>
                        </div>
                        <div class="perf-bar-wrap">
                            <div class="perf-bar-bg">
                                <div class="perf-bar-fill" style="width:{{ $maxPerf>0?round(($hp['revenue']/$maxPerf)*100):0 }}%"></div>
                            </div>
                        </div>
                        <div class="perf-rev">{{ number_format($hp['revenue']/1000,0) }}k</div>
                    </div>
                    @empty
                    <div style="text-align:center;color:#94a3b8;padding:20px;font-size:13px;">Aucune donnée</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Liste hôtels --}}
        <div class="col-lg-4">
            <div class="dash-card">
                <div class="dash-card-header">
                    <h3>🏨 Hôtels partenaires</h3>
                    <a href="{{ route('admin.hotels.index') }}">Gérer →</a>
                </div>
                <div class="dash-card-body" style="padding:0;">
                    @forelse($hotels->take(6) as $hotel)
                    <div class="hotel-mini" style="padding:10px 18px;">
                        <div class="hotel-mini-avatar">🏨</div>
                        <div style="flex:1;min-width:0;">
                            <div class="hotel-mini-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $hotel->nom }}
                            </div>
                            <div class="hotel-mini-sub">
                                {{ $hotel->ville ?? 'Djibouti' }} · {{ $hotel->types_chambre_count }} types · {{ $hotel->avis_count }} avis
                            </div>
                        </div>
                        <a href="{{ route('admin.hotels.edit', $hotel) }}"
                           style="background:#f0f7ff;color:#0071c2;padding:5px 10px;border-radius:6px;text-decoration:none;font-size:11px;font-weight:700;white-space:nowrap;">
                            ✏️ Modifier
                        </a>
                    </div>
                    @empty
                    <div style="text-align:center;color:#94a3b8;padding:24px;font-size:13px;">Aucun hôtel</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Avis récents --}}
        <div class="col-lg-3">
            <div class="dash-card">
                <div class="dash-card-header">
                    <h3>⭐ Avis récents</h3>
                    <a href="{{ route('admin.avis.index') }}">Voir →</a>
                </div>
                <div style="padding:0;">
                    @php
                        $recentAvis = \App\Models\Avis::with('hotel')
                            ->latest()->take(5)->get();
                    @endphp
                    @forelse($recentAvis as $av)
                    <div style="padding:11px 16px;border-bottom:1px solid #f1f5f9;">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:3px;">
                            <span style="font-size:13px;font-weight:700;color:#1e293b;">{{ $av->nom_client }}</span>
                            <div style="display:flex;gap:1px;">
                                @for($i=1;$i<=5;$i++)
                                <i class="bi bi-star-fill" style="font-size:9px;color:{{ $i<=$av->note?'#febb02':'#e2e8f0' }};"></i>
                                @endfor
                            </div>
                        </div>
                        <div style="font-size:11px;color:#94a3b8;">{{ $av->hotel->nom ?? '—' }}</div>
                        @if($av->commentaire)
                        <p style="font-size:11px;color:#64748b;margin:3px 0 0;line-height:1.4;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                            {{ $av->commentaire }}
                        </p>
                        @endif
                    </div>
                    @empty
                    <div style="text-align:center;color:#94a3b8;padding:24px;font-size:13px;">Aucun avis</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const revDataRaw = @json($revenueData);
    // ApexCharts attends l'ordre chrono, donc on le reverse
    const revData = revDataRaw.reverse();
    
    // 1. REVENUE CHART
    const revOptions = {
        series: [{
            name: 'Revenus',
            data: revData.map(d => d.amount)
        }],
        chart: {
            type: 'bar',
            height: 180,
            toolbar: { show: false },
            fontFamily: 'inherit',
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800,
            }
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                columnWidth: '45%',
            }
        },
        dataLabels: { enabled: false },
        stroke: { width: 2, colors: ['transparent'] },
        xaxis: {
            categories: revData.map(d => d.month),
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: { style: { colors: '#64748b', fontSize: '11px', fontWeight: 600 } }
        },
        yaxis: {
            labels: {
                formatter: function (val) {
                    if (val >= 1000000) return (val/1000000).toFixed(1) + "M";
                    if (val >= 1000) return (val/1000).toFixed(0) + "k";
                    return val;
                },
                style: { colors: '#64748b', fontSize: '11px', fontWeight: 600 }
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: "vertical",
                shadeIntensity: 0.25,
                gradientToColors: undefined,
                inverseColors: true,
                opacityFrom: 1,
                opacityTo: 1,
                stops: [50, 0, 100],
                colorStops: [
                    { offset: 0, color: '#0071c2', opacity: 1 },
                    { offset: 100, color: '#003580', opacity: 1 }
                ]
            }
        },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4, yaxis: { lines: { show: true } } },
        tooltip: {
            y: { formatter: function (val) { return val.toLocaleString() + " " + "{{ \App\Models\SiteSetting::get('app_devise','DJF') }}" } }
        }
    };
    new ApexCharts(document.querySelector("#revenueChart"), revOptions).render();

    // 2. BOOKINGS CHART
    const guestDataRaw = @json($guestsData);
    const guestData = guestDataRaw.reverse();
    
    const bookingsOptions = {
        series: [{
            name: 'Réservations',
            data: guestData.map(d => d.count)
        }],
        chart: {
            type: 'area',
            height: 180,
            toolbar: { show: false },
            fontFamily: 'inherit',
            animations: { enabled: true, easing: 'easeinout', speed: 800 }
        },
        colors: ['#febb02'],
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 90, 100] }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        xaxis: {
            categories: guestData.map(d => d.day),
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: { style: { colors: '#64748b', fontSize: '11px', fontWeight: 600 } }
        },
        yaxis: {
            labels: { style: { colors: '#64748b', fontSize: '11px', fontWeight: 600 }, formatter: (v) => Math.round(v) }
        },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
        tooltip: {
            y: { formatter: function (val) { return val + " rés." } }
        }
    };
    new ApexCharts(document.querySelector("#bookingsChart"), bookingsOptions).render();

    // 3. OCCUPANCY CHART (RadialBar)
    const occOptions = {
        series: [{{ $occupancyPercent }}],
        chart: {
            height: 160,
            type: 'radialBar',
            fontFamily: 'inherit',
            animations: { enabled: true, easing: 'easeinout', speed: 1200 }
        },
        plotOptions: {
            radialBar: {
                hollow: { size: '65%' },
                track: { background: '#f1f5f9' },
                dataLabels: {
                    name: { show: false },
                    value: {
                        fontSize: '22px',
                        fontWeight: 900,
                        color: '#003580',
                        offsetY: 8,
                        formatter: function (val) { return val + "%" }
                    }
                }
            }
        },
        fill: { colors: ['#0071c2'] },
        stroke: { lineCap: 'round' }
    };
    new ApexCharts(document.querySelector("#occupancyChart"), occOptions).render();
});
</script>
@endpush