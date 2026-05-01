@extends('layouts.admin')
@section('page_title', 'Réservations')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
    <div>
        <h1 style="font-size:22px;font-weight:900;color:#1e293b;margin:0;">📋 Réservations</h1>
        <p style="font-size:13px;color:#64748b;margin:4px 0 0;">{{ $reservations->total() }} réservation(s) au total</p>
    </div>
</div>

{{-- Stats --}}
@php
    $totalRes   = \App\Models\Reservation::count();
    $confirmees = \App\Models\Reservation::where('statut','CONFIRMEE')->count();
    $enAttente  = \App\Models\Reservation::where('statut','EN_ATTENTE')->count();
    $annulees   = \App\Models\Reservation::where('statut','ANNULEE')->count();
@endphp
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;">
    <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:16px;text-align:center;box-shadow:0 2px 8px rgba(0,53,128,0.07);">
        <div style="font-size:24px;font-weight:900;color:#003580;">{{ $totalRes }}</div>
        <div style="font-size:12px;color:#64748b;font-weight:600;margin-top:3px;">Total</div>
    </div>
    <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:16px;text-align:center;box-shadow:0 2px 8px rgba(0,53,128,0.07);">
        <div style="font-size:24px;font-weight:900;color:#16a34a;">{{ $confirmees }}</div>
        <div style="font-size:12px;color:#64748b;font-weight:600;margin-top:3px;">✅ Confirmées</div>
    </div>
    <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:16px;text-align:center;box-shadow:0 2px 8px rgba(0,53,128,0.07);">
        <div style="font-size:24px;font-weight:900;color:#f59e0b;">{{ $enAttente }}</div>
        <div style="font-size:12px;color:#64748b;font-weight:600;margin-top:3px;">⏳ En attente</div>
    </div>
    <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:16px;text-align:center;box-shadow:0 2px 8px rgba(0,53,128,0.07);">
        <div style="font-size:24px;font-weight:900;color:#dc2626;">{{ $annulees }}</div>
        <div style="font-size:12px;color:#64748b;font-weight:600;margin-top:3px;">❌ Annulées</div>
    </div>
</div>

{{-- Filtres --}}
<div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:16px;margin-bottom:20px;box-shadow:0 1px 4px rgba(0,53,128,0.06);">
    <form method="GET" action="{{ route('admin.reservations.index') }}">
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="Code, nom, email..."
                   style="flex:1;min-width:180px;border:2px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;">
            <select name="statut"
                    style="border:2px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;font-weight:600;color:#003580;">
                <option value="">Tous les statuts</option>
                <option value="EN_ATTENTE" {{ request('statut')==='EN_ATTENTE' ? 'selected':'' }}>⏳ En attente</option>
                <option value="CONFIRMEE"  {{ request('statut')==='CONFIRMEE'  ? 'selected':'' }}>✅ Confirmées</option>
                <option value="ANNULEE"    {{ request('statut')==='ANNULEE'    ? 'selected':'' }}>❌ Annulées</option>
            </select>
            <button type="submit"
                    style="background:#003580;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-weight:700;font-size:13px;cursor:pointer;">
                <i class="bi bi-search"></i> Filtrer
            </button>
            @if(request('q') || request('statut'))
            <a href="{{ route('admin.reservations.index') }}"
               style="background:#f1f5f9;color:#64748b;border-radius:8px;padding:8px 14px;text-decoration:none;font-size:13px;font-weight:600;">
                <i class="bi bi-x"></i> Effacer
            </a>
            @endif
        </div>
    </form>
</div>

@if($reservations->isEmpty())
<div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;padding:60px;text-align:center;">
    <div style="font-size:48px;margin-bottom:12px;">📋</div>
    <h3 style="color:#003580;font-weight:700;">Aucune réservation</h3>
    <p style="color:#64748b;">Aucune réservation ne correspond à votre recherche.</p>
</div>
@else
<div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 12px rgba(0,53,128,0.07);overflow:hidden;">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:12px 14px;text-align:left;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;white-space:nowrap;">Code</th>
                    <th style="padding:12px 14px;text-align:left;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Client</th>
                    <th style="padding:12px 14px;text-align:left;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Hôtel / Chambre</th>
                    <th style="padding:12px 14px;text-align:center;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;white-space:nowrap;">Arrivée</th>
                    <th style="padding:12px 14px;text-align:center;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Nuits</th>
                    <th style="padding:12px 14px;text-align:center;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Montant</th>
                    <th style="padding:12px 14px;text-align:center;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Acompte</th>
                    <th style="padding:12px 14px;text-align:center;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Statut</th>
                    <th style="padding:12px 14px;text-align:center;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservations as $res)
                @php $estExpiree = $res->statut === 'ANNULEE' && $res->date_debut->isPast(); @endphp
                <tr style="border-bottom:1px solid #f1f5f9;{{ $estExpiree ? 'opacity:0.7;' : '' }}"
                    onmouseover="this.style.background='#f8fafc'"
                    onmouseout="this.style.background=''">

                    {{-- Code --}}
                    <td style="padding:12px 14px;">
                        <span style="font-family:monospace;font-size:11px;color:{{ $estExpiree ? '#94a3b8' : '#003580' }};font-weight:800;background:{{ $estExpiree ? '#f1f5f9' : '#dbeafe' }};padding:3px 8px;border-radius:5px;">
                            {{ $res->code_reservation }}
                        </span>
                        <div style="font-size:11px;color:#94a3b8;margin-top:3px;">
                            {{ $res->date_reservation->format('d/m/Y') }}
                        </div>
                    </td>

                    {{-- Client --}}
                    <td style="padding:12px 14px;">
                        <div style="font-weight:700;color:#1e293b;">{{ $res->prenom_client }} {{ $res->nom_client }}</div>
                        <div style="font-size:11px;color:#94a3b8;">{{ $res->email_client }}</div>
                        @if($res->telephone_client)
                        <div style="font-size:11px;color:#94a3b8;">{{ $res->telephone_client }}</div>
                        @endif
                    </td>

                    {{-- Hôtel / Chambre --}}
                    <td style="padding:12px 14px;">
                        <div style="font-weight:600;color:#1e293b;font-size:12px;">
                            {{ $res->chambre->typeChambre->hotel->nom ?? '—' }}
                        </div>
                        <div style="font-size:11px;color:#64748b;margin-top:2px;">
                            {{ $res->chambre->typeChambre->nom_type ?? '—' }}
                            · N° {{ $res->chambre->numero }}
                        </div>
                    </td>

                    {{-- Arrivée --}}
                    <td style="padding:12px 14px;text-align:center;font-weight:700;color:#003580;white-space:nowrap;">
                        {{ $res->date_debut->format('d/m/Y') }}
                        <div style="font-size:11px;color:#94a3b8;font-weight:400;">
                            → {{ $res->date_fin->format('d/m/Y') }}
                        </div>
                    </td>

                    {{-- Nuits --}}
                    <td style="padding:12px 14px;text-align:center;">
                        <span style="background:#f1f5f9;color:#475569;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:700;">
                            {{ $res->date_debut->diffInDays($res->date_fin) }}j
                        </span>
                    </td>

                    {{-- Montant --}}
                    <td style="padding:12px 14px;text-align:center;">
                        <div style="font-weight:800;color:#003580;font-size:14px;">
                            {{ number_format($res->montant_total,0,',',' ') }}
                        </div>
                        <div style="font-size:10px;color:#94a3b8;">DJF</div>
                    </td>

                    {{-- Acompte --}}
                    <td style="padding:12px 14px;text-align:center;">
                        @if($res->hasPaidDeposit())
                            <span style="background:#dcfce7;color:#14532d;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">✅ Payé</span>
                        @else
                            <span style="background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">⏳ En attente</span>
                        @endif
                    </td>

                    {{-- Statut --}}
                    <td style="padding:12px 14px;text-align:center;">
                        @if($res->statut === 'CONFIRMEE')
                            <span style="background:#dcfce7;color:#14532d;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;">✅ Confirmée</span>
                        @elseif($res->statut === 'EN_ATTENTE')
                            <span style="background:#fef3c7;color:#92400e;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;">⏳ En attente</span>
                        @elseif($estExpiree)
                            <span style="background:#f3e8ff;color:#6b21a8;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;">🕐 Expirée</span>
                        @else
                            <span style="background:#fee2e2;color:#991b1b;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;">❌ Annulée</span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td style="padding:12px 14px;">
                        <div style="display:flex;align-items:center;justify-content:center;gap:6px;flex-wrap:wrap;">

                            {{-- Voir : toujours disponible --}}
                            <a href="{{ route('admin.reservations.show', $res) }}"
                               style="background:#dbeafe;color:#1e40af;padding:5px 10px;border-radius:6px;text-decoration:none;font-size:11px;font-weight:700;display:inline-flex;align-items:center;gap:3px;">
                                <i class="bi bi-eye"></i> Voir
                            </a>

                            {{-- Modifier : grisé si expirée --}}
                            @if($estExpiree)
                                <span style="background:#e2e8f0;color:#94a3b8;padding:5px 10px;border-radius:6px;font-size:11px;font-weight:700;cursor:not-allowed;display:inline-flex;align-items:center;gap:3px;">
                                    <i class="bi bi-pencil"></i> Modifier
                                </span>
                            @else
                                <a href="{{ route('admin.reservations.edit', $res) }}"
                                   style="background:#fef3c7;color:#92400e;padding:5px 10px;border-radius:6px;text-decoration:none;font-size:11px;font-weight:700;display:inline-flex;align-items:center;gap:3px;">
                                    <i class="bi bi-pencil"></i> Modifier
                                </a>
                            @endif

                            {{-- Supprimer : grisé si expirée --}}
                            @if($estExpiree)
                                <span style="background:#e2e8f0;color:#94a3b8;padding:5px 10px;border-radius:6px;font-size:11px;font-weight:700;cursor:not-allowed;display:inline-flex;align-items:center;gap:3px;">
                                    <i class="bi bi-trash"></i>
                                </span>
                            @else
                                <form method="POST" action="{{ route('admin.reservations.destroy', $res) }}"
                                      onsubmit="return confirm('Supprimer la réservation {{ $res->code_reservation }} ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            style="background:#fee2e2;color:#991b1b;padding:5px 10px;border-radius:6px;border:none;font-size:11px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:3px;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endif

                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@if($reservations->hasPages())
<div style="display:flex;justify-content:center;margin-top:24px;">
    {{ $reservations->links() }}
</div>
@endif
@endif

@endsection