@extends('layouts.admin')

@section('title', 'Réservation ' . $reservation->code_reservation)

@section('content')
<div class="flex justify-between items-start mb-6">
    <h1 class="text-2xl font-semibold">Réservation {{ $reservation->code_reservation }}</h1>
    <a href="{{ route('admin.reservations.index') }}" class="text-sm underline">Retour</a>
</div>

<div class="grid gap-6 md:grid-cols-2">
    <div class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded p-4">
        <h2 class="font-semibold mb-2">Client</h2>
        <p>{{ $reservation->prenom_client }} {{ $reservation->nom_client }}</p>
        <p>{{ $reservation->email_client }}</p>
        @if ($reservation->telephone_client)
            <p>{{ $reservation->telephone_client }}</p>
        @endif
        <p>Pièce : {{ $reservation->code_identite }}</p>
        @if ($reservation->photo_carte || $reservation->photo_visage)
            <div class="mt-4 flex gap-4 flex-wrap">
                @if ($reservation->photo_carte)
                    <div>
                        <p class="text-sm font-medium mb-1">Carte d'identité</p>
                        <img src="{{ asset('storage/'.$reservation->photo_carte) }}" alt="Carte" class="w-32 h-auto rounded border object-cover">
                    </div>
                @endif
                @if ($reservation->photo_visage)
                    <div>
                        <p class="text-sm font-medium mb-1">Photo visage</p>
                        <img src="{{ asset('storage/'.$reservation->photo_visage) }}" alt="Visage" class="w-32 h-auto rounded border object-cover">
                    </div>
                @endif
            </div>
        @endif
    </div>
    <div class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded p-4">
        <h2 class="font-semibold mb-2">Séjour</h2>
        <p><strong>Hôtel :</strong> {{ $reservation->chambre->typeChambre->hotel->nom }}</p>
        <p><strong>Chambre :</strong> {{ $reservation->chambre->numero }} ({{ $reservation->chambre->typeChambre->nom_type }})</p>
        <p><strong>Arrivée :</strong> {{ $reservation->date_debut->format('d/m/Y') }}</p>
        <p><strong>Départ :</strong> {{ $reservation->date_fin->format('d/m/Y') }}</p>
        <p><strong>Quantité :</strong> {{ $reservation->quantite }}</p>
        <p><strong>Montant total :</strong> {{ number_format($reservation->montant_total, 0, ',', ' ') }} FCFA</p>
    </div>
</div>

<form action="{{ route('admin.reservations.update', $reservation) }}" method="POST" class="mt-6 max-w-xs">
    @csrf
    @method('PUT')
    <label for="statut" class="block text-sm font-medium mb-1">Modifier le statut</label>
    <div class="flex gap-2">
        <select name="statut" id="statut" class="flex-1 px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
            <option value="EN_ATTENTE" {{ $reservation->statut === 'EN_ATTENTE' ? 'selected' : '' }}>En attente</option>
            <option value="CONFIRMEE" {{ $reservation->statut === 'CONFIRMEE' ? 'selected' : '' }}>Confirmée</option>
            <option value="ANNULEE" {{ $reservation->statut === 'ANNULEE' ? 'selected' : '' }}>Annulée</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] rounded">Mettre à jour</button>
    </div>
</form>

<form action="{{ route('admin.reservations.destroy', $reservation) }}" method="POST" class="mt-4" onsubmit="return confirm('Supprimer cette réservation ?')">
    @csrf
    @method('DELETE')
    <button type="submit" class="text-sm text-red-600 hover:underline">Supprimer la réservation</button>
</form>
@endsection
