@extends('layouts.app')

@section('title', $hotel->nom)

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-semibold">{{ $hotel->nom }}</h1>
    @if ($hotel->adresse || $hotel->ville)
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $hotel->adresse }} {{ $hotel->ville }}</p>
    @endif
    @if ($hotel->description)
        <p class="mt-4">{{ $hotel->description }}</p>
    @endif
</div>

<h2 class="text-xl font-semibold mb-4">Types de chambres</h2>
@forelse ($hotel->typesChambre as $type)
    <div class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg p-4 mb-4">
        <div class="flex justify-between items-start flex-wrap gap-2">
            <div>
                <h3 class="font-semibold">{{ $type->nom_type }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Capacité : {{ $type->capacite }} personne(s)</p>
                <p class="font-medium mt-1">{{ number_format($type->prix_par_nuit, 0, ',', ' ') }} FCFA / nuit</p>
                @if ($type->description)
                    <p class="text-sm mt-2">{{ $type->description }}</p>
                @endif
            </div>
            @php $dispos = $type->chambres->where('etat', 'DISPONIBLE'); @endphp
            @if ($dispos->isNotEmpty())
                <form action="{{ route('reservations.create') }}" method="GET" class="flex flex-col gap-2">
                    <input type="hidden" name="chambre_id" value="{{ $dispos->first()->id }}">
                    <button type="submit" class="px-4 py-2 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] rounded text-sm font-medium">
                        Réserver
                    </button>
                </form>
            @else
                <span class="text-sm text-gray-500">Indisponible</span>
            @endif
        </div>
    </div>
@empty
    <p class="text-gray-600 dark:text-gray-400">Aucun type de chambre.</p>
@endforelse

<hr class="my-8 border-[#e3e3e0] dark:border-[#3E3E3A]">

<h2 class="text-xl font-semibold mb-4">Laisser un avis</h2>
<form action="{{ route('avis.store') }}" method="POST" class="max-w-xl space-y-4">
    @csrf
    <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="nom_client" class="block text-sm font-medium mb-1">Nom</label>
            <input type="text" name="nom_client" id="nom_client" required value="{{ old('nom_client') }}"
                class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
        </div>
        <div>
            <label for="email_client" class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email_client" id="email_client" required value="{{ old('email_client') }}"
                class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
        </div>
    </div>
    <div>
        <label for="note" class="block text-sm font-medium mb-1">Note (1-5)</label>
        <select name="note" id="note" required class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
            @for ($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}" {{ old('note') == $i ? 'selected' : '' }}>{{ $i }} étoile(s)</option>
            @endfor
        </select>
    </div>
    <div>
        <label for="commentaire" class="block text-sm font-medium mb-1">Commentaire</label>
        <textarea name="commentaire" id="commentaire" rows="3" class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">{{ old('commentaire') }}</textarea>
    </div>
    <button type="submit" class="px-4 py-2 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] rounded font-medium">Envoyer l'avis</button>
</form>

@if ($hotel->avis->isNotEmpty())
    <h2 class="text-xl font-semibold mt-8 mb-4">Avis des clients</h2>
    <div class="space-y-3">
        @foreach ($hotel->avis->take(10) as $avis)
            <div class="p-3 bg-gray-50 dark:bg-[#161615] rounded">
                <p class="font-medium">{{ $avis->nom_client }}</p>
                <p class="text-yellow-500">{{ str_repeat('★', $avis->note) }}{{ str_repeat('☆', 5 - $avis->note) }}</p>
                @if ($avis->commentaire)
                    <p class="text-sm mt-1">{{ $avis->commentaire }}</p>
                @endif
            </div>
        @endforeach
    </div>
@endif
@endsection
