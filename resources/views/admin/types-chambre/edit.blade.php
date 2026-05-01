@extends('layouts.admin')
@section('page_title', 'Modifier type de chambre')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
    <div>
        <h1 style="font-size:22px;font-weight:900;color:#1e293b;margin:0;">✏️ {{ $typeChambre->nom_type }}</h1>
        <p style="font-size:13px;color:#64748b;margin:4px 0 0;">Modifier ce type de chambre</p>
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
                <span style="font-size:15px;font-weight:800;color:#003580;"><i class="bi bi-pencil-square me-2"></i>Informations</span>
            </div>
            <div style="padding:24px;">
                <form method="POST" action="{{ route('admin.types-chambre.update',$typeChambre) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Hôtel *</label>
                            <select name="hotel_id" style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required>
                                @foreach($hotels as $hotel)
                                <option value="{{ $hotel->id }}" {{ old('hotel_id',$typeChambre->hotel_id)==$hotel->id?'selected':'' }}>{{ $hotel->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Nom du type *</label>
                            <input type="text" name="nom_type" value="{{ old('nom_type',$typeChambre->nom_type) }}"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required>
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Prix / nuit ({{ \App\Models\SiteSetting::get('app_devise','DJF') }}) *</label>
                            <input type="number" name="prix_par_nuit" value="{{ old('prix_par_nuit',$typeChambre->prix_par_nuit) }}"
                                   min="0" style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required>
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Capacité *</label>
                            <input type="number" name="capacite" value="{{ old('capacite',$typeChambre->capacite) }}"
                                   min="1" style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required>
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Superficie (m²)</label>
                            <input type="number" name="superficie_m2" value="{{ old('superficie_m2',$typeChambre->superficie_m2) }}"
                                   min="0" step="0.5" style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;">
                        </div>
                        <div class="col-12">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Description du lit</label>
                            <input type="text" name="lit_description" value="{{ old('lit_description',$typeChambre->lit_description) }}"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;">
                        </div>
                        <div class="col-12">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Description</label>
                            <textarea name="description" rows="3"
                                      style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;resize:vertical;">{{ old('description',$typeChambre->description) }}</textarea>
                        </div>
                        <div class="col-12">
                            <div style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:10px;">Équipements</div>
                            <div style="display:flex;gap:24px;flex-wrap:wrap;">
                                @foreach([['has_wifi','bi-wifi','WiFi gratuit'],['has_climatisation','bi-snow2','Climatisation'],['has_minibar','bi-cup-straw','Minibar']] as [$name,$icon,$label])
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <input type="checkbox" name="{{ $name }}" value="1" id="{{ $name }}"
                                           {{ old($name,$typeChambre->$name)?'checked':'' }}
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
                                      style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;resize:vertical;">{{ old('equipements_salle_bain',$typeChambre->equipements_salle_bain) }}</textarea>
                        </div>
                        <div class="col-sm-6">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Équipements généraux</label>
                            <textarea name="equipements_generaux" rows="3"
                                      style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;resize:vertical;">{{ old('equipements_generaux',$typeChambre->equipements_generaux) }}</textarea>
                        </div>

                        {{-- Photos existantes --}}
                        @if($typeChambre->images->count() > 0)
                        <div class="col-12">
                            <div style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:10px;">Photos actuelles</div>
                            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                @foreach($typeChambre->images as $img)
                                <div style="position:relative;">
                                    <img src="{{ $img->url }}" alt="" style="width:80px;height:60px;object-fit:cover;border-radius:6px;border:2px solid #e2e8f0;">
                                    <form method="POST" action="{{ route('admin.types-chambre.images.destroy',$img) }}" style="position:absolute;top:-6px;right:-6px;">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="width:20px;height:20px;border-radius:50%;background:#dc2626;color:#fff;border:none;cursor:pointer;font-size:10px;display:flex;align-items:center;justify-content:center;" onclick="return confirm('Supprimer cette photo ?')">×</button>
                                    </form>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="col-12">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Ajouter des photos</label>
                            <input type="file" name="images[]" multiple accept="image/*"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;">
                        </div>
                        <div class="col-12">
                            <button type="submit"
                                    style="background:linear-gradient(135deg,#003580,#0071c2);color:#fff;border:none;border-radius:8px;font-weight:700;font-size:14px;padding:12px 24px;cursor:pointer;display:inline-flex;align-items:center;gap:7px;">
                                <i class="bi bi-check-lg"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:18px 20px;">
            <div style="font-size:14px;font-weight:800;color:#003580;margin-bottom:12px;"><i class="bi bi-door-open me-1"></i>Chambres ({{ $typeChambre->chambres->count() }})</div>
            @forelse($typeChambre->chambres as $ch)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f1f5f9;font-size:13px;">
                <span style="font-weight:700;">N° {{ $ch->numero }}</span>
                <span style="padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;background:{{ $ch->etat==='DISPONIBLE'?'#dcfce7':($ch->etat==='OCCUPEE'?'#fee2e2':'#fef3c7') }};color:{{ $ch->etat==='DISPONIBLE'?'#14532d':($ch->etat==='OCCUPEE'?'#991b1b':'#92400e') }};">
                    {{ $ch->etat }}
                </span>
            </div>
            @empty
            <p style="font-size:13px;color:#94a3b8;">Aucune chambre pour ce type.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection