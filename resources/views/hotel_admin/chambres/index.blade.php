@extends('layouts.hotel_admin')
@section('page_title', 'Chambres')
@section('title', 'Mes chambres')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h1 class="page-title">🛏️ Chambres</h1>
        <p class="page-sub">{{ $chambres->count() }} chambre(s) — {{ $hotel->nom }}</p>
    </div>
    <a href="{{ route('hoteladmin.chambres.create') }}" class="btn-ha-primary">
        <i class="bi bi-plus-lg"></i> Nouvelle chambre
    </a>
</div>

{{-- Stats rapides --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px;">
    @php
        $dispo = $chambres->where('etat','DISPONIBLE')->count();
        $occup = $chambres->where('etat','OCCUPEE')->count();
        $maint = $chambres->where('etat','MAINTENANCE')->count();
    @endphp
    <div class="card-admin p-3 text-center">
        <div style="font-size:24px;font-weight:900;color:#16a34a;">{{ $dispo }}</div>
        <div style="font-size:12px;color:#64748b;font-weight:600;margin-top:2px;">✅ Disponibles</div>
    </div>
    <div class="card-admin p-3 text-center">
        <div style="font-size:24px;font-weight:900;color:#dc2626;">{{ $occup }}</div>
        <div style="font-size:12px;color:#64748b;font-weight:600;margin-top:2px;">🔴 Occupées</div>
    </div>
    <div class="card-admin p-3 text-center">
        <div style="font-size:24px;font-weight:900;color:#f59e0b;">{{ $maint }}</div>
        <div style="font-size:12px;color:#64748b;font-weight:600;margin-top:2px;">🔧 Maintenance</div>
    </div>
</div>

{{-- Filtres par type --}}
@if($types->count() > 1)
<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px;">
    <a href="{{ route('hoteladmin.chambres.index') }}"
       style="padding:6px 14px;border-radius:20px;font-size:13px;font-weight:700;text-decoration:none;
              background:{{ !request('type') ? '#003580' : '#f1f5f9' }};
              color:{{ !request('type') ? '#fff' : '#475569' }};">
        Tous
    </a>
    @foreach($types as $type)
    <a href="{{ route('hoteladmin.chambres.index', ['type' => $type->id]) }}"
       style="padding:6px 14px;border-radius:20px;font-size:13px;font-weight:700;text-decoration:none;
              background:{{ request('type') == $type->id ? '#003580' : '#f1f5f9' }};
              color:{{ request('type') == $type->id ? '#fff' : '#475569' }};">
        {{ $type->nom_type }}
    </a>
    @endforeach
</div>
@endif

{{-- Table --}}
<div class="card-admin overflow-hidden">
    <table class="ha-table">
        <thead>
            <tr>
                <th>N° Chambre</th>
                <th>Type</th>
                <th>Capacité</th>
                <th>Prix/nuit</th>
                <th>État</th>
                <th style="text-align:center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($chambres as $chambre)
            <tr>
                <td>
                    <span style="background:#003580;color:#fff;padding:4px 10px;border-radius:6px;font-size:13px;font-weight:800;">
                        {{ $chambre->numero }}
                    </span>
                </td>
                <td style="font-weight:600;">{{ $chambre->typeChambre->nom_type ?? '—' }}</td>
                <td>
                    <i class="bi bi-people" style="color:#0071c2;"></i>
                    {{ $chambre->typeChambre->capacite ?? '—' }} pers.
                </td>
                <td style="font-weight:700;color:#003580;">
                    {{ $chambre->typeChambre ? number_format($chambre->typeChambre->prix_par_nuit,0,',',' ').' DJF' : '—' }}
                </td>
                <td>
                    @if($chambre->etat === 'DISPONIBLE')
                        <span style="background:#dcfce7;color:#14532d;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;">✅ Disponible</span>
                    @elseif($chambre->etat === 'OCCUPEE')
                        <span style="background:#fee2e2;color:#991b1b;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;">🔴 Occupée</span>
                    @else
                        <span style="background:#fef3c7;color:#92400e;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;">🔧 Maintenance</span>
                    @endif
                </td>
                <td>
                    <div style="display:flex;gap:8px;justify-content:center;">
                        <a href="{{ route('hoteladmin.chambres.edit', $chambre) }}" class="btn-ha-outline" style="font-size:12px;padding:6px 12px;">
                            <i class="bi bi-pencil"></i> Modifier
                        </a>
                        <form method="POST" action="{{ route('hoteladmin.chambres.destroy', $chambre) }}"
                              onsubmit="return confirm('Supprimer la chambre N° {{ $chambre->numero }} ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-ha-danger" style="font-size:12px;padding:6px 12px;">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:48px;color:#94a3b8;">
                    <div style="font-size:40px;margin-bottom:12px;">🛏️</div>
                    <div style="font-weight:700;font-size:15px;margin-bottom:8px;">Aucune chambre</div>
                    <a href="{{ route('hoteladmin.chambres.create') }}" class="btn-ha-primary" style="display:inline-flex;">
                        <i class="bi bi-plus-lg"></i> Ajouter une chambre
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection