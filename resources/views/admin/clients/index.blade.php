@extends('layouts.admin')
@section('page_title', 'Gestion des Clients')
@section('title', 'Clients — DjibStay Administration')

@section('content')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
<style>
    .page-wrapper {
        font-family: 'DM Sans', sans-serif;
    }

    /* ── Card ── */
    .dash-card {
        background: #ffffff;
        border-radius: 16px;
        border: 0.5px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
    }

    .dash-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        border-bottom: 0.5px solid #edf2f7;
        flex-wrap: wrap;
        gap: 12px;
    }

    .dash-card-header h3 {
        font-size: 15px;
        font-weight: 600;
        color: #1a202c;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
    }

    .dash-card-header h3 i {
        color: #3b82f6;
        font-size: 16px;
    }

    /* ── Search ── */
    .search-form {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .search-form .form-control {
        border: 0.5px solid #cbd5e0;
        border-radius: 10px !important;
        padding: 7px 13px;
        font-size: 13px;
        width: 230px;
        background: #f7fafc;
        color: #2d3748;
        box-shadow: none !important;
        transition: border-color .15s;
    }

    .search-form .form-control:focus {
        border-color: #3b82f6;
        background: #fff;
    }

    .search-form .btn-search {
        width: 34px;
        height: 34px;
        padding: 0;
        border-radius: 10px !important;
        background: #3b82f6;
        border: none;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .search-form .btn-search:hover {
        background: #2563eb;
    }

    .search-form .btn-clear {
        width: 34px;
        height: 34px;
        padding: 0;
        border-radius: 10px !important;
        background: #fff;
        border: 0.5px solid #e2e8f0;
        color: #718096;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: background .15s;
    }

    .search-form .btn-clear:hover {
        background: #f7fafc;
    }

    /* ── Table ── */
    .dash-table {
        width: 100%;
        border-collapse: collapse;
    }

    .dash-table thead tr {
        background: #f8fafc;
        border-bottom: 0.5px solid #e2e8f0;
    }

    .dash-table th {
        padding: 11px 18px;
        font-size: 10.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: #94a3b8;
        white-space: nowrap;
    }

    .dash-table td {
        padding: 14px 18px;
        font-size: 13.5px;
        color: #2d3748;
        border-bottom: 0.5px solid #f1f5f9;
        vertical-align: middle;
    }

    .dash-table tbody tr:last-child td {
        border-bottom: none;
    }

    .dash-table tbody tr {
        transition: background .12s;
    }

    .dash-table tbody tr:hover td {
        background: #f8fafc;
    }

    /* ── Avatar ── */
    .client-avatar {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 12px;
        color: #fff;
        flex-shrink: 0;
        letter-spacing: .03em;
    }

    .client-name {
        font-weight: 600;
        font-size: 13.5px;
        color: #1a202c;
        line-height: 1.3;
    }

    .client-id {
        font-size: 11px;
        color: #b0bec5;
        margin-top: 1px;
    }

    /* ── Contact ── */
    .contact-line {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 12.5px;
        color: #4a5568;
        line-height: 1.6;
    }

    .contact-line i {
        font-size: 11px;
        color: #cbd5e0;
        width: 12px;
    }

    /* ── Badges ── */
    .badge-resa {
        display: inline-flex;
        align-items: center;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        background: #f1f5f9;
        color: #475569;
        border: 0.5px solid #e2e8f0;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 11px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-badge .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
    }

    .status-active {
        background: #e6fffa;
        color: #276749;
    }

    .status-active .dot {
        background: #38a169;
    }

    .status-suspended {
        background: #fff5f5;
        color: #c53030;
    }

    .status-suspended .dot {
        background: #e53e3e;
    }

    /* ── Date ── */
    .date-cell {
        font-size: 12.5px;
        color: #94a3b8;
        white-space: nowrap;
    }

    /* ── Action buttons ── */
    .action-btn {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        border: 0.5px solid #e2e8f0;
        background: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 13px;
        color: #64748b;
        transition: background .12s, border-color .12s, color .12s;
        padding: 0;
        text-decoration: none;
    }

    .action-btn:hover {
        background: #f1f5f9;
        color: #1a202c;
    }

    .action-btn.btn-activate {
        border-color: #86efac;
        color: #16a34a;
    }

    .action-btn.btn-activate:hover {
        background: #f0fdf4;
    }

    .action-btn.btn-suspend {
        border-color: #fcd34d;
        color: #d97706;
    }

    .action-btn.btn-suspend:hover {
        background: #fffbeb;
    }

    .action-btn.btn-delete {
        border-color: #fca5a5;
        color: #dc2626;
    }

    .action-btn.btn-delete:hover {
        background: #fef2f2;
    }

    /* ── Empty state ── */
    .empty-state {
        text-align: center;
        padding: 56px 24px;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 36px;
        display: block;
        margin-bottom: 12px;
        color: #cbd5e0;
    }

    .empty-state p {
        font-size: 14px;
        margin: 0;
    }

    /* ── Pagination wrapper ── */
    .pagination-wrap {
        padding: 14px 20px;
        border-top: 0.5px solid #f1f5f9;
        display: flex;
        justify-content: flex-end;
    }
</style>
@endpush

<div class="page-wrapper">
    <div class="dash-card fade-in-up">

        {{-- Header --}}
        <div class="dash-card-header">
            <h3>
                <i class="bi bi-people-fill"></i>
                Liste des Clients
            </h3>
            <form action="{{ route('admin.clients.index') }}" method="GET" class="search-form">
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    class="form-control"
                    placeholder="Rechercher un client…"
                >
                <button type="submit" class="btn-search" title="Rechercher">
                    <i class="bi bi-search" style="font-size:13px;"></i>
                </button>
                @if(request('q'))
                    <a href="{{ route('admin.clients.index') }}" class="btn-clear" title="Réinitialiser">
                        <i class="bi bi-x-lg" style="font-size:12px;"></i>
                    </a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Contact</th>
                        <th>Réservations</th>
                        <th>Statut</th>
                        <th>Inscrit le</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                    <tr>
                        {{-- Client --}}
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="client-avatar">
                                    {{ strtoupper(substr($client->prenom ?? $client->name, 0, 1)) }}{{ strtoupper(substr($client->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="client-name">{{ $client->prenom }} {{ $client->name }}</div>
                                    <div class="client-id">#{{ $client->id }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Contact --}}
                        <td>
                            <div class="contact-line">
                                <i class="bi bi-envelope"></i>
                                {{ $client->email }}
                            </div>
                            @if($client->phone)
                            <div class="contact-line">
                                <i class="bi bi-telephone"></i>
                                {{ $client->phone }}
                            </div>
                            @endif
                        </td>

                        {{-- Réservations --}}
                        <td>
                            <span class="badge-resa">
                                {{ $client->reservations_count }}
                                {{ Str::plural('résa', $client->reservations_count) }}.
                            </span>
                        </td>

                        {{-- Statut --}}
                        <td>
                            @if($client->is_suspended)
                                <span class="status-badge status-suspended">
                                    <span class="dot"></span> Suspendu
                                </span>
                            @else
                                <span class="status-badge status-active">
                                    <span class="dot"></span> Actif
                                </span>
                            @endif
                        </td>

                        {{-- Date --}}
                        <td class="date-cell">
                            {{ $client->created_at->format('d/m/Y') }}
                        </td>

                        {{-- Actions --}}
                        <td>
                            <div class="d-flex justify-content-end gap-2">

                                {{-- Voir --}}
                                <a href="{{ route('admin.clients.show', $client) }}"
                                   class="action-btn"
                                   title="Voir les détails">
                                    <i class="bi bi-eye"></i>
                                </a>

                                {{-- Modifier --}}
                                <a href="{{ route('admin.clients.edit', $client) }}"
                                   class="action-btn"
                                   title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                {{-- Suspendre / Activer --}}
                                <form action="{{ route('admin.clients.toggle-status', $client) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    @if($client->is_suspended)
                                        <button type="submit"
                                                class="action-btn btn-activate"
                                                title="Activer le compte">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    @else
                                        <button type="submit"
                                                class="action-btn btn-suspend"
                                                title="Suspendre le compte">
                                            <i class="bi bi-slash-circle"></i>
                                        </button>
                                    @endif
                                </form>

                                {{-- Supprimer --}}
                                <form action="{{ route('admin.clients.destroy', $client) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer ce client ? Cette action est irréversible.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="action-btn btn-delete"
                                            title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-people"></i>
                                <p>Aucun client trouvé.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($clients->hasPages())
        <div class="pagination-wrap">
            {{ $clients->appends(request()->query())->links() }}
        </div>
        @endif

    </div>
</div>
@endsection