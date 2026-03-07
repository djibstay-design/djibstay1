@extends('layouts.app')

@section('title', 'Suivi de réservation')

@push('styles')
<style>
    .status-page-hero {
        background: linear-gradient(135deg, #003580 0%, #0047a0 50%, #003580 100%);
        color: #fff;
    }
    .status-btn-primary {
        background-color: #003580 !important;
        color: #fff !important;
        box-shadow: 0 2px 8px rgba(0, 53, 128, 0.35);
        padding: 0.6rem 1.25rem !important;
        font-size: 1rem !important;
        min-height: 48px !important;
    }
    .status-btn-primary:hover {
        background-color: #0047a0 !important;
        box-shadow: 0 4px 12px rgba(0, 53, 128, 0.45);
    }
    .status-input-code {
        min-height: 48px !important;
        padding: 0.75rem 1.25rem !important;
        font-size: 1rem !important;
        min-width: 280px !important;
    }
</style>
@endpush

@section('content')
<div class="min-h-[60vh] flex flex-col">
    {{-- Bandeau d'en-tête professionnel --}}
    <div class="status-page-hero rounded-2xl mb-8 overflow-hidden shadow-xl">
        <div class="px-6 py-10 md:py-14 text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-white/20 mb-4">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-white tracking-tight">Suivi de réservation</h1>
            <p class="text-white/90 mt-2 max-w-md mx-auto text-sm md:text-base">Vérifiez le statut de votre réservation en quelques secondes avec votre code unique.</p>
        </div>
    </div>

    {{-- Carte formulaire --}}
    <div class="flex-1 max-w-2xl mx-auto w-full">
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200/80 overflow-hidden">
            <div class="border-t-4 border-t-[#003580]"></div>
            <div class="p-6 md:p-8">
                <form action="{{ route('reservations.status') }}" method="GET" class="space-y-4">
                    <label for="code" class="block text-sm font-semibold text-slate-700">Code de réservation</label>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input type="text" name="code" id="code" value="{{ $code ?? '' }}" placeholder="Ex: RES-20250228-ABC123"
                            class="status-input-code flex-1 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#003580] focus:border-[#003580] outline-none transition-all placeholder-slate-400 text-slate-800">
                        <button type="submit" class="status-btn-primary rounded-xl transition-all whitespace-nowrap flex items-center justify-center gap-2 border-0 cursor-pointer" style="background-color:#003580;color:#fff;">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Rechercher
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if ($code)
            @if ($reservation)
                <div class="mt-8 bg-white rounded-2xl shadow-lg border border-slate-200/80 overflow-hidden">
                    <div class="border-l-4 border-l-[#003580]"></div>
                    <div class="p-6 md:p-8">
                        <div class="flex flex-wrap justify-between items-start gap-4 mb-6">
                            <h2 class="text-lg font-bold text-[#003580]">{{ $reservation->code_reservation }}</h2>
                            <span class="px-4 py-2 rounded-xl text-sm font-semibold
                                @if($reservation->statut === 'CONFIRMEE') bg-emerald-50 text-emerald-700 border border-emerald-200
                                @elseif($reservation->statut === 'ANNULEE') bg-red-50 text-red-700 border border-red-200
                                @else bg-amber-50 text-amber-700 border border-amber-200 @endif">
                                {{ $reservation->statut }}
                            </span>
                        </div>
                        <dl class="grid gap-3 text-sm">
                            <div class="flex flex-wrap gap-2"><dt class="font-semibold text-slate-600 min-w-[100px]">Hôtel</dt><dd class="text-slate-800">{{ $reservation->chambre->typeChambre->hotel->nom }}</dd></div>
                            <div class="flex flex-wrap gap-2"><dt class="font-semibold text-slate-600 min-w-[100px]">Chambre</dt><dd class="text-slate-800">{{ $reservation->chambre->numero }} ({{ $reservation->chambre->typeChambre->nom_type }})</dd></div>
                            <div class="flex flex-wrap gap-2"><dt class="font-semibold text-slate-600 min-w-[100px]">Arrivée</dt><dd class="text-slate-800">{{ $reservation->date_debut->format('d/m/Y') }}</dd></div>
                            <div class="flex flex-wrap gap-2"><dt class="font-semibold text-slate-600 min-w-[100px]">Départ</dt><dd class="text-slate-800">{{ $reservation->date_fin->format('d/m/Y') }}</dd></div>
                            <div class="flex flex-wrap gap-2 pt-3 mt-1 border-t border-slate-100"><dt class="font-semibold text-slate-600 min-w-[100px]">Montant total</dt><dd class="text-slate-800 font-bold">{{ number_format($reservation->montant_total, 0, ',', ' ') }} DJF</dd></div>
                        </dl>
                    </div>
                </div>
            @else
                <div class="mt-8 p-6 bg-red-50 border border-red-100 rounded-2xl text-center">
                    <p class="text-red-700 font-medium">Aucune réservation trouvée pour ce code.</p>
                    <p class="text-sm text-red-600 mt-1">Vérifiez le code et réessayez.</p>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
