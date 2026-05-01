@extends('layouts.admin')
@section('page_title', 'Paramètres')
@section('title', 'Paramètres — DjibStay')

@push('styles')
<style>
    /* ════════ ANIMATIONS ════════ */
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .settings-wrapper {
        display: flex;
        gap: 30px;
        align-items: flex-start;
        margin-top: 10px;
    }
    @media(max-width: 991px) {
        .settings-wrapper { flex-direction: column; }
    }

    /* ════════ HEADER ════════ */
    .settings-header {
        background: linear-gradient(135deg, #001f4d, #003580);
        border-radius: 16px;
        padding: 30px 40px;
        color: #fff;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0,53,128,0.15);
    }
    .settings-header::before {
        content: ''; position: absolute; top: -50%; left: -50%;
        width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 50%);
        transform: rotate(30deg); pointer-events: none;
    }
    .settings-header h1 { font-size: 28px; font-weight: 900; margin: 0; position: relative; z-index: 1; }
    .settings-header p { font-size: 14px; color: rgba(255,255,255,0.7); margin: 5px 0 0; font-weight: 500; position: relative; z-index: 1; }

    /* ════════ VERTICAL TABS ════════ */
    .tab-sidebar {
        width: 280px;
        flex-shrink: 0;
        background: #fff;
        border-radius: 16px;
        padding: 20px 15px;
        box-shadow: 0 8px 25px rgba(0,53,128,0.04);
        position: sticky;
        top: 80px;
    }
    @media(max-width: 991px) {
        .tab-sidebar { width: 100%; position: relative; top: 0; display: flex; overflow-x: auto; padding: 10px; }
    }

    .tab-btn {
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
        padding: 12px 20px;
        border-radius: 12px;
        border: none;
        background: transparent;
        font-size: 14px;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-align: left;
        margin-bottom: 4px;
        position: relative;
        overflow: hidden;
    }
    @media(max-width: 991px) {
        .tab-btn { width: auto; white-space: nowrap; margin-bottom: 0; margin-right: 4px; }
    }
    .tab-btn:hover {
        color: #003580;
        background: #f8fafc;
        transform: translateX(4px);
    }
    @media(max-width: 991px) {
        .tab-btn:hover { transform: translateY(-2px); }
    }
    .tab-btn.active {
        background: linear-gradient(135deg, #003580, #0071c2);
        color: #fff;
        font-weight: 700;
        box-shadow: 0 4px 15px rgba(0,53,128,0.25);
    }
    .tab-btn.active:hover { transform: none; }
    .tab-btn .tab-icon { font-size: 18px; transition: transform 0.3s; }
    .tab-btn.active .tab-icon { transform: scale(1.2); }
    .tab-btn.has-error { color: #dc2626; background: #fef2f2; }
    .tab-btn.has-error.active { background: linear-gradient(135deg, #dc2626, #ef4444); color: #fff; }

    /* ════════ TAB PANELS ════════ */
    .tab-content {
        flex: 1;
        min-width: 0;
    }
    .tab-panel {
        display: none;
        animation: slideInRight 0.4s ease-out forwards;
    }
    .tab-panel.active { display: block; }

    /* ════════ CARDS ════════ */
    .s-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 10px 40px rgba(0,53,128,0.03);
        margin-bottom: 24px;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .s-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 50px rgba(0,53,128,0.06);
    }
    .s-card-header {
        padding: 24px 30px;
        border-bottom: 1px solid #f1f5f9;
        background: #fafbfc;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .s-card-header .icon-wrap {
        width: 48px; height: 48px;
        background: #e0f2fe;
        color: #0071c2;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px;
        box-shadow: 0 4px 10px rgba(0,113,194,0.1);
    }
    .s-card-header h3 { font-size: 18px; font-weight: 800; color: #1e293b; margin: 0; }
    .s-card-header p { font-size: 13px; color: #64748b; margin: 4px 0 0; font-weight: 500; }
    .s-card-body { padding: 30px; }

    /* ════════ FORMS ════════ */
    .fg { display: flex; flex-direction: column; gap: 8px; position: relative; }
    .fg label { font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; }
    .fg input[type="text"], .fg input[type="email"], .fg input[type="tel"], .fg input[type="number"], .fg input[type="url"], .fg textarea, .fg select {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 14px;
        color: #1e293b;
        background: #fafbfc;
        transition: all 0.2s;
        font-weight: 500;
        width: 100%;
    }
    .fg input:focus, .fg textarea:focus, .fg select:focus {
        border-color: #0071c2;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(0,113,194,0.1);
        outline: none;
    }
    .fg .hint { font-size: 11px; color: #94a3b8; font-weight: 500; }
    .row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px; }
    .row3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 24px; margin-bottom: 24px; }
    .row1 { display: grid; grid-template-columns: 1fr; gap: 24px; margin-bottom: 24px; }
    @media(max-width: 768px) { .row2, .row3 { grid-template-columns: 1fr; gap: 16px; } }

    /* ════════ UPLOAD ZONE ════════ */
    .upload-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        background: #f8fafc;
        position: relative;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        min-height: 120px;
    }
    .upload-zone:hover {
        border-color: #0071c2;
        background: #f0f9ff;
        transform: translateY(-2px);
    }
    .upload-zone input { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; z-index: 10; }
    
    .logo-preview {
        width: auto; height: 60px; border-radius: 8px;
        object-fit: contain; background: #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05); padding: 5px; margin-bottom: 15px;
    }

    /* ════════ CUSTOM TOGGLES (SWITCHES) ════════ */
    .toggle-wrap {
        display: flex; align-items: center; justify-content: space-between;
        background: #f8fafc; border: 1px solid #e2e8f0;
        border-radius: 12px; padding: 20px; transition: all 0.2s;
    }
    .toggle-wrap:hover { border-color: #cbd5e1; background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.02); }
    .toggle-info .title { font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 4px; display: flex; align-items: center; gap: 8px; }
    .toggle-info .sub { font-size: 12px; color: #64748b; font-weight: 500; }
    
    /* The Switch */
    .switch { position: relative; display: inline-block; width: 50px; height: 28px; flex-shrink: 0; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px; }
    .slider:before { position: absolute; content: ""; height: 20px; width: 20px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
    input:checked + .slider { background-color: #22c55e; }
    input:focus + .slider { box-shadow: 0 0 1px #22c55e; }
    input:checked + .slider:before { transform: translateX(22px); }

    /* ════════ SAVE BAR ════════ */
    .save-bar {
        position: sticky;
        bottom: 20px;
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(10px);
        padding: 16px 30px;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,53,128,0.15);
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 16px;
        z-index: 100;
        border: 1px solid rgba(255,255,255,0.5);
        margin-top: 30px;
    }
    .btn-save {
        background: linear-gradient(135deg, #003580, #0071c2);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-weight: 800;
        font-size: 15px;
        padding: 14px 45px;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 8px 20px rgba(0,113,194,0.3);
        display: flex; align-items: center; gap: 8px;
    }
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(0,113,194,0.4);
    }

    /* ════════ TEAM MEMBER CARD ════════ */
    .member-card {
        background: #fff; border: 1px solid #e2e8f0; border-radius: 14px;
        padding: 24px; margin-bottom: 24px; position: relative;
        transition: all 0.2s;
    }
    .member-card:hover { border-color: #cbd5e1; box-shadow: 0 8px 20px rgba(0,0,0,0.03); }
    .member-card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px dashed #e2e8f0; }
    .member-badge {
        width: 42px; height: 42px;
        background: linear-gradient(135deg, #febb02, #f5a623);
        border-radius: 10px; display: flex; align-items: center; justify-content: center;
        color: #003580; font-size: 16px; font-weight: 900; box-shadow: 0 4px 10px rgba(254,187,2,0.3);
    }
    
    /* Validation Errors */
    .is-invalid { border-color: #ef4444 !important; background: #fef2f2 !important; }
    .error-msg { font-size: 11px; color: #ef4444; font-weight: 600; margin-top: 4px; display: block; }
    
    .social-wrap { position: relative; }
    .social-wrap i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); font-size: 18px; color: #94a3b8; }
    .social-wrap input { padding-left: 45px !important; }
</style>
@endpush

@section('content')

<div class="settings-header">
    <h1>⚙️ Configurations Globales</h1>
    <p>Gérez l'identité, les politiques et les paramètres de votre plateforme DjibStay.</p>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:12px; border:none; background:#dcfce7; color:#166534; font-weight:600; padding:16px 20px;">
    <i class="bi bi-check-circle-fill me-2" style="font-size:18px;"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" id="settings-form">
@csrf @method('PUT')

<div class="settings-wrapper">

    {{-- ══ TAB SIDEBAR ══ --}}
    <div class="tab-sidebar" role="tablist">
        <button type="button" class="tab-btn active" data-tab="identite" role="tab"><span class="tab-icon">🏢</span> Identité Visuelle</button>
        <button type="button" class="tab-btn" data-tab="coordonnees" role="tab"><span class="tab-icon">📞</span> Coordonnées</button>
        <button type="button" class="tab-btn" data-tab="emails" role="tab"><span class="tab-icon">📧</span> Serveur d'Emails</button>
        <button type="button" class="tab-btn" data-tab="equipe" role="tab"><span class="tab-icon">🎧</span> Équipe Support</button>
        <button type="button" class="tab-btn" data-tab="pages" role="tab"><span class="tab-icon">📄</span> Contenus Publics</button>
        <button type="button" class="tab-btn" data-tab="reservation" role="tab"><span class="tab-icon">💰</span> Règles Financières</button>
        <button type="button" class="tab-btn" data-tab="localisation" role="tab"><span class="tab-icon">🌍</span> Région & Devise</button>
        <button type="button" class="tab-btn" data-tab="securite" role="tab"><span class="tab-icon">🔒</span> Accès & Sécurité</button>
        <button type="button" class="tab-btn" data-tab="notifications" role="tab"><span class="tab-icon">🔔</span> Alertes Système</button>
        <button type="button" class="tab-btn" data-tab="politique" role="tab"><span class="tab-icon">🏨</span> Politiques Hôtels</button>
    </div>

    {{-- ══ TAB CONTENTS ══ --}}
    <div class="tab-content">
        
        {{-- 1. IDENTITÉ --}}
        <div class="tab-panel active" id="panel-identite">
            <div class="s-card">
                <div class="s-card-header">
                    <div class="icon-wrap">🏢</div>
                    <div>
                        <h3>Identité Visuelle</h3>
                        <p>L'image de marque de votre application web</p>
                    </div>
                </div>
                <div class="s-card-body">
                    <div class="row2">
                        <div class="fg">
                            <label>Nom de l'application *</label>
                            <input type="text" name="app_name" value="{{ old('app_name', $settings['app_name'] ?? 'DjibStay') }}" placeholder="DjibStay" required class="{{ $errors->has('app_name') ? 'is-invalid' : '' }}">
                            @error('app_name')<span class="error-msg">{{ $message }}</span>@enderror
                            <span class="hint">Apparaît dans le header, le titre du navigateur et les emails.</span>
                        </div>
                        <div class="fg">
                            <label>Slogan (Tagline)</label>
                            <input type="text" name="app_slogan" value="{{ old('app_slogan', $settings['app_slogan'] ?? '') }}" placeholder="Réservez les meilleurs hôtels à Djibouti">
                        </div>
                    </div>
                    <div class="row2">
                        <div class="fg">
                            <label>Logo Principal</label>
                            @if(!empty($settings['app_logo']))
                                <img src="{{ asset('storage/'.$settings['app_logo']) }}" alt="Logo" class="logo-preview">
                            @endif
                            <div class="upload-zone">
                                <input type="file" name="app_logo_file" accept="image/png,image/svg+xml,image/jpeg,image/webp">
                                <i class="bi bi-cloud-arrow-up" style="font-size:32px;color:#0071c2;"></i>
                                <div style="font-size:14px;font-weight:700;color:#1e293b;margin-top:8px;">Glissez un fichier ou cliquez</div>
                                <div style="font-size:11px;color:#94a3b8;margin-top:4px;">Format recommandé: PNG ou SVG transparent (Max 2Mo)</div>
                            </div>
                        </div>
                        <div class="fg">
                            <label>Icône du navigateur (Favicon)</label>
                            @if(!empty($settings['app_favicon']))
                                <img src="{{ asset('storage/'.$settings['app_favicon']) }}" alt="Favicon" class="logo-preview" style="height:40px; width:40px;">
                            @endif
                            <div class="upload-zone">
                                <input type="file" name="app_favicon_file" accept="image/x-icon,image/png">
                                <i class="bi bi-filetype-png" style="font-size:32px;color:#0071c2;"></i>
                                <div style="font-size:14px;font-weight:700;color:#1e293b;margin-top:8px;">Uploader l'icône</div>
                                <div style="font-size:11px;color:#94a3b8;margin-top:4px;">Format ICO ou PNG (32x32px)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. COORDONNÉES --}}
        <div class="tab-panel" id="panel-coordonnees">
            <div class="s-card">
                <div class="s-card-header">
                    <div class="icon-wrap">📞</div>
                    <div>
                        <h3>Coordonnées Officielles</h3>
                        <p>Où et comment vos clients peuvent vous contacter</p>
                    </div>
                </div>
                <div class="s-card-body">
                    <div class="row2">
                        <div class="fg">
                            <label>Adresse Physique</label>
                            <input type="text" name="contact_adresse" value="{{ old('contact_adresse', $settings['contact_adresse'] ?? '') }}" placeholder="Ex: Centre-ville, Plateau du Serpent">
                        </div>
                        <div class="fg">
                            <label>Ville & Pays</label>
                            <input type="text" name="contact_ville" value="{{ old('contact_ville', $settings['contact_ville'] ?? '') }}" placeholder="Djibouti, République de Djibouti">
                        </div>
                    </div>
                    <div class="row3">
                        <div class="fg">
                            <label>Téléphone Standard</label>
                            <input type="tel" name="contact_telephone" value="{{ old('contact_telephone', $settings['contact_telephone'] ?? '') }}" placeholder="+253 77 00 00 00">
                        </div>
                        <div class="fg">
                            <label>Email Support</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" placeholder="support@djibstay.dj">
                        </div>
                        <div class="fg">
                            <label>Numéro WhatsApp</label>
                            <input type="tel" name="contact_whatsapp" value="{{ old('contact_whatsapp', $settings['contact_whatsapp'] ?? '') }}" placeholder="+253 77 00 00 00">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. EMAILS --}}
        <div class="tab-panel" id="panel-emails">
            <div class="s-card">
                <div class="s-card-header">
                    <div class="icon-wrap">📧</div>
                    <div>
                        <h3>Configuration Serveur d'Emails</h3>
                        <p>Gérez les adresses d'expédition et de réception système</p>
                    </div>
                </div>
                <div class="s-card-body">
                    <div class="row2">
                        <div class="fg">
                            <label>Email d'Expédition (From Address)</label>
                            <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $settings['mail_from_address'] ?? '') }}" placeholder="noreply@djibstay.dj">
                            <span class="hint">L'adresse utilisée par le système pour envoyer les alertes.</span>
                        </div>
                        <div class="fg">
                            <label>Nom d'Expédition (From Name)</label>
                            <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $settings['mail_from_name'] ?? '') }}" placeholder="Service Client DjibStay">
                        </div>
                    </div>
                    <hr style="border-color:#f1f5f9; margin:30px 0;">
                    <div class="row2">
                        <div class="fg">
                            <label>Destinataire Formulaire Contact</label>
                            <input type="email" name="mail_contact_receiver" value="{{ old('mail_contact_receiver', $settings['mail_contact_receiver'] ?? '') }}" placeholder="contact@djibstay.dj">
                            <span class="hint">Reçoit les demandes de la page "Nous Contacter".</span>
                        </div>
                        <div class="fg">
                            <label>Destinataire Alertes Réservations</label>
                            <input type="email" name="mail_resa_receiver" value="{{ old('mail_resa_receiver', $settings['mail_resa_receiver'] ?? '') }}" placeholder="reservations@djibstay.dj">
                            <span class="hint">Reçoit une copie de chaque nouvelle réservation.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. ÉQUIPE SUPPORT --}}
        <div class="tab-panel" id="panel-equipe">
            <div class="s-card">
                <div class="s-card-header">
                    <div class="icon-wrap">🎧</div>
                    <div>
                        <h3>Membres de l'Équipe Support</h3>
                        <p>Configurez les profils affichés sur la page de contact</p>
                    </div>
                </div>
                <div class="s-card-body">
                    <div class="row2" style="background:#f8fafc; padding:20px; border-radius:12px; margin-bottom:30px;">
                        <div class="fg">
                            <label>Horaires Globaux du Support</label>
                            <input type="text" name="support_horaires" value="{{ old('support_horaires', $settings['support_horaires'] ?? '') }}" placeholder="Lun-Jeu : 8h-18h | Ven-Sam : 9h-12h">
                        </div>
                        <div class="fg">
                            <label>Membres visibles en ligne</label>
                            <select name="support_team_count">
                                @for($i=1;$i<=5;$i++)
                                <option value="{{ $i }}" {{ ($settings['support_team_count'] ?? '2') == $i ? 'selected' : '' }}>{{ $i }} Membre(s)</option>
                                @endfor
                            </select>
                            <span class="hint">Ajustez pour ne montrer que les membres réellement actifs.</span>
                        </div>
                    </div>

                    @for($m = 1; $m <= 5; $m++)
                    <div class="member-card">
                        <div class="member-card-header">
                            <div style="display:flex;align-items:center;gap:15px;">
                                <div class="member-badge">{{ $m }}</div>
                                <div>
                                    <div style="font-size:16px;font-weight:800;color:#1e293b;">Agent Support #{{ $m }}</div>
                                    <div style="font-size:12px;color:#64748b;font-weight:600;margin-top:2px;">
                                        @if($m <= ($settings['support_team_count'] ?? 2))
                                            <span style="color:#22c55e;"><i class="bi bi-circle-fill" style="font-size:8px;"></i> Visible sur le site</span>
                                        @else
                                            <span style="color:#94a3b8;"><i class="bi bi-circle-fill" style="font-size:8px;"></i> Masqué</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <span style="font-size:13px;font-weight:700;color:#475569;">En service</span>
                                <label class="switch">
                                    <input type="hidden" name="support_{{ $m }}_disponible" value="0">
                                    <input type="checkbox" name="support_{{ $m }}_disponible" value="1" {{ ($settings['support_'.$m.'_disponible'] ?? '0') === '1' ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                        <div class="row2">
                            <div class="fg">
                                <label>Nom & Prénom</label>
                                <input type="text" name="support_{{ $m }}_nom" value="{{ old('support_'.$m.'_nom', $settings['support_'.$m.'_nom'] ?? '') }}" placeholder="Ex : Hawa Ali">
                            </div>
                            <div class="fg">
                                <label>Rôle Exact</label>
                                <input type="text" name="support_{{ $m }}_poste" value="{{ old('support_'.$m.'_poste', $settings['support_'.$m.'_poste'] ?? '') }}" placeholder="Ex : Chargée de Clientèle">
                            </div>
                        </div>
                        <div class="row3 mb-0">
                            <div class="fg">
                                <label>Email direct</label>
                                <input type="email" name="support_{{ $m }}_email" value="{{ old('support_'.$m.'_email', $settings['support_'.$m.'_email'] ?? '') }}" placeholder="hawa@djibstay.dj">
                            </div>
                            <div class="fg">
                                <label>Ligne directe</label>
                                <input type="tel" name="support_{{ $m }}_telephone" value="{{ old('support_'.$m.'_telephone', $settings['support_'.$m.'_telephone'] ?? '') }}" placeholder="+253 77...">
                            </div>
                            <div class="fg">
                                <label>Lien WhatsApp</label>
                                <input type="tel" name="support_{{ $m }}_whatsapp" value="{{ old('support_'.$m.'_whatsapp', $settings['support_'.$m.'_whatsapp'] ?? '') }}" placeholder="+253 77...">
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- 5. PAGES PUBLIQUES --}}
        <div class="tab-panel" id="panel-pages">
            <div class="s-card">
                <div class="s-card-header">
                    <div class="icon-wrap">📄</div>
                    <div>
                        <h3>Contenus & Réseaux Sociaux</h3>
                        <p>Texte de présentation et liens sociaux en pied de page</p>
                    </div>
                </div>
                <div class="s-card-body">
                    <div class="row1">
                        <div class="fg">
                            <label>Texte de présentation "À propos"</label>
                            <textarea name="about_text" rows="6" placeholder="DjibStay est la plateforme numéro 1 pour réserver vos séjours...">{{ old('about_text', $settings['about_text'] ?? '') }}</textarea>
                        </div>
                    </div>
                    <div class="row1">
                        <div class="fg">
                            <label>Mention Copyright Footer</label>
                            <input type="text" name="footer_copyright" value="{{ old('footer_copyright', $settings['footer_copyright'] ?? '') }}" placeholder="© 2026 DjibStay — Créé avec passion.">
                        </div>
                    </div>
                    <hr style="border-color:#f1f5f9; margin:30px 0;">
                    <h4 style="font-size:14px; font-weight:800; color:#1e293b; margin-bottom:20px; text-transform:uppercase;">Liens Sociaux</h4>
                    <div class="row3">
                        <div class="fg social-wrap">
                            <label>Page Facebook</label>
                            <i class="bi bi-facebook" style="color:#1877f2;"></i>
                            <input type="url" name="social_facebook" value="{{ old('social_facebook', $settings['social_facebook'] ?? '') }}" placeholder="https://facebook.com/djibstay">
                        </div>
                        <div class="fg social-wrap">
                            <label>Profil Instagram</label>
                            <i class="bi bi-instagram" style="color:#e1306c;"></i>
                            <input type="url" name="social_instagram" value="{{ old('social_instagram', $settings['social_instagram'] ?? '') }}" placeholder="https://instagram.com/djibstay">
                        </div>
                        <div class="fg social-wrap">
                            <label>Compte X (Twitter)</label>
                            <i class="bi bi-twitter-x" style="color:#0f1419;"></i>
                            <input type="url" name="social_twitter" value="{{ old('social_twitter', $settings['social_twitter'] ?? '') }}" placeholder="https://x.com/djibstay">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 6. RÉSERVATION --}}
        <div class="tab-panel" id="panel-reservation">
            <div class="s-card">
                <div class="s-card-header">
                    <div class="icon-wrap">💰</div>
                    <div>
                        <h3>Règles Financières</h3>
                        <p>Acomptes et conditions d'annulation</p>
                    </div>
                </div>
                <div class="s-card-body">
                    <div class="row2">
                        <div class="fg">
                            <label>Pourcentage d'Acompte Exigé (%)</label>
                            <div style="position:relative;">
                                <input type="number" name="resa_acompte_percent" value="{{ old('resa_acompte_percent', $settings['resa_acompte_percent'] ?? '30') }}" min="1" max="100" style="padding-right:40px;">
                                <span style="position:absolute; right:15px; top:50%; transform:translateY(-50%); font-weight:800; color:#94a3b8;">%</span>
                            </div>
                            <span class="hint">Taux prélevé immédiatement lors de la réservation.</span>
                        </div>
                        <div class="fg">
                            <label>Délai limite d'annulation (Heures)</label>
                            <div style="position:relative;">
                                <input type="number" name="resa_annulation_heures" value="{{ old('resa_annulation_heures', $settings['resa_annulation_heures'] ?? '48') }}" min="0" style="padding-right:50px;">
                                <span style="position:absolute; right:15px; top:50%; transform:translateY(-50%); font-weight:800; color:#94a3b8;">HRS</span>
                            </div>
                            <span class="hint">Nombre d'heures avant check-in pour annulation gratuite.</span>
                        </div>
                    </div>
                    <div class="row1">
                        <div class="fg">
                            <label>Conditions Générales de Vente & Réservation</label>
                            <textarea name="resa_conditions" rows="6" placeholder="En confirmant cette réservation, le client accepte que l'acompte de...">{{ old('resa_conditions', $settings['resa_conditions'] ?? '') }}</textarea>
                            <span class="hint">Ce texte sera affiché au moment du paiement final sur le site.</span>
                        </div>
                    </div>

                    <hr style="border-color:#f1f5f9; margin:30px 0;">
                    <h4 style="font-size:14px; font-weight:800; color:#1e293b; margin-bottom:20px; text-transform:uppercase;">💰 Numéros Marchands (Wallets)</h4>
                    <div class="row2">
                        <div class="fg">
                            <label>Numéro Marchand WAAFI</label>
                            <input type="text" name="payment_waafi_merchant" value="{{ old('payment_waafi_merchant', $settings['payment_waafi_merchant'] ?? '') }}" placeholder="Ex: 123456">
                            <span class="hint">Affiché aux clients lors du paiement par Waafi.</span>
                        </div>
                        <div class="fg">
                            <label>Numéro Marchand D-MONEY</label>
                            <input type="text" name="payment_dmoney_merchant" value="{{ old('payment_dmoney_merchant', $settings['payment_dmoney_merchant'] ?? '') }}" placeholder="Ex: 654321">
                            <span class="hint">Affiché aux clients lors du paiement par D-Money.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 7. LOCALISATION --}}
        <div class="tab-panel" id="panel-localisation">
            <div class="s-card">
                <div class="s-card-header">
                    <div class="icon-wrap">🌍</div>
                    <div>
                        <h3>Paramètres Régionaux</h3>
                        <p>Format de dates, devise et fuseau horaire</p>
                    </div>
                </div>
                <div class="s-card-body">
                    <div class="row2">
                        <div class="fg">
                            <label>Devise par défaut</label>
                            <select name="app_devise">
                                <option value="DJF" {{ ($settings['app_devise'] ?? 'DJF') === 'DJF' ? 'selected' : '' }}>DJF — Franc Djiboutien</option>
                                <option value="USD" {{ ($settings['app_devise'] ?? '') === 'USD' ? 'selected' : '' }}>USD — Dollar Américain</option>
                                <option value="EUR" {{ ($settings['app_devise'] ?? '') === 'EUR' ? 'selected' : '' }}>EUR — Euro</option>
                            </select>
                        </div>
                        <div class="fg">
                            <label>Langue Principale</label>
                            <select name="app_langue">
                                <option value="fr" {{ ($settings['app_langue'] ?? 'fr') === 'fr' ? 'selected' : '' }}>🇫🇷 Français</option>
                                <option value="ar" {{ ($settings['app_langue'] ?? '') === 'ar' ? 'selected' : '' }}>🇸🇦 Arabe</option>
                                <option value="en" {{ ($settings['app_langue'] ?? '') === 'en' ? 'selected' : '' }}>🇬🇧 Anglais</option>
                            </select>
                        </div>
                    </div>
                    <div class="row2">
                        <div class="fg">
                            <label>Fuseau Horaire (Timezone)</label>
                            <select name="app_timezone">
                                <option value="Africa/Djibouti" {{ ($settings['app_timezone'] ?? 'Africa/Djibouti') === 'Africa/Djibouti' ? 'selected' : '' }}>Africa/Djibouti (UTC+3)</option>
                                <option value="UTC" {{ ($settings['app_timezone'] ?? '') === 'UTC' ? 'selected' : '' }}>UTC Universel</option>
                                <option value="Europe/Paris" {{ ($settings['app_timezone'] ?? '') === 'Europe/Paris' ? 'selected' : '' }}>Europe/Paris</option>
                            </select>
                        </div>
                        <div class="fg">
                            <label>Format des Dates</label>
                            <select name="app_date_format">
                                <option value="DD/MM/YYYY" {{ ($settings['app_date_format'] ?? 'DD/MM/YYYY') === 'DD/MM/YYYY' ? 'selected' : '' }}>Jour/Mois/Année (25/12/2026)</option>
                                <option value="MM/DD/YYYY" {{ ($settings['app_date_format'] ?? '') === 'MM/DD/YYYY' ? 'selected' : '' }}>Mois/Jour/Année (12/25/2026)</option>
                                <option value="YYYY-MM-DD" {{ ($settings['app_date_format'] ?? '') === 'YYYY-MM-DD' ? 'selected' : '' }}>Année-Mois-Jour (2026-12-25)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 8. SÉCURITÉ --}}
        <div class="tab-panel" id="panel-securite">
            <div class="s-card">
                <div class="s-card-header">
                    <div class="icon-wrap">🔒</div>
                    <div>
                        <h3>Accès et Limites</h3>
                        <p>Gérez les autorisations d'inscription et quotas</p>
                    </div>
                </div>
                <div class="s-card-body">
                    <div class="toggle-wrap mb-4">
                        <div class="toggle-info">
                            <div class="title"><i class="bi bi-person-plus" style="color:#0071c2;"></i> Inscriptions Ouvertes</div>
                            <div class="sub">Autorise les nouveaux utilisateurs à créer des comptes sur le site public.</div>
                        </div>
                        <label class="switch">
                            <input type="hidden" name="inscription_active" value="0">
                            <input type="checkbox" name="inscription_active" value="1" {{ ($settings['inscription_active'] ?? '1') === '1' ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>
                    
                    <div class="row1">
                        <div class="fg" style="max-width:350px;">
                            <label>Limite Réservations Actives / Client</label>
                            <input type="number" name="max_resa_client" value="{{ old('max_resa_client', $settings['max_resa_client'] ?? '5') }}" min="1" max="50">
                            <span class="hint">Prévient les abus en limitant le nombre de réservations simultanées par compte.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 9. NOTIFICATIONS --}}
        <div class="tab-panel" id="panel-notifications">
            <div class="s-card">
                <div class="s-card-header">
                    <div class="icon-wrap">🔔</div>
                    <div>
                        <h3>Événements et Alertes</h3>
                        <p>Déclencheurs d'envoi d'emails système</p>
                    </div>
                </div>
                <div class="s-card-body">
                    <div class="toggle-wrap mb-3">
                        <div class="toggle-info">
                            <div class="title"><i class="bi bi-bookmark-check" style="color:#22c55e;"></i> Nouvelles Réservations</div>
                            <div class="sub">Envoie un récapitulatif par email à la confirmation d'une réservation.</div>
                        </div>
                        <label class="switch">
                            <input type="hidden" name="notif_nouvelle_resa" value="0">
                            <input type="checkbox" name="notif_nouvelle_resa" value="1" {{ ($settings['notif_nouvelle_resa'] ?? '1') === '1' ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="toggle-wrap mb-3">
                        <div class="toggle-info">
                            <div class="title"><i class="bi bi-x-circle" style="color:#ef4444;"></i> Annulations</div>
                            <div class="sub">Avertit les administrateurs et le client lors de l'annulation.</div>
                        </div>
                        <label class="switch">
                            <input type="hidden" name="notif_annulation" value="0">
                            <input type="checkbox" name="notif_annulation" value="1" {{ ($settings['notif_annulation'] ?? '1') === '1' ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="toggle-wrap">
                        <div class="toggle-info">
                            <div class="title"><i class="bi bi-star" style="color:#febb02;"></i> Nouveaux Avis</div>
                            <div class="sub">Alerte l'admin de l'hôtel lorsqu'un client laisse une note.</div>
                        </div>
                        <label class="switch">
                            <input type="hidden" name="notif_avis" value="0">
                            <input type="checkbox" name="notif_avis" value="1" {{ ($settings['notif_avis'] ?? '1') === '1' ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- 10. POLITIQUE HÔTELS --}}
        <div class="tab-panel" id="panel-politique">
            <div class="s-card">
                <div class="s-card-header">
                    <div class="icon-wrap">🏨</div>
                    <div>
                        <h3>Règles Standard des Hôtels</h3>
                        <p>Politiques appliquées par défaut à tous les partenaires</p>
                    </div>
                </div>
                <div class="s-card-body">
                    <div class="row2">
                        <div class="fg">
                            <label>Âge Minimum Requis</label>
                            <div style="position:relative;">
                                <input type="number" name="hotel_age_minimum" value="{{ old('hotel_age_minimum', $settings['hotel_age_minimum'] ?? '18') }}" min="16" max="30" style="padding-right:50px;">
                                <span style="position:absolute; right:15px; top:50%; transform:translateY(-50%); font-weight:800; color:#94a3b8;">ANS</span>
                            </div>
                        </div>
                    </div>
                    <div class="toggle-wrap">
                        <div class="toggle-info">
                            <div class="title"><i class="bi bi-hash" style="color:#64748b;"></i> Animaux de compagnie</div>
                            <div class="sub">Autoriser par défaut les animaux domestiques dans les chambres.</div>
                        </div>
                        <label class="switch">
                            <input type="hidden" name="hotel_animaux" value="0">
                            <input type="checkbox" name="hotel_animaux" value="1" {{ ($settings['hotel_animaux'] ?? '0') === '1' ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="save-bar">
            <span style="font-size:12px;color:#64748b;font-weight:600;margin-right:auto;">Mise à jour en temps réel sur tout le système</span>
            <button type="submit" class="btn-save"><i class="bi bi-cloud-arrow-up-fill" style="font-size:18px;"></i> Sauvegarder les paramètres</button>
        </div>

    </div>
</div>
</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const STORAGE_KEY = 'djibstay_settings_premium_tab';

    // ── Tab switching ──────────────────────────────
    const btns   = document.querySelectorAll('.tab-btn');
    const panels = document.querySelectorAll('.tab-panel');

    function activateTab(tabId) {
        btns.forEach(b => b.classList.toggle('active', b.dataset.tab === tabId));
        panels.forEach(p => p.classList.toggle('active', p.id === 'panel-' + tabId));
        try { localStorage.setItem(STORAGE_KEY, tabId); } catch(e) {}
    }

    btns.forEach(btn => {
        btn.addEventListener('click', () => activateTab(btn.dataset.tab));
    });

    // ── Restore last active tab ────────────────────
    @if($errors->any())
        // Find which tab contains the first error field
        const errorFields = @json($errors->keys());
        const tabMap = {
            app_name: 'identite', app_slogan: 'identite',
            contact_adresse: 'coordonnees', contact_ville: 'coordonnees',
            contact_telephone: 'coordonnees', contact_email: 'coordonnees', contact_whatsapp: 'coordonnees',
            mail_from_address: 'emails', mail_from_name: 'emails',
            resa_acompte_percent: 'reservation', resa_annulation_heures: 'reservation',
            app_devise: 'localisation', app_langue: 'localisation'
        };
        let errorTabId = null;
        for (let field of errorFields) {
            if (tabMap[field]) { errorTabId = tabMap[field]; break; }
            if (field.startsWith('support_')) { errorTabId = 'equipe'; break; }
        }

        if (errorTabId) {
            activateTab(errorTabId);
            // Highlight button
            const errorBtn = document.querySelector(`.tab-btn[data-tab="${errorTabId}"]`);
            if (errorBtn) errorBtn.classList.add('has-error');
        }
    @else
        let lastTab = null;
        try { lastTab = localStorage.getItem(STORAGE_KEY); } catch(e) {}
        if (lastTab && document.querySelector(`.tab-btn[data-tab="${lastTab}"]`)) {
            activateTab(lastTab);
        }
    @endif
});
</script>
@endpush