<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulaire d'inscription Partenaire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background:linear-gradient(135deg,#003580,#0071c2); min-height:100vh; padding:40px 16px; font-family:'Inter',sans-serif; }
        .form-card { background:#fff; border-radius:20px; box-shadow:0 24px 64px rgba(0,0,0,0.2); padding:40px; max-width:680px; margin:0 auto; }
        .form-header { text-align:center; margin-bottom:32px; }
        .form-header img { height:60px; object-fit:contain; margin-bottom:12px; }
        .form-header h1 { font-size:24px; font-weight:900; color:#003580; margin-bottom:6px; }
        .form-header p { font-size:14px; color:#64748b; }
        .section-title { font-size:13px; font-weight:800; color:#003580; text-transform:uppercase; letter-spacing:.5px; margin-bottom:16px; padding-bottom:8px; border-bottom:2px solid #f1f5f9; }
        .form-label { font-size:12px; font-weight:700; color:#003580; text-transform:uppercase; letter-spacing:.4px; }
        .form-control, .form-select { border:2px solid #e2e8f0; border-radius:9px; padding:11px 14px; font-size:14px; }
        .form-control:focus, .form-select:focus { border-color:#0071c2; box-shadow:0 0 0 3px rgba(0,113,194,0.1); }
        .btn-submit { background:linear-gradient(135deg,#003580,#0071c2); color:#fff; border:none; border-radius:10px; font-weight:800; font-size:15px; padding:14px; width:100%; cursor:pointer; transition:all .2s; box-shadow:0 4px 16px rgba(0,53,128,0.3); }
        .btn-submit:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,53,128,0.4); }
    </style>
</head>
<body>
<div class="form-card">

    {{-- Header --}}
    <div class="form-header">
        @php $logoPath = \App\Models\SiteSetting::get('app_logo',''); $appName = \App\Models\SiteSetting::get('app_name','DjibStay'); @endphp
        @if($logoPath)
            <img src="{{ asset('storage/'.$logoPath) }}" alt="{{ $appName }}">
        @endif
        <h1>Rejoignez {{ $appName }}</h1>
        <p>Remplissez ce formulaire pour finaliser votre inscription en tant que partenaire hôtelier.</p>
    </div>

    @if($errors->any())
    <div class="alert alert-danger mb-4" style="border-radius:10px;">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('partenaire.soumettre',$token) }}">
        @csrf

        {{-- Contact --}}
        <div class="section-title">👤 Vos informations</div>
        <div class="row g-3 mb-4">
            <div class="col-sm-6">
                <label class="form-label">Nom complet *</label>
                <input type="text" name="nom_contact" class="form-control" value="{{ old('nom_contact',$demande->nom_contact) }}" required>
            </div>
            <div class="col-sm-6">
                <label class="form-label">Téléphone</label>
                <input type="tel" name="telephone" class="form-control" value="{{ old('telephone',$demande->telephone) }}" placeholder="+253 77 00 00 00">
            </div>
            <div class="col-12">
                <label class="form-label">Email *</label>
                <input type="email" name="email_contact" class="form-control" value="{{ old('email_contact',$demande->email_contact) }}" required>
            </div>
        </div>

        {{-- Hôtel --}}
        <div class="section-title">🏨 Informations de votre hôtel</div>
        <div class="row g-3 mb-4">
            <div class="col-sm-8">
                <label class="form-label">Nom de l'hôtel *</label>
                <input type="text" name="nom_hotel" class="form-control" value="{{ old('nom_hotel',$demande->nom_hotel) }}" required>
            </div>
            <div class="col-sm-4">
                <label class="form-label">Nombre de chambres</label>
                <input type="number" name="nombre_chambres" class="form-control" value="{{ old('nombre_chambres',$demande->nombre_chambres) }}" min="1">
            </div>
            <div class="col-sm-6">
                <label class="form-label">Ville *</label>
                <input type="text" name="ville" class="form-control" value="{{ old('ville',$demande->ville) }}" required>
            </div>
            <div class="col-sm-6">
                <label class="form-label">Adresse *</label>
                <input type="text" name="adresse" class="form-control" value="{{ old('adresse') }}" required>
            </div>
            <div class="col-12">
                <label class="form-label">Site web</label>
                <input type="url" name="site_web" class="form-control" value="{{ old('site_web',$demande->site_web) }}" placeholder="https://...">
            </div>
            <div class="col-12">
                <label class="form-label">Description de l'hôtel</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Décrivez votre hôtel, ses équipements, son ambiance...">{{ old('description',$demande->description) }}</textarea>
            </div>
        </div>

        <button type="submit" class="btn-submit">
            <i class="bi bi-send-fill me-2"></i> Soumettre mon dossier
        </button>
    </form>
</div>
</body>
</html>