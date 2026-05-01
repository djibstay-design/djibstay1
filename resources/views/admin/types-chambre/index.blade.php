@extends('layouts.admin')
@section('page_title', 'Types de chambre')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
    <div>
        <h1 style="font-size:22px;font-weight:900;color:#1e293b;margin:0;">🏷️ Types de chambre</h1>
        <p style="font-size:13px;color:#64748b;margin:4px 0 0;">{{ $typesChambre->total() }} type(s) au total</p>
    </div>
    <a href="{{ route('admin.types-chambre.create') }}"
       style="background:#003580;color:#fff;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:700;font-size:14px;display:inline-flex;align-items:center;gap:8px;">
        <i class="bi bi-plus-lg"></i> Nouveau type
    </a>
</div>

{{-- Recherche --}}
<form method="GET" action="{{ route('admin.types-chambre.index') }}" style="margin-bottom:20px;">
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <input type="text" name="q" value="{{ request('q') }}"
               placeholder="Rechercher un type ou hôtel..."
               style="flex:1;min-width:200px;border:2px solid #e2e8f0;border-radius:8px;padding:9px 14px;font-size:14px;max-width:400px;">
        <button type="submit"
                style="background:#003580;color:#fff;border:none;border-radius:8px;padding:9px 18px;font-weight:700;font-size:14px;cursor:pointer;">
            <i class="bi bi-search"></i> Rechercher
        </button>
        @if(request('q'))
        <a href="{{ route('admin.types-chambre.index') }}"
           style="background:#f1f5f9;color:#64748b;border-radius:8px;padding:9px 14px;text-decoration:none;font-size:14px;font-weight:600;">
            <i class="bi bi-x"></i> Effacer
        </a>
        @endif
    </div>
</form>

@if($typesChambre->isEmpty())
<div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;padding:60px;text-align:center;box-shadow:0 2px 12px rgba(0,53,128,0.07);">
    <div style="font-size:48px;margin-bottom:12px;">🏷️</div>
    <h3 style="color:#003580;font-weight:700;margin-bottom:8px;">Aucun type de chambre</h3>
    <p style="color:#64748b;margin-bottom:16px;">Créez votre premier type de chambre.</p>
    <a href="{{ route('admin.types-chambre.create') }}"
       style="background:#003580;color:#fff;padding:10px 24px;border-radius:8px;text-decoration:none;font-weight:700;font-size:14px;">
        <i class="bi bi-plus-lg me-1"></i> Créer un type
    </a>
</div>
@else
<div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 12px rgba(0,53,128,0.07);overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;font-size:14px;">
        <thead>
            <tr style="background:#f8fafc;">
                <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Type</th>
                <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Hôtel</th>
                <th style="padding:12px 16px;text-align:center;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Capacité</th>
                <th style="padding:12px 16px;text-align:center;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Prix / nuit</th>
                <th style="padding:12px 16px;text-align:center;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Équipements</th>
                <th style="padding:12px 16px;text-align:center;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Chambres</th>
                <th style="padding:12px 16px;text-align:center;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($typesChambre as $type)
            <tr style="border-bottom:1px solid #f1f5f9;"
                onmouseover="this.style.background='#f8fafc'"
                onmouseout="this.style.background=''">
                <td style="padding:14px 16px;">
                    <div style="font-weight:700;color:#1e293b;">{{ $type->nom_type }}</div>
                    @if($type->lit_description)
                    <div style="font-size:12px;color:#94a3b8;margin-top:2px;">{{ $type->lit_description }}</div>
                    @endif
                    @if($type->superficie_m2)
                    <div style="font-size:12px;color:#94a3b8;">{{ $type->superficie_m2 }} m²</div>
                    @endif
                </td>
                <td style="padding:14px 16px;color:#475569;">
                    <i class="bi bi-building" style="color:#0071c2;"></i>
                    {{ $type->hotel->nom ?? '—' }}
                </td>
                <td style="padding:14px 16px;text-align:center;">
                    <span style="background:#dbeafe;color:#1e40af;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;">
                        <i class="bi bi-people"></i> {{ $type->capacite }} pers.
                    </span>
                </td>
                <td style="padding:14px 16px;text-align:center;">
                    <span style="font-weight:800;color:#003580;font-size:15px;">
                        {{ number_format($type->prix_par_nuit, 0, ',', ' ') }}
                    </span>
                    <span style="font-size:11px;color:#94a3b8;"> DJF</span>
                </td>
                <td style="padding:14px 16px;text-align:center;">
                    <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;">
                        @if($type->has_wifi)
                        <span style="background:#f0fdf4;color:#16a34a;padding:2px 8px;border-radius:5px;font-size:11px;font-weight:600;">
                            <i class="bi bi-wifi"></i> WiFi
                        </span>
                        @endif
                        @if($type->has_climatisation)
                        <span style="background:#eff6ff;color:#1d4ed8;padding:2px 8px;border-radius:5px;font-size:11px;font-weight:600;">
                            <i class="bi bi-snow2"></i> Clim
                        </span>
                        @endif
                        @if($type->has_minibar)
                        <span style="background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:5px;font-size:11px;font-weight:600;">
                            <i class="bi bi-cup-straw"></i> Minibar
                        </span>
                        @endif
                        @if(!$type->has_wifi && !$type->has_climatisation && !$type->has_minibar)
                        <span style="color:#94a3b8;font-size:12px;">—</span>
                        @endif
                    </div>
                </td>
                <td style="padding:14px 16px;text-align:center;">
                    @php $nbChambres = $type->chambres->count(); @endphp
                    <span style="background:{{ $nbChambres > 0 ? '#dcfce7' : '#f1f5f9' }};color:{{ $nbChambres > 0 ? '#14532d' : '#64748b' }};padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;">
                        {{ $nbChambres }} chambre(s)
                    </span>
                </td>
                <td style="padding:14px 16px;">
                    <div style="display:flex;align-items:center;justify-content:center;gap:8px;">
                        <a href="{{ route('admin.types-chambre.edit', $type) }}"
                           style="background:#fef3c7;color:#92400e;padding:6px 12px;border-radius:6px;text-decoration:none;font-size:12px;font-weight:700;display:inline-flex;align-items:center;gap:4px;">
                            <i class="bi bi-pencil"></i> Modifier
                        </a>
                        <form method="POST" action="{{ route('admin.types-chambre.destroy', $type) }}"
                              onsubmit="return confirm('Supprimer le type {{ $type->nom_type }} ?')">
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

{{-- Pagination --}}
@if($typesChambre->hasPages())
<div style="display:flex;justify-content:center;margin-top:24px;">
    {{ $typesChambre->links() }}
</div>
@endif
@endif

@endsection