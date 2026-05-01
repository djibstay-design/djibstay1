@extends('layouts.admin')
@section('page_title', 'Demande — '.$partenaire->nom_hotel)
@section('title', 'Demande Partenaire')

@push('styles')
<style>
.info-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:16px; }
@media(max-width:700px){ .info-grid { grid-template-columns:1fr; } }
.info-card { background:#fff; border-radius:14px; border:1px solid #e2e8f0; padding:20px; box-shadow:0 2px 10px rgba(0,53,128,0.07); }
.info-card h4 { font-size:13px; font-weight:800; color:#003580; margin-bottom:16px; text-transform:uppercase; letter-spacing:.4px; border-bottom:2px solid #f1f5f9; padding-bottom:10px; }
.info-row { display:flex; justify-content:space-between; align-items:flex-start; padding:8px 0; border-bottom:1px solid #f9fafb; }
.info-row:last-child { border-bottom:none; }
.info-label { font-size:12px; color:#64748b; font-weight:600; }
.info-value { font-size:13px; color:#1e293b; font-weight:600; text-align:right; max-width:60%; }
.action-btn { display:inline-flex; align-items:center; gap:7px; padding:10px 20px; border-radius:9px; font-size:13px; font-weight:700; border:none; cursor:pointer; text-decoration:none; transition:all .2s; }
.action-btn:hover { transform:translateY(-1px); }
.btn-blue   { background:#003580; color:#fff; }
.btn-blue:hover { background:#0071c2; color:#fff; }
.btn-green  { background:#16a34a; color:#fff; }
.btn-green:hover { background:#15803d; color:#fff; }
.btn-red    { background:#dc2626; color:#fff; }
.btn-red:hover { background:#b91c1c; color:#fff; }
.btn-yellow { background:#febb02; color:#003580; }
.btn-yellow:hover { background:#f5a623; color:#003580; }
.statut-badge { padding:5px 14px; border-radius:20px; font-size:12px; font-weight:700; }
.statut-en_attente    { background:#fef3c7; color:#92400e; }
.statut-en_discussion { background:#dbeafe; color:#1e40af; }
.statut-valide        { background:#dcfce7; color:#14532d; }
.statut-refuse        { background:#fee2e2; color:#991b1b; }
</style>
@endpush

@section('content')

{{-- Header --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
    <div style="display:flex;align-items:center;gap:12px;">
        <a href="{{ route('admin.partenaires.index') }}"
           style="background:#f1f5f9;color:#64748b;padding:8px 14px;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600;">
            ← Retour
        </a>
        <div>
            <h1 style="font-size:22px;font-weight:900;color:#003580;margin:0;">{{ $partenaire->nom_hotel }}</h1>
            <div style="font-size:13px;color:#64748b;margin-top:2px;">
                Demande reçue le {{ $partenaire->created_at->format('d/m/Y à H:i') }}
            </div>
        </div>
    </div>
    <span class="statut-badge statut-{{ $partenaire->statut }}">
        {{ $partenaire->statutLabel() }}
    </span>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;align-items:start;">

    {{-- COLONNE GAUCHE --}}
    <div>

        {{-- Infos contact --}}
        <div class="info-card" style="margin-bottom:20px;">
            <h4>👤 Informations contact</h4>
            <div class="info-row">
                <span class="info-label">Nom</span>
                <span class="info-value">{{ $partenaire->nom_contact }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value">
                    <a href="mailto:{{ $partenaire->email_contact }}" style="color:#0071c2;">
                        {{ $partenaire->email_contact }}
                    </a>
                </span>
            </div>
            @if($partenaire->telephone)
            <div class="info-row">
                <span class="info-label">Téléphone</span>
                <span class="info-value">{{ $partenaire->telephone }}</span>
            </div>
            @endif
        </div>

        {{-- Infos hôtel --}}
        <div class="info-card" style="margin-bottom:20px;">
            <h4>🏨 Informations hôtel</h4>
            <div class="info-row">
                <span class="info-label">Nom de l'hôtel</span>
                <span class="info-value">{{ $partenaire->nom_hotel }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Ville</span>
                <span class="info-value">{{ $partenaire->ville ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nombre de chambres</span>
                <span class="info-value">{{ $partenaire->nombre_chambres ?? '—' }}</span>
            </div>
            @if($partenaire->site_web)
            <div class="info-row">
                <span class="info-label">Site web</span>
                <span class="info-value">
                    <a href="{{ $partenaire->site_web }}" target="_blank" style="color:#0071c2;">
                        {{ $partenaire->site_web }}
                    </a>
                </span>
            </div>
            @endif
            @if($partenaire->description)
            <div style="margin-top:12px;padding-top:12px;border-top:1px solid #f1f5f9;">
                <div class="info-label" style="margin-bottom:6px;">Description</div>
                <p style="font-size:13px;color:#475569;line-height:1.7;margin:0;">{{ $partenaire->description }}</p>
            </div>
            @endif
            @if($partenaire->message)
            <div style="margin-top:12px;padding-top:12px;border-top:1px solid #f1f5f9;">
                <div class="info-label" style="margin-bottom:6px;">Message initial</div>
                <p style="font-size:13px;color:#475569;line-height:1.7;margin:0;font-style:italic;">
                    "{{ $partenaire->message }}"
                </p>
            </div>
            @endif
        </div>

        {{-- Notes admin --}}
        <div class="info-card">
            <h4>📝 Notes internes</h4>
            <form method="POST" action="{{ route('admin.partenaires.note',$partenaire) }}">
                @csrf
                <textarea name="notes_admin" rows="4"
                    style="width:100%;border:2px solid #e2e8f0;border-radius:9px;padding:10px 13px;font-size:13px;resize:vertical;font-family:inherit;margin-bottom:12px;"
                    placeholder="Ajoutez vos notes internes ici...">{{ $partenaire->notes_admin }}</textarea>
                <button type="submit" class="action-btn btn-blue" style="font-size:12px;padding:8px 16px;">
                    <i class="bi bi-save"></i> Sauvegarder
                </button>
            </form>
        </div>

    </div>

    {{-- COLONNE DROITE --}}
    <div>

        {{-- Actions --}}
        <div class="info-card" style="margin-bottom:20px;">
            <h4>⚡ Actions</h4>

            {{-- Changer statut --}}
            <form method="POST" action="{{ route('admin.partenaires.statut',$partenaire) }}" style="margin-bottom:16px;">
                @csrf @method('PATCH')
                <label style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.4px;display:block;margin-bottom:6px;">
                    Changer le statut
                </label>
                <div style="display:flex;gap:8px;">
                    <select name="statut" style="flex:1;border:2px solid #e2e8f0;border-radius:8px;padding:8px 10px;font-size:13px;font-weight:600;">
                        <option value="en_attente"    {{ $partenaire->statut==='en_attente'    ?'selected':'' }}>⏳ En attente</option>
                        <option value="en_discussion" {{ $partenaire->statut==='en_discussion' ?'selected':'' }}>💬 En discussion</option>
                        <option value="valide"        {{ $partenaire->statut==='valide'        ?'selected':'' }}>✅ Validé</option>
                        <option value="refuse"        {{ $partenaire->statut==='refuse'        ?'selected':'' }}>❌ Refusé</option>
                    </select>
                    <button type="submit" class="action-btn btn-blue" style="padding:8px 14px;font-size:12px;">OK</button>
                </div>
            </form>

            <hr style="border:none;border-top:1px solid #f1f5f9;margin:16px 0;">

            {{-- Envoyer invitation --}}
            @if($partenaire->statut !== 'valide' && $partenaire->statut !== 'refuse')
            <div style="margin-bottom:12px;">
                <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.4px;margin-bottom:8px;">
                    Formulaire d'inscription
                </div>
                @if($partenaire->token_invitation)
                <div style="background:#f0f7ff;border-radius:8px;padding:10px 12px;margin-bottom:10px;font-size:12px;color:#475569;">
                    <i class="bi bi-check-circle-fill" style="color:#16a34a;"></i>
                    Invitation envoyée le {{ $partenaire->invitation_envoyee_le?->format('d/m/Y') }}
                    <br>Expire le {{ $partenaire->token_expire_le?->format('d/m/Y') }}
                    @if($partenaire->formulaire_rempli)
                    <br><strong style="color:#16a34a;">✅ Formulaire rempli !</strong>
                    @endif
                </div>
                @endif
                <form method="POST" action="{{ route('admin.partenaires.invitation',$partenaire) }}">
                    @csrf
                    <button type="submit" class="action-btn btn-yellow" style="width:100%;justify-content:center;">
                        <i class="bi bi-envelope-fill"></i>
                        {{ $partenaire->token_invitation ? 'Renvoyer l\'invitation' : 'Envoyer le formulaire' }}
                    </button>
                </form>
            </div>
            @endif

            {{-- Valider --}}
            @if($partenaire->formulaire_rempli && $partenaire->statut !== 'valide')
            <form method="POST" action="{{ route('admin.partenaires.valider',$partenaire) }}"
                  onsubmit="return confirm('Créer automatiquement le compte Admin Hôtel et l\'hôtel ?')"
                  style="margin-bottom:10px;">
                @csrf
                <button type="submit" class="action-btn btn-green" style="width:100%;justify-content:center;padding:12px;white-space:normal;text-align:center;">
                    <i class="bi bi-check-circle-fill"></i> Créer automatiquement le compte Admin Hôtel et l'hôtel
                </button>
            </form>
            @endif

            {{-- Refuser --}}
            @if($partenaire->statut !== 'valide' && $partenaire->statut !== 'refuse')
            <form method="POST" action="{{ route('admin.partenaires.refuser',$partenaire) }}"
                  onsubmit="return confirm('Refuser cette demande ?')"
                  style="margin-bottom:10px;">
                @csrf
                <button type="submit" class="action-btn btn-red" style="width:100%;justify-content:center;">
                    <i class="bi bi-x-circle-fill"></i> Refuser la demande
                </button>
            </form>
            @endif

            {{-- Création Directe --}}
            @if($partenaire->statut !== 'valide')
            <div style="margin-top:16px; border-top:1px solid #f1f5f9; padding-top:16px;">
                <a href="{{ route('admin.partenaires.create', [
                    'nom'   => $partenaire->nom_contact,
                    'email' => $partenaire->email_contact,
                    'tel'   => $partenaire->telephone,
                    'hotel' => $partenaire->nom_hotel,
                    'ville' => $partenaire->ville
                ]) }}" 
                class="action-btn" style="width:100%;justify-content:center;background:linear-gradient(135deg,#003580,#0071c2);color:#fff;">
                    <i class="bi bi-magic"></i> Création directe (Pré-rempli)
                </a>
                <p style="font-size:10px;color:#94a3b8;margin-top:6px;text-align:center;">
                    Inscrire immédiatement sans attendre le formulaire du partenaire.
                </p>
            </div>
            @endif

        </div>

        {{-- Timeline --}}
        <div class="info-card">
            <h4>📅 Timeline</h4>
            <div style="font-size:12px;color:#475569;">
                <div style="display:flex;gap:10px;margin-bottom:10px;">
                    <div style="width:8px;height:8px;border-radius:50%;background:#003580;flex-shrink:0;margin-top:4px;"></div>
                    <div>
                        <div style="font-weight:700;color:#1e293b;">Demande reçue</div>
                        <div style="color:#94a3b8;">{{ $partenaire->created_at->format('d/m/Y à H:i') }}</div>
                    </div>
                </div>
                @if($partenaire->invitation_envoyee_le)
                <div style="display:flex;gap:10px;margin-bottom:10px;">
                    <div style="width:8px;height:8px;border-radius:50%;background:#febb02;flex-shrink:0;margin-top:4px;"></div>
                    <div>
                        <div style="font-weight:700;color:#1e293b;">Invitation envoyée</div>
                        <div style="color:#94a3b8;">{{ $partenaire->invitation_envoyee_le->format('d/m/Y à H:i') }}</div>
                    </div>
                </div>
                @endif
                @if($partenaire->formulaire_rempli)
                <div style="display:flex;gap:10px;margin-bottom:10px;">
                    <div style="width:8px;height:8px;border-radius:50%;background:#0071c2;flex-shrink:0;margin-top:4px;"></div>
                    <div>
                        <div style="font-weight:700;color:#1e293b;">Formulaire rempli</div>
                        <div style="color:#94a3b8;">{{ $partenaire->updated_at->format('d/m/Y à H:i') }}</div>
                    </div>
                </div>
                @endif
                @if($partenaire->valide_le)
                <div style="display:flex;gap:10px;">
                    <div style="width:8px;height:8px;border-radius:50%;background:#16a34a;flex-shrink:0;margin-top:4px;"></div>
                    <div>
                        <div style="font-weight:700;color:#16a34a;">✅ Compte créé</div>
                        <div style="color:#94a3b8;">{{ $partenaire->valide_le->format('d/m/Y à H:i') }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection