@extends('layouts.admin')
@section('title', 'Hôtels')
@section('content')

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Hôtels</h1>
    <div class="flex flex-wrap items-center gap-3">
        <div class="crud-toolbar" style="margin-bottom:0">
            <form method="GET" action="{{ route('admin.hotels.index') }}" class="flex flex-wrap items-center gap-2">
                <div class="crud-search-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Nom, ville..." class="crud-search" autocomplete="off">
                </div>
                <button type="submit" class="crud-btn-submit">Rechercher</button>
                @if(request('q'))<a href="{{ route('admin.hotels.index') }}" class="crud-btn-reset">Réinitialiser</a>@endif
            </form>
        </div>
        <a href="{{ route('admin.hotels.create') }}"
           class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors min-w-[180px]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Nouvel hôtel
        </a>
    </div>
</div>

@if ($hotels->isEmpty())
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-12 text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-100 flex items-center justify-center">
            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        </div>
        <h2 class="text-lg font-semibold text-slate-700 mb-1">Aucun hôtel</h2>
        <p class="text-slate-500 text-sm">Les hôtels que vous créez apparaîtront ici.</p>
    </div>
@else
    <div class="crud-table-wrap">
        <div class="overflow-x-auto">
            <table class="crud-table" style="min-width:640px">
                <thead>
                    <tr>
                        <th>NOM</th>
                        <th>VILLE</th>
                        <th>TYPES</th>
                        <th>AVIS</th>
                        <th style="text-align:right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hotels as $hotel)
                    <tr>
                        <td class="font-semibold text-slate-800">{{ $hotel->nom }}</td>
                        <td class="text-slate-600">{{ $hotel->ville ?? '—' }}</td>
                        <td class="text-slate-600">{{ $hotel->types_chambre_count }}</td>
                        <td class="text-slate-600">{{ $hotel->avis_count }}</td>
                        <td>
                            <div class="crud-actions">
                                <a href="{{ route('hotels.show', $hotel) }}" title="Voir la fiche" class="crud-btn crud-btn-view">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('admin.hotels.images.index', $hotel) }}" title="Gérer les images" class="crud-btn crud-btn-view">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </a>
                                <a href="{{ route('admin.hotels.edit', $hotel) }}" title="Modifier" class="crud-btn crud-btn-edit">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <button type="button" title="Supprimer" data-delete-url="{{ route('admin.hotels.destroy', $hotel) }}" data-delete-label="l'hôtel {{ $hotel->nom }}" class="crud-btn crud-btn-delete">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="crud-pagination">{{ $hotels->links() }}</div>
    </div>
@endif
@endsection
