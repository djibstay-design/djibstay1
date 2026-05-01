@extends('layouts.admin')
@section('page_title', 'Ajouter un Partenaire (Manuel)')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
    <div>
        <h1 style="font-size:22px;font-weight:900;color:#1e293b;margin:0;">🤝 Création manuelle</h1>
        <p style="font-size:13px;color:#64748b;margin:4px 0 0;">Inscrire un partenaire et son hôtel directement</p>
    </div>
    <a href="{{ route('admin.partenaires.index') }}" 
       style="background:#f1f5f9;color:#475569;padding:9px 16px;border-radius:8px;text-decoration:none;font-weight:700;font-size:13px;display:inline-flex;align-items:center;gap:6px;">
        <i class="bi bi-arrow-left"></i> Retour à la liste
    </a>
</div>

<form method="POST" action="{{ route('admin.partenaires.store') }}">
    @csrf
    <div class="row g-4">
        
        {{-- Section Partenaire --}}
        <div class="col-lg-6">
            <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 12px rgba(0,53,128,0.07);overflow:hidden;height:100%;">
                <div style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:14px 22px;">
                    <span style="font-size:15px;font-weight:800;color:#003580;"><i class="bi bi-person-badge me-2"></i>Informations du Partenaire</span>
                </div>
                <div style="padding:24px;">
                    <div class="mb-3">
                        <label class="form-label-custom">Nom complet *</label>
                        <input type="text" name="nom_contact" value="{{ old('nom_contact', $prefill['nom'] ?? '') }}" class="form-control-custom" placeholder="Ex: Jean Dupont" required>
                        @error('nom_contact') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Email de connexion *</label>
                        <input type="email" name="email_contact" value="{{ old('email_contact', $prefill['email'] ?? '') }}" class="form-control-custom" placeholder="jean@email.com" required>
                        @error('email_contact') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Téléphone</label>
                        <input type="text" name="telephone" value="{{ old('telephone', $prefill['tel'] ?? '') }}" class="form-control-custom" placeholder="+253 ...">
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label-custom">Mot de passe *</label>
                            <input type="password" name="password" class="form-control-custom" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Confirmer mot de passe *</label>
                            <input type="password" name="password_confirmation" class="form-control-custom" required>
                        </div>
                    </div>
                    @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- Section Hôtel --}}
        <div class="col-lg-6">
            <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 12px rgba(0,53,128,0.07);overflow:hidden;height:100%;">
                <div style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:14px 22px;">
                    <span style="font-size:15px;font-weight:800;color:#003580;"><i class="bi bi-building me-2"></i>Informations de l'Hôtel</span>
                </div>
                <div style="padding:24px;">
                    <div class="mb-3">
                        <label class="form-label-custom">Nom de l'hôtel *</label>
                        <input type="text" name="nom_hotel" value="{{ old('nom_hotel', $prefill['hotel'] ?? '') }}" class="form-control-custom" placeholder="Ex: Sheraton Djibouti" required>
                        @error('nom_hotel') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Ville *</label>
                            <select name="ville" class="form-control-custom" required>
                                @foreach(['Djibouti', 'Tadjourah', 'Obock', 'Dikhil', 'Ali Sabieh', 'Arta'] as $v)
                                <option value="{{ $v }}" {{ (old('ville', $prefill['ville'] ?? '') == $v) ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Adresse complète *</label>
                            <input type="text" name="adresse" value="{{ old('adresse') }}" class="form-control-custom" placeholder="Quartier, Rue..." required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Description courte</label>
                        <textarea name="description" rows="4" class="form-control-custom" placeholder="Quelques mots sur l'hôtel...">{{ old('description') }}</textarea>
                    </div>

                    <div style="background:#fef3c7;border-radius:8px;padding:12px;display:flex;gap:10px;align-items:flex-start;">
                        <i class="bi bi-info-circle-fill text-warning"></i>
                        <p style="font-size:12px;color:#92400e;margin:0;">
                            <strong>Note :</strong> Après la création, le partenaire pourra uploader ses photos et ajouter ses types de chambres depuis son espace dédié.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 text-center mt-4 mb-5">
            <button type="submit" 
                    style="background:linear-gradient(135deg,#003580,#0071c2);color:#fff;border:none;padding:14px 40px;border-radius:12px;font-weight:800;font-size:16px;cursor:pointer;box-shadow:0 8px 24px rgba(0,53,128,0.25);transition:all .2s;">
                <i class="bi bi-check2-circle me-2"></i> Finaliser l'inscription manuelle
            </button>
        </div>

    </div>
</form>

<style>
.form-label-custom { font-size:12px; font-weight:700; color:#003580; text-transform:uppercase; letter-spacing:.4px; margin-bottom:6px; display:block; }
.form-control-custom { border:2px solid #e2e8f0; border-radius:8px; padding:10px 13px; font-size:14px; width:100%; transition:all .2s; outline:none; }
.form-control-custom:focus { border-color:#0071c2; box-shadow:0 0 0 4px rgba(0,113,194,0.1); }
</style>
@endsection
