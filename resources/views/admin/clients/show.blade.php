@extends('layouts.admin')
@section('page_title', 'Détails du Client')
@section('title', 'Détails Client — DjibStay Administration')

@section('content')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    .show-wrapper {
        font-family: 'DM Sans', sans-serif;
    }

    /* ── Cards ── */
    .show-card {
        background: #fff;
        border-radius: 16px;
        border: 0.5px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,.04);
        height: 100%;
    }

    .show-card-header {
        padding: 18px 24px;
        border-bottom: 0.5px solid #edf2f7;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .show-card-header h3 {
        font-size: 14px;
        font-weight: 600;
        color: #1a202c;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .show-card-header h3 i {
        color: #6366f1;
        font-size: 15px;
    }

    /* ── Profile section ── */
    .profile-section {
        padding: 28px 24px 20px;
        text-align: center;
        border-bottom: 0.5px solid #f1f5f9;
    }

    .profile-avatar {
        width: 72px;
        height: 72px;
        border-radius: 18px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 22px;
        color: #fff;
        margin: 0 auto 14px;
        letter-spacing: .02em;
    }

    .profile-name {
        font-size: 16px;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 6px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-badge .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .status-active   { background: #e6fffa; color: #276749; }
    .status-active .dot { background: #38a169; }
    .status-suspended { background: #fff5f5; color: #c53030; }
    .status-suspended .dot { background: #e53e3e; }

    /* ── Info list ── */
    .info-list {
        padding: 0 24px;
    }

    .info-item {
        padding: 14px 0;
        border-bottom: 0.5px solid #f1f5f9;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-size: 10.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: #94a3b8;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 13.5px;
        font-weight: 500;
        color: #1a202c;
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .info-value i {
        font-size: 13px;
        color: #94a3b8;
    }

    .info-value.muted {
        color: #94a3b8;
        font-weight: 400;
        font-style: italic;
    }

    /* ── Action buttons ── */
    .profile-actions {
        padding: 20px 24px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        border-top: 0.5px solid #f1f5f9;
    }

    .btn-edit {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 10px;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: #fff;
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: opacity .15s, transform .1s;
        font-family: 'DM Sans', sans-serif;
    }

    .btn-edit:hover {
        opacity: .9;
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        transition: background .15s, transform .1s;
        font-family: 'DM Sans', sans-serif;
        width: 100%;
        border: 0.5px solid;
    }

    .btn-toggle:hover { transform: translateY(-1px); }

    .btn-toggle-activate {
        background: #f0fdf4;
        color: #16a34a;
        border-color: #86efac;
    }

    .btn-toggle-activate:hover { background: #dcfce7; }

    .btn-toggle-suspend {
        background: #fffbeb;
        color: #d97706;
        border-color: #fcd34d;
    }

    .btn-toggle-suspend:hover { background: #fef3c7; }

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

    .dash-table tbody tr:last-child td { border-bottom: none; }
    .dash-table tbody tr { transition: background .12s; }
    .dash-table tbody tr:hover td { background: #f8fafc; }

    /* ── Reservation badges ── */
    .resa-ref {
        font-weight: 700;
        font-size: 13px;
        color: #6366f1;
    }

    .hotel-name {
        font-weight: 600;
        font-size: 13px;
        color: #1a202c;
    }

    .room-type {
        font-size: 11.5px;
        color: #94a3b8;
        margin-top: 2px;
    }

    .date-range {
        font-size: 12px;
        color: #64748b;
        line-height: 1.7;
    }

    .date-range span {
        display: block;
    }

    .price-cell {
        font-weight: 600;
        font-size: 13px;
        color: #1a202c;
        white-space: nowrap;
    }

    .resa-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 500;
        white-space: nowrap;
    }

    .resa-badge .dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .badge-confirmed  { background: #e6fffa; color: #276749; }
    .badge-confirmed .dot  { background: #38a169; }
    .badge-pending    { background: #fffbeb; color: #92400e; }
    .badge-pending .dot    { background: #d97706; }
    .badge-cancelled  { background: #fff5f5; color: #c53030; }
    .badge-cancelled .dot  { background: #e53e3e; }
    .badge-default    { background: #f1f5f9; color: #475569; }
    .badge-default .dot    { background: #94a3b8; }

    /* ── Empty state ── */
    .empty-state {
        text-align: center;
        padding: 48px 24px;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 34px;
        display: block;
        margin-bottom: 10px;
        color: #cbd5e0;
    }

    .empty-state p {
        font-size: 13.5px;
        margin: 0;
    }
</style>
@endpush

<div class="show-wrapper fade-in-up">
    <div class="row g-4">

        {{-- ── Colonne gauche : profil ── --}}
        <div class="col-md-4">
            <div class="show-card">

                <div class="show-card-header">
                    <h3><i class="bi bi-person-circle"></i> Informations Personnelles</h3>
                </div>

                {{-- Avatar + nom + statut --}}
                <div class="profile-section">
                    <div class="profile-avatar">
                        {{ strtoupper(substr($client->prenom ?? $client->name, 0, 1)) }}{{ strtoupper(substr($client->name, 0, 1)) }}
                    </div>
                    <div class="profile-name">{{ $client->prenom }} {{ $client->name }}</div>
                    @if($client->is_suspended)
                        <span class="status-badge status-suspended">
                            <span class="dot"></span> Compte Suspendu
                        </span>
                    @else
                        <span class="status-badge status-active">
                            <span class="dot"></span> Compte Actif
                        </span>
                    @endif
                </div>

                {{-- Infos --}}
                <div class="info-list">
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">
                            <i class="bi bi-envelope"></i>
                            {{ $client->email }}
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Téléphone</div>
                        <div class="info-value {{ $client->phone ? '' : 'muted' }}">
                            <i class="bi bi-telephone"></i>
                            {{ $client->phone ?: 'Non renseigné' }}
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Inscrit le</div>
                        <div class="info-value">
                            <i class="bi bi-calendar3"></i>
                            {{ $client->created_at->format('d F Y') }}
                            <span style="color:#94a3b8;font-weight:400;font-size:12px;">
                                à {{ $client->created_at->format('H:i') }}
                            </span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Réservations</div>
                        <div class="info-value">
                            <i class="bi bi-calendar-check"></i>
                            {{ $client->reservations->count() }} au total
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="profile-actions">
                    <a href="{{ route('admin.clients.edit', $client) }}" class="btn-edit">
                        <i class="bi bi-pencil"></i> Modifier les informations
                    </a>
                    <form action="{{ route('admin.clients.toggle-status', $client) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        @if($client->is_suspended)
                            <button type="submit" class="btn-toggle btn-toggle-activate">
                                <i class="bi bi-check-circle"></i> Réactiver le compte
                            </button>
                        @else
                            <button type="submit" class="btn-toggle btn-toggle-suspend">
                                <i class="bi bi-slash-circle"></i> Suspendre le compte
                            </button>
                        @endif
                    </form>
                </div>

            </div>
        </div>

        {{-- ── Colonne droite : réservations ── --}}
        <div class="col-md-8">
            <div class="show-card">

                <div class="show-card-header">
                    <h3>
                        <i class="bi bi-calendar-check"></i>
                        Historique des Réservations
                        <span style="margin-left:6px;background:#f1f5f9;color:#475569;border:0.5px solid #e2e8f0;border-radius:20px;padding:2px 10px;font-size:11.5px;font-weight:500;">
                            {{ $client->reservations->count() }}
                        </span>
                    </h3>
                </div>

                @php
                    $now = \Carbon\Carbon::now();
                    $activeReservations = $client->reservations->filter(fn($r) => \Carbon\Carbon::parse($r->date_fin)->isFuture() || $r->statut === 'EN_ATTENTE');
                    $pastReservations = $client->reservations->filter(fn($r) => \Carbon\Carbon::parse($r->date_fin)->isPast() && $r->statut !== 'EN_ATTENTE');
                @endphp

                <div class="table-responsive">
                    <div class="px-4 py-2 bg-light border-bottom">
                        <span class="fw-bold text-primary small text-uppercase">🕒 En cours ou à venir</span>
                    </div>
                    <table class="dash-table">
                        <thead>
                            <tr>
                                <th>Réf</th>
                                <th>Hôtel / Chambre</th>
                                <th>Dates</th>
                                <th>Prix</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeReservations as $reservation)
                            <tr>
                                <td><span class="resa-ref">#{{ $reservation->id }}</span></td>
                                <td>
                                    <div class="hotel-name">{{ $reservation->chambre->typeChambre->hotel->nom }}</div>
                                    <div class="room-type">{{ $reservation->chambre->typeChambre->nom_type }}</div>
                                </td>
                                <td>
                                    <div class="date-range">
                                        <span>Du {{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y') }}</span>
                                        <span>Au {{ \Carbon\Carbon::parse($reservation->date_fin)->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td><span class="price-cell">{{ number_format($reservation->prix_total, 0, ',', ' ') }} FDJ</span></td>
                                <td>
                                    @php
                                        $badgeClass = match($reservation->statut) {
                                            'CONFIRMEE'  => 'badge-confirmed',
                                            'EN_ATTENTE' => 'badge-pending',
                                            'ANNULEE'    => 'badge-cancelled',
                                            default      => 'badge-default',
                                        };
                                        $label = match($reservation->statut) {
                                            'CONFIRMEE'  => 'Confirmée',
                                            'EN_ATTENTE' => 'En attente',
                                            'ANNULEE'    => 'Annulée',
                                            default      => $reservation->statut,
                                        };
                                    @endphp
                                    <span class="resa-badge {{ $badgeClass }}">
                                        <span class="dot"></span>
                                        {{ $label }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted small">Aucune réservation active.</td></tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="px-4 py-2 bg-light border-top border-bottom">
                        <span class="fw-bold text-secondary small text-uppercase">✅ Séjours passés</span>
                    </div>
                    <table class="dash-table">
                        <tbody>
                            @forelse($pastReservations as $reservation)
                            <tr style="opacity: 0.8;">
                                <td><span class="resa-ref">#{{ $reservation->id }}</span></td>
                                <td>
                                    <div class="hotel-name">{{ $reservation->chambre->typeChambre->hotel->nom }}</div>
                                    <div class="room-type">{{ $reservation->chambre->typeChambre->nom_type }}</div>
                                </td>
                                <td>
                                    <div class="date-range">
                                        <span>Du {{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y') }}</span>
                                        <span>Au {{ \Carbon\Carbon::parse($reservation->date_fin)->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td><span class="price-cell">{{ number_format($reservation->prix_total, 0, ',', ' ') }} FDJ</span></td>
                                <td>
                                    <span class="resa-badge badge-default">
                                        <span class="dot"></span> Terminé
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted small">Aucun historique de séjour passé.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection