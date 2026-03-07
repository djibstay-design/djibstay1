@extends('layouts.admin')

@section('title', 'Dashboard')

@section('header')
<header class="admin-header">
    <div class="flex items-center gap-4 flex-1">
        <h1 class="text-xl font-bold text-slate-900 whitespace-nowrap">Dashboard</h1>
        <div class="relative flex-1 max-w-xl mx-4 hidden sm:block">
            <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" placeholder="Rechercher réservation, chambre..." class="w-full pl-11 pr-4 py-2.5 border border-slate-200 rounded-xl bg-slate-50/80 text-sm focus:bg-white focus:ring-2 focus:border-slate-300 transition-all placeholder:text-slate-400">
        </div>
    </div>
    <div class="flex items-center gap-3">
        <button class="relative p-2 rounded-xl hover:bg-slate-100 text-slate-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
        </button>
        <button class="p-2 rounded-xl hover:bg-slate-100 text-slate-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        </button>
        <div class="flex items-center gap-3 pl-4 border-l border-slate-200">
            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold text-sm shadow-sm" style="background:#2196f3;">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="hidden sm:block">
                <p class="font-semibold text-slate-900 text-sm leading-tight">{{ auth()->user()->prenom ?? '' }} {{ auth()->user()->name ?? 'Admin' }}</p>
                <p class="text-xs text-slate-500">{{ auth()->user()->role === 'SUPER_ADMIN' ? 'Super Admin' : 'Admin' }}</p>
            </div>
        </div>
    </div>
</header>
@endsection

@php
    $kpis = [
        ['label' => 'Revenu total', 'value' => number_format($totalRevenue ?? 0, 0, ',', ' ') . ' DJF', 'change' => $revenueChange ?? 0],
        ['label' => 'Nv. réservations', 'value' => $newBookings ?? 0, 'change' => $bookingsChange ?? 0],
        ['label' => 'Check-in', 'value' => $checkInToday ?? 0, 'change' => $checkInChange ?? 0],
        ['label' => 'Check-out', 'value' => $checkOutToday ?? 0, 'change' => $checkOutChange ?? 0],
    ];

    $tot = max($totalRooms ?? 1, 1);
    $occ = $occupied ?? 0;
    $av = $available ?? 0;
    $res = $reserved ?? 0;
    $maint = $notReady ?? 0;
    $occPct = round($occ/$tot*100);

    $avgR = $avgRating ?? 0;
    $ratingLabel = $avgR >= 4.5 ? 'Excellent' : ($avgR >= 4 ? 'Très bien' : ($avgR >= 3 ? 'Bien' : ($avgR > 0 ? 'Correct' : '—')));
    $ratingCategories = [
        'Propreté'      => min(10, round($avgR * 2 + 0.2, 1)),
        'Services'      => min(10, round($avgR * 2 + 0.4, 1)),
        'Emplacement'   => min(10, round($avgR * 2, 1)),
        'Confort'       => min(10, round($avgR * 2 + 0.1, 1)),
        'Rapport Q/P'   => min(10, round($avgR * 2 - 0.2, 1)),
    ];

    $recentReservations = $recentActivity ?? collect();
    $bookingListData    = $bookingList ?? collect();

    $statusConf  = $reservationsByStatus['CONFIRMEE'] ?? 0;
    $statusPend  = $reservationsByStatus['EN_ATTENTE'] ?? 0;
    $statusCanc  = $reservationsByStatus['ANNULEE'] ?? 0;
    $statusTotal = $statusConf + $statusPend + $statusCanc;
@endphp

@section('content')
<div class="dash-layout">

    {{-- ══════════ LEFT COLUMN ══════════ --}}
    <div class="dash-main flex flex-col gap-8">

        {{-- KPI CARDS --}}
        <div class="kpi-grid">
            @foreach($kpis as $kpi)
            <div class="kpi-card">
                <div class="flex items-center justify-between mb-3">
                    <span class="kpi-label">{{ $kpi['label'] }}</span>
                    <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="4" r="1.3"/><circle cx="10" cy="10" r="1.3"/><circle cx="10" cy="16" r="1.3"/></svg>
                </div>
                <div class="kpi-value">{{ $kpi['value'] }}</div>
                <div class="kpi-change">
                    @if(($kpi['change'] ?? 0) >= 0)
                        <span class="kpi-up">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                            {{ number_format(abs($kpi['change']), 1) }}%
                        </span>
                    @else
                        <span class="kpi-down">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ number_format(abs($kpi['change']), 1) }}%
                        </span>
                    @endif
                    <span class="kpi-period">vs sem. dern.</span>
                </div>
            </div>
            @endforeach
        </div>

        {{-- CHARTS: Guests + Revenue --}}
        <div class="charts-grid">
            <div class="chart-card">
                <div class="flex items-center justify-between mb-1">
                    <h3 class="text-sm font-bold text-slate-900">Séjours</h3>
                    <select class="chart-select">
                        <option selected>Cette semaine</option>
                        <option>Ce mois</option>
                        <option>Cette année</option>
                    </select>
                </div>
                <div class="h-52 mt-2"><canvas id="guestsChart"></canvas></div>
            </div>
            <div class="chart-card">
                <div class="flex items-center justify-between mb-1">
                    <h3 class="text-sm font-bold text-slate-900">Revenus</h3>
                    <select class="chart-select">
                        <option>Cette semaine</option>
                        <option selected>8 derniers mois</option>
                        <option>Cette année</option>
                    </select>
                </div>
                <div class="h-52 mt-2"><canvas id="revenueChart"></canvas></div>
            </div>
        </div>

        {{-- CHARTS: Bookings Bar + Status Donut --}}
        <div class="charts-grid">
            <div class="chart-card">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-bold text-slate-900">Réservations</h3>
                    <select class="chart-select">
                        <option>Ce mois</option>
                        <option selected>Cette année</option>
                    </select>
                </div>
                <div class="flex items-center gap-4 mb-3 text-[11px]">
                    <span class="flex items-center gap-1.5 text-slate-500"><span class="w-2 h-2 rounded-sm inline-block" style="background:#2196f3;"></span>Confirmées <b class="text-slate-700 ml-0.5">{{ number_format($totalBooked ?? 0) }}</b></span>
                    <span class="flex items-center gap-1.5 text-slate-500"><span class="w-2 h-2 rounded-sm bg-red-300 inline-block"></span>Annulées <b class="text-slate-700 ml-0.5">{{ number_format($totalCanceled ?? 0) }}</b></span>
                </div>
                <div class="h-48"><canvas id="bookingsBarChart"></canvas></div>
            </div>

            <div class="chart-card donut-card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-[13px] font-bold text-slate-900">Répartition par statut</h3>
                    <select id="statusFilter" class="chart-select">
                        <option value="-1">Tous les statuts</option>
                        <option value="0">Confirmées</option>
                        <option value="1">En attente</option>
                        <option value="2">Annulées</option>
                    </select>
                </div>
                <div class="donut-layout">
                    <div class="donut-chart-area">
                        <div class="relative">
                            <canvas id="statusDonut"></canvas>
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                <span class="text-[9px] text-slate-400 font-medium" id="donutSubLabel">Total réserv.</span>
                                <span class="text-lg font-extrabold text-slate-900 leading-none mt-0.5" id="donutValue">{{ number_format($statusTotal, 0, ',', ' ') }}</span>
                                <span class="text-[8px] text-slate-400 mt-1" id="donutLabel"></span>
                            </div>
                        </div>
                    </div>
                    <div class="donut-legend-area">
                        @php $statusItems = [
                            ['label' => 'Confirmées', 'count' => $statusConf, 'color' => '#10b981', 'pct' => $statusTotal > 0 ? round($statusConf/$statusTotal*100) : 0],
                            ['label' => 'En attente', 'count' => $statusPend, 'color' => '#f59e0b', 'pct' => $statusTotal > 0 ? round($statusPend/$statusTotal*100) : 0],
                            ['label' => 'Annulées', 'count' => $statusCanc, 'color' => '#ef4444', 'pct' => $statusTotal > 0 ? round($statusCanc/$statusTotal*100) : 0],
                        ]; @endphp
                        @foreach($statusItems as $idx => $si)
                        <div class="donut-legend-item" data-index="{{ $idx }}">
                            <span class="donut-dot" style="background:{{ $si['color'] }};"></span>
                            <span class="donut-lbl">{{ $si['label'] }} ({{ $si['count'] }})</span>
                            <span class="donut-pct">{{ $si['pct'] }}%</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- BOOKING LIST TABLE --}}
        <div class="chart-card booking-list-card">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-sm font-bold text-slate-900">Liste des réservations</h3>
                <div class="flex items-center gap-3">
                    <select class="chart-select">
                        <option selected>Aujourd'hui</option>
                        <option>Cette semaine</option>
                        <option>Ce mois</option>
                    </select>
                    <a href="{{ route('admin.reservations.index') }}" class="booking-see-all">Voir tout</a>
                </div>
            </div>
            <div class="booking-table-wrap">
                <table class="booking-table">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Type chambre</th>
                            <th>N° chambre</th>
                            <th>Durée</th>
                            <th>Check-In</th>
                            <th>Check-Out</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookingListData->take(8) as $b)
                        @php $nights = ($b->date_debut && $b->date_fin) ? $b->date_debut->diffInDays($b->date_fin) : 0; @endphp
                        <tr>
                            <td class="font-medium text-slate-800">{{ $b->prenom_client }} {{ $b->nom_client }}</td>
                            <td>{{ $b->chambre?->typeChambre?->nom_type ?? '-' }}</td>
                            <td class="font-semibold">{{ $b->chambre?->numero ?? '-' }}</td>
                            <td>{{ $nights }} nuit{{ $nights > 1 ? 's' : '' }}</td>
                            <td>{{ $b->date_debut?->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ $b->date_fin?->format('d/m/Y') ?? '-' }}</td>
                            <td>
                                @if($b->statut === 'CONFIRMEE')
                                    <span class="booking-status booking-confirmed">Confirmée</span>
                                @elseif($b->statut === 'EN_ATTENTE')
                                    <span class="booking-status booking-pending">En attente</span>
                                @elseif($b->statut === 'ANNULEE')
                                    <span class="booking-status booking-canceled">Annulée</span>
                                @else
                                    <span class="booking-status booking-default">{{ $b->statut ?? '-' }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="empty-row">Aucune réservation pour aujourd'hui</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ══════════ RIGHT COLUMN ══════════ --}}
    <div class="dash-sidebar flex flex-col gap-8">

        {{-- ROOM OCCUPANCY --}}
        <div class="sidebar-card">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-bold text-slate-900">Occupation des chambres</h3>
                <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="4" r="1.3"/><circle cx="10" cy="10" r="1.3"/><circle cx="10" cy="16" r="1.3"/></svg>
            </div>
            <div class="flex items-center gap-2.5 mb-4">
                <div class="w-9 h-9 rounded-full flex items-center justify-center bg-blue-50">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <div>
                    <span class="text-2xl font-extrabold text-slate-900">{{ $totalRooms ?? 0 }}</span>
                    <span class="text-[12px] text-slate-400 ml-1">Chambres</span>
                </div>
            </div>

            <div class="h-3 rounded-full overflow-hidden flex bg-slate-100 mb-5">
                <div class="h-full" style="width:{{ round($occ/$tot*100) }}%; background:#2563eb;"></div>
                <div class="h-full" style="width:{{ round($av/$tot*100) }}%; background:#7dd3fc;"></div>
                <div class="h-full" style="width:{{ round($res/$tot*100) }}%; background:#e2e8f0;"></div>
                <div class="h-full" style="width:{{ round($maint/$tot*100) }}%; background:#94a3b8;"></div>
            </div>

            <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                @php $roomStats = [
                    ['label' => 'Occupées', 'val' => $occ, 'color' => '#2563eb'],
                    ['label' => 'Disponibles', 'val' => $av, 'color' => '#7dd3fc'],
                    ['label' => 'Réservées', 'val' => $res, 'color' => '#e2e8f0'],
                    ['label' => 'Maintenance', 'val' => $maint, 'color' => '#94a3b8'],
                ]; @endphp
                @foreach($roomStats as $rs)
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-sm flex-shrink-0" style="background:{{ $rs['color'] }};"></span>
                    <span class="text-[12px] font-bold text-slate-800">{{ $rs['val'] }}</span>
                    <span class="text-[12px] text-slate-400">{{ $rs['label'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- RATINGS --}}
        <div class="sidebar-card">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-bold text-slate-900">Avis & Notes</h3>
                <a href="{{ route('admin.avis.index') }}" class="text-[11px] font-semibold text-blue-400 hover:underline">Voir tout</a>
            </div>
            <div class="flex items-center gap-3 mb-5">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 bg-sky-50">
                    <span class="text-lg font-extrabold text-sky-500">{{ number_format($avgR, 1) }}</span>
                </div>
                <div>
                    <p class="text-[13px] font-bold text-slate-900">{{ $ratingLabel }}</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $reviewsCount ?? 0 }} avis vérifiés</p>
                </div>
            </div>
            <div class="space-y-3">
                @foreach($ratingCategories as $cat => $score)
                <div class="flex items-center gap-2">
                    <span class="text-[11px] text-slate-500 flex-shrink-0" style="width:68px">{{ $cat }}</span>
                    <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full" style="width:{{ min(100, ($score/10)*100) }}%; background:#38bdf8;"></div>
                    </div>
                    <span class="text-[12px] font-bold text-slate-700" style="width:24px;text-align:right">{{ number_format($score, 1) }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- RECENT ACTIVITY --}}
        <div class="sidebar-card activity-card">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-bold text-slate-900">Activité récente</h3>
                <a href="{{ route('admin.reservations.index') }}" class="activity-see-all">Voir tout</a>
            </div>
            <div class="activity-list custom-scrollbar">
                @forelse($recentReservations->take(6) as $idx => $act)
                <div class="activity-item">
                    <div class="activity-icon
                        @if($act->statut === 'CONFIRMEE') activity-icon--green
                        @elseif($act->statut === 'ANNULEE') activity-icon--red
                        @else activity-icon--blue @endif">
                        @if($act->statut === 'CONFIRMEE')
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        @elseif($act->statut === 'ANNULEE')
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        @else
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>
                    <div class="activity-content">
                        <p class="activity-title">
                            @if($act->statut === 'CONFIRMEE') Réservation confirmée
                            @elseif($act->statut === 'ANNULEE') Réservation annulée
                            @else Nouvelle réservation @endif</p>
                        <p class="activity-desc">{{ $act->prenom_client }} {{ $act->nom_client }}, {{ $act->chambre?->typeChambre?->nom_type ?? '' }} N°{{ $act->chambre?->numero ?? '' }}</p>
                        <p class="activity-time">{{ $act->date_reservation?->diffForHumans() ?? '-' }}</p>
                    </div>
                </div>
                @empty
                <div class="activity-empty">
                    <svg width="32" height="32" fill="none" stroke="#cbd5e1" stroke-width="1.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p>Aucune activité récente</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .dash-layout {
        display: grid;
        grid-template-columns: 1fr 260px;
        gap: 28px;
        align-items: start;
    }
    .dash-main { min-width: 0; }
    .dash-sidebar { min-width: 0; }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }
    .kpi-card {
        background: #fff;
        border: 1px solid rgba(226,232,240,0.6);
        border-radius: 16px;
        padding: 20px 22px;
        transition: box-shadow 0.2s;
    }
    .kpi-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
    .kpi-label {
        font-size: 12px;
        font-weight: 500;
        color: #94a3b8;
        line-height: 1;
    }
    .kpi-value {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        margin-bottom: 10px;
    }
    .kpi-change {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
    }
    .kpi-up {
        display: inline-flex;
        align-items: center;
        gap: 2px;
        color: #10b981;
        font-weight: 600;
        background: #ecfdf5;
        padding: 2px 6px;
        border-radius: 4px;
    }
    .kpi-down {
        display: inline-flex;
        align-items: center;
        gap: 2px;
        color: #ef4444;
        font-weight: 600;
        background: #fef2f2;
        padding: 2px 6px;
        border-radius: 4px;
    }
    .kpi-period { color: #94a3b8; font-size: 11px; }

    .charts-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }

    .sidebar-card {
        background: #fff;
        border: 1px solid rgba(226,232,240,0.6);
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.03);
        box-sizing: border-box;
        max-width: 100%;
    }
    .chart-card {
        background: #fff;
        border: 1px solid rgba(226,232,240,0.6);
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.03);
        box-sizing: border-box;
        max-width: 100%;
    }
    .chart-select {
        font-size: 11px;
        font-weight: 500;
        color: #64748b;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 5px 24px 5px 10px;
        cursor: pointer;
        outline: none;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 6px center;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .chart-select:hover {
        border-color: #cbd5e1;
    }
    .chart-select:focus {
        border-color: #93c5fd;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }

    @media (max-width: 1280px) {
        .dash-layout { grid-template-columns: 1fr 240px; gap: 24px; }
    }
    @media (max-width: 1100px) {
        .dash-layout { grid-template-columns: 1fr; gap: 28px; }
        .dash-sidebar { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
    }
    @media (max-width: 800px) {
        .kpi-grid { grid-template-columns: repeat(2, 1fr); }
        .charts-grid { grid-template-columns: 1fr; }
        .dash-sidebar { grid-template-columns: 1fr; }
    }
    @media (max-width: 480px) {
        .kpi-grid { grid-template-columns: 1fr; }
    }

    .donut-card { overflow: hidden; }
    .donut-layout {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .donut-chart-area {
        flex-shrink: 0;
        width: 120px;
        height: 120px;
    }
    .donut-chart-area .relative {
        width: 100%;
        height: 100%;
    }
    .donut-legend-area {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .donut-legend-item {
        cursor: pointer;
        transition: opacity 0.3s, transform 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 4px 6px;
        border-radius: 8px;
    }
    .donut-legend-item:hover {
        background: rgba(0,0,0,0.03);
    }
    .donut-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
        display: inline-block;
    }
    .donut-lbl {
        font-size: 11px;
        color: #475569;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .donut-pct {
        font-size: 11px;
        font-weight: 700;
        color: #1e293b;
        margin-left: auto;
        flex-shrink: 0;
    }
    #statusDonut {
        cursor: pointer;
    }
    .booking-list-card { padding: 22px 22px 8px; }
    .booking-see-all {
        font-size: 12px;
        font-weight: 600;
        color: #3b82f6;
        text-decoration: none;
        padding: 5px 14px;
        border-radius: 8px;
        background: #eff6ff;
        transition: background 0.2s;
    }
    .booking-see-all:hover { background: #dbeafe; }
    .booking-table-wrap { overflow-x: auto; }
    .booking-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 12px;
    }
    .booking-table thead tr {
        border-bottom: 1px solid #f1f5f9;
    }
    .booking-table th {
        text-align: left;
        font-size: 11px;
        font-weight: 500;
        color: #94a3b8;
        padding: 10px 12px;
        white-space: nowrap;
        border-bottom: 1px solid #f1f5f9;
    }
    .booking-table td {
        padding: 14px 12px;
        color: #64748b;
        white-space: nowrap;
        border-bottom: 1px solid #f8fafc;
    }
    .booking-table tbody tr {
        transition: background 0.15s;
    }
    .booking-table tbody tr:hover {
        background: #f8fafc;
    }
    .booking-table tbody tr:last-child td {
        border-bottom: none;
    }
    .booking-status {
        display: inline-block;
        font-size: 11px;
        font-weight: 600;
        padding: 0;
        background: none;
        border: none;
    }
    .booking-confirmed { color: #10b981; }
    .booking-pending { color: #f59e0b; }
    .booking-canceled { color: #ef4444; }
    .booking-default { color: #64748b; }
    .empty-row {
        text-align: center;
        padding: 40px 16px !important;
        color: #94a3b8;
        font-size: 13px;
    }

    .activity-card { padding-bottom: 12px; }
    .activity-see-all {
        font-size: 11px;
        font-weight: 600;
        color: #3b82f6;
        text-decoration: none;
        padding: 4px 12px;
        border-radius: 6px;
        background: #eff6ff;
        transition: background 0.2s;
    }
    .activity-see-all:hover { background: #dbeafe; }
    .activity-list {
        max-height: 420px;
        overflow-y: auto;
        padding-right: 2px;
    }
    .activity-item {
        display: flex;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .activity-item:last-child { border-bottom: none; }
    .activity-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .activity-icon--green { background: #d1fae5; color: #059669; }
    .activity-icon--red { background: #fee2e2; color: #dc2626; }
    .activity-icon--blue { background: #dbeafe; color: #2563eb; }
    .activity-content { flex: 1; min-width: 0; }
    .activity-title {
        font-size: 12px;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.3;
        margin: 0 0 2px;
    }
    .activity-desc {
        font-size: 11px;
        color: #64748b;
        line-height: 1.4;
        margin: 0 0 4px;
    }
    .activity-time {
        font-size: 10px;
        color: #94a3b8;
        margin: 0;
    }
    .activity-empty {
        text-align: center;
        padding: 32px 0;
        color: #94a3b8;
        font-size: 12px;
    }
    .activity-empty svg { margin: 0 auto 8px; }

    .custom-scrollbar::-webkit-scrollbar { width: 3px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 8px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart === 'undefined') return;

    var blue = '#2196f3';
    var gridColor = 'rgba(148,163,184,0.08)';
    var tickColor = '#94a3b8';
    Chart.defaults.font.family = "'Inter', sans-serif";

    var tooltip = {
        backgroundColor: '#1e293b', titleColor: '#f1f5f9', bodyColor: '#cbd5e1',
        padding: {x:12,y:10}, cornerRadius: 10, displayColors: false,
        titleFont: { size: 12, weight: '700' }, bodyFont: { size: 11 }
    };

    function scaleOpts(yCallback) {
        return {
            y: { beginAtZero: true, grid: { color: gridColor, drawBorder: false }, ticks: { color: tickColor, font:{size:11}, callback: yCallback || function(v){return v;} }, border: { display: false } },
            x: { grid: { display: false }, ticks: { color: tickColor, font:{size:11} }, border: { display: false } }
        };
    }

    function gradientFill(ctx, top, bot) {
        var g = ctx.chart.ctx.createLinearGradient(0, 0, 0, ctx.chart.height);
        g.addColorStop(0, top); g.addColorStop(1, bot); return g;
    }

    var gL = {!! json_encode(!empty($guestsData) ? array_column($guestsData, 'day') : []) !!};
    var gV = {!! json_encode(!empty($guestsData) ? array_column($guestsData, 'count') : []) !!};
    var el1 = document.getElementById('guestsChart');
    if (el1 && gL.length) {
        new Chart(el1, { type:'line', data:{ labels:gL, datasets:[{
            data:gV, borderColor:blue, backgroundColor:function(c){return gradientFill(c,'rgba(33,150,243,0.15)','rgba(33,150,243,0)');},
            fill:true, borderWidth:2.5, pointBackgroundColor:'#fff', pointBorderColor:blue, pointBorderWidth:2.5, pointRadius:4, pointHoverRadius:7, tension:0.4
        }]}, options:{ responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false},tooltip:tooltip}, scales:scaleOpts() }});
    }

    var rL = {!! json_encode(!empty($revenueData) ? array_column($revenueData, 'month') : []) !!};
    var rV = {!! json_encode(!empty($revenueData) ? array_column($revenueData, 'amount') : []) !!};
    var el2 = document.getElementById('revenueChart');
    if (el2 && rL.length) {
        new Chart(el2, { type:'line', data:{ labels:rL, datasets:[{
            data:rV, borderColor:'#8b5cf6', backgroundColor:function(c){return gradientFill(c,'rgba(139,92,246,0.12)','rgba(139,92,246,0)');},
            fill:true, borderWidth:2.5, pointBackgroundColor:'#fff', pointBorderColor:'#8b5cf6', pointBorderWidth:2.5, pointRadius:4, pointHoverRadius:7, tension:0.4
        }]}, options:{ responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false},tooltip:tooltip},
            scales:scaleOpts(function(v){return v>=1000?(v/1000)+'K':v;}) }});
    }

    var bD = {!! json_encode($bookedData ?? []) !!};
    var cD = {!! json_encode($canceledData ?? []) !!};
    var el3 = document.getElementById('bookingsBarChart');
    if (el3) {
        new Chart(el3, { type:'bar', data:{
            labels:['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'],
            datasets:[
                { label:'Confirmées', data:bD, backgroundColor:blue, borderRadius:6, barPercentage:0.55, categoryPercentage:0.7 },
                { label:'Annulées', data:cD, backgroundColor:'#fca5a5', borderRadius:6, barPercentage:0.55, categoryPercentage:0.7 }
            ]
        }, options:{ responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false},tooltip:tooltip}, scales:scaleOpts() }});
    }

    var el4 = document.getElementById('statusDonut');
    if (el4) {
        var donutData = [{{ $statusConf }},{{ $statusPend }},{{ $statusCanc }}];
        var donutLabels = ['Confirmées','En attente','Annulées'];
        var donutColors = ['#10b981','#f59e0b','#ef4444'];
        var donutFaded  = ['rgba(16,185,129,0.25)','rgba(245,158,11,0.25)','rgba(239,68,68,0.25)'];
        var donutTotal = donutData.reduce(function(a,b){return a+b;},0) || 1;
        var selectedIdx = -1;
        var statusChart = null;

        function donutSelect(idx) {
            if (!statusChart) return;
            var filterEl = document.getElementById('statusFilter');
            if (selectedIdx === idx || idx === -1) {
                selectedIdx = -1;
                statusChart.data.datasets[0].backgroundColor = [donutColors[0], donutColors[1], donutColors[2]];
                document.getElementById('donutValue').textContent = '{{ number_format($statusTotal, 0, ",", " ") }}';
                document.getElementById('donutSubLabel').textContent = 'Total réserv.';
                document.getElementById('donutLabel').textContent = '';
                document.querySelectorAll('.donut-legend-item').forEach(function(item) {
                    item.style.opacity = '1';
                    item.style.transform = 'scale(1)';
                });
                if (filterEl) filterEl.value = '-1';
            } else {
                selectedIdx = idx;
                var pct = Math.round(donutData[idx] / donutTotal * 100);
                document.getElementById('donutValue').textContent = donutData[idx];
                document.getElementById('donutSubLabel').textContent = donutLabels[idx];
                document.getElementById('donutLabel').textContent = pct + '% du total';
                statusChart.data.datasets[0].backgroundColor = [
                    idx === 0 ? donutColors[0] : donutFaded[0],
                    idx === 1 ? donutColors[1] : donutFaded[1],
                    idx === 2 ? donutColors[2] : donutFaded[2]
                ];
                document.querySelectorAll('.donut-legend-item').forEach(function(item, i) {
                    item.style.opacity = i === idx ? '1' : '0.4';
                    item.style.transform = i === idx ? 'scale(1.05)' : 'scale(1)';
                });
                if (filterEl) filterEl.value = idx;
            }
            statusChart.update('none');
        }

        statusChart = new Chart(el4, {
            type: 'doughnut',
            data: {
                labels: donutLabels,
                datasets: [{
                    data: donutData,
                    backgroundColor: [donutColors[0], donutColors[1], donutColors[2]],
                    borderWidth: 4,
                    borderColor: '#fff',
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                onClick: function(event, elements) {
                    if (elements && elements.length > 0) {
                        donutSelect(elements[0].index);
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#f1f5f9',
                        bodyColor: '#e2e8f0',
                        padding: { x: 14, y: 10 },
                        cornerRadius: 10,
                        displayColors: true,
                        boxWidth: 10,
                        boxHeight: 10,
                        callbacks: {
                            label: function(ctx) {
                                var pct = Math.round(ctx.raw / donutTotal * 100);
                                return ' ' + ctx.label + ': ' + ctx.raw + ' (' + pct + '%)';
                            }
                        }
                    }
                }
            }
        });

        var legendItems = document.querySelectorAll('.donut-legend-item');
        for (var li = 0; li < legendItems.length; li++) {
            (function(index) {
                legendItems[index].addEventListener('click', function() {
                    donutSelect(index);
                    document.getElementById('statusFilter').value = index;
                });
            })(li);
        }

        var statusFilter = document.getElementById('statusFilter');
        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                donutSelect(parseInt(this.value));
            });
        }
    }
});
</script>
@endpush
