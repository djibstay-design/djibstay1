@extends('layouts.app')

@section('title', 'Réservation confirmée')

@section('content')
<div class="max-w-xl mx-auto text-center">
    <h1 class="text-2xl font-semibold text-green-600 dark:text-green-400 mb-4">Réservation enregistrée</h1>
    <p class="mb-2">Votre réservation a bien été envoyée.</p>
    <p class="text-lg font-mono font-bold bg-gray-100 dark:bg-[#3E3E3A] px-4 py-3 rounded inline-block">
        {{ $reservation->code_reservation }}
    </p>
    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Conservez ce code pour suivre votre réservation.</p>

    <div class="mt-8 text-left border border-[#e3e3e0] dark:border-[#3E3E3A] rounded p-4">
        <p><strong>Hôtel :</strong> {{ $reservation->chambre->typeChambre->hotel->nom }}</p>
        <p><strong>Chambre :</strong> {{ $reservation->chambre->numero }}</p>
        <p><strong>Dates :</strong> {{ $reservation->date_debut->format('d/m/Y') }} - {{ $reservation->date_fin->format('d/m/Y') }}</p>
        <p><strong>Montant total :</strong> {{ number_format($reservation->montant_total, 0, ',', ' ') }} FCFA</p>
    </div>

    <a href="{{ route('hotels.show', $reservation->chambre->typeChambre->hotel) }}" class="inline-block mt-6 px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded hover:bg-gray-100 dark:hover:bg-[#3E3E3A]">
        Retour à l'hôtel
    </a>
</div>
@endsection
