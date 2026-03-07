@extends('layouts.admin')
@section('title', 'Types de chambre')
@section('content')

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Types de chambre</h1>
    <div class="flex flex-wrap items-center gap-3">
        <div class="crud-toolbar" style="margin-bottom:0">
            <form method="GET" action="{{ route('admin.types-chambre.index') }}" class="flex flex-wrap items-center gap-2">
                <div class="crud-search-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Type, hôtel..." class="crud-search" autocomplete="off">
                </div>
                <button type="submit" class="crud-btn-submit">Rechercher</button>
                @if(request('q'))<a href="{{ route('admin.types-chambre.index') }}" class="crud-btn-reset">Réinitialiser</a>@endif
            </form>
        </div>
        <a href="{{ route('admin.types-chambre.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors min-w-[160px] justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Nouveau type
        </a>
    </div>
</div>

@if ($typesChambre->isEmpty())
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center">
        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
        <p class="text-slate-400 text-sm">Aucun type de chambre enregistré.</p>
    </div>
@else
    <div class="crud-table-wrap">
        <div class="overflow-x-auto">
            <table class="crud-table">
                <thead>
                    <tr>
                        <th>TYPE</th>
                        <th>HÔTEL</th>
                        <th>CAPACITÉ</th>
                        <th>PRIX / NUIT</th>
                        <th style="text-align:right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($typesChambre as $type)
                    <tr>
                        <td class="font-semibold text-slate-800">{{ $type->nom_type }}</td>
                        <td class="text-slate-600">{{ $type->hotel->nom }}</td>
                        <td class="text-slate-600">{{ $type->capacite }} pers.</td>
                        <td class="font-medium text-slate-800">{{ number_format($type->prix_par_nuit, 0, ',', ' ') }} DJF</td>
                        <td>
                            <div class="crud-actions">
                                <a href="{{ route('admin.types-chambre.edit', $type) }}" title="Modifier" class="crud-btn crud-btn-edit">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <button type="button" title="Supprimer" data-delete-url="{{ route('admin.types-chambre.destroy', $type) }}" data-delete-label="ce type de chambre ({{ $type->nom_type }})" class="crud-btn crud-btn-delete">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="crud-pagination">{{ $typesChambre->links() }}</div>
    </div>
@endif
@endsection
