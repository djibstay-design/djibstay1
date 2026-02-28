@extends('layouts.admin')

@section('title', 'Chambres')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold">Chambres</h1>
    <a href="{{ route('admin.chambres.create') }}" class="px-4 py-2 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] rounded font-medium">Nouvelle chambre</a>
</div>

@if ($chambres->isEmpty())
    <p class="text-gray-600 dark:text-gray-400">Aucune chambre.</p>
@else
    <div class="overflow-x-auto">
        <table class="w-full border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <thead class="bg-gray-100 dark:bg-[#3E3E3A]">
                <tr>
                    <th class="px-4 py-2 text-left">Numéro</th>
                    <th class="px-4 py-2 text-left">Type / Hôtel</th>
                    <th class="px-4 py-2 text-left">État</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($chambres as $chambre)
                    <tr class="border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                        <td class="px-4 py-2">{{ $chambre->numero }}</td>
                        <td class="px-4 py-2">{{ $chambre->typeChambre->nom_type }} - {{ $chambre->typeChambre->hotel->nom }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-sm
                                @if($chambre->etat === 'DISPONIBLE') bg-green-100 dark:bg-green-900/30
                                @elseif($chambre->etat === 'OCCUPEE') bg-yellow-100 dark:bg-yellow-900/30
                                @else bg-red-100 dark:bg-red-900/30 @endif">
                                {{ $chambre->etat }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.chambres.edit', $chambre) }}" class="text-sm underline">Modifier</a>
                            <form action="{{ route('admin.chambres.destroy', $chambre) }}" method="POST" class="inline ml-2" onsubmit="return confirm('Supprimer cette chambre ?')">
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
    <div class="mt-4">{{ $chambres->links() }}</div>
@endif
@endsection
