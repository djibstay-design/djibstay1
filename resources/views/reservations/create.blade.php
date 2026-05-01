@extends('layouts.app')
@section('title', 'Réserver — '.$chambre->typeChambre->nom_type.' — '.(\App\Models\SiteSetting::get('app_name','DjibStay')))

@push('styles')
<style>
.resa-hero { background:linear-gradient(135deg,#003580,#0071c2); padding:32px 0; color:#fff; }
.resa-hero h1 { font-size:clamp(18px,3vw,28px); font-weight:900; }
.resa-layout { display:grid; grid-template-columns:1fr 340px; gap:24px; align-items:start; }
@media(max-width:991px){ .resa-layout { grid-template-columns:1fr; } }
.form-card { background:#fff; border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 2px 16px rgba(0,53,128,0.08); overflow:hidden; margin-bottom:16px; }
.form-card-header { background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:15px 22px; display:flex; align-items:center; gap:10px; }
.form-card-header h2 { font-size:15px; font-weight:800; color:#003580; margin:0; }
.step-badge { background:#003580; color:#fff; font-size:11px; font-weight:700; padding:3px 10px; border-radius:20px; }
.form-card-body { padding:22px; }
.form-label-djib { font-size:12px; font-weight:700; color:#003580; text-transform:uppercase; letter-spacing:.4px; margin-bottom:6px; display:block; }
.form-control-djib { border:2px solid #e2e8f0; border-radius:8px; padding:10px 13px; font-size:14px; color:#1a1a2e; width:100%; transition:border-color .2s; }
.form-control-djib:focus { border-color:#0071c2; box-shadow:0 0 0 3px rgba(0,113,194,0.11); outline:none; }
.upload-zone { border:2px dashed #cbd5e1; border-radius:10px; padding:20px; text-align:center; cursor:pointer; transition:all .2s; background:#f8fafc; position:relative; }
.upload-zone:hover { border-color:#0071c2; background:#f0f7ff; }
.upload-zone input { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
.btn-submit { background:linear-gradient(135deg,#003580,#0071c2); color:#fff; border:none; border-radius:10px; font-weight:800; font-size:15px; padding:14px; width:100%; cursor:pointer; transition:all .2s; box-shadow:0 4px 16px rgba(0,53,128,0.25); }
.btn-submit:hover { transform:translateY(-1px); box-shadow:0 8px 24px rgba(0,53,128,0.32); }
.recap-card { background:#fff; border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 2px 16px rgba(0,53,128,0.08); overflow:hidden; position:sticky; top:80px; }
.recap-header { background:linear-gradient(135deg,#003580,#0071c2); color:#fff; padding:16px 20px; }
.recap-header h3 { font-size:14px; font-weight:800; margin:0; }
</style>
@endpush

@section('content')
@php
    $appName = \App\Models\SiteSetting::get('app_name','DjibStay');
    $logoPath = \App\Models\SiteSetting::get('app_logo','');
@endphp

<section class="resa-hero">
    <div class="container" style="max-width:1200px;">
        <nav style="font-size:12px;color:rgba(255,255,255,0.7);margin-bottom:8px;">
            <a href="{{ route('home') }}" style="color:rgba(255,255,255,0.7);text-decoration:none;">Accueil</a> ›
            <a href="{{ route('hotels.show',$chambre->typeChambre->hotel) }}" style="color:rgba(255,255,255,0.7);text-decoration:none;">{{ $chambre->typeChambre->hotel->nom }}</a> ›
            <span style="color:#fff;">Réservation</span>
        </nav>
        <h1><i class="bi bi-calendar-plus me-2"></i>Formulaire de réservation</h1>
        <p style="color:rgba(255,255,255,0.82);font-size:13px;margin-top:4px;">
            {{ $chambre->typeChambre->hotel->nom }} · {{ $chambre->typeChambre->nom_type }} · Chambre N° {{ $chambre->numero }}
        </p>

        {{-- Stepper --}}
        <div style="display:flex;align-items:center;gap:0;margin-top:16px;">
            <div style="display:flex;align-items:center;gap:7px;font-size:13px;font-weight:700;">
                <span style="width:26px;height:26px;border-radius:50%;background:#febb02;color:#003580;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;">1</span>
                <span style="color:#fff;">Formulaire</span>
            </div>
            <div style="flex:1;height:2px;background:rgba(255,255,255,0.3);margin:0 10px;max-width:60px;"></div>
            <div style="display:flex;align-items:center;gap:7px;font-size:13px;font-weight:600;">
                <span style="width:26px;height:26px;border-radius:50%;background:rgba(255,255,255,0.25);color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;">2</span>
                <span style="color:rgba(255,255,255,0.65);">Paiement</span>
            </div>
            <div style="flex:1;height:2px;background:rgba(255,255,255,0.3);margin:0 10px;max-width:60px;"></div>
            <div style="display:flex;align-items:center;gap:7px;font-size:13px;font-weight:600;">
                <span style="width:26px;height:26px;border-radius:50%;background:rgba(255,255,255,0.25);color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;">3</span>
                <span style="color:rgba(255,255,255,0.65);">Confirmation</span>
            </div>
        </div>
    </div>
</section>

<div class="container py-4" style="max-width:1200px;">
    <div class="resa-layout">

        {{-- FORMULAIRE --}}
        <div>
            <form method="POST" action="{{ route('reservations.store') }}" enctype="multipart/form-data" id="resaForm">
                @csrf
                <input type="hidden" name="chambre_id" value="{{ $chambre->id }}">

                {{-- Infos personnelles --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <span class="step-badge">1</span>
                        <h2><i class="bi bi-person me-2"></i>Informations personnelles</h2>
                    </div>
                    <div class="form-card-body">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label-djib">Nom *</label>
                                <input type="text" name="nom_client" class="form-control-djib {{ $errors->has('nom_client')?'border-danger':'' }}"
                                       placeholder="Votre nom" value="{{ old('nom_client') }}" required>
                                @error('nom_client')<div style="color:#dc2626;font-size:12px;margin-top:3px;">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label-djib">Prénom *</label>
                                <input type="text" name="prenom_client" class="form-control-djib {{ $errors->has('prenom_client')?'border-danger':'' }}"
                                       placeholder="Votre prénom" value="{{ old('prenom_client') }}" required>
                                @error('prenom_client')<div style="color:#dc2626;font-size:12px;margin-top:3px;">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label-djib">Email *</label>
                                <input type="email" name="email_client" class="form-control-djib {{ $errors->has('email_client')?'border-danger':'' }}"
                                       placeholder="votre@email.com" value="{{ old('email_client') }}" required>
                                @error('email_client')<div style="color:#dc2626;font-size:12px;margin-top:3px;">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label-djib">Téléphone</label>
                                <input type="tel" name="telephone_client" class="form-control-djib" placeholder="+253 77 00 00 00" value="{{ old('telephone_client') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label-djib">N° Pièce d'identité / Passeport *</label>
                                <input type="text" name="code_identite" class="form-control-djib {{ $errors->has('code_identite')?'border-danger':'' }}"
                                       placeholder="Ex : DJ123456789" value="{{ old('code_identite') }}" required>
                                @error('code_identite')<div style="color:#dc2626;font-size:12px;margin-top:3px;">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Dates --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <span class="step-badge">2</span>
                        <h2><i class="bi bi-calendar3 me-2"></i>Dates du séjour</h2>
                    </div>
                    <div class="form-card-body">
                        <div class="row g-3">
                            <div class="col-sm-4">
                                <label class="form-label-djib">Arrivée *</label>
                                <input type="date" name="date_debut" id="date_debut" class="form-control-djib {{ $errors->has('date_debut')?'border-danger':'' }}"
                                       value="{{ old('date_debut',request('check_in')) }}" min="{{ date('Y-m-d') }}" required>
                                @error('date_debut')<div style="color:#dc2626;font-size:12px;margin-top:3px;">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label-djib">Départ *</label>
                                <input type="date" name="date_fin" id="date_fin" class="form-control-djib {{ $errors->has('date_fin')?'border-danger':'' }}"
                                       value="{{ old('date_fin',request('check_out')) }}" min="{{ date('Y-m-d',strtotime('+1 day')) }}" required>
                                @error('date_fin')<div style="color:#dc2626;font-size:12px;margin-top:3px;">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label-djib">Chambres *</label>
                                <select name="quantite" id="quantite" class="form-control-djib">
                                    @for($i=1;$i<=5;$i++)<option value="{{ $i }}" {{ old('quantite',1)==$i?'selected':'' }}>{{ $i }} chambre(s)</option>@endfor
                                </select>
                            </div>
                            <div class="col-12" id="duree-recap" style="display:none;">
                                <div style="background:#f0f7ff;border-radius:8px;padding:11px 14px;font-size:14px;color:#003580;font-weight:600;">
                                    <i class="bi bi-moon-stars me-2"></i><span id="duree-text"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Documents --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <span class="step-badge">3</span>
                        <h2><i class="bi bi-card-image me-2"></i>Documents d'identité</h2>
                    </div>
                    <div class="form-card-body">
                        <p style="font-size:13px;color:#64748b;margin-bottom:14px;">
                            <i class="bi bi-info-circle me-1 text-primary"></i>
                            Documents requis pour valider votre réservation. JPG, PNG — max 2 Mo.
                        </p>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label-djib">Photo pièce d'identité *</label>
                                <div class="upload-zone" id="zone-carte">
                                    <input type="file" name="photo_carte" accept="image/jpeg,image/png"
                                           onchange="previewImg(this,'prev-carte','zone-carte')" required>
                                    <div style="font-size:28px;color:#94a3b8;margin-bottom:6px;"><i class="bi bi-credit-card-2-front"></i></div>
                                    <div style="font-size:13px;font-weight:600;color:#475569;">CNI, Passeport ou Titre de séjour</div>
                                </div>
                                <div id="prev-carte" style="display:none;margin-top:8px;">
                                    <img src="" alt="" style="max-height:80px;border-radius:6px;border:2px solid #e2e8f0;">
                                </div>
                                @error('photo_carte')<div style="color:#dc2626;font-size:12px;margin-top:3px;">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label-djib">Photo du visage *</label>
                                <div class="upload-zone" id="zone-visage">
                                    <input type="file" name="photo_visage" accept="image/jpeg,image/png"
                                           onchange="previewImg(this,'prev-visage','zone-visage')" required>
                                    <div style="font-size:28px;color:#94a3b8;margin-bottom:6px;"><i class="bi bi-person-bounding-box"></i></div>
                                    <div style="font-size:13px;font-weight:600;color:#475569;">Photo récente, fond neutre</div>
                                </div>
                                <div id="prev-visage" style="display:none;margin-top:8px;">
                                    <img src="" alt="" style="max-height:80px;border-radius:6px;border:2px solid #e2e8f0;">
                                </div>
                                @error('photo_visage')<div style="color:#dc2626;font-size:12px;margin-top:3px;">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <button type="submit" class="btn-submit mt-4">
                            <i class="bi bi-lock-fill me-2"></i>Confirmer et passer au paiement
                        </button>
                        <p style="text-align:center;font-size:12px;color:#94a3b8;margin-top:8px;">
                            <i class="bi bi-shield-check me-1"></i>Données sécurisées — Acompte {{ \App\Models\SiteSetting::get('resa_acompte_percent','30') }}% demandé à l'étape suivante
                        </p>
                    </div>
                </div>
            </form>
        </div>

        {{-- RÉCAP SIDEBAR --}}
        <div>
            <div class="recap-card">
                <div class="recap-header">
                    <h3><i class="bi bi-receipt me-2"></i>Récapitulatif</h3>
                </div>
                <div style="padding:18px 20px;">
                    @php
                        $type  = $chambre->typeChambre;
                        $hotel = $type->hotel;
                        $imgs  = $type->images;
                        $imgUrl = $imgs->first() ? asset('storage/'.$imgs->first()->path) : asset('images/ayla.jpg');
                    @endphp
                    <img src="{{ $imgUrl }}" alt="{{ $type->nom_type }}" style="width:100%;height:150px;object-fit:cover;border-radius:8px;margin-bottom:14px;" onerror="this.src='{{ asset('images/ayla.jpg') }}'">
                    <div style="font-size:15px;font-weight:800;color:#003580;margin-bottom:2px;">{{ $hotel->nom }}</div>
                    <div style="font-size:13px;color:#0071c2;font-weight:600;margin-bottom:12px;">{{ $type->nom_type }}</div>
                    <div style="font-size:13px;border-bottom:1px solid #f1f5f9;padding-bottom:8px;margin-bottom:8px;display:flex;justify-content:space-between;">
                        <span style="color:#64748b;"><i class="bi bi-door-open me-1"></i>Chambre</span>
                        <span style="font-weight:700;">N° {{ $chambre->numero }}</span>
                    </div>
                    <div style="font-size:13px;border-bottom:1px solid #f1f5f9;padding-bottom:8px;margin-bottom:8px;display:flex;justify-content:space-between;">
                        <span style="color:#64748b;"><i class="bi bi-people me-1"></i>Capacité</span>
                        <span style="font-weight:700;">{{ $type->capacite }} pers.</span>
                    </div>
                    <div style="font-size:13px;border-bottom:1px solid #f1f5f9;padding-bottom:8px;margin-bottom:8px;display:flex;justify-content:space-between;">
                        <span style="color:#64748b;"><i class="bi bi-tag me-1"></i>Prix/nuit</span>
                        <span style="font-weight:700;">{{ number_format($type->prix_par_nuit,0,',',' ') }} {{ \App\Models\SiteSetting::get('app_devise','DJF') }}</span>
                    </div>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        @if($type->has_wifi)<span style="font-size:11px;background:#e8f0f8;color:#0071c2;padding:3px 8px;border-radius:4px;font-weight:600;"><i class="bi bi-wifi me-1"></i>WiFi</span>@endif
                        @if($type->has_climatisation)<span style="font-size:11px;background:#e8f0f8;color:#0071c2;padding:3px 8px;border-radius:4px;font-weight:600;"><i class="bi bi-snow2 me-1"></i>Clim</span>@endif
                        @if($type->has_minibar)<span style="font-size:11px;background:#e8f0f8;color:#0071c2;padding:3px 8px;border-radius:4px;font-weight:600;"><i class="bi bi-cup-straw me-1"></i>Minibar</span>@endif
                    </div>
                    <div id="recap-total" style="display:none;background:#f0f7ff;border-radius:10px;padding:12px 14px;margin-top:14px;">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <div>
                                <div style="font-size:13px;font-weight:700;color:#003580;">Total estimé</div>
                                <div style="font-size:11px;color:#64748b;" id="recap-detail"></div>
                            </div>
                            <div style="font-size:19px;font-weight:900;color:#003580;" id="recap-price">—</div>
                        </div>
                    </div>
                    <div style="background:#fef3c7;border-radius:8px;padding:9px 12px;font-size:12px;color:#92400e;font-weight:600;margin-top:10px;">
                        <i class="bi bi-info-circle me-1"></i>Acompte {{ \App\Models\SiteSetting::get('resa_acompte_percent','30') }}% requis — solde à l'hôtel
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
const prixNuit = {{ (float) $chambre->typeChambre->prix_par_nuit }};
function previewImg(input, prevId, zoneId) {
    const p = document.getElementById(prevId);
    if (input.files && input.files[0]) {
        const r = new FileReader();
        r.onload = e => { p.querySelector('img').src = e.target.result; p.style.display='block'; };
        r.readAsDataURL(input.files[0]);
    }
}
function updateRecap() {
    const debut = document.getElementById('date_debut').value;
    const fin   = document.getElementById('date_fin').value;
    const qte   = parseInt(document.getElementById('quantite').value)||1;
    if (debut && fin && fin > debut) {
        const nuits = Math.round((new Date(fin)-new Date(debut))/86400000);
        const total = prixNuit*qte*nuits;
        document.getElementById('duree-text').textContent = nuits+' nuit(s) · '+qte+' chambre(s)';
        document.getElementById('duree-recap').style.display = 'block';
        document.getElementById('recap-price').textContent  = total.toLocaleString('fr-FR')+' {{ \App\Models\SiteSetting::get('app_devise','DJF') }}';
        document.getElementById('recap-detail').textContent = nuits+' nuits × '+qte+' ch. × '+prixNuit.toLocaleString('fr-FR')+' {{ \App\Models\SiteSetting::get('app_devise','DJF') }}';
        document.getElementById('recap-total').style.display= 'block';
    } else {
        document.getElementById('duree-recap').style.display='none';
        document.getElementById('recap-total').style.display='none';
    }
}
document.getElementById('date_debut').addEventListener('change', function() {
    const n = new Date(this.value); n.setDate(n.getDate()+1);
    document.getElementById('date_fin').min = n.toISOString().split('T')[0];
    updateRecap();
});
document.getElementById('date_fin').addEventListener('change', updateRecap);
document.getElementById('quantite').addEventListener('change', updateRecap);
updateRecap();
</script>
@endpush