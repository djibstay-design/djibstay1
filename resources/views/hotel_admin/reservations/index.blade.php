@extends('layouts.hotel_admin')
@section('page_title', 'Réservations')
@section('title', 'Réservations')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h1 class="page-title">📋 Réservations</h1>
        <p class="page-sub">{{ $hotel->nom }} · {{ $reservations->total() }} réservation(s)</p>
    </div>
</div>

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;">
    <div class="card-admin p-3 text-center">
        <div style="font-size:22px;font-weight:900;color:#003580;">{{ $stats['total'] }}</div>
        <div style="font-size:11px;color:#64748b;font-weight:600;margin-top:2px;">Total</div>
    </div>
    <div class="card-admin p-3 text-center">
        <div style="font-size:22px;font-weight:900;color:#16a34a;">{{ $stats['confirmee'] }}</div>
        <div style="font-size:11px;color:#64748b;font-weight:600;margin-top:2px;">✅ Confirmées</div>
    </div>
    <div class="card-admin p-3 text-center">
        <div style="font-size:22px;font-weight:900;color:#f59e0b;">{{ $stats['en_attente'] }}</div>
        <div style="font-size:11px;color:#64748b;font-weight:600;margin-top:2px;">⏳ En attente</div>
    </div>
    <div class="card-admin p-3 text-center">
        <div style="font-size:22px;font-weight:900;color:#dc2626;">{{ $stats['annulee'] }}</div>
        <div style="font-size:11px;color:#64748b;font-weight:600;margin-top:2px;">❌ Annulées</div>
    </div>
</div>

{{-- Filtres --}}
<div class="card-admin p-3 mb-4">
    <form method="GET" action="{{ route('hoteladmin.reservations.index') }}">
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Code, nom, email..."
                   style="flex:1;min-width:200px;border:2px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;">
            <select name="statut"
                    style="border:2px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;font-weight:600;color:#003580;">
                <option value="">Tous les statuts</option>
                <option value="EN_ATTENTE" {{ request('statut') === 'EN_ATTENTE' ? 'selected' : '' }}>⏳ En attente</option>
                <option value="CONFIRMEE"  {{ request('statut') === 'CONFIRMEE'  ? 'selected' : '' }}>✅ Confirmées</option>
                <option value="ANNULEE"    {{ request('statut') === 'ANNULEE'    ? 'selected' : '' }}>❌ Annulées</option>
            </select>
            <button type="submit" class="btn-ha-primary" style="padding:8px 18px;">
                <i class="bi bi-search"></i> Filtrer
            </button>
            @if(request('search') || request('statut'))
            <a href="{{ route('hoteladmin.reservations.index') }}" class="btn-ha-outline" style="padding:8px 14px;">
                <i class="bi bi-x"></i>
            </a>
            @endif
        </div>
    </form>
</div>

{{-- Table --}}
<div class="card-admin overflow-hidden">
    <table class="ha-table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Client</th>
                <th>Chambre</th>
                <th>Arrivée</th>
                <th>Départ</th>
                <th>Montant</th>
                <th>Statut</th>
                <th style="text-align:center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservations as $res)
            <tr>
                <td>
                    <span style="font-family:monospace;font-size:11px;color:#003580;font-weight:700;">
                        {{ $res->code_reservation }}
                    </span>
                </td>
                <td>
                    <div style="font-weight:700;">{{ $res->prenom_client }} {{ $res->nom_client }}</div>
                    <div style="font-size:11px;color:#94a3b8;">{{ $res->email_client }}</div>
                </td>
                <td style="font-size:12px;">
                    {{ $res->chambre->typeChambre->nom_type ?? '—' }}<br>
                    <span style="color:#94a3b8;">N° {{ $res->chambre->numero }}</span>
                </td>
                <td style="font-weight:700;color:#003580;">{{ $res->date_debut->format('d/m/Y') }}</td>
                <td style="color:#64748b;">{{ $res->date_fin->format('d/m/Y') }}</td>
                <td style="font-weight:700;color:#003580;">{{ number_format($res->montant_total,0,',',' ') }} DJF</td>
                <td>
                    <span class="badge-{{ strtolower($res->statut) }}">
                        @if($res->statut==='CONFIRMEE') ✅ Confirmée
                        @elseif($res->statut==='EN_ATTENTE') ⏳ En attente
                        @else ❌ Annulée
                        @endif
                    </span>
                </td>
                <td>
                    <div style="display:flex;gap:6px;justify-content:center;">
                        <a href="{{ route('hoteladmin.reservations.show', $res) }}"
                           style="background:#dbeafe;color:#1e40af;padding:5px 10px;border-radius:6px;text-decoration:none;font-size:12px;font-weight:700;display:inline-flex;align-items:center;gap:4px;">
                            <i class="bi bi-eye"></i> Voir
                        </a>
                        @if($res->statut === 'EN_ATTENTE')
                        <form method="POST" action="{{ route('hoteladmin.reservations.statut', $res) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="statut" value="CONFIRMEE">
                            <button type="submit"
                                    style="background:#dcfce7;color:#14532d;padding:5px 10px;border-radius:6px;border:none;font-size:12px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:4px;">
                                <i class="bi bi-check"></i> Confirmer
                            </button>
                        </form>
                        <form method="POST" action="{{ route('hoteladmin.reservations.statut', $res) }}"
                              onsubmit="return confirm('Annuler cette réservation ?')">
                            @csrf @method('PATCH')
                            <input type="hidden" name="statut" value="ANNULEE">
                            <button type="submit"
                                    style="background:#fee2e2;color:#991b1b;padding:5px 10px;border-radius:6px;border:none;font-size:12px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:4px;">
                                <i class="bi bi-x"></i> Annuler
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center;padding:48px;color:#94a3b8;">
                    <div style="font-size:40px;margin-bottom:12px;">📋</div>
                    <div style="font-weight:700;">Aucune réservation</div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($reservations->hasPages())
<div class="d-flex justify-content-center mt-4">{{ $reservations->links() }}</div>
@endif
@endsection