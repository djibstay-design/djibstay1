@extends('layouts.hotel_admin')
@section('page_title', 'Nouvelle chambre')
@section('title', 'Créer une chambre')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="page-title">🛏️ Nouvelle chambre</h1>
        <p class="page-sub">{{ $hotel->nom }}</p>
    </div>
    <a href="{{ route('hoteladmin.chambres.index') }}" class="btn-ha-outline">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card-admin p-4">
            <form method="POST" action="{{ route('hoteladmin.chambres.store') }}" class="form-ha">
                @csrf
                <div class="mb-3">
                    <label>Numéro de chambre *</label>
                    <input type="text" name="numero" class="form-control"
                           value="{{ old('numero') }}"
                           placeholder="Ex : 101, 202A, Suite-1" required>
                </div>
                <div class="mb-3">
                    <label>Type de chambre *</label>
                    <select name="type_id" class="form-select" required>
                        <option value="">Choisir un type...</option>
                        @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->nom_type }} — {{ number_format($type->prix_par_nuit,0,',',' ') }} DJF/nuit
                        </option>
                        @endforeach
                    </select>
                    @if($types->isEmpty())
                    <div style="font-size:12px;color:#dc2626;margin-top:4px;">
                        ⚠️ Aucun type de chambre. <a href="{{ route('hoteladmin.types-chambre.create') }}" style="color:#0071c2;">Créez-en un d'abord</a>
                    </div>
                    @endif
                </div>
                <div class="mb-4">
                    <label>État *</label>
                    <select name="etat" class="form-select" required>
                        <option value="DISPONIBLE" {{ old('etat','DISPONIBLE') === 'DISPONIBLE' ? 'selected' : '' }}>✅ Disponible</option>
                        <option value="OCCUPEE"    {{ old('etat') === 'OCCUPEE' ? 'selected' : '' }}>🔴 Occupée</option>
                        <option value="MAINTENANCE"{{ old('etat') === 'MAINTENANCE' ? 'selected' : '' }}>🔧 Maintenance</option>
                    </select>
                </div>
                <div class="d-flex gap-3">
                    <button type="submit" class="btn-ha-primary" {{ $types->isEmpty() ? 'disabled' : '' }}>
                        <i class="bi bi-check-lg"></i> Créer la chambre
                    </button>
                    <a href="{{ route('hoteladmin.chambres.index') }}" class="btn-ha-outline">Annuler</a>
                </div>
            </form>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card-admin p-4">
            <h5 style="font-size:14px;font-weight:800;color:#003580;margin-bottom:12px;">Types disponibles</h5>
            @foreach($types as $type)
            <div style="padding:10px 0;border-bottom:1px solid #f1f5f9;font-size:13px;">
                <div style="font-weight:700;color:#1e293b;">{{ $type->nom_type }}</div>
                <div style="color:#64748b;margin-top:2px;">
                    {{ $type->capacite }} pers. · {{ number_format($type->prix_par_nuit,0,',',' ') }} DJF/nuit
                    · {{ $type->chambres->count() }} chambre(s)
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection