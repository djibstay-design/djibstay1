@extends('layouts.admin')

@section('title', 'Réservation ' . $reservation->code_reservation)

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div class="flex items-center gap-3 flex-wrap">
        <h1 class="text-2xl font-bold text-slate-900">Réservation {{ $reservation->code_reservation }}</h1>
        <span class="px-3 py-1.5 rounded-xl text-sm font-semibold
            @if($reservation->statut === 'CONFIRMEE') bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20
            @elseif($reservation->statut === 'ANNULEE') bg-red-50 text-red-600 ring-1 ring-red-600/20
            @else bg-amber-50 text-amber-700 ring-1 ring-amber-600/20 @endif">
            {{ $reservation->statut === 'CONFIRMEE' ? 'Confirmée' : ($reservation->statut === 'ANNULEE' ? 'Annulée' : 'En attente') }}
        </span>
    </div>
    <a href="{{ route('admin.reservations.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Retour aux réservations
    </a>
</div>

<div class="grid gap-6 lg:grid-cols-2">
    {{-- Carte Client --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" style="border-top: 4px solid #2563eb;">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </span>
                Client
            </h2>
        </div>
        <div class="p-6 space-y-3">
            <p class="text-slate-800 font-medium">{{ $reservation->prenom_client }} {{ $reservation->nom_client }}</p>
            <p class="text-slate-600 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                {{ $reservation->email_client }}
            </p>
            @if ($reservation->telephone_client)
            <p class="text-slate-600 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                {{ $reservation->telephone_client }}
            </p>
            @endif
            <p class="text-slate-600 text-sm">Pièce : {{ $reservation->code_identite }}</p>
            @if ($reservation->photo_carte || $reservation->photo_visage)
            <div class="pt-4 mt-4 border-t border-slate-100 flex gap-6 flex-wrap">
                @if ($reservation->photo_carte)
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Carte d'identité</p>
                    <img src="{{ asset('storage/'.$reservation->photo_carte) }}" alt="Carte d'identité" class="w-36 h-auto rounded-xl border border-slate-200 object-cover shadow-sm">
                </div>
                @endif
                @if ($reservation->photo_visage)
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Photo visage</p>
                    <img src="{{ asset('storage/'.$reservation->photo_visage) }}" alt="Photo visage" class="w-36 h-auto rounded-xl border border-slate-200 object-cover shadow-sm">
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    {{-- Carte Séjour --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" style="border-top: 4px solid #2563eb;">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </span>
                Séjour
            </h2>
        </div>
        <div class="p-6">
            <dl class="space-y-3">
                <div class="flex flex-wrap gap-2"><dt class="font-semibold text-slate-600 min-w-[90px]">Hôtel</dt><dd class="text-slate-800">{{ $reservation->chambre->typeChambre->hotel->nom }}</dd></div>
                <div class="flex flex-wrap gap-2"><dt class="font-semibold text-slate-600 min-w-[90px]">Chambre</dt><dd class="text-slate-800">{{ $reservation->chambre->numero }} ({{ $reservation->chambre->typeChambre->nom_type }})</dd></div>
                <div class="flex flex-wrap gap-2"><dt class="font-semibold text-slate-600 min-w-[90px]">Arrivée</dt><dd class="text-slate-800">{{ $reservation->date_debut->format('d/m/Y') }}</dd></div>
                <div class="flex flex-wrap gap-2"><dt class="font-semibold text-slate-600 min-w-[90px]">Départ</dt><dd class="text-slate-800">{{ $reservation->date_fin->format('d/m/Y') }}</dd></div>
                <div class="flex flex-wrap gap-2"><dt class="font-semibold text-slate-600 min-w-[90px]">Quantité</dt><dd class="text-slate-800">{{ $reservation->quantite }}</dd></div>
                <div class="flex flex-wrap gap-2 pt-3 mt-3 border-t border-slate-100"><dt class="font-semibold text-slate-600 min-w-[90px]">Montant total</dt><dd class="text-slate-900 font-bold text-lg">{{ number_format($reservation->montant_total, 0, ',', ' ') }} DJF</dd></div>
            </dl>
        </div>
    </div>
</div>

{{-- Modifier le statut --}}
<div class="mt-8 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden max-w-xl">
    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
        <h2 class="text-base font-bold text-slate-800">Modifier le statut</h2>
    </div>
    <form action="{{ route('admin.reservations.update', $reservation) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')
        <div class="flex flex-col sm:flex-row gap-3">
            <select name="statut" id="statut" class="flex-1 px-4 py-3 border border-slate-200 rounded-xl text-slate-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                <option value="EN_ATTENTE" {{ $reservation->statut === 'EN_ATTENTE' ? 'selected' : '' }}>En attente</option>
                <option value="CONFIRMEE" {{ $reservation->statut === 'CONFIRMEE' ? 'selected' : '' }}>Confirmée</option>
                <option value="ANNULEE" {{ $reservation->statut === 'ANNULEE' ? 'selected' : '' }}>Annulée</option>
            </select>
            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-sm transition-colors whitespace-nowrap">
                Mettre à jour
            </button>
        </div>
    </form>
</div>

<div class="mt-6">
    <button type="button" data-delete-url="{{ route('admin.reservations.destroy', $reservation) }}" data-delete-label="la réservation {{ $reservation->code_reservation }}"
        class="inline-flex items-center gap-2 px-4 py-2.5 border border-red-200 text-red-600 font-medium rounded-xl hover:bg-red-50 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        Supprimer la réservation
    </button>
</div>
@endsection
