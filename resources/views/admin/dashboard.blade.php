@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('header')
<header class="bg-white border-b border-gray-200 px-6 py-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <div class="flex items-center gap-4">
            <div class="relative">
                <input type="text" placeholder="Rechercher réservation, chambre..." class="pl-10 pr-4 py-2 w-72 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <div class="flex items-center gap-2">
                <button class="p-2 rounded-lg hover:bg-gray-100 text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg></button>
                <button class="p-2 rounded-lg hover:bg-gray-100 text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg></button>
            </div>
            <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                <div>
                    <p class="font-semibold text-gray-900">{{ auth()->user()->prenom ?? '' }} {{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-sm text-gray-500">{{ auth()->user()->role === 'SUPER_ADMIN' ? 'Super Admin' : 'Admin' }}</p>
                </div>
            </div>
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="space-y-6">
    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 relative">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Revenu total</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalRevenue, 0, ',', ' ') }} FCFA</p>
                    <p class="text-sm mt-2 flex items-center gap-1 {{ $revenueChange >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        @if($revenueChange >= 0)<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>@else<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>@endif
                        {{ abs($revenueChange) }}% vs semaine dernière
                    </p>
                </div>
                <button class="text-gray-400 hover:text-gray-600 p-1">⋮</button>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 relative">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Nouvelles réservations</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $newBookings }}</p>
                    <p class="text-sm mt-2 flex items-center gap-1 {{ $bookingsChange >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        @if($bookingsChange >= 0)<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>@else<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>@endif
                        {{ abs($bookingsChange) }}% vs semaine dernière
                    </p>
                </div>
                <button class="text-gray-400 hover:text-gray-600 p-1">⋮</button>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 relative">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Check-in</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $checkInToday }}</p>
                    <p class="text-sm mt-2 flex items-center gap-1 {{ $checkInChange >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        @if($checkInChange >= 0)<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>@else<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>@endif
                        {{ abs($checkInChange) }}% vs semaine dernière
                    </p>
                </div>
                <button class="text-gray-400 hover:text-gray-600 p-1">⋮</button>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 relative">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Check-out</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $checkOutToday }}</p>
                    <p class="text-sm mt-2 flex items-center gap-1 {{ $checkOutChange >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        @if($checkOutChange >= 0)<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>@else<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>@endif
                        {{ abs($checkOutChange) }}% vs semaine dernière
                    </p>
                </div>
                <button class="text-gray-400 hover:text-gray-600 p-1">⋮</button>
            </div>
        </div>
    </div>

    {{-- Charts Row 1 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-900">Séjours</h3>
                <select class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-blue-500">
                    <option>Cette semaine</option>
                </select>
            </div>
            <div class="h-64"><canvas id="guestsChart"></canvas></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-900">Revenus</h3>
                <select class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-blue-500">
                    <option>8 derniers mois</option>
                </select>
            </div>
            <div class="h-64"><canvas id="revenueChart"></canvas></div>
        </div>
    </div>

    {{-- Charts Row 2 + Sidebar --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-900">Réservations</h3>
                <select class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-blue-500">
                    <option>Cette année</option>
                </select>
            </div>
            <div class="flex gap-4 mb-4">
                <span class="text-sm text-gray-600">Confirmées: <strong>{{ $totalBooked }}</strong></span>
                <span class="text-sm text-gray-600">Annulées: <strong>{{ $totalCanceled }}</strong></span>
            </div>
            <div class="h-56"><canvas id="bookingsChart"></canvas></div>
        </div>
        <div class="space-y-6">
            {{-- Room Occupancy --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Occupation des chambres
                </h3>
                <p class="text-2xl font-bold text-gray-900">{{ $totalRooms }} chambres</p>
                <div class="mt-3 h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500 rounded-full" style="width: {{ $occupancyPercent }}%"></div>
                </div>
                <div class="mt-4 space-y-2">
                    <div class="flex items-center justify-between text-sm"><span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-blue-500"></span>Occupées</span><span>{{ $occupied }}</span></div>
                    <div class="flex items-center justify-between text-sm"><span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-green-500"></span>Disponibles</span><span>{{ $available }}</span></div>
                    <div class="flex items-center justify-between text-sm"><span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-amber-500"></span>Réservées</span><span>{{ $reserved }}</span></div>
                    <div class="flex items-center justify-between text-sm"><span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-gray-400"></span>Non prêtes</span><span>{{ $notReady }}</span></div>
                </div>
            </div>
            {{-- Overall Ratings --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-semibold text-gray-900 mb-4 flex justify-between items-center">
                    Notes globales
                    <a href="{{ route('admin.avis.index') }}" class="text-sm text-blue-600 font-medium">Voir tout</a>
                </h3>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($avgRating, 1) }} <span class="text-lg font-normal text-gray-500">Excellent</span></p>
                <p class="text-sm text-gray-500 mt-1">{{ $reviewsCount }} avis vérifiés</p>
                <div class="mt-4 space-y-2">
                    <div class="flex items-center justify-between text-sm"><span>Propreté</span><div class="flex-1 mx-2 h-1.5 bg-gray-200 rounded"><div class="h-full bg-blue-500 rounded" style="width: {{ $avgRating ? ($avgRating/5)*100 : 0 }}%"></div></div><span>{{ number_format($avgRating, 1) }}</span></div>
                    <div class="flex items-center justify-between text-sm"><span>Équipements</span><div class="flex-1 mx-2 h-1.5 bg-gray-200 rounded"><div class="h-full bg-blue-500 rounded" style="width: {{ $avgRating ? ($avgRating/5)*100 : 0 }}%"></div></div><span>{{ number_format($avgRating, 1) }}</span></div>
                    <div class="flex items-center justify-between text-sm"><span>Emplacement</span><div class="flex-1 mx-2 h-1.5 bg-gray-200 rounded"><div class="h-full bg-blue-500 rounded" style="width: {{ $avgRating ? ($avgRating/5)*100 : 0 }}%"></div></div><span>{{ number_format($avgRating, 1) }}</span></div>
                    <div class="flex items-center justify-between text-sm"><span>Service</span><div class="flex-1 mx-2 h-1.5 bg-gray-200 rounded"><div class="h-full bg-blue-500 rounded" style="width: {{ $avgRating ? ($avgRating/5)*100 : 0 }}%"></div></div><span>{{ number_format($avgRating, 1) }}</span></div>
                </div>
            </div>
            {{-- Recent Activity --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-semibold text-gray-900 mb-4 flex justify-between items-center">
                    Activité récente
                    <a href="{{ route('admin.reservations.index') }}" class="text-sm text-blue-600 font-medium">Voir tout</a>
                </h3>
                <div class="space-y-4 max-h-48 overflow-y-auto">
                    @forelse($recentActivity as $r)
                    <div class="flex gap-3 text-sm">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-gray-900">{{ $r->prenom_client }} {{ $r->nom_client }} — {{ $r->chambre->typeChambre->hotel->nom }}</p>
                            <p class="text-gray-500 text-xs">{{ $r->date_reservation->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm">Aucune activité récente</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Booking List --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-900">Liste des réservations</h3>
            <div class="flex gap-2">
                <select class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-blue-500">
                    <option>Aujourd'hui</option>
                </select>
                <a href="{{ route('admin.reservations.index') }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">Voir tout</a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-left text-gray-500 font-medium">
                        <th class="pb-3 pr-4">ID</th>
                        <th class="pb-3 pr-4">Client</th>
                        <th class="pb-3 pr-4">Type chambre</th>
                        <th class="pb-3 pr-4">N°</th>
                        <th class="pb-3 pr-4">Durée</th>
                        <th class="pb-3 pr-4">Check-in</th>
                        <th class="pb-3 pr-4">Check-out</th>
                        <th class="pb-3">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookingList as $r)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 pr-4 font-mono">{{ $r->code_reservation }}</td>
                        <td class="py-3 pr-4">{{ $r->prenom_client }} {{ $r->nom_client }}</td>
                        <td class="py-3 pr-4">{{ $r->chambre->typeChambre->nom_type }}</td>
                        <td class="py-3 pr-4">{{ $r->chambre->numero }}</td>
                        <td class="py-3 pr-4">{{ $r->date_debut->diffInDays($r->date_fin) }} nuit(s)</td>
                        <td class="py-3 pr-4">{{ $r->date_debut->format('d/m/Y') }}</td>
                        <td class="py-3 pr-4">{{ $r->date_fin->format('d/m/Y') }}</td>
                        <td class="py-3"><span class="px-2 py-1 rounded text-xs font-medium {{ $r->statut === 'CONFIRMEE' ? 'bg-green-100 text-green-800' : ($r->statut === 'ANNULEE' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">{{ $r->statut }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="py-8 text-center text-gray-500">Aucune réservation aujourd'hui</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const blue = 'rgb(59, 130, 246)';
    const blueLight = 'rgba(59, 130, 246, 0.2)';

    new Chart(document.getElementById('guestsChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($guestsData, 'day')) !!},
            datasets: [{
                label: 'Séjours',
                data: {!! json_encode(array_column($guestsData, 'count')) !!},
                borderColor: blue,
                backgroundColor: blueLight,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($revenueData, 'month')) !!},
            datasets: [{
                label: 'Revenus',
                data: {!! json_encode(array_column($revenueData, 'amount')) !!},
                borderColor: blue,
                backgroundColor: blueLight,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    new Chart(document.getElementById('bookingsChart'), {
        type: 'bar',
        data: {
            labels: ['Jan','Fév','Mar','Avr','Mai','Juin','Juil','Août','Sep','Oct','Nov','Déc'],
            datasets: [
                { label: 'Confirmées', data: {!! json_encode($bookedData) !!}, backgroundColor: blue },
                { label: 'Annulées', data: {!! json_encode($canceledData) !!}, backgroundColor: 'rgb(239, 68, 68)' }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { stacked: true },
                y: { stacked: true, beginAtZero: true }
            }
        }
    });
});
</script>
@endsection
