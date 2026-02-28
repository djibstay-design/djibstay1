@extends('layouts.admin')

@section('title', 'Types de chambre')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold">Types de chambre</h1>
    <a href="{{ route('admin.types-chambre.create') }}" class="px-4 py-2 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] rounded font-medium">Nouveau type</a>
</div>

@if ($typesChambre->isEmpty())
    <p class="text-gray-600 dark:text-gray-400">Aucun type de chambre.</p>
@else
    <div class="overflow-x-auto">
        <table class="w-full border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <thead class="bg-gray-100 dark:bg-[#3E3E3A]">
                <tr>
                    <th class="px-4 py-2 text-left">Type</th>
                    <th class="px-4 py-2 text-left">Hôtel</th>
                    <th class="px-4 py-2 text-left">Capacité</th>
                    <th class="px-4 py-2 text-left">Prix/nuit</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($typesChambre as $type)
                    <tr class="border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                        <td class="px-4 py-2">{{ $type->nom_type }}</td>
                        <td class="px-4 py-2">{{ $type->hotel->nom }}</td>
                        <td class="px-4 py-2">{{ $type->capacite }}</td>
                        <td class="px-4 py-2">{{ number_format($type->prix_par_nuit, 0, ',', ' ') }} FCFA</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.types-chambre.edit', $type) }}" class="text-sm underline">Modifier</a>
                            <form action="{{ route('admin.types-chambre.destroy', $type) }}" method="POST" class="inline ml-2" onsubmit="return confirm('Supprimer ce type ?')">
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
    <div class="mt-4">{{ $typesChambre->links() }}</div>
@endif
@endsection
