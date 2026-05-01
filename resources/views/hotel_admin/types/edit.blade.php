@extends('layouts.hotel_admin')
@section('page_title', 'Modifier le type')
@section('title', 'Modifier un type de chambre')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h1 class="page-title">✏️ Modifier — {{ $typeChambre->nom_type }}</h1>
        <p class="page-sub">{{ $hotel->nom }}</p>
    </div>
    <a href="{{ route('hoteladmin.types-chambre.index') }}" class="btn-ha-outline">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-admin p-4">
            <form method="POST" action="{{ route('hoteladmin.types-chambre.update', $typeChambre) }}"
                  enctype="multipart/form-data" class="form-ha">
                @csrf @method('PUT')

                <h6 style="font-size:13px;font-weight:800;color:#003580;text-transform:uppercase;letter-spacing:.5px;margin-bottom:16px;border-bottom:2px solid #f1f5f9;padding-bottom:8px;">
                    Informations générales
                </h6>
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <label>Nom du type *</label>
                        <input type="text" name="nom_type" class="form-control"
                               value="{{ old('nom_type', $typeChambre->nom_type) }}" required>
                    </div>
                    <div class="col-sm-6">
                        <label>Prix / nuit (DJF) *</label>
                        <input type="number" name="prix_par_nuit" class="form-control"
                               value="{{ old('prix_par_nuit', $typeChambre->prix_par_nuit) }}" min="0" required>
                    </div>
                    <div class="col-sm-6">
                        <label>Capacité *</label>
                        <input type="number" name="capacite" class="form-control"
                               value="{{ old('capacite', $typeChambre->capacite) }}" min="1" required>
                    </div>
                    <div class="col-sm-6">
                        <label>Superficie (m²)</label>
                        <input type="number" name="superficie_m2" class="form-control"
                               value="{{ old('superficie_m2', $typeChambre->superficie_m2) }}" min="0" step="0.5">
                    </div>
                    <div class="col-12">
                        <label>Description du lit</label>
                        <input type="text" name="lit_description" class="form-control"
                               value="{{ old('lit_description', $typeChambre->lit_description) }}">
                    </div>
                    <div class="col-12">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $typeChambre->description) }}</textarea>
                    </div>
                </div>

                <h6 style="font-size:13px;font-weight:800;color:#003580;text-transform:uppercase;letter-spacing:.5px;margin-bottom:16px;border-bottom:2px solid #f1f5f9;padding-bottom:8px;">
                    Équipements
                </h6>
                <div class="row g-3 mb-4">
                    <div class="col-sm-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="has_wifi" value="1" id="wifi"
                                   {{ old('has_wifi', $typeChambre->has_wifi) ? 'checked' : '' }}>
                            <label class="form-check-label" for="wifi" style="font-size:14px;font-weight:600;">WiFi</label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="has_climatisation" value="1" id="clim"
                                   {{ old('has_climatisation', $typeChambre->has_climatisation) ? 'checked' : '' }}>
                            <label class="form-check-label" for="clim" style="font-size:14px;font-weight:600;">Climatisation</label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="has_minibar" value="1" id="mini"
                                   {{ old('has_minibar', $typeChambre->has_minibar) ? 'checked' : '' }}>
                            <label class="form-check-label" for="mini" style="font-size:14px;font-weight:600;">Minibar</label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label>Équipements salle de bain</label>
                        <textarea name="equipements_salle_bain" class="form-control" rows="3">{{ old('equipements_salle_bain', $typeChambre->equipements_salle_bain) }}</textarea>
                    </div>
                    <div class="col-sm-6">
                        <label>Équipements généraux</label>
                        <textarea name="equipements_generaux" class="form-control" rows="3">{{ old('equipements_generaux', $typeChambre->equipements_generaux) }}</textarea>
                    </div>
                </div>

                <h6 style="font-size:13px;font-weight:800;color:#003580;text-transform:uppercase;letter-spacing:.5px;margin-bottom:16px;border-bottom:2px solid #f1f5f9;padding-bottom:8px;">
                    Ajouter des photos
                </h6>
                <div class="mb-3">
                    @if($typeChambre->images->count() > 0)
                    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px;">
                        @foreach($typeChambre->images as $img)
                        <img src="{{ $img->url }}" alt=""
                             style="width:80px;height:60px;object-fit:cover;border-radius:6px;border:2px solid #e2e8f0;"
                             onerror="this.src='{{ asset('images/ayla.jpg') }}'">
                        @endforeach
                    </div>
                    @endif
                    <input type="file" name="images[]" multiple accept="image/*" class="form-control">
                    <div style="font-size:12px;color:#94a3b8;margin-top:4px;">Les nouvelles photos s'ajouteront aux existantes</div>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn-ha-primary">
                        <i class="bi bi-check-lg"></i> Enregistrer
                    </button>
                    <a href="{{ route('hoteladmin.types-chambre.index') }}" class="btn-ha-outline">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-admin p-4">
            <h5 style="font-size:14px;font-weight:800;color:#003580;margin-bottom:12px;">
                <i class="bi bi-door-open me-2"></i>Chambres ({{ $typeChambre->chambres->count() }})
            </h5>
            @forelse($typeChambre->chambres as $ch)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f1f5f9;font-size:13px;">
                <span style="font-weight:700;">N° {{ $ch->numero }}</span>
                <span style="padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;
                    background:{{ $ch->etat==='DISPONIBLE'?'#dcfce7':($ch->etat==='OCCUPEE'?'#fee2e2':'#fef3c7') }};
                    color:{{ $ch->etat==='DISPONIBLE'?'#14532d':($ch->etat==='OCCUPEE'?'#991b1b':'#92400e') }};">
                    {{ $ch->etat }}
                </span>
            </div>
            @empty
            <p style="font-size:13px;color:#94a3b8;">Aucune chambre pour ce type.</p>
            @endforelse
            <a href="{{ route('hoteladmin.chambres.create') }}" class="btn-ha-primary mt-3 w-100" style="justify-content:center;">
                <i class="bi bi-plus-lg"></i> Ajouter une chambre
            </a>
        </div>
    </div>
</div>
@endsection