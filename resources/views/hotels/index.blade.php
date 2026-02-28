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

@if ($avis->isNotEmpty())
    <section class="mt-12 pt-8 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
        <h2 class="text-xl font-semibold mb-4">Avis des clients</h2>
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($avis as $a)
                <div class="p-4 bg-gray-50 dark:bg-[#161615] rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <p class="text-yellow-500">{{ str_repeat('★', $a->note) }}{{ str_repeat('☆', 5 - $a->note) }}</p>
                    <p class="font-medium mt-1">{{ $a->nom_client }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $a->hotel->nom }}</p>
                    @if ($a->commentaire)
                        <p class="text-sm mt-2">{{ Str::limit($a->commentaire, 100) }}</p>
                    @endif
                    <p class="text-xs text-gray-500 mt-2">{{ $a->date_avis->format('d/m/Y') }}</p>
                </div>
            @endforeach
        </div>
    </section>
@endif
@endsection
