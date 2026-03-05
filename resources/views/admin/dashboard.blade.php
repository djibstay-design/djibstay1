@extends('layouts.admin')

@section('title', 'Dashboard')

@section('header')
<header class="admin-header">
    <div class="flex items-center gap-4 flex-1">
        <h1 class="text-xl font-bold text-slate-800">Dashboard</h1>
        <div class="relative flex-1 max-w-md">
            <input type="text" placeholder="Rechercher réservation, chambre..." class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg bg-slate-50 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <button class="p-2 rounded-lg hover:bg-slate-100 text-slate-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg></button>
        <button class="p-2 rounded-lg hover:bg-slate-100 text-slate-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg></button>
        <div class="flex items-center gap-3 pl-4 border-l border-slate-200">
            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold text-sm" style="background:#003580">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
            <div>
                <p class="font-semibold text-slate-800 text-sm">{{ auth()->user()->prenom ?? '' }} {{ auth()->user()->name ?? 'Admin' }}</p>
                <p class="text-xs text-slate-500">{{ auth()->user()->role === 'SUPER_ADMIN' ? 'Super Admin' : 'Admin' }}</p>
            </div>
        </div>
    </div>
</header>
@endsection

@section('content')
{{-- Ligne 1 : 4 cartes KPI --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['label' => 'Revenu total', 'value' => number_format($totalRevenue, 0, ',', ' ') . ' FCFA', 'change' => $revenueChange, 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label' => 'Nouvelles réservations', 'value' => $newBookings, 'change' => $bookingsChange, 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ['label' => 'Check-in', 'value' => $checkInToday, 'change' => $checkInChange, 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
        ['label' => 'Check-out', 'value' => $checkOutToday, 'change' => $checkOutChange, 'icon' => 'M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1'],
    ] as $kpi)
    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-slate-500 font-medium">{{ $kpi['label'] }}</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">{{ $kpi['value'] }}</p>
                <p class="text-sm mt-2 flex items-center gap-1 {{ $kpi['change'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    @if($kpi['change'] >= 0)<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>@else<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>@endif
                    {{ abs($kpi['change']) }}% vs semaine dernière
                </p>
            </div>
            <button class="text-slate-400 hover:text-slate-600 p-1 text-xl leading-none">⋮</button>
        </div>
    </div>
    @endforeach
</div>

{{-- Ligne 2 : Graphiques Séjours + Revenus --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-slate-800">Séjours</h3>
            <select class="text-sm border border-slate-200 rounded-lg px-3 py-1.5 text-slate-600 bg-slate-50">
                <option>Cette semaine</option>
            </select>
        </div>
        <div class="h-64"><canvas id="guestsChart"></canvas></div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-slate-800">Revenus</h3>
            <select class="text-sm border border-slate-200 rounded-lg px-3 py-1.5 text-slate-600 bg-slate-50">
                <option>8 derniers mois</option>
            </select>
        </div>
        <div class="h-64"><canvas id="revenueChart"></canvas></div>
    </div>
</div>

{{-- Ligne 3 : Réservations (barres) + Colonne droite (Occupation, Notes, Activité) --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-slate-800">Réservations</h3>
            <select class="text-sm border border-slate-200 rounded-lg px-3 py-1.5 text-slate-600 bg-slate-50">
                <option>Cette année</option>
            </select>
        </div>
        <div class="flex gap-6 mb-4 text-sm text-slate-600">
            <span>Confirmées: <strong class="text-slate-800">{{ $totalBooked }}</strong></span>
            <span>Annulées: <strong class="text-slate-800">{{ $totalCanceled }}</strong></span>
        </div>
        <div class="h-56"><canvas id="bookingsChart"></canvas></div>
    </div>
    <div class="space-y-4">
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <h3 class="font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Occupation des chambres
            </h3>
            <p class="text-xl font-bold text-slate-800">{{ $totalRooms }} chambres</p>
            <div class="mt-3 h-2 bg-slate-200 rounded-full overflow-hidden">
                <div class="h-full rounded-full" style="width:{{ $occupancyPercent }}%;background:#003580"></div>
            </div>
            <div class="mt-4 space-y-2 text-sm">
                <div class="flex justify-between"><span class="flex items-center gap-2"><span class="w-3 h-3 rounded-sm" style="background:#1e3a5f"></span>Occupées</span><span class="font-medium">{{ $occupied }}</span></div>
                <div class="flex justify-between"><span class="flex items-center gap-2"><span class="w-3 h-3 rounded-sm" style="background:#3b82f6"></span>Réservées</span><span class="font-medium">{{ $reserved }}</span></div>
                <div class="flex justify-between"><span class="flex items-center gap-2"><span class="w-3 h-3 rounded-sm bg-slate-300"></span>Disponibles</span><span class="font-medium">{{ $available }}</span></div>
                <div class="flex justify-between"><span class="flex items-center gap-2"><span class="w-3 h-3 rounded-sm bg-slate-200"></span>Non prêtes</span><span class="font-medium">{{ $notReady }}</span></div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <h3 class="font-semibold text-slate-800 mb-4 flex justify-between items-center">
                Notes globales
                <a href="{{ route('admin.avis.index') }}" class="text-sm font-medium" style="color:#003580">Voir tout</a>
            </h3>
            <p class="text-2xl font-bold text-slate-800">{{ number_format($avgRating, 1) }} <span class="text-base font-normal text-slate-500">Excellent</span></p>
            <p class="text-sm text-slate-500 mt-1">{{ $reviewsCount }} avis vérifiés</p>
            <div class="mt-4 space-y-2 text-sm">
                @foreach(['Propreté','Équipements','Emplacement','Service'] as $cat)
                <div class="flex items-center gap-2"><span class="w-20">{{ $cat }}</span><div class="flex-1 h-1.5 bg-slate-200 rounded overflow-hidden"><div class="h-full rounded" style="width:{{ $avgRating ? ($avgRating/5)*100 : 0 }}%;background:#003580"></div></div><span class="w-8 text-right font-medium">{{ number_format($avgRating, 1) }}</span></div>
                @endforeach
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <h3 class="font-semibold text-slate-800 mb-4 flex justify-between items-center">
                Activité récente
                <a href="{{ route('admin.reservations.index') }}" class="text-sm font-medium" style="color:#003580">Voir tout</a>
            </h3>
            <div class="space-y-4 max-h-40 overflow-y-auto">
                @forelse($recentActivity as $r)
                <div class="flex gap-3 text-sm">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0" style="background:rgba(0,53,128,0.1)"><svg class="w-4 h-4" style="color:#003580" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                    <div><p class="text-slate-800 font-medium">{{ $r->prenom_client }} {{ $r->nom_client }} — {{ $r->chambre->typeChambre->hotel->nom }}</p><p class="text-slate-500 text-xs mt-0.5">{{ $r->date_reservation->diffForHumans() }}</p></div>
                </div>
                @empty
                <p class="text-slate-500 text-sm">Aucune activité récente</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Ligne 4 : Tableaux statistiques professionnels --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- Prochains check-in --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Prochains check-in (7 jours)
            </h3>
            <a href="{{ route('admin.reservations.index') }}" class="text-sm font-medium hover:underline" style="color:#003580">Voir tout</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr class="text-left text-slate-500 font-medium text-xs uppercase tracking-wider">
                        <th class="px-5 py-3">Client</th>
                        <th class="px-5 py-3">Hôtel / Chambre</th>
                        <th class="px-5 py-3">Check-in</th>
                        <th class="px-5 py-3">Montant</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($upcomingCheckins ?? [] as $r)
                    <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-3">
                            <p class="font-medium text-slate-800">{{ $r->prenom_client }} {{ $r->nom_client }}</p>
                            <p class="text-xs text-slate-500">{{ $r->code_reservation }}</p>
                        </td>
                        <td class="px-5 py-3">
                            <p class="text-slate-800">{{ $r->chambre?->typeChambre?->hotel?->nom ?? '-' }}</p>
                            <p class="text-xs text-slate-500">{{ $r->chambre?->typeChambre?->nom_type ?? '-' }} — N°{{ $r->chambre?->numero ?? '-' }}</p>
                        </td>
                        <td class="px-5 py-3 text-slate-800">{{ $r->date_debut?->format('d/m/Y') ?? '-' }}</td>
                        <td class="px-5 py-3 font-medium text-slate-800">{{ number_format($r->montant_total ?? 0, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-5 py-8 text-center text-slate-500">Aucun check-in prévu</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Performance par hôtel --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                Performance par hôtel
            </h3>
            <a href="{{ route('admin.hotels.index') }}" class="text-sm font-medium hover:underline" style="color:#003580">Voir tout</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr class="text-left text-slate-500 font-medium text-xs uppercase tracking-wider">
                        <th class="px-5 py-3">#</th>
                        <th class="px-5 py-3">Hôtel</th>
                        <th class="px-5 py-3">Réservations</th>
                        <th class="px-5 py-3">Revenus</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hotelsPerformance ?? [] as $i => $hp)
                    <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-3">
                            <span class="inline-flex w-6 h-6 items-center justify-center rounded-full text-xs font-bold {{ $i === 0 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' }}">{{ $i + 1 }}</span>
                        </td>
                        <td class="px-5 py-3">
                            <p class="font-medium text-slate-800">{{ $hp['hotel']->nom }}</p>
                            <p class="text-xs text-slate-500">{{ $hp['hotel']->ville ?? '-' }}</p>
                        </td>
                        <td class="px-5 py-3 font-medium text-slate-800">{{ $hp['reservations_count'] }}</td>
                        <td class="px-5 py-3 font-semibold text-slate-800">{{ number_format($hp['revenue'], 0, ',', ' ') }} FCFA</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-5 py-8 text-center text-slate-500">Aucune donnée</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Réservations par statut --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-emerald-50">
            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-sm text-slate-500 font-medium">Confirmées</p>
            <p class="text-2xl font-bold text-slate-800">{{ $reservationsByStatus['CONFIRMEE'] ?? 0 }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-amber-50">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-sm text-slate-500 font-medium">En attente</p>
            <p class="text-2xl font-bold text-slate-800">{{ $reservationsByStatus['EN_ATTENTE'] ?? 0 }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-red-50">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-sm text-slate-500 font-medium">Annulées</p>
            <p class="text-2xl font-bold text-slate-800">{{ $reservationsByStatus['ANNULEE'] ?? 0 }}</p>
        </div>
    </div>
</div>

{{-- Ligne 5 : Tableau des réservations du jour --}}
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-semibold text-slate-800 flex items-center gap-2">
            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Liste des réservations du jour
        </h3>
        <div class="flex gap-2">
            <a href="{{ route('admin.reservations.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-white hover:opacity-90 transition-opacity" style="background:#003580">Voir tout</a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50">
                <tr class="text-left text-slate-500 font-medium text-xs uppercase tracking-wider">
                    <th class="px-5 py-3">ID</th>
                    <th class="px-5 py-3">Client</th>
                    <th class="px-5 py-3">Type chambre</th>
                    <th class="px-5 py-3">N°</th>
                    <th class="px-5 py-3">Durée</th>
                    <th class="px-5 py-3">Check-in</th>
                    <th class="px-5 py-3">Check-out</th>
                    <th class="px-5 py-3">Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookingList as $r)
                <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition-colors">
                    <td class="px-5 py-3 font-mono text-slate-800 font-medium">{{ $r->code_reservation }}</td>
                    <td class="px-5 py-3 text-slate-800">{{ $r->prenom_client }} {{ $r->nom_client }}</td>
                    <td class="px-5 py-3 text-slate-800">{{ $r->chambre?->typeChambre?->nom_type ?? '-' }}</td>
                    <td class="px-5 py-3 text-slate-800">{{ $r->chambre?->numero ?? '-' }}</td>
                    <td class="px-5 py-3 text-slate-800">{{ $r->date_debut->diffInDays($r->date_fin) }} nuit(s)</td>
                    <td class="px-5 py-3 text-slate-800">{{ $r->date_debut->format('d/m/Y') }}</td>
                    <td class="px-5 py-3 text-slate-800">{{ $r->date_fin->format('d/m/Y') }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium {{ $r->statut === 'CONFIRMEE' ? 'bg-emerald-100 text-emerald-800' : ($r->statut === 'ANNULEE' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                            {{ $r->statut === 'CONFIRMEE' ? 'Confirmée' : ($r->statut === 'ANNULEE' ? 'Annulée' : 'En attente') }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-5 py-12 text-center text-slate-500">Aucune réservation aujourd'hui</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const blue = '#003580';
    const blueRgba = 'rgba(0, 53, 128, 0.15)';

    new Chart(document.getElementById('guestsChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($guestsData, 'day')) !!},
            datasets: [{ label: 'Séjours', data: {!! json_encode(array_column($guestsData, 'count')) !!}, borderColor: blue, backgroundColor: blueRgba, fill: true, tension: 0.4, borderWidth: 2, pointBackgroundColor: blue, pointRadius: 3 }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.06)' } }, x: { grid: { display: false } } }
    });

    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($revenueData, 'month')) !!},
            datasets: [{ label: 'Revenus', data: {!! json_encode(array_column($revenueData, 'amount')) !!}, borderColor: blue, backgroundColor: blueRgba, fill: true, tension: 0.4, borderWidth: 2, pointBackgroundColor: blue, pointRadius: 3 }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.06)' }, ticks: { callback: v => v >= 1000 ? (v/1000)+'K' : v } }, x: { grid: { display: false } } }
    });

    new Chart(document.getElementById('bookingsChart'), {
        type: 'bar',
        data: {
            labels: ['Jan','Fév','Mar','Avr','Mai','Juin','Juil','Août','Sep','Oct','Nov','Déc'],
            datasets: [
                { label: 'Confirmées', data: {!! json_encode($bookedData) !!}, backgroundColor: blue },
                { label: 'Annulées', data: {!! json_encode($canceledData) !!}, backgroundColor: '#94a3b8' }
            ]
        },
        options: { responsive: true, maintainAspectRatio: false, scales: { x: { stacked: true, grid: { display: false } }, y: { stacked: true, beginAtZero: true, grid: { color: 'rgba(0,0,0,0.06)' } } }, plugins: { legend: { position: 'top' } }
    });
});
</script>
@endsection
