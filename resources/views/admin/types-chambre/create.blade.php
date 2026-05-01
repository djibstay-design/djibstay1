@extends('layouts.admin')
@section('page_title', 'Nouveau type de chambre')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
    <div>
        <h1 style="font-size:22px;font-weight:900;color:#1e293b;margin:0;">🏷️ Nouveau type de chambre</h1>
        <p style="font-size:13px;color:#64748b;margin:4px 0 0;">Créer un type pour un hôtel</p>
    </div>
    <a href="{{ route('admin.types-chambre.index') }}"
       style="background:#f1f5f9;color:#475569;padding:9px 16px;border-radius:8px;text-decoration:none;font-weight:700;font-size:13px;display:inline-flex;align-items:center;gap:6px;">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 12px rgba(0,53,128,0.07);overflow:hidden;">
            <div style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:14px 22px;">
                <span style="font-size:15px;font-weight:800;color:#003580;"><i class="bi bi-plus-circle me-2"></i>Informations du type</span>
            </div>
            <div style="padding:24px;">
                <form method="POST" action="{{ route('admin.types-chambre.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Hôtel *</label>
                            <select name="hotel_id" style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required>
                                <option value="">Sélectionner un hôtel...</option>
                                @foreach($hotels as $hotel)
                                <option value="{{ $hotel->id }}" {{ old('hotel_id')==$hotel->id?'selected':'' }}>{{ $hotel->nom }}</option>
                                @endforeach
                            </select>
                            @error('hotel_id')<div style="color:#dc2626;font-size:12px;margin-top:3px;">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Nom du type *</label>
                            <input type="text" name="nom_type" value="{{ old('nom_type') }}"
                                   placeholder="Ex : Suite Présidentielle"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required>
                            @error('nom_type')<div style="color:#dc2626;font-size:12px;margin-top:3px;">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Prix / nuit ({{ \App\Models\SiteSetting::get('app_devise','DJF') }}) *</label>
                            <input type="number" name="prix_par_nuit" value="{{ old('prix_par_nuit') }}"
                                   placeholder="Ex : 25000" min="0"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required>
                            @error('prix_par_nuit')<div style="color:#dc2626;font-size:12px;margin-top:3px;">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Capacité (pers.) *</label>
                            <input type="number" name="capacite" value="{{ old('capacite',2) }}" min="1"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required>
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Superficie (m²)</label>
                            <input type="number" name="superficie_m2" value="{{ old('superficie_m2') }}" min="0" step="0.5"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;">
                        </div>
                        <div class="col-12">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Description du lit</label>
                            <input type="text" name="lit_description" value="{{ old('lit_description') }}"
                                   placeholder="Ex : 1 lit King Size"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;">
                        </div>
                        <div class="col-12">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Description</label>
                            <textarea name="description" rows="3"
                                      placeholder="Décrivez ce type de chambre..."
                                      style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;resize:vertical;">{{ old('description') }}</textarea>
                        </div>

                        {{-- Équipements --}}
                        <div class="col-12">
                            <div style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:10px;">Équipements</div>
                            <div style="display:flex;gap:24px;flex-wrap:wrap;">
                                @foreach([['has_wifi','bi-wifi','WiFi gratuit'],['has_climatisation','bi-snow2','Climatisation'],['has_minibar','bi-cup-straw','Minibar']] as [$name,$icon,$label])
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <input type="checkbox" name="{{ $name }}" value="1" id="{{ $name }}"
                                           {{ old($name)?'checked':'' }}
                                           style="width:18px;height:18px;accent-color:#003580;cursor:pointer;">
                                    <label for="{{ $name }}" style="font-size:14px;font-weight:600;color:#1e293b;cursor:pointer;">
                                        <i class="bi {{ $icon }} me-1 text-primary"></i>{{ $label }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Équipements salle de bain</label>
                            <textarea name="equipements_salle_bain" rows="3"
                                      placeholder="Un équipement par ligne&#10;Ex: Douche&#10;Baignoire"
                                      style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;resize:vertical;">{{ old('equipements_salle_bain') }}</textarea>
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Équipements généraux</label>
                            <textarea name="equipements_generaux" rows="3"
                                      placeholder="Un équipement par ligne&#10;Ex: TV écran plat&#10;Coffre-fort"
                                      style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;resize:vertical;">{{ old('equipements_generaux') }}</textarea>
                        </div>

                        <div class="col-12">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Photos</label>
                            <input type="file" name="images[]" multiple accept="image/*"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;">
                            <div style="font-size:11px;color:#94a3b8;margin-top:3px;">JPG, PNG — Max 5 Mo par photo</div>
                        </div>

                        <div class="col-12">
                            <button type="submit"
                                    style="background:linear-gradient(135deg,#003580,#0071c2);color:#fff;border:none;border-radius:8px;font-weight:700;font-size:14px;padding:12px 24px;cursor:pointer;display:inline-flex;align-items:center;gap:7px;">
                                <i class="bi bi-check-lg"></i> Créer le type de chambre
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:18px 20px;">
            <div style="font-size:14px;font-weight:800;color:#003580;margin-bottom:12px;"><i class="bi bi-lightbulb me-1" style="color:#febb02;"></i>Conseils</div>
            <ul style="font-size:13px;color:#64748b;line-height:1.9;padding-left:18px;margin:0;">
                <li>Donnez un nom clair et attractif</li>
                <li>Indiquez le prix compétitif</li>
                <li>Cochez tous les équipements disponibles</li>
                <li>Ajoutez au moins 3-4 photos de qualité</li>
                <li>Une bonne description attire plus de clients</li>
            </ul>
        </div>
    </div>
</div>
@endsection