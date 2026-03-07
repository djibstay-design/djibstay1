@extends('layouts.confirmation')

@section('title', 'Réservation confirmée')

@php
    $hotel = $reservation->chambre->typeChambre->hotel;
    $typeChambre = $reservation->chambre->typeChambre;
    $chambre = $reservation->chambre;
    $nuits = $reservation->date_debut->diffInDays($reservation->date_fin);
    $reference = '#' . str_pad($reservation->id, 6, '0', STR_PAD_LEFT);
    $statusLabels = [
        'EN_ATTENTE' => 'Pending',
        'CONFIRMEE' => 'Confirmed',
        'ANNULEE' => 'Cancelled',
        'TERMINEE' => 'Completed',
    ];
    $statusLabel = $statusLabels[$reservation->statut] ?? $reservation->statut;
@endphp

@section('content')
<div class="max-w-lg mx-auto mt-16 px-2">
    <div class="confirmation-card bg-gradient-to-br from-[#1E293B] to-[#0F172A] rounded-2xl shadow-2xl p-8 border border-white/5">
        {{-- Icône confirmation --}}
        <div class="flex justify-center mb-6">
            <div class="confirmation-icon w-16 h-16 rounded-full bg-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/50">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        {{-- Titre --}}
        <h1 class="confirmation-title text-3xl font-bold text-blue-400 text-center mb-2">Booking Confirmed!</h1>

        {{-- Sous-titre --}}
        <p class="confirmation-subtitle text-gray-400 text-sm text-center mb-6">
            Thank you, {{ $reservation->prenom_client }} {{ $reservation->nom_client }}. Your reservation has been received.
        </p>

        {{-- Booking reference --}}
        <div class="confirmation-ref bg-[#1E293B] rounded-xl p-3 text-center mb-6">
            <span class="label text-gray-400">Booking Reference: </span>
            <span class="num text-blue-400 font-bold">{{ $reference }}</span>
        </div>

        {{-- Détails réservation (grille : label à gauche, valeur à droite, textes longs à la ligne) --}}
        <div class="space-y-0 border-t border-white/5">
            <div class="confirmation-detail">
                <span class="label">Hotel</span>
                <span class="value">{{ $hotel->nom }}</span>
            </div>
            <div class="confirmation-detail">
                <span class="label">Room</span>
                <span class="value">Room {{ $chambre->numero }} ({{ $typeChambre->nom_type }})</span>
            </div>
            <div class="confirmation-detail">
                <span class="label">Check-In</span>
                <span class="value">{{ $reservation->date_debut->format('D, M d Y') }}</span>
            </div>
            <div class="confirmation-detail">
                <span class="label">Check-Out</span>
                <span class="value">{{ $reservation->date_fin->format('D, M d Y') }}</span>
            </div>
            <div class="confirmation-detail">
                <span class="label">Duration</span>
                <span class="value">{{ $nuits }} {{ $nuits === 1 ? 'night' : 'nights' }}</span>
            </div>
            <div class="confirmation-detail">
                <span class="label">Guests</span>
                <span class="value">{{ $reservation->quantite }}</span>
            </div>
            <div class="confirmation-detail">
                <span class="label">Email</span>
                <span class="value">{{ $reservation->email_client }}</span>
            </div>
            <div class="confirmation-detail">
                <span class="label">Status</span>
                <span class="value"><span class="confirmation-status">{{ $statusLabel }}</span></span>
            </div>
        </div>

        {{-- Total Price --}}
        <div class="confirmation-total-wrap mt-6 pt-4">
            <div class="confirmation-total flex justify-between items-baseline">
                <span class="label text-gray-400 text-sm">Total Price</span>
                <span class="price text-2xl font-bold text-blue-400">${{ number_format($reservation->montant_total, 2, '.', ',') }}</span>
            </div>
        </div>

        {{-- Boutons --}}
        <div class="confirmation-btns mt-8 flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('hotels.index') }}" class="confirmation-btn-primary inline-flex justify-center items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition duration-300 shadow-lg shadow-blue-500/30">
                Browse More Hotels
            </a>
            <a href="{{ route('hotels.show', $hotel) }}" class="confirmation-btn-secondary inline-flex justify-center items-center px-6 py-3 bg-[#1E293B] border border-white/10 hover:bg-[#243145] text-gray-300 font-medium rounded-xl transition duration-300">
                Back to Hotel
            </a>
        </div>
    </div>
</div>
@endsection
