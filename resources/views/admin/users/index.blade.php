@extends('layouts.admin')
@section('title', 'Utilisateurs')
@section('content')

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Administrateurs</h1>
    <div class="flex flex-wrap items-center gap-3">
        <div class="crud-toolbar" style="margin-bottom:0">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-center gap-2">
                <div class="crud-search-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Nom, email..." class="crud-search" autocomplete="off">
                </div>
                <select name="role" class="crud-filter">
                    <option value="">Tous les rôles</option>
                    <option value="SUPER_ADMIN" {{ request('role') === 'SUPER_ADMIN' ? 'selected' : '' }}>Super Admin</option>
                    <option value="ADMIN" {{ request('role') === 'ADMIN' ? 'selected' : '' }}>Admin</option>
                </select>
                <button type="submit" class="crud-btn-submit">Rechercher</button>
                @if(request()->hasAny(['q','role']))<a href="{{ route('admin.users.index') }}" class="crud-btn-reset">Réinitialiser</a>@endif
            </form>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-md transition-colors min-w-[200px]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Nouvel utilisateur
        </a>
    </div>
</div>

@if (session('error'))
    <div class="bg-red-50 text-red-700 border border-red-100 px-4 py-3 rounded-xl mb-5 text-sm">{{ session('error') }}</div>
@endif

<div class="crud-table-wrap">
    <div class="overflow-x-auto">
        <table class="crud-table">
            <thead>
                <tr>
                    <th>NOM</th>
                    <th>EMAIL</th>
                    <th>RÔLE</th>
                    <th style="text-align:right">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                            </div>
                            <span class="font-semibold text-slate-800">{{ $user->prenom }} {{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="text-slate-600">{{ $user->email }}</td>
                    <td>
                        @if($user->role === 'SUPER_ADMIN')
                            <span class="crud-badge crud-badge-info">Super Admin</span>
                        @else
                            <span class="crud-badge crud-badge-neutral">Admin</span>
                        @endif
                    </td>
                    <td>
                        <div class="crud-actions">
                            <a href="{{ route('admin.users.edit', $user) }}" title="Modifier" class="crud-btn crud-btn-edit">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            @if ($user->id !== auth()->id())
                                <button type="button" title="Supprimer" data-delete-url="{{ route('admin.users.destroy', $user) }}" data-delete-label="cet utilisateur" class="crud-btn crud-btn-delete">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            @else
                                <span class="crud-btn" style="background:#f1f5f9;color:#94a3b8;cursor:default" title="Vous ne pouvez pas vous supprimer">
                                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="crud-pagination">{{ $users->links() }}</div>
</div>
@endsection
