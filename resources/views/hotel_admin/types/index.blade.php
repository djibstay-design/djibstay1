@extends('layouts.hotel_admin')
@section('page_title', 'Types de chambre')
@section('title', 'Types de chambre')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h1 class="page-title">🏷️ Types de chambre</h1>
        <p class="page-sub">{{ $types->count() }} type(s) — {{ $hotel->nom }}</p>
    </div>
    <a href="{{ route('hoteladmin.types-chambre.create') }}" class="btn-ha-primary">
        <i class="bi bi-plus-lg"></i> Nouveau type
    </a>
</div>

@if($types->isEmpty())
<div class="card-admin p-5 text-center">
    <div style="font-size:48px;margin-bottom:12px;">🏷️</div>
    <h3 style="color:#003580;font-weight:700;">Aucun type de chambre</h3>
    <p style="color:#64748b;margin-bottom:16px;">Créez votre premier type de chambre.</p>
    <a href="{{ route('hoteladmin.types-chambre.create') }}" class="btn-ha-primary">
        <i class="bi bi-plus-lg"></i> Créer un type
    </a>
</div>
@else
<div class="row g-4">
    @foreach($types as $type)
    <div class="col-md-6 col-xl-4">
        <div class="card-admin overflow-hidden">
            {{-- Image --}}
            @php $img = $type->images->first(); @endphp
            @if($img)
            <img src="{{ $img->url }}" alt="{{ $type->nom_type }}"
                 style="width:100%;height:160px;object-fit:cover;"
                 onerror="this.src='{{ asset('images/ayla.jpg') }}'">
            @else
            <div style="width:100%;height:120px;background:linear-gradient(135deg,#003580,#0071c2);display:flex;align-items:center;justify-content:center;font-size:40px;">🛏️</div>
            @endif

            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h3 style="font-size:16px;font-weight:800;color:#1e293b;margin:0;">{{ $type->nom_type }}</h3>
                    <span style="background:#003580;color:#fff;font-size:13px;font-weight:800;padding:4px 10px;border-radius:8px;">
                        {{ number_format($type->prix_par_nuit,0,',',' ') }} DJF
                    </span>
                </div>

                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span style="font-size:12px;background:#dbeafe;color:#1e40af;padding:3px 8px;border-radius:5px;font-weight:600;">
                        <i class="bi bi-people"></i> {{ $type->capacite }} pers.
                    </span>
                    @if($type->superficie_m2)
                    <span style="font-size:12px;background:#f1f5f9;color:#475569;padding:3px 8px;border-radius:5px;font-weight:600;">
                        {{ $type->superficie_m2 }} m²
                    </span>
                    @endif
                    @if($type->has_wifi)
                    <span style="font-size:12px;background:#f0fdf4;color:#16a34a;padding:3px 8px;border-radius:5px;font-weight:600;">
                        <i class="bi bi-wifi"></i> WiFi
                    </span>
                    @endif
                    @if($type->has_climatisation)
                    <span style="font-size:12px;background:#eff6ff;color:#1d4ed8;padding:3px 8px;border-radius:5px;font-weight:600;">
                        <i class="bi bi-snow2"></i> Clim
                    </span>
                    @endif
                    @if($type->has_minibar)
                    <span style="font-size:12px;background:#fef3c7;color:#92400e;padding:3px 8px;border-radius:5px;font-weight:600;">
                        <i class="bi bi-cup-straw"></i> Minibar
                    </span>
                    @endif
                </div>

                <div class="d-flex justify-content-between align-items-center" style="font-size:13px;color:#64748b;margin-bottom:14px;">
                    <span><i class="bi bi-door-open me-1"></i>{{ $type->chambres->count() }} chambre(s)</span>
                    <span><i class="bi bi-check-circle me-1 text-success"></i>{{ $type->chambres->where('etat','DISPONIBLE')->count() }} dispo</span>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('hoteladmin.types-chambre.edit', $type) }}" class="btn-ha-outline flex-1" style="justify-content:center;">
                        <i class="bi bi-pencil"></i> Modifier
                    </a>
                    <form method="POST" action="{{ route('hoteladmin.types-chambre.destroy', $type) }}"
                          onsubmit="return confirm('Supprimer ce type ? Toutes les chambres associées seront supprimées.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-ha-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection