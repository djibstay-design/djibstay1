@extends('layouts.admin')
@section('page_title', 'Modifier le Client')
@section('title', 'Modifier Client — DjibStay Administration')

@section('content')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    .edit-wrapper {
        max-width: 580px;
        margin: 0 auto;
        font-family: 'DM Sans', sans-serif;
    }

    /* ── Card ── */
    .edit-card {
        background: #fff;
        border-radius: 16px;
        border: 0.5px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,.05);
    }

    /* ── Header ── */
    .edit-card-header {
        padding: 20px 28px;
        border-bottom: 0.5px solid #edf2f7;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .edit-avatar {
        width: 42px;
        height: 42px;
        border-radius: 11px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        color: #fff;
        flex-shrink: 0;
        letter-spacing: .03em;
    }

    .edit-header-text h3 {
        font-size: 15px;
        font-weight: 600;
        color: #1a202c;
        margin: 0 0 2px;
    }

    .edit-header-text span {
        font-size: 12px;
        color: #94a3b8;
    }

    /* ── Body ── */
    .edit-card-body {
        padding: 28px;
    }

    /* ── Field groups ── */
    .field-group {
        margin-bottom: 20px;
    }

    .field-group label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #64748b;
        margin-bottom: 7px;
    }

    .field-group .form-control {
        border: 0.5px solid #cbd5e0;
        border-radius: 10px !important;
        padding: 10px 14px;
        font-size: 13.5px;
        color: #1a202c;
        background: #f8fafc;
        box-shadow: none !important;
        transition: border-color .15s, background .15s;
        font-family: 'DM Sans', sans-serif;
        width: 100%;
    }

    .field-group .form-control:focus {
        border-color: #6366f1;
        background: #fff;
        outline: none;
    }

    .field-group .form-control.is-invalid {
        border-color: #f87171;
        background: #fff;
    }

    .field-group .invalid-feedback {
        font-size: 12px;
        color: #dc2626;
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .field-group .invalid-feedback::before {
        content: '⚠';
        font-size: 11px;
    }

    /* ── Input icon wrapper ── */
    .input-icon-wrap {
        position: relative;
    }

    .input-icon-wrap > i:first-child {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 13px;
        color: #94a3b8;
        pointer-events: none;
    }

    .input-icon-wrap .form-control {
        padding-left: 36px;
        padding-right: 36px;
    }

    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        cursor: pointer;
        font-size: 15px;
        padding: 5px;
        transition: color .2s;
        z-index: 10;
    }
    .password-toggle:hover { color: #6366f1; }

    /* ── Divider ── */
    .section-divider {
        border: none;
        border-top: 0.5px solid #f1f5f9;
        margin: 24px 0;
    }

    /* ── Footer actions ── */
    .edit-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 28px;
        border-top: 0.5px solid #edf2f7;
        background: #fafbfc;
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 18px;
        border-radius: 10px;
        border: 0.5px solid #e2e8f0;
        background: #fff;
        color: #64748b;
        font-size: 13.5px;
        font-weight: 500;
        text-decoration: none;
        transition: background .12s, color .12s;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
    }

    .btn-cancel:hover {
        background: #f1f5f9;
        color: #1a202c;
    }

    .btn-save {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 22px;
        border-radius: 10px;
        border: none;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: #fff;
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        transition: opacity .15s, transform .12s;
        font-family: 'DM Sans', sans-serif;
    }

    .btn-save:hover {
        opacity: .9;
        transform: translateY(-1px);
    }

    .btn-save:active {
        transform: translateY(0);
    }

    .btn-save i {
        font-size: 14px;
    }

    /* ── Row layout ── */
    .fields-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    @media (max-width: 480px) {
        .fields-row { grid-template-columns: 1fr; }
        .edit-card-body { padding: 20px; }
        .edit-footer { padding: 16px 20px; flex-wrap: wrap; gap: 10px; }
        .btn-save { width: 100%; justify-content: center; }
    }
</style>
@endpush

<div class="edit-wrapper fade-in-up">
    <div class="edit-card">

        {{-- Header --}}
        <div class="edit-card-header">
            <div class="edit-avatar">
                {{ strtoupper(substr($client->prenom ?? $client->name, 0, 1)) }}{{ strtoupper(substr($client->name, 0, 1)) }}
            </div>
            <div class="edit-header-text">
                <h3>{{ $client->prenom }} {{ $client->name }}</h3>
                <span>Modification du profil client · #{{ $client->id }}</span>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.clients.update', $client) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="edit-card-body">

                {{-- Nom / Prénom --}}
                <div class="fields-row">
                    <div class="field-group">
                        <label for="name">Nom</label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-person"></i>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $client->name) }}"
                                placeholder="Nom de famille"
                                required
                            >
                        </div>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label for="prenom">Prénom</label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-person"></i>
                            <input
                                type="text"
                                id="prenom"
                                name="prenom"
                                class="form-control @error('prenom') is-invalid @enderror"
                                value="{{ old('prenom', $client->prenom) }}"
                                placeholder="Prénom"
                            >
                        </div>
                        @error('prenom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="section-divider">

                {{-- Email --}}
                <div class="field-group">
                    <label for="email">Adresse e-mail</label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-envelope"></i>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $client->email) }}"
                            placeholder="exemple@email.com"
                            required
                        >
                    </div>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Téléphone --}}
                <div class="field-group">
                    <label for="phone">Téléphone</label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-telephone"></i>
                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', $client->phone) }}"
                            placeholder="+253 77 00 00 00"
                        >
                    </div>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="section-divider">
                <div style="margin-bottom: 15px;">
                    <h4 style="font-size: 13px; font-weight: 700; color: #1a202c; text-transform: uppercase; letter-spacing: .05em;">Sécurité</h4>
                    <p style="font-size: 12px; color: #94a3b8;">Laissez vide si vous ne souhaitez pas modifier le mot de passe.</p>
                </div>

                {{-- Mot de passe --}}
                <div class="fields-row">
                    <div class="field-group">
                        <label for="password">Nouveau mot de passe</label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-shield-lock"></i>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Min. 8 caractères"
                            >
                            <i class="bi bi-eye password-toggle" onclick="togglePass('password', this)"></i>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label for="password_confirmation">Confirmation</label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-shield-check"></i>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-control"
                                placeholder="Répéter le mot de passe"
                            >
                            <i class="bi bi-eye password-toggle" onclick="togglePass('password_confirmation', this)"></i>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="edit-footer">
                <a href="{{ route('admin.clients.index') }}" class="btn-cancel">
                    <i class="bi bi-arrow-left"></i> Annuler
                </a>
                <button type="submit" class="btn-save">
                    <i class="bi bi-check-lg"></i> Enregistrer
                </button>
            </div>

        </form>
    </div>
</div>

@push('scripts')
<script>
function togglePass(id, el) {
    const input = document.getElementById(id);
    if (input.type === 'password') {
        input.type = 'text';
        el.classList.remove('bi-eye');
        el.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        el.classList.remove('bi-eye-slash');
        el.classList.add('bi-eye');
    }
}
</script>
@endpush
@endsection