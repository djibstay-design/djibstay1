@extends('layouts.app')
@section('title', \App\Models\SiteSetting::get('app_name','DjibStay').' — Contact')

@push('styles')
<style>
.contact-hero {
    position: relative;
    overflow: hidden;
    padding: 52px 0;
    color: #fff;
    text-align: center;
    background: url('https://images.unsplash.com/photo-1582719508461-905c673771fd?q=80&w=1920&auto=format&fit=crop') center/cover no-repeat;
}
.contact-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(160deg, rgba(0,30,80,0.62) 0%, rgba(0,80,160,0.48) 100%);
}
.contact-hero .container { position: relative; z-index: 2; }
.contact-hero h1 { font-size:clamp(24px,4vw,40px); font-weight:900; }
.contact-card { background:#fff; border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 4px 24px rgba(0,53,128,0.09); padding:32px; }
.form-label-djib { font-size:12px; font-weight:700; color:#003580; text-transform:uppercase; letter-spacing:.5px; margin-bottom:6px; display:block; }
.form-control-djib { border:2px solid #e2e8f0; border-radius:8px; padding:10px 14px; font-size:14px; color:#1a1a2e; width:100%; transition:border-color .2s; }
.form-control-djib:focus { border-color:#0071c2; box-shadow:0 0 0 3px rgba(0,113,194,0.12); outline:none; }
.btn-send { background:linear-gradient(135deg,#003580,#0071c2); color:#fff; border:none; border-radius:8px; font-weight:700; font-size:15px; padding:12px; width:100%; cursor:pointer; transition:all .2s; }
.btn-send:hover { transform:translateY(-1px); }
.info-icon { width:44px; height:44px; background:linear-gradient(135deg,#003580,#0071c2); border-radius:10px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:18px; flex-shrink:0; }
.support-card { background:#fff; border-radius:12px; border:1px solid #e2e8f0; padding:20px; display:flex; align-items:center; gap:16px; box-shadow:0 2px 10px rgba(0,53,128,0.07); transition:all .25s; }
.support-card:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,53,128,0.13); }
.support-avatar { width:56px; height:56px; background:linear-gradient(135deg,#003580,#0071c2); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:22px; font-weight:800; color:#fff; flex-shrink:0; }
.online-dot { width:10px; height:10px; background:#22c55e; border-radius:50%; display:inline-block; margin-right:4px; animation:pulse 2s infinite; }
@keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:.5;} }
.faq-item { border-bottom:1px solid #f1f5f9; padding:14px 0; }
.faq-item:last-child { border-bottom:none; }

/* Onglets */
.tab-nav { display:flex; gap:0; margin-bottom:24px; border-bottom:2px solid #e2e8f0; }
.tab-btn { padding:12px 20px; font-size:14px; font-weight:700; color:#64748b; background:none; border:none; cursor:pointer; border-bottom:3px solid transparent; margin-bottom:-2px; transition:all .2s; display:flex; align-items:center; gap:7px; }
.tab-btn:hover { color:#003580; }
.tab-btn.active { color:#003580; border-bottom-color:#003580; }
.tab-content { display:none; }
.tab-content.active { display:block; }

/* Conditions partenariat */
.conditions-box { background:#f0f7ff; border-radius:10px; border:1px solid #bfdbfe; padding:18px 20px; margin-bottom:20px; max-height:240px; overflow-y:auto; }
.conditions-box h4 { font-size:14px; font-weight:800; color:#003580; margin-bottom:12px; }
.conditions-box ul { font-size:13px; color:#475569; line-height:1.9; padding-left:18px; margin:0; }
.conditions-box ul li { margin-bottom:4px; }
.success-partenaire { background:#dcfce7; border:1px solid #bbf7d0; border-radius:12px; padding:20px 24px; text-align:center; }
</style>
@endpush

@section('content')
@php
    $appName    = \App\Models\SiteSetting::get('app_name','DjibStay');
    $adresse    = \App\Models\SiteSetting::get('contact_adresse','Plateau du Serpent, Djibouti-Ville');
    $ville      = \App\Models\SiteSetting::get('contact_ville','Djibouti-Ville');
    $telephone  = \App\Models\SiteSetting::get('contact_telephone','+253 77 00 00 00');
    $email      = \App\Models\SiteSetting::get('contact_email','contact@djibstay.dj');
    $whatsapp   = \App\Models\SiteSetting::get('contact_whatsapp','+253 77 00 00 00');
    $horaires   = \App\Models\SiteSetting::get('support_horaires','Lun–Ven : 8h00 – 17h00');
    $teamCount  = (int) \App\Models\SiteSetting::get('support_team_count','2');
    $logoPath   = \App\Models\SiteSetting::get('app_logo','');
    $activeTab  = session('success_partenaire') ? 'partenaire' : (old('_tab','contact'));
@endphp

{{-- HERO --}}
<section class="contact-hero">
    <div class="container">
        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;margin-bottom:28px;">
            @if($logoPath)
                <img src="{{ asset('storage/'.$logoPath) }}" alt="{{ $appName }}"
                     style="height:60px;max-width:220px;object-fit:contain;display:block;margin-bottom:22px;">
            @else
                <div style="font-size:48px;margin-bottom:22px;line-height:1;">📬</div>
            @endif
            <h1 style="margin:0 0 10px 0;font-size:28px;font-weight:700;letter-spacing:-0.5px;text-align:center;">
                Contactez-nous
            </h1>
            <p style="color:rgba(255,255,255,0.78);font-size:15px;margin:0;text-align:center;max-width:320px;line-height:1.6;">
                Une question ? Notre équipe vous répond rapidement.
            </p>
        </div>
    </div>
</section>

<div class="container py-5" style="max-width:1200px;">
    <div class="row g-4">

        {{-- FORMULAIRES avec onglets --}}
        <div class="col-lg-7">
            <div class="contact-card">

                {{-- Onglets --}}
                <div class="tab-nav">
                    <button class="tab-btn {{ $activeTab === 'contact' ? 'active' : '' }}"
                            onclick="switchTab('contact')">
                        <i class="bi bi-envelope-paper"></i> Nous contacter
                    </button>
                    <button class="tab-btn {{ $activeTab === 'partenaire' ? 'active' : '' }}"
                            onclick="switchTab('partenaire')">
                        <i class="bi bi-building-add"></i> Devenir Partenaire
                    </button>
                </div>

                {{-- ═══ ONGLET CONTACT ═══ --}}
                <div id="tab-contact" class="tab-content {{ $activeTab === 'contact' ? 'active' : '' }}">
                    <h2 style="font-size:18px;font-weight:800;color:#003580;margin-bottom:6px;">
                        <i class="bi bi-envelope-paper me-2"></i>Envoyer un message
                    </h2>
                    <p style="font-size:13px;color:#64748b;margin-bottom:20px;">
                        Remplissez le formulaire, nous vous répondrons sous 24h.
                    </p>

                    @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                        <i class="bi bi-check-circle-fill fs-5"></i>{{ session('success') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('pages.contact.submit') }}">
                        @csrf
                        <input type="hidden" name="_tab" value="contact">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label-djib">Votre nom *</label>
                                <input type="text" name="name" class="form-control-djib"
                                       placeholder="Mohamed Ali" value="{{ old('name') }}" required>
                                @error('name')<div style="color:#dc2626;font-size:12px;margin-top:3px;">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label-djib">Email *</label>
                                <input type="email" name="email" class="form-control-djib"
                                       placeholder="vous@exemple.com" value="{{ old('email') }}" required>
                                @error('email')<div style="color:#dc2626;font-size:12px;margin-top:3px;">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label-djib">Téléphone</label>
                                <input type="tel" name="phone" class="form-control-djib"
                                       placeholder="+253 77 00 00 00" value="{{ old('phone') }}">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label-djib">Sujet</label>
                                <select name="subject" class="form-control-djib">
                                    <option value="">Choisir...</option>
                                    <option value="Réservation" {{ old('subject')==='Réservation'?'selected':'' }}>Réservation</option>
                                    <option value="Paiement"    {{ old('subject')==='Paiement'   ?'selected':'' }}>Paiement</option>
                                    <option value="Annulation"  {{ old('subject')==='Annulation' ?'selected':'' }}>Annulation</option>
                                    <option value="Partenariat" {{ old('subject')==='Partenariat'?'selected':'' }}>Partenariat hôtel</option>
                                    <option value="Autre"       {{ old('subject')==='Autre'      ?'selected':'' }}>Autre</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label-djib">Message *</label>
                                <textarea name="message" class="form-control-djib" rows="5"
                                          placeholder="Décrivez votre demande..." required>{{ old('message') }}</textarea>
                                @error('message')<div style="color:#dc2626;font-size:12px;margin-top:3px;">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn-send">
                                    <i class="bi bi-send me-2"></i>Envoyer le message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- ═══ ONGLET PARTENAIRE ═══ --}}
                <div id="tab-partenaire" class="tab-content {{ $activeTab === 'partenaire' ? 'active' : '' }}">
                    <h2 style="font-size:18px;font-weight:800;color:#003580;margin-bottom:6px;">
                        <i class="bi bi-building-add me-2"></i>Rejoindre {{ $appName }}
                    </h2>
                    <p style="font-size:13px;color:#64748b;margin-bottom:20px;">
                        Vous gérez un hôtel ? Rejoignez notre plateforme et augmentez votre visibilité.
                    </p>

                    {{-- Succès --}}
                    @if(session('success_partenaire'))
                    <div class="success-partenaire">
                        <div style="font-size:40px;margin-bottom:12px;">🎉</div>
                        <div style="font-size:16px;font-weight:800;color:#15803d;margin-bottom:8px;">
                            Demande envoyée avec succès !
                        </div>
                        <p style="font-size:13px;color:#64748b;margin:0;">
                            {{ session('success_partenaire') }}
                        </p>
                    </div>

                    @else

                    {{-- Avantages --}}
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:20px;">
                        @foreach([
                            ['🏆','Visibilité','Accédez à des milliers de clients'],
                            ['💰','Revenus','Augmentez vos réservations'],
                            ['📊','Dashboard','Gérez tout en ligne'],
                        ] as [$icon,$title,$desc])
                        <div style="background:#f0f7ff;border-radius:10px;padding:14px;text-align:center;">
                            <div style="font-size:24px;margin-bottom:6px;">{{ $icon }}</div>
                            <div style="font-size:12px;font-weight:800;color:#003580;">{{ $title }}</div>
                            <div style="font-size:11px;color:#64748b;margin-top:2px;">{{ $desc }}</div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Conditions --}}
                    <div class="conditions-box">
                        <h4>📋 Conditions de partenariat</h4>
                        <ul>
                            <li>Commission de <strong>5%</strong> sur chaque réservation confirmée, prélevée mensuellement le 1er du mois</li>
                            <li>L'hôtel doit disposer d'un minimum de <strong>3 chambres</strong> disponibles à la réservation</li>
                            <li>Photos de qualité et description complète obligatoires dans les <strong>7 jours</strong> suivant l'activation</li>
                            <li>Respect de la charte qualité {{ $appName }} : propreté, accueil, conformité des photos</li>
                            <li>Confirmation des réservations sous <strong>24h</strong> maximum après réception</li>
                            <li>Toute annulation de l'hôtel après confirmation engage sa responsabilité</li>
                            <li>{{ $appName }} se réserve le droit de suspendre un partenaire en cas de manquements répétés</li>
                            <li>Les tarifs affichés doivent être exacts et mis à jour régulièrement</li>
                            <li>Le partenaire s'engage à maintenir un taux de disponibilité de <strong>70%</strong> minimum</li>
                            <li>Accord valable pour une durée d'<strong>1 an</strong>, renouvelable tacitement</li>
                        </ul>
                    </div>

                    {{-- Formulaire partenaire --}}
                    <form method="POST" action="{{ route('pages.partenaire.submit') }}">
                        @csrf
                        <input type="hidden" name="_tab" value="partenaire">

                        <div style="font-size:12px;font-weight:800;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:12px;padding-bottom:8px;border-bottom:2px solid #f1f5f9;">
                            👤 Vos informations
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <label class="form-label-djib">Nom complet *</label>
                                <input type="text" name="nom_contact" class="form-control-djib"
                                       placeholder="Mohamed Ali" value="{{ old('nom_contact') }}" required>
                                @error('nom_contact')<div style="color:#dc2626;font-size:12px;margin-top:3px;">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label-djib">Téléphone</label>
                                <input type="tel" name="telephone" class="form-control-djib"
                                       placeholder="+253 77 00 00 00" value="{{ old('telephone') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label-djib">Email *</label>
                                <input type="email" name="email_contact" class="form-control-djib"
                                       placeholder="votre@email.com" value="{{ old('email_contact') }}" required>
                                @error('email_contact')<div style="color:#dc2626;font-size:12px;margin-top:3px;">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div style="font-size:12px;font-weight:800;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:12px;padding-bottom:8px;border-bottom:2px solid #f1f5f9;">
                            🏨 Votre hôtel
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-sm-8">
                                <label class="form-label-djib">Nom de l'hôtel *</label>
                                <input type="text" name="nom_hotel" class="form-control-djib"
                                       placeholder="Hôtel exemple" value="{{ old('nom_hotel') }}" required>
                                @error('nom_hotel')<div style="color:#dc2626;font-size:12px;margin-top:3px;">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label-djib">Nb. chambres</label>
                                <input type="number" name="nombre_chambres" class="form-control-djib"
                                       placeholder="Ex: 20" value="{{ old('nombre_chambres') }}" min="1">
                            </div>
                            <div class="col-12">
                                <label class="form-label-djib">Ville</label>
                                <input type="text" name="ville" class="form-control-djib"
                                       placeholder="Djibouti-Ville" value="{{ old('ville') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label-djib">Message (optionnel)</label>
                                <textarea name="message" class="form-control-djib" rows="3"
                                          placeholder="Parlez-nous de votre hôtel...">{{ old('message') }}</textarea>
                            </div>
                        </div>

                        {{-- Checkbox conditions --}}
                        <div style="background:#fef3c7;border-radius:10px;border:1px solid #fde68a;padding:14px 16px;margin-bottom:16px;">
                            <label style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;font-size:13px;color:#475569;line-height:1.6;">
                                <input type="checkbox" name="accepte_conditions" id="accepte_conditions"
                                       style="width:18px;height:18px;margin-top:2px;accent-color:#003580;cursor:pointer;flex-shrink:0;"
                                       {{ old('accepte_conditions') ? 'checked' : '' }} required>
                                <span>
                                    J'ai lu et j'accepte les
                                    <strong style="color:#003580;">conditions de partenariat</strong>
                                    de {{ $appName }} décrites ci-dessus. Je m'engage à les respecter.
                                </span>
                            </label>
                            @error('accepte_conditions')
                            <div style="color:#dc2626;font-size:12px;margin-top:6px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn-send"
                                style="background:linear-gradient(135deg,#16a34a,#15803d);">
                            <i class="bi bi-send-fill me-2"></i>Envoyer ma demande de partenariat
                        </button>
                    </form>
                    @endif
                </div>

            </div>
        </div>

        {{-- INFOS + ÉQUIPE --}}
        <div class="col-lg-5 d-flex flex-column gap-4">

            {{-- Coordonnées --}}
            <div class="contact-card">
                <h3 style="font-size:16px;font-weight:800;color:#003580;margin-bottom:16px;">
                    <i class="bi bi-pin-map me-2"></i>Nos coordonnées
                </h3>
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="info-icon"><i class="bi bi-geo-alt-fill"></i></div>
                    <div>
                        <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:2px;">Adresse</div>
                        <div style="font-size:14px;font-weight:600;color:#1e293b;">{{ $adresse }}</div>
                    </div>
                </div>
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="info-icon"><i class="bi bi-envelope-fill"></i></div>
                    <div>
                        <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:2px;">Email</div>
                        <div style="font-size:14px;font-weight:600;color:#1e293b;">{{ $email }}</div>
                    </div>
                </div>
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="info-icon"><i class="bi bi-telephone-fill"></i></div>
                    <div>
                        <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:2px;">Téléphone</div>
                        <div style="font-size:14px;font-weight:600;color:#1e293b;">{{ $telephone }}</div>
                    </div>
                </div>
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="info-icon" style="background:#25d366;"><i class="bi bi-whatsapp"></i></div>
                    <div>
                        <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:2px;">WhatsApp</div>
                        <a href="https://wa.me/{{ preg_replace('/\D/','',$whatsapp) }}" target="_blank"
                           style="font-size:14px;font-weight:600;color:#25d366;text-decoration:none;">{{ $whatsapp }}</a>
                    </div>
                </div>
                <div class="d-flex align-items-start gap-3">
                    <div class="info-icon"><i class="bi bi-clock-fill"></i></div>
                    <div>
                        <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:2px;">Horaires</div>
                        <div style="font-size:14px;font-weight:600;color:#1e293b;">{{ $horaires }}</div>
                    </div>
                </div>
            </div>

            {{-- Équipe support --}}
            @if($teamCount > 0)
            <div class="contact-card">
                <h3 style="font-size:16px;font-weight:800;color:#003580;margin-bottom:16px;">
                    <i class="bi bi-headset me-2"></i>Notre équipe support
                </h3>
                <div class="d-flex flex-column gap-3">
                    @for($m = 1; $m <= $teamCount; $m++)
                    @php
                        $nom    = \App\Models\SiteSetting::get('support_'.$m.'_nom','');
                        $poste  = \App\Models\SiteSetting::get('support_'.$m.'_poste','');
                        $sEmail = \App\Models\SiteSetting::get('support_'.$m.'_email','');
                        $sPhone = \App\Models\SiteSetting::get('support_'.$m.'_telephone','');
                        $sWa    = \App\Models\SiteSetting::get('support_'.$m.'_whatsapp','');
                        $dispo  = \App\Models\SiteSetting::get('support_'.$m.'_disponible','0') === '1';
                    @endphp
                    @if($nom)
                    <div class="support-card">
                        <div class="support-avatar">{{ strtoupper(substr($nom,0,1)) }}</div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-weight:800;color:#1e293b;font-size:14px;">{{ $nom }}</div>
                            <div style="font-size:12px;color:#0071c2;font-weight:600;">{{ $poste }}</div>
                            @if($sPhone)
                            <div style="font-size:12px;color:#64748b;margin-top:3px;">
                                <i class="bi bi-telephone me-1"></i>{{ $sPhone }}
                            </div>
                            @endif
                            <div style="margin-top:4px;">
                                @if($dispo)
                                    <span style="font-size:11px;font-weight:700;color:#16a34a;">
                                        <span class="online-dot"></span> Disponible
                                    </span>
                                @else
                                    <span style="font-size:11px;font-weight:700;color:#94a3b8;">⏸ Hors ligne</span>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex flex-column gap-1">
                            @if($sEmail)
                            <a href="mailto:{{ $sEmail }}"
                               style="background:#dbeafe;color:#1e40af;padding:5px 10px;border-radius:6px;font-size:11px;font-weight:700;text-decoration:none;">
                                <i class="bi bi-envelope"></i>
                            </a>
                            @endif
                            @if($sWa)
                            <a href="https://wa.me/{{ preg_replace('/\D/','',$sWa) }}" target="_blank"
                               style="background:#dcfce7;color:#16a34a;padding:5px 10px;border-radius:6px;font-size:11px;font-weight:700;text-decoration:none;">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif
                    @endfor
                </div>
            </div>
            @endif

            {{-- FAQ --}}
            <div class="contact-card">
                <h3 style="font-size:16px;font-weight:800;color:#003580;margin-bottom:14px;">
                    <i class="bi bi-question-circle me-2"></i>Questions fréquentes
                </h3>
                <div class="faq-item">
                    <div style="font-weight:700;color:#003580;font-size:14px;margin-bottom:4px;">Comment annuler ma réservation ?</div>
                    <div style="font-size:13px;color:#64748b;">Contactez-nous avec votre code de réservation. Traitée sous 24h.</div>
                </div>
                <div class="faq-item">
                    <div style="font-weight:700;color:#003580;font-size:14px;margin-bottom:4px;">Comment suivre ma réservation ?</div>
                    <div style="font-size:13px;color:#64748b;">
                        Rendez-vous sur <a href="{{ route('reservations.status') }}" style="color:#0071c2;">Suivi réservation</a> avec votre code.
                    </div>
                </div>
                <div class="faq-item">
                    <div style="font-weight:700;color:#003580;font-size:14px;margin-bottom:4px;">Quels modes de paiement ?</div>
                    <div style="font-size:13px;color:#64748b;">Waafi, D-Money, virement bancaire, carte et espèces à l'hôtel.</div>
                </div>
                <div class="faq-item">
                    <div style="font-weight:700;color:#003580;font-size:14px;margin-bottom:4px;">Mon hôtel peut rejoindre {{ $appName }} ?</div>
                    <div style="font-size:13px;color:#64748b;">
                        Oui ! Cliquez sur l'onglet
                        <strong style="color:#003580;cursor:pointer;" onclick="switchTab('partenaire')">"Devenir Partenaire"</strong>
                        ci-contre.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    document.querySelector('[onclick="switchTab(\'' + tab + '\')"]').classList.add('active');
}

// Ouvrir automatiquement l'onglet partenaire si erreurs de validation
@if($errors->hasAny(['nom_contact','email_contact','nom_hotel','accepte_conditions']))
switchTab('partenaire');
@endif
</script>
@endpush

@endsection