@extends('layouts.admin')
@section('page_title', 'Chambres')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
    <div>
        <h1 style="font-size:22px;font-weight:900;color:#1e293b;margin:0;">🛏️ Chambres</h1>
        <p style="font-size:13px;color:#64748b;margin:4px 0 0;">{{ $chambres->total() }} chambre(s) au total</p>
    </div>
    <a href="{{ route('admin.chambres.create') }}"
       style="background:#003580;color:#fff;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:700;font-size:14px;display:inline-flex;align-items:center;gap:8px;">
        <i class="bi bi-plus-lg"></i> Nouvelle chambre
    </a>
</div>

{{-- Stats --}}
@php
    $totalAll = $chambres->total();
    $dispoCount = \App\Models\Chambre::where('etat','DISPONIBLE')->count();
    $occupeCount = \App\Models\Chambre::where('etat','OCCUPEE')->count();
    $maintCount = \App\Models\Chambre::where('etat','MAINTENANCE')->count();
@endphp
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;">
    <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:16px;text-align:center;box-shadow:0 2px 8px rgba(0,53,128,0.07);">
        <div style="font-size:24px;font-weight:900;color:#003580;">{{ $totalAll }}</div>
        <div style="font-size:12px;color:#64748b;font-weight:600;margin-top:3px;">Total chambres</div>
    </div>
    <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:16px;text-align:center;box-shadow:0 2px 8px rgba(0,53,128,0.07);">
        <div style="font-size:24px;font-weight:900;color:#16a34a;">{{ $dispoCount }}</div>
        <div style="font-size:12px;color:#64748b;font-weight:600;margin-top:3px;">✅ Disponibles</div>
    </div>
    <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:16px;text-align:center;box-shadow:0 2px 8px rgba(0,53,128,0.07);">
        <div style="font-size:24px;font-weight:900;color:#dc2626;">{{ $occupeCount }}</div>
        <div style="font-size:12px;color:#64748b;font-weight:600;margin-top:3px;">🔴 Occupées</div>
    </div>
    <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:16px;text-align:center;box-shadow:0 2px 8px rgba(0,53,128,0.07);">
        <div style="font-size:24px;font-weight:900;color:#f59e0b;">{{ $maintCount }}</div>
        <div style="font-size:12px;color:#64748b;font-weight:600;margin-top:3px;">🔧 Maintenance</div>
    </div>
</div>

{{-- Filtres --}}
<form method="GET" action="{{ route('admin.chambres.index') }}" style="margin-bottom:20px;">
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <input type="text" name="q" value="{{ request('q') }}"
               placeholder="Numéro de chambre..."
               style="flex:1;min-width:180px;border:2px solid #e2e8f0;border-radius:8px;padding:9px 14px;font-size:14px;max-width:300px;">
        <select name="etat"
                style="border:2px solid #e2e8f0;border-radius:8px;padding:9px 14px;font-size:13px;font-weight:600;color:#003580;">
            <option value="">Tous les états</option>
            <option value="DISPONIBLE"  {{ request('etat')==='DISPONIBLE'  ? 'selected':'' }}>✅ Disponible</option>
            <option value="OCCUPEE"     {{ request('etat')==='OCCUPEE'     ? 'selected':'' }}>🔴 Occupée</option>
            <option value="MAINTENANCE" {{ request('etat')==='MAINTENANCE' ? 'selected':'' }}>🔧 Maintenance</option>
        </select>
        <button type="submit"
                style="background:#003580;color:#fff;border:none;border-radius:8px;padding:9px 18px;font-weight:700;font-size:14px;cursor:pointer;">
            <i class="bi bi-search"></i> Filtrer
        </button>
        @if(request('q') || request('etat'))
        <a href="{{ route('admin.chambres.index') }}"
           style="background:#f1f5f9;color:#64748b;border-radius:8px;padding:9px 14px;text-decoration:none;font-size:14px;font-weight:600;">
            <i class="bi bi-x"></i> Effacer
        </a>
        @endif
    </div>
</form>

@if($chambres->isEmpty())
<div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;padding:60px;text-align:center;">
    <div style="font-size:48px;margin-bottom:12px;">🛏️</div>
    <h3 style="color:#003580;font-weight:700;margin-bottom:8px;">Aucune chambre</h3>
    <p style="color:#64748b;margin-bottom:16px;">Créez votre première chambre.</p>
    <a href="{{ route('admin.chambres.create') }}"
       style="background:#003580;color:#fff;padding:10px 24px;border-radius:8px;text-decoration:none;font-weight:700;font-size:14px;">
        <i class="bi bi-plus-lg me-1"></i> Créer une chambre
    </a>
</div>
@else
<div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 12px rgba(0,53,128,0.07);overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;font-size:14px;">
        <thead>
            <tr style="background:#f8fafc;">
                <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">N° Chambre</th>
                <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Type</th>
                <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Hôtel</th>
                <th style="padding:12px 16px;text-align:center;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Capacité</th>
                <th style="padding:12px 16px;text-align:center;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Prix/nuit</th>
                <th style="padding:12px 16px;text-align:center;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">État</th>
                <th style="padding:12px 16px;text-align:center;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($chambres as $chambre)
            <tr style="border-bottom:1px solid #f1f5f9;"
                onmouseover="this.style.background='#f8fafc'"
                onmouseout="this.style.background=''">
                <td style="padding:14px 16px;">
                    <span style="background:#003580;color:#fff;padding:4px 12px;border-radius:6px;font-size:13px;font-weight:800;">
                        {{ $chambre->numero }}
                    </span>
                </td>
                <td style="padding:14px 16px;">
                    <div style="font-weight:700;color:#1e293b;">{{ $chambre->typeChambre->nom_type ?? '—' }}</div>
                </td>
                <td style="padding:14px 16px;color:#475569;">
                    <i class="bi bi-building" style="color:#0071c2;"></i>
                    {{ $chambre->typeChambre->hotel->nom ?? '—' }}
                </td>
                <td style="padding:14px 16px;text-align:center;">
                    <span style="background:#dbeafe;color:#1e40af;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:700;">
                        {{ $chambre->typeChambre->capacite ?? '—' }} pers.
                    </span>
                </td>
                <td style="padding:14px 16px;text-align:center;font-weight:800;color:#003580;">
                    {{ $chambre->typeChambre ? number_format($chambre->typeChambre->prix_par_nuit,0,',',' ').' DJF' : '—' }}
                </td>
                <td style="padding:14px 16px;text-align:center;">
                    @if($chambre->etat === 'DISPONIBLE')
                        <span style="background:#dcfce7;color:#14532d;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;">✅ Disponible</span>
                    @elseif($chambre->etat === 'OCCUPEE')
                        <span style="background:#fee2e2;color:#991b1b;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;">🔴 Occupée</span>
                    @else
                        <span style="background:#fef3c7;color:#92400e;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;">🔧 Maintenance</span>
                    @endif
                </td>
                <td style="padding:14px 16px;">
                    <div style="display:flex;align-items:center;justify-content:center;gap:8px;">
                        <a href="{{ route('admin.chambres.edit', $chambre) }}"
                           style="background:#fef3c7;color:#92400e;padding:6px 12px;border-radius:6px;text-decoration:none;font-size:12px;font-weight:700;display:inline-flex;align-items:center;gap:4px;">
                            <i class="bi bi-pencil"></i> Modifier
                        </a>
                        <form method="POST" action="{{ route('admin.chambres.destroy', $chambre) }}"
                              onsubmit="return confirm('Supprimer la chambre N° {{ $chambre->numero }} ?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    style="background:#fee2e2;color:#991b1b;padding:6px 12px;border-radius:6px;border:none;font-size:12px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:4px;">
                                <i class="bi bi-trash"></i> Suppr.
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if($chambres->hasPages())
<div style="display:flex;justify-content:center;margin-top:24px;">
    {{ $chambres->links() }}
</div>
@endif
@endif

@endsection