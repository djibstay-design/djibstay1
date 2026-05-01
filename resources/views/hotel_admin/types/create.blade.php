@extends('layouts.hotel_admin')
@section('page_title', 'Nouveau type de chambre')
@section('title', 'Créer un type de chambre')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h1 class="page-title">🏷️ Nouveau type de chambre</h1>
        <p class="page-sub">{{ $hotel->nom }}</p>
    </div>
    <a href="{{ route('hoteladmin.types-chambre.index') }}" class="btn-ha-outline">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-admin p-4">
            <form method="POST" action="{{ route('hoteladmin.types-chambre.store') }}"
                  enctype="multipart/form-data" class="form-ha">
                @csrf

                <h6 style="font-size:13px;font-weight:800;color:#003580;text-transform:uppercase;letter-spacing:.5px;margin-bottom:16px;border-bottom:2px solid #f1f5f9;padding-bottom:8px;">
                    Informations générales
                </h6>
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <label>Nom du type *</label>
                        <input type="text" name="nom_type" class="form-control"
                               value="{{ old('nom_type') }}"
                               placeholder="Ex : Suite Deluxe" required>
                    </div>
                    <div class="col-sm-6">
                        <label>Prix / nuit (DJF) *</label>
                        <input type="number" name="prix_par_nuit" class="form-control"
                               value="{{ old('prix_par_nuit') }}"
                               placeholder="Ex : 25000" min="0" required>
                    </div>
                    <div class="col-sm-6">
                        <label>Capacité (personnes) *</label>
                        <input type="number" name="capacite" class="form-control"
                               value="{{ old('capacite', 2) }}" min="1" required>
                    </div>
                    <div class="col-sm-6">
                        <label>Superficie (m²)</label>
                        <input type="number" name="superficie_m2" class="form-control"
                               value="{{ old('superficie_m2') }}" min="0" step="0.5">
                    </div>
                    <div class="col-12">
                        <label>Description du lit</label>
                        <input type="text" name="lit_description" class="form-control"
                               value="{{ old('lit_description') }}"
                               placeholder="Ex : 1 lit King Size">
                    </div>
                    <div class="col-12">
                        <label>Description générale</label>
                        <textarea name="description" class="form-control" rows="3"
                                  placeholder="Décrivez ce type de chambre...">{{ old('description') }}</textarea>
                    </div>
                </div>

                <h6 style="font-size:13px;font-weight:800;color:#003580;text-transform:uppercase;letter-spacing:.5px;margin-bottom:16px;border-bottom:2px solid #f1f5f9;padding-bottom:8px;">
                    Équipements
                </h6>
                <div class="row g-3 mb-4">
                    <div class="col-sm-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="has_wifi" value="1" id="wifi" {{ old('has_wifi') ? 'checked' : '' }}>
                            <label class="form-check-label" for="wifi" style="font-size:14px;font-weight:600;">WiFi gratuit</label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="has_climatisation" value="1" id="clim" {{ old('has_climatisation') ? 'checked' : '' }}>
                            <label class="form-check-label" for="clim" style="font-size:14px;font-weight:600;">Climatisation</label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="has_minibar" value="1" id="mini" {{ old('has_minibar') ? 'checked' : '' }}>
                            <label class="form-check-label" for="mini" style="font-size:14px;font-weight:600;">Minibar</label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label>Équipements salle de bain</label>
                        <textarea name="equipements_salle_bain" class="form-control" rows="3"
                                  placeholder="Un équipement par ligne&#10;Ex: Douche&#10;Baignoire&#10;Sèche-cheveux">{{ old('equipements_salle_bain') }}</textarea>
                    </div>
                    <div class="col-sm-6">
                        <label>Équipements généraux</label>
                        <textarea name="equipements_generaux" class="form-control" rows="3"
                                  placeholder="Un équipement par ligne&#10;Ex: TV écran plat&#10;Coffre-fort&#10;Bureau">{{ old('equipements_generaux') }}</textarea>
                    </div>
                </div>

                <h6 style="font-size:13px;font-weight:800;color:#003580;text-transform:uppercase;letter-spacing:.5px;margin-bottom:16px;border-bottom:2px solid #f1f5f9;padding-bottom:8px;">
                    Photos de la chambre
                </h6>
                <div class="mb-4">
                    <input type="file" name="images[]" multiple accept="image/*" class="form-control">
                    <div style="font-size:12px;color:#94a3b8;margin-top:4px;">Sélectionnez plusieurs photos (JPG, PNG — max 5 Mo)</div>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn-ha-primary">
                        <i class="bi bi-check-lg"></i> Créer le type
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
            <h5 style="font-size:14px;font-weight:800;color:#003580;margin-bottom:12px;">💡 Conseils</h5>
            <ul style="font-size:13px;color:#64748b;line-height:1.8;padding-left:16px;">
                <li>Donnez un nom clair et attractif</li>
                <li>Indiquez tous les équipements disponibles</li>
                <li>Ajoutez au moins 3-4 photos de qualité</li>
                <li>Le prix doit être compétitif</li>
                <li>Une bonne description attire plus de clients</li>
            </ul>
        </div>
    </div>
</div>
@endsection