@extends('layouts.admin')
@section('title', 'Réservations')
@section('content')

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Réservations</h1>
    <div class="crud-toolbar">
        <form method="GET" action="{{ route('admin.reservations.index') }}" class="flex flex-wrap items-center gap-2">
            <div class="crud-search-wrap">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Code, client..." class="crud-search" autocomplete="off">
            </div>
            <select name="statut" class="crud-filter">
                <option value="">Tous les statuts</option>
                <option value="CONFIRMEE" {{ request('statut') === 'CONFIRMEE' ? 'selected' : '' }}>Confirmée</option>
                <option value="EN_ATTENTE" {{ request('statut') === 'EN_ATTENTE' ? 'selected' : '' }}>En attente</option>
                <option value="ANNULEE" {{ request('statut') === 'ANNULEE' ? 'selected' : '' }}>Annulée</option>
            </select>
            <button type="submit" class="crud-btn-submit">Rechercher</button>
            @if(request()->hasAny(['q','statut']))<a href="{{ route('admin.reservations.index') }}" class="crud-btn-reset">Réinitialiser</a>@endif
        </form>
    </div>
</div>

@if ($reservations->isEmpty())
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-12 text-center max-w-md mx-auto">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-100 flex items-center justify-center">
            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <h2 class="text-lg font-semibold text-slate-700 mb-1">Aucune réservation</h2>
        <p class="text-slate-500 text-sm">Les réservations apparaîtront ici lorsque des clients effectueront une réservation.</p>
    </div>
@else
    <div class="crud-table-wrap">
        <div class="overflow-x-auto">
            <table class="crud-table" style="min-width:700px">
                <thead>
                    <tr>
                        <th>CODE</th>
                        <th>CLIENT</th>
                        <th>CHAMBRE / HÔTEL</th>
                        <th>DATES</th>
                        <th>STATUT</th>
                        <th style="text-align:right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservations as $r)
                    <tr class="{{ $r->statut === 'EN_ATTENTE' ? 'crud-row-warning' : '' }}">
                        <td class="font-mono text-xs font-semibold text-slate-800">{{ $r->code_reservation }}</td>
                        <td class="font-medium text-slate-700">{{ $r->prenom_client }} {{ $r->nom_client }}</td>
                        <td class="text-slate-600">N°{{ $r->chambre->numero }} — {{ $r->chambre->typeChambre->hotel->nom }}</td>
                        <td class="text-slate-600">{{ $r->date_debut->format('d/m/Y') }} - {{ $r->date_fin->format('d/m/Y') }}</td>
                        <td>
                            @if($r->statut === 'CONFIRMEE')
                                <span class="crud-badge crud-badge-success">Confirmée</span>
                            @elseif($r->statut === 'ANNULEE')
                                <span class="crud-badge crud-badge-danger">Annulée</span>
                            @else
                                <span class="crud-badge crud-badge-warning">En attente</span>
                            @endif
                        </td>
                        <td>
                            <div class="crud-actions">
                                <a href="{{ route('admin.reservations.show', $r) }}" title="Voir" class="crud-btn crud-btn-view">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('admin.reservations.edit', $r) }}" title="Modifier" class="crud-btn crud-btn-edit">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <button type="button" title="Supprimer" data-delete-url="{{ route('admin.reservations.destroy', $r) }}" data-delete-label="la réservation {{ $r->code_reservation }}" class="crud-btn crud-btn-delete">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="crud-pagination">{{ $reservations->links() }}</div>
    </div>
@endif
@endsection
