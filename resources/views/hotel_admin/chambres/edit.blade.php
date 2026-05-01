@extends('layouts.hotel_admin')
@section('page_title', 'Modifier chambre')
@section('title', 'Modifier une chambre')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="page-title">✏️ Chambre N° {{ $chambre->numero }}</h1>
        <p class="page-sub">{{ $hotel->nom }}</p>
    </div>
    <a href="{{ route('hoteladmin.chambres.index') }}" class="btn-ha-outline">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card-admin p-4">
            <form method="POST" action="{{ route('hoteladmin.chambres.update', $chambre) }}" class="form-ha">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label>Numéro de chambre *</label>
                    <input type="text" name="numero" class="form-control"
                           value="{{ old('numero', $chambre->numero) }}" required>
                </div>
                <div class="mb-3">
                    <label>Type de chambre *</label>
                    <select name="type_id" class="form-select" required>
                        @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ old('type_id', $chambre->type_id) == $type->id ? 'selected' : '' }}>
                            {{ $type->nom_type }} — {{ number_format($type->prix_par_nuit,0,',',' ') }} DJF/nuit
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label>État *</label>
                    <select name="etat" class="form-select" required>
                        <option value="DISPONIBLE" {{ old('etat',$chambre->etat) === 'DISPONIBLE' ? 'selected' : '' }}>✅ Disponible</option>
                        <option value="OCCUPEE"    {{ old('etat',$chambre->etat) === 'OCCUPEE'    ? 'selected' : '' }}>🔴 Occupée</option>
                        <option value="MAINTENANCE"{{ old('etat',$chambre->etat) === 'MAINTENANCE'? 'selected' : '' }}>🔧 Maintenance</option>
                    </select>
                </div>
                <div class="d-flex gap-3">
                    <button type="submit" class="btn-ha-primary">
                        <i class="bi bi-check-lg"></i> Enregistrer
                    </button>
                    <a href="{{ route('hoteladmin.chambres.index') }}" class="btn-ha-outline">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection