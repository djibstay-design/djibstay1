@extends('layouts.app')

@section('title', 'Nos hôtels')

@section('content')
<h1 class="text-2xl font-semibold mb-6">Nos hôtels</h1>
@if ($hotels->isEmpty())
    <p class="text-gray-600 dark:text-gray-400">Aucun hôtel disponible.</p>
@else
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($hotels as $hotel)
            <a href="{{ route('hotels.show', $hotel) }}" class="block p-4 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg hover:bg-gray-50 dark:hover:bg-[#161615]">
                <h2 class="font-semibold text-lg">{{ $hotel->nom }}</h2>
                @if ($hotel->ville)
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $hotel->ville }}</p>
                @endif
                <p class="text-sm mt-2">{{ $hotel->types_chambre_count }} type(s) de chambre</p>
                <p class="text-sm">{{ $hotel->avis_count }} avis</p>
            </a>
        @endforeach
    </div>
    <div class="mt-6">
        {{ $hotels->links() }}
    </div>
@endif
@endsection
