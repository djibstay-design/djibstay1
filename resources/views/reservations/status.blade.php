@extends('layouts.app')

@section('title', 'Suivi de réservation')

@section('content')
<div class="max-w-xl mx-auto">
    <h1 class="text-2xl font-semibold mb-6">Suivi de réservation</h1>

    <form action="{{ route('reservations.status') }}" method="GET" class="mb-8">
        <label for="code" class="block text-sm font-medium mb-1">Entrez votre code de réservation</label>
        <div class="flex gap-2">
            <input type="text" name="code" id="code" value="{{ $code ?? '' }}" placeholder="Ex: RES-20250228-ABC123"
                class="flex-1 px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
            <button type="submit" class="px-4 py-2 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] rounded font-medium">Rechercher</button>
        </div>
    </form>

    @if ($code)
        @if ($reservation)
            <div class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg p-6">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="font-semibold text-lg">{{ $reservation->code_reservation }}</h2>
                    <span class="px-3 py-1 rounded text-sm font-medium
                        @if($reservation->statut === 'CONFIRMEE') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200
                        @elseif($reservation->statut === 'ANNULEE') bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200
                        @else bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200 @endif">
                        {{ $reservation->statut }}
                    </span>
                </div>
                <p><strong>Hôtel :</strong> {{ $reservation->chambre->typeChambre->hotel->nom }}</p>
                <p><strong>Chambre :</strong> {{ $reservation->chambre->numero }} ({{ $reservation->chambre->typeChambre->nom_type }})</p>
                <p><strong>Arrivée :</strong> {{ $reservation->date_debut->format('d/m/Y') }}</p>
                <p><strong>Départ :</strong> {{ $reservation->date_fin->format('d/m/Y') }}</p>
                <p><strong>Montant total :</strong> {{ number_format($reservation->montant_total, 0, ',', ' ') }} FCFA</p>
            </div>
        @else
            <p class="text-red-600 dark:text-red-400">Aucune réservation trouvée pour ce code.</p>
        @endif
    @endif
</div>
@endsection
