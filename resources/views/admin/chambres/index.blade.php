@extends('layouts.admin')
@section('title', 'Chambres')
@section('content')

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Chambres</h1>
    <div class="flex flex-wrap items-center gap-3">
        <div class="crud-toolbar" style="margin-bottom:0">
            <form method="GET" action="{{ route('admin.chambres.index') }}" class="flex flex-wrap items-center gap-2">
                <div class="crud-search-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Numéro, type, hôtel..." class="crud-search" autocomplete="off">
                </div>
                <select name="etat" class="crud-filter">
                    <option value="">Tous les états</option>
                    <option value="DISPONIBLE" {{ request('etat') === 'DISPONIBLE' ? 'selected' : '' }}>Disponible</option>
                    <option value="OCCUPEE" {{ request('etat') === 'OCCUPEE' ? 'selected' : '' }}>Occupée</option>
                    <option value="MAINTENANCE" {{ request('etat') === 'MAINTENANCE' ? 'selected' : '' }}>Maintenance</option>
                </select>
                <button type="submit" class="crud-btn-submit">Rechercher</button>
                @if(request()->hasAny(['q','etat']))<a href="{{ route('admin.chambres.index') }}" class="crud-btn-reset">Réinitialiser</a>@endif
            </form>
        </div>
        <a href="{{ route('admin.chambres.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors min-w-[180px] justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Nouvelle chambre
        </a>
    </div>
</div>

@if ($chambres->isEmpty())
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center">
        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        <p class="text-slate-400 text-sm">Aucune chambre enregistrée.</p>
    </div>
@else
    <div class="crud-table-wrap">
        <div class="overflow-x-auto">
            <table class="crud-table">
                <thead>
                    <tr>
                        <th>NUMÉRO</th>
                        <th>TYPE / HÔTEL</th>
                        <th>ÉTAT</th>
                        <th style="text-align:right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($chambres as $chambre)
                    <tr>
                        <td class="font-semibold text-slate-800">{{ $chambre->numero }}</td>
                        <td class="text-slate-600">{{ $chambre->typeChambre->nom_type }} — {{ $chambre->typeChambre->hotel->nom }}</td>
                        <td>
                            @if($chambre->etat === 'DISPONIBLE')
                                <span class="crud-badge crud-badge-success">Disponible</span>
                            @elseif($chambre->etat === 'OCCUPEE')
                                <span class="crud-badge crud-badge-warning">Occupée</span>
                            @else
                                <span class="crud-badge crud-badge-danger">Maintenance</span>
                            @endif
                        </td>
                        <td>
                            <div class="crud-actions">
                                <a href="{{ route('admin.chambres.edit', $chambre) }}" title="Modifier" class="crud-btn crud-btn-edit">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <button type="button" title="Supprimer" data-delete-url="{{ route('admin.chambres.destroy', $chambre) }}" data-delete-label="cette chambre" class="crud-btn crud-btn-delete">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="crud-pagination">{{ $chambres->links() }}</div>
    </div>
@endif
@endsection
