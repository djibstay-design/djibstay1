@extends('layouts.admin')
@section('page_title', 'Hôtels')

@section('content')
<div style="padding:0;">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
        <div>
            <h1 style="font-size:22px;font-weight:900;color:#1e293b;margin:0;">🏨 Gestion des hôtels</h1>
            <p style="font-size:13px;color:#64748b;margin:4px 0 0;">{{ $hotels->total() }} hôtel(s) au total</p>
        </div>
        @if(auth()->user()->role === 'SUPER_ADMIN')
        <a href="{{ route('admin.hotels.create') }}"
           style="background:#003580;color:#fff;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:700;font-size:14px;display:inline-flex;align-items:center;gap:8px;">
            <i class="bi bi-plus-lg"></i> Ajouter un hôtel
        </a>
        @endif
    </div>

    {{-- Recherche --}}
    <form method="GET" action="{{ route('admin.hotels.index') }}" style="margin-bottom:20px;">
        <div style="display:flex;gap:10px;">
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="Rechercher un hôtel..."
                   style="flex:1;border:2px solid #e2e8f0;border-radius:8px;padding:9px 14px;font-size:14px;max-width:400px;">
            <button type="submit"
                    style="background:#003580;color:#fff;border:none;border-radius:8px;padding:9px 18px;font-weight:700;font-size:14px;cursor:pointer;">
                <i class="bi bi-search"></i> Rechercher
            </button>
            @if(request('q'))
            <a href="{{ route('admin.hotels.index') }}"
               style="background:#f1f5f9;color:#64748b;border-radius:8px;padding:9px 14px;text-decoration:none;font-size:14px;font-weight:600;">
                <i class="bi bi-x"></i> Effacer
            </a>
            @endif
        </div>
    </form>

    {{-- Table --}}
    <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 12px rgba(0,53,128,0.07);overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;font-size:14px;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Hôtel</th>
                    <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Ville</th>
                    <th style="padding:12px 16px;text-align:center;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Chambres</th>
                    <th style="padding:12px 16px;text-align:center;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Avis</th>
                    @if(auth()->user()->role === 'SUPER_ADMIN')
                    <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Admin</th>
                    @endif
                    <th style="padding:12px 16px;text-align:center;font-size:11px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hotels as $hotel)
                <tr style="border-bottom:1px solid #f1f5f9;transition:background .15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                    <td style="padding:14px 16px;">
                        <div style="font-weight:700;color:#1e293b;">{{ $hotel->nom }}</div>
                        @if($hotel->adresse)
                        <div style="font-size:12px;color:#94a3b8;margin-top:2px;">{{ $hotel->adresse }}</div>
                        @endif
                    </td>
                    <td style="padding:14px 16px;color:#475569;">
                        <i class="bi bi-geo-alt" style="color:#0071c2;"></i>
                        {{ $hotel->ville ?? '—' }}
                    </td>
                    <td style="padding:14px 16px;text-align:center;">
                        <span style="background:#dbeafe;color:#1e40af;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;">
                            {{ $hotel->types_chambre_count }}
                        </span>
                    </td>
                    <td style="padding:14px 16px;text-align:center;">
                        <span style="background:#fef3c7;color:#92400e;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;">
                            ⭐ {{ $hotel->avis_count }}
                        </span>
                    </td>
                    @if(auth()->user()->role === 'SUPER_ADMIN')
                    <td style="padding:14px 16px;font-size:13px;color:#64748b;">
                        {{ $hotel->user->name ?? '—' }}
                    </td>
                    @endif
                    <td style="padding:14px 16px;">
                        <div style="display:flex;align-items:center;justify-content:center;gap:8px;flex-wrap:wrap;">
                            {{-- Voir sur le site --}}
                            <a href="{{ route('hotels.show', $hotel) }}" target="_blank"
                               style="background:#f0f7ff;color:#0071c2;padding:6px 12px;border-radius:6px;text-decoration:none;font-size:12px;font-weight:700;display:inline-flex;align-items:center;gap:5px;">
                                <i class="bi bi-eye"></i> Voir
                            </a>
                            {{-- Photos --}}
                            <a href="{{ route('admin.hotels.images.index', $hotel) }}"
                               style="background:#f0fdf4;color:#16a34a;padding:6px 12px;border-radius:6px;text-decoration:none;font-size:12px;font-weight:700;display:inline-flex;align-items:center;gap:5px;">
                                <i class="bi bi-images"></i> Photos
                            </a>
                            {{-- Modifier --}}
                            <a href="{{ route('admin.hotels.edit', $hotel) }}"
                               style="background:#fef3c7;color:#92400e;padding:6px 12px;border-radius:6px;text-decoration:none;font-size:12px;font-weight:700;display:inline-flex;align-items:center;gap:5px;">
                                <i class="bi bi-pencil"></i> Modifier
                            </a>
                            {{-- Supprimer SUPER_ADMIN seulement --}}
                            @if(auth()->user()->role === 'SUPER_ADMIN')
                            <form method="POST" action="{{ route('admin.hotels.destroy', $hotel) }}"
                                  onsubmit="return confirm('Supprimer {{ $hotel->nom }} ? Cette action est irréversible.')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        style="background:#fee2e2;color:#991b1b;padding:6px 12px;border-radius:6px;border:none;font-size:12px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:5px;">
                                    <i class="bi bi-trash"></i> Suppr.
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:48px;color:#94a3b8;">
                        <div style="font-size:40px;margin-bottom:12px;">🏨</div>
                        <div style="font-weight:700;font-size:15px;">Aucun hôtel trouvé</div>
                        @if(auth()->user()->role === 'SUPER_ADMIN')
                        <a href="{{ route('admin.hotels.create') }}"
                           style="display:inline-block;margin-top:14px;background:#003580;color:#fff;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:700;font-size:14px;">
                            Ajouter le premier hôtel
                        </a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($hotels->hasPages())
    <div style="display:flex;justify-content:center;margin-top:24px;">
        {{ $hotels->links() }}
    </div>
    @endif

</div>
@endsection