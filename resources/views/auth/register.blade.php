@extends('layouts.app')

@section('title', 'Inscription — '.\App\Models\SiteSetting::get('app_name','DjibStay'))

@push('styles')
<style>
    .register-wrapper {
        min-height: calc(100vh - 64px);
        display: flex;
        align-items: center;
        justify-content: center;
background: url('https://images.unsplash.com/photo-1582719508461-905c673771fd?q=80&w=1920&auto=format&fit=crop') center/cover no-repeat;        padding: 40px 16px;
    }
    .register-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        padding: 40px 36px;
        width: 100%;
        max-width: 520px;
    }
    .register-logo {
        text-align: center;
        margin-bottom: 28px;
    }
    .register-logo img {
        height: 80px;
        object-fit: contain;
        display: block;
        margin: 0 auto 10px;
    }
    .register-logo .icon {
        width: 80px; height: 80px;
        background: linear-gradient(135deg, #003580, #0071c2);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 12px;
        font-size: 36px; color: #fff;
        box-shadow: 0 8px 24px rgba(0,53,128,0.3);
    }
    .register-logo h1 {
        font-size: 26px; font-weight: 900;
        color: #003580; margin-bottom: 4px;
    }
    .register-logo p { font-size: 13px; color: #64748b; }
    .form-label-djib {
        font-size: 12px; font-weight: 700;
        color: #003580; text-transform: uppercase;
        letter-spacing: .5px; margin-bottom: 6px; display: block;
    }
    .form-control-djib {
        border: 2px solid #e2e8f0; border-radius: 9px;
        padding: 11px 14px; font-size: 14px; color: #1a1a2e;
        width: 100%; transition: border-color .2s, box-shadow .2s;
    }
    .form-control-djib:focus {
        border-color: #0071c2;
        box-shadow: 0 0 0 3px rgba(0,113,194,0.12);
        outline: none;
    }
    .form-control-djib.is-error { border-color: #dc2626; }
    .field-error { color: #dc2626; font-size: 12px; margin-top: 4px; }
    .input-icon-wrap { position: relative; }
    .input-icon-wrap > i:first-child {
        position: absolute; left: 13px; top: 50%;
        transform: translateY(-50%);
        color: #94a3b8; font-size: 15px;
    }
    .input-icon-wrap .form-control-djib { padding-left: 38px; padding-right: 38px; }
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
    .password-toggle:hover { color: #0071c2; }
    .btn-register {
        background: linear-gradient(135deg, #003580, #0071c2);
        color: #fff; border: none; border-radius: 9px;
        font-weight: 800; font-size: 15px;
        padding: 13px; width: 100%;
        cursor: pointer; transition: all .2s;
        box-shadow: 0 4px 16px rgba(0,53,128,0.25);
        margin-top: 4px;
    }
    .btn-register:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(0,53,128,0.35); }
    .divider { text-align: center; position: relative; margin: 20px 0; }
    .divider::before {
        content: ''; position: absolute; top: 50%; left: 0; right: 0;
        height: 1px; background: #e2e8f0;
    }
    .divider span {
        background: #fff; padding: 0 12px;
        font-size: 12px; color: #94a3b8; position: relative;
    }
    .login-link { text-align: center; font-size: 14px; color: #475569; margin-top: 16px; }
    .login-link a { color: #0071c2; font-weight: 700; text-decoration: none; }
    .login-link a:hover { text-decoration: underline; }
</style>
@endpush

@section('content')
@php
    $appName  = \App\Models\SiteSetting::get('app_name','DjibStay');
    $logoPath = \App\Models\SiteSetting::get('app_logo','');
    $email    = \App\Models\SiteSetting::get('contact_email','contact@djibstay.dj');
@endphp

<div class="register-wrapper">
    <div class="register-card">

        {{-- Logo --}}
        <div class="register-logo">
            @if($logoPath)
                <img src="{{ asset('storage/'.$logoPath) }}" alt="{{ $appName }}">
            @else
                <div class="icon">🏨</div>
            @endif
            <h1>Inscription</h1>
            <p>Créez votre compte et réservez en quelques clics</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="row g-3">

                <div class="col-sm-6">
                    <label class="form-label-djib">Nom *</label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-person"></i>
                        <input type="text" name="name"
                               class="form-control-djib {{ $errors->has('name') ? 'is-error' : '' }}"
                               placeholder="Votre nom"
                               value="{{ old('name') }}" required>
                    </div>
                    @error('name')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="col-sm-6">
                    <label class="form-label-djib">Prénom *</label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-person"></i>
                        <input type="text" name="prenom"
                               class="form-control-djib {{ $errors->has('prenom') ? 'is-error' : '' }}"
                               placeholder="Votre prénom"
                               value="{{ old('prenom') }}" required>
                    </div>
                    @error('prenom')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label-djib">Adresse email *</label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-envelope"></i>
                        <input type="email" name="email"
                               class="form-control-djib {{ $errors->has('email') ? 'is-error' : '' }}"
                               placeholder="votre@email.com"
                               value="{{ old('email') }}" required>
                    </div>
                    @error('email')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label-djib">Téléphone</label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-telephone"></i>
                        <input type="tel" name="phone"
                               class="form-control-djib"
                               placeholder="+253 77 00 00 00"
                               value="{{ old('phone') }}">
                    </div>
                </div>

                <div class="col-sm-6">
                    <label class="form-label-djib">Mot de passe *</label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-lock"></i>
                        <input type="password" name="password" id="password"
                               class="form-control-djib {{ $errors->has('password') ? 'is-error' : '' }}"
                               placeholder="Min. 8 caractères" required>
                        <i class="bi bi-eye password-toggle" onclick="togglePass('password', this)"></i>
                    </div>
                    @error('password')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="col-sm-6">
                    <label class="form-label-djib">Confirmer *</label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-lock-fill"></i>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="form-control-djib"
                               placeholder="Répétez le mot de passe" required>
                        <i class="bi bi-eye password-toggle" onclick="togglePass('password_confirmation', this)"></i>
                    </div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn-register">
                        <i class="bi bi-person-plus me-2"></i>
                        Créer mon compte gratuitement
                    </button>
                </div>
            </div>
        </form>

        <divider class="divider"><span>ou</span></divider>

        <div class="login-link">
            Vous avez déjà un compte ?
            <a href="{{ route('login') }}">Connectez-vous ici</a>
        </div>

        {{-- Info partenaire --}}
        <div style="margin-top:20px;padding:14px 16px;background:#f0f7ff;border-radius:10px;border-left:3px solid #0071c2;">
            <div style="font-size:12px;font-weight:800;color:#003580;margin-bottom:6px;text-transform:uppercase;letter-spacing:.4px;">
                <i class="bi bi-building me-1"></i> Vous êtes un hôtelier ?
            </div>
            <div style="font-size:13px;color:#475569;line-height:1.6;">
                Contactez-nous à
                <a href="mailto:{{ $email }}" style="color:#0071c2;font-weight:700;">{{ $email }}</a>
                pour rejoindre notre plateforme.
            </div>
        </div>

        <div style="text-align:center;margin-top:16px;">
            <a href="{{ route('home') }}" style="font-size:13px;color:#94a3b8;text-decoration:none;">
                <i class="bi bi-arrow-left me-1"></i> Retour au site
            </a>
        </div>

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