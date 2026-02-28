@extends('layouts.admin')

@section('title', 'Réservations')

@section('content')
<h1 class="text-2xl font-semibold mb-6">Réservations</h1>

@if ($reservations->isEmpty())
    <p class="text-gray-600 dark:text-gray-400">Aucune réservation.</p>
@else
    <div class="overflow-x-auto">
        <table class="w-full border border-[#e3e3e0] dark:border-[#3E3E3A]">
            <thead class="bg-gray-100 dark:bg-[#3E3E3A]">
                <tr>
                    <th class="px-4 py-2 text-left">Code</th>
                    <th class="px-4 py-2 text-left">Client</th>
                    <th class="px-4 py-2 text-left">Chambre / Hôtel</th>
                    <th class="px-4 py-2 text-left">Dates</th>
                    <th class="px-4 py-2 text-left">Statut</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservations as $r)
                    <tr class="border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                        <td class="px-4 py-2 font-mono">{{ $r->code_reservation }}</td>
                        <td class="px-4 py-2">{{ $r->prenom_client }} {{ $r->nom_client }}</td>
                        <td class="px-4 py-2">{{ $r->chambre->numero }} - {{ $r->chambre->typeChambre->hotel->nom }}</td>
                        <td class="px-4 py-2">{{ $r->date_debut->format('d/m/Y') }} - {{ $r->date_fin->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-sm
                                @if($r->statut === 'CONFIRMEE') bg-green-100 dark:bg-green-900/30
                                @elseif($r->statut === 'ANNULEE') bg-red-100 dark:bg-red-900/30
                                @else bg-yellow-100 dark:bg-yellow-900/30 @endif">
                                {{ $r->statut }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.reservations.show', $r) }}" class="text-sm underline">Détails</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $reservations->links() }}</div>
@endif
@endsection
