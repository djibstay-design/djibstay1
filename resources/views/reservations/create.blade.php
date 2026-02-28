@extends('layouts.app')

@section('title', 'Réservation')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-semibold mb-2">Réservation</h1>
    <p class="text-gray-600 dark:text-gray-400 mb-6">
        {{ $chambre->typeChambre->hotel->nom }} - Chambre {{ $chambre->numero }} ({{ $chambre->typeChambre->nom_type }})
        - {{ number_format($chambre->typeChambre->prix_par_nuit, 0, ',', ' ') }} FCFA / nuit
    </p>

    <form action="{{ route('reservations.store') }}" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="chambre_id" value="{{ $chambre->id }}">

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="nom_client" class="block text-sm font-medium mb-1">Nom *</label>
                <input type="text" name="nom_client" id="nom_client" required value="{{ old('nom_client') }}"
                    class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
            </div>
            <div>
                <label for="prenom_client" class="block text-sm font-medium mb-1">Prénom *</label>
                <input type="text" name="prenom_client" id="prenom_client" required value="{{ old('prenom_client') }}"
                    class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="email_client" class="block text-sm font-medium mb-1">Email *</label>
                <input type="email" name="email_client" id="email_client" required value="{{ old('email_client') }}"
                    class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
            </div>
            <div>
                <label for="telephone_client" class="block text-sm font-medium mb-1">Téléphone</label>
                <input type="text" name="telephone_client" id="telephone_client" value="{{ old('telephone_client') }}"
                    class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
            </div>
        </div>

        <div>
            <label for="code_identite" class="block text-sm font-medium mb-1">Code identité / Pièce d'identité *</label>
            <input type="text" name="code_identite" id="code_identite" required value="{{ old('code_identite') }}"
                class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <div>
                <label for="date_debut" class="block text-sm font-medium mb-1">Date d'arrivée *</label>
                <input type="date" name="date_debut" id="date_debut" required value="{{ old('date_debut') }}"
                    class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
            </div>
            <div>
                <label for="date_fin" class="block text-sm font-medium mb-1">Date de départ *</label>
                <input type="date" name="date_fin" id="date_fin" required value="{{ old('date_fin') }}"
                    class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
            </div>
            <div>
                <label for="quantite" class="block text-sm font-medium mb-1">Quantité (personnes) *</label>
                <input type="number" name="quantite" id="quantite" required min="1" value="{{ old('quantite', 1) }}"
                    class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
            </div>
        </div>

        <div>
            <label for="photos" class="block text-sm font-medium mb-1">Photos (URLs séparées par des virgules)</label>
            <input type="text" name="photos" id="photos" value="{{ old('photos') }}"
                placeholder="https://exemple.com/photo1.jpg, https://exemple.com/photo2.jpg"
                class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
        </div>

        <button type="submit" class="px-6 py-2 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] rounded font-medium">
            Confirmer la réservation
        </button>
    </form>
</div>
@endsection
