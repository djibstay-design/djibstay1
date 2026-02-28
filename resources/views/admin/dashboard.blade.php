@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
<h1 class="text-2xl font-semibold mb-6">Tableau de bord</h1>

<div class="grid gap-4 md:grid-cols-2 mb-8">
    <div class="p-4 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
        <p class="text-gray-600 dark:text-gray-400">Nombre d'hôtels</p>
        <p class="text-2xl font-semibold">{{ $hotels->count() }}</p>
    </div>
    <div class="p-4 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
        <p class="text-gray-600 dark:text-gray-400">Réservations totales</p>
        <p class="text-2xl font-semibold">{{ $reservationsCount }}</p>
    </div>
</div>

<h2 class="text-xl font-semibold mb-4">Mes hôtels</h2>
@if ($hotels->isEmpty())
    <p class="text-gray-600 dark:text-gray-400">Aucun hôtel. <a href="{{ route('admin.hotels.create') }}" class="underline">Créer un hôtel</a></p>
@else
    <div class="space-y-2">
        @foreach ($hotels as $hotel)
            <a href="{{ route('admin.hotels.edit', $hotel) }}" class="block p-4 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded hover:bg-gray-50 dark:hover:bg-[#161615]">
                <span class="font-medium">{{ $hotel->nom }}</span>
                <span class="text-sm text-gray-600 dark:text-gray-400 ml-2">{{ $hotel->types_chambre_count }} types, {{ $hotel->avis_count }} avis</span>
            </a>
        @endforeach
    </div>
@endif
@endsection
