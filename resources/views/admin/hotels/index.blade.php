@extends('layouts.admin')

@section('title', 'Hôtels')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold">Hôtels</h1>
    <a href="{{ route('admin.hotels.create') }}" class="px-4 py-2 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] rounded font-medium">Nouvel hôtel</a>
</div>

@if ($hotels->isEmpty())
    <p class="text-gray-600 dark:text-gray-400">Aucun hôtel.</p>
@else
    <div class="overflow-x-auto">
        <table class="w-full border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <thead class="bg-gray-100 dark:bg-[#3E3E3A]">
                <tr>
                    <th class="px-4 py-2 text-left">Nom</th>
                    <th class="px-4 py-2 text-left">Ville</th>
                    <th class="px-4 py-2 text-left">Types</th>
                    <th class="px-4 py-2 text-left">Avis</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($hotels as $hotel)
                    <tr class="border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                        <td class="px-4 py-2">{{ $hotel->nom }}</td>
                        <td class="px-4 py-2">{{ $hotel->ville ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $hotel->types_chambre_count }}</td>
                        <td class="px-4 py-2">{{ $hotel->avis_count }}</td>
                        <td class="px-4 py-2 flex gap-2">
                            <a href="{{ route('hotels.show', $hotel) }}" class="text-sm underline">Voir</a>
                            <a href="{{ route('admin.hotels.edit', $hotel) }}" class="text-sm underline">Modifier</a>
                            <form action="{{ route('admin.hotels.destroy', $hotel) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cet hôtel ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $hotels->links() }}</div>
@endif
@endsection
