@extends('layouts.app')

@section('title', \App\Models\SiteSetting::get('app_name','DjibStay').' — Connexion')

@push('styles')
<style>
    .login-wrapper {
        min-height: calc(100vh - 64px);
        display: flex;
        align-items: center;
        justify-content: center;
background: url('https://images.unsplash.com/photo-1582719508461-905c673771fd?q=80&w=1920&auto=format&fit=crop') center/cover no-repeat;        padding: 40px 16px;
    }
    .login-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        padding: 44px 40px;
        width: 100%;
        max-width: 440px;
    }
    .login-logo {
        text-align: center;
        margin-bottom: 32px;
    }
    .login-logo img {
        height: 80px;
        object-fit: contain;
        display: block;
        margin: 0 auto 10px;
    }
    .login-logo .icon {
        width: 80px; height: 80px;
        background: linear-gradient(135deg, #003580, #0071c2);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 14px;
        font-size: 36px; color: #fff;
        box-shadow: 0 8px 24px rgba(0,53,128,0.3);
    }
    .login-logo h1 {
        font-size: 26px; font-weight: 900;
        color: #003580; margin-bottom: 4px;
    }
    .login-logo p { font-size: 13px; color: #64748b; }
    .form-label-djib {
        font-size: 12px; font-weight: 700;
        color: #003580; text-transform: uppercase;
        letter-spacing: .5px; margin-bottom: 6px; display: block;
    }
    .form-control-djib {
        border: 2px solid #e2e8f0;
        border-radius: 9px; padding: 11px 14px;
        font-size: 14px; color: #1a1a2e;
        width: 100%; transition: border-color .2s, box-shadow .2s;
    }
    .form-control-djib:focus {
        border-color: #0071c2;
        box-shadow: 0 0 0 3px rgba(0,113,194,0.12);
        outline: none;
    }
    .form-control-djib.is-error { border-color: #dc2626; }
    .field-error { color: #dc2626; font-size: 12px; margin-top: 5px; }
    .input-icon-wrap { position: relative; }
    .input-icon-wrap .bi {
        position: absolute; left: 13px; top: 50%;
        transform: translateY(-50%);
        color: #94a3b8; font-size: 16px;
    }
    .input-icon-wrap .form-control-djib { padding-left: 38px; }
    .btn-login {
        background: linear-gradient(135deg, #003580, #0071c2);
        color: #fff; border: none; border-radius: 9px;
        font-weight: 800; font-size: 15px;
        padding: 13px; width: 100%;
        cursor: pointer; transition: all .2s;
        letter-spacing: .3px;
        box-shadow: 0 4px 16px rgba(0,53,128,0.25);
    }
    .btn-login:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(0,53,128,0.35); }
    .btn-login:active { transform: translateY(0); }
    .login-footer {
        text-align: center; margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
    }
    .remember-row {
        display: flex; align-items: center;
        justify-content: space-between; margin-bottom: 24px;
    }
    .remember-row label {
        font-size: 13px; color: #475569;
        font-weight: 500; cursor: pointer;
        display: flex; align-items: center; gap: 7px;
    }
    .remember-row input[type="checkbox"] {
        width: 16px; height: 16px;
        accent-color: #003580; cursor: pointer;
    }
</style>
@endpush

@section('content')
@php
    $appName  = \App\Models\SiteSetting::get('app_name','DjibStay');
    $logoPath = \App\Models\SiteSetting::get('app_logo','');
@endphp

<div class="login-wrapper">
    <div class="login-card">

        {{-- Logo centré --}}
        <div class="login-logo">
            @if($logoPath)
                <img src="{{ asset('storage/'.$logoPath) }}" alt="{{ $appName }}">
            @else
                <div class="icon"><i class="bi bi-building"></i></div>
            @endif
            <h1>{{ $appName }}</h1>
            <p>Bienvenue ! Connectez-vous à votre compte.</p>
        </div>

        @if($errors->has('email'))
            <div class="alert alert-danger mb-4" style="border-radius:9px;font-size:13px;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first('email') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label class="form-label-djib" for="email">Adresse email</label>
                <div class="input-icon-wrap">
                    <i class="bi bi-envelope"></i>
                    <input type="email" id="email" name="email"
                           class="form-control-djib {{ $errors->has('email') ? 'is-error' : '' }}"
                           placeholder="votre@email.com"
                           value="{{ old('email') }}"
                           required autofocus autocomplete="email">
                </div>
                @error('email')
                    <div class="field-error">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label-djib" for="password">Mot de passe</label>
                <div class="input-icon-wrap">
                    <i class="bi bi-lock"></i>
                    <input type="password" id="password" name="password"
                           class="form-control-djib {{ $errors->has('password') ? 'is-error' : '' }}"
                           placeholder="••••••••"
                           required autocomplete="current-password">
                </div>
                @error('password')
                    <div class="field-error">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <div class="remember-row">
                <label>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Se souvenir de moi
                </label>
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i> Se connecter
            </button>
        </form>

        <div class="login-footer">
            <p style="font-size:14px;color:#475569;margin-bottom:10px;">
                Pas encore de compte ?
                <a href="{{ route('register') }}" style="color:#0071c2;font-weight:700;">
                    Inscrivez-vous gratuitement
                </a>
            </p>
            <a href="{{ route('home') }}" style="font-size:13px;color:#94a3b8;text-decoration:none;">
                <i class="bi bi-arrow-left me-1"></i> Retour au site
            </a>
        </div>
    </div>
</div>
@endsection