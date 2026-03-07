<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion - DjibStay</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background: #f5f5f5; color: #374151; min-height: 100vh; margin: 0; }
        .login-page { min-height: 100vh; display: flex; flex-direction: column; }
        .login-header { padding: 24px 32px; }
        .logo { display: inline-flex; align-items: center; gap: 10px; text-decoration: none; color: #1f2937; font-weight: 700; font-size: 20px; }
        .logo-icon { width: 36px; height: 36px; background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; }
        .logo-icon svg { width: 20px; height: 20px; }
        .login-body { flex: 1; display: flex; align-items: center; justify-content: center; padding: 24px; }
        .login-card { background: #fff; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -2px rgba(0,0,0,0.1); padding: 40px; width: 100%; max-width: 400px; }
        .login-subtitle { color: #9ca3af; font-size: 14px; margin-bottom: 4px; }
        .login-title { font-size: 28px; font-weight: 700; color: #1f2937; margin-bottom: 28px; }
        .form-group { margin-bottom: 20px; }
        .form-input { width: 100%; padding: 12px 14px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 15px; font-family: inherit; background: #fff; }
        .form-input::placeholder { color: #9ca3af; }
        .form-input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }
        .form-options { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
        .form-options label { display: flex; align-items: center; gap: 8px; font-size: 14px; color: #374151; cursor: pointer; }
        .form-options input[type="checkbox"] { width: 16px; height: 16px; accent-color: #2563eb; }
        .forgot-link { font-size: 14px; color: #2563eb; text-decoration: none; font-weight: 500; }
        .forgot-link:hover { text-decoration: underline; }
        .btn-primary { width: 100%; padding: 12px 16px; background: #2563eb; color: #fff; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; font-family: inherit; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-google { width: 100%; padding: 12px 16px; background: #fff; color: #374151; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 15px; font-weight: 500; cursor: pointer; font-family: inherit; margin-top: 12px; display: flex; align-items: center; justify-content: center; gap: 10px; }
        .btn-google:hover { background: #f9fafb; border-color: #d1d5db; }
        .btn-google svg { width: 20px; height: 20px; }
        .alert-error { background: #fef2f2; color: #b91c1c; padding: 12px; border-radius: 8px; font-size: 14px; margin-bottom: 20px; }
        .alert-error ul { margin: 0; padding-left: 18px; }
    </style>
</head>
<body>
    <div class="login-page">
        <header class="login-header">
            <a href="{{ url('/') }}" class="logo">
                <div class="logo-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                        <line x1="12" y1="22.08" x2="12" y2="12"/>
                    </svg>
                </div>
                <span>DjibStay</span>
            </a>
        </header>

        <div class="login-body">
            <div class="login-card">
                <p class="login-subtitle">Please enter your details</p>
                <h1 class="login-title">Welcome back</h1>

                @if ($errors->any())
                    <div class="alert-error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                            class="form-input" placeholder="Email address">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" id="password" required
                            class="form-input" placeholder="Password">
                    </div>
                    <div class="form-options">
                        <label for="remember">
                            <input type="checkbox" name="remember" id="remember">
                            Remember for 30 days
                        </label>
                        <a href="#" class="forgot-link">Forgot password</a>
                    </div>
                    <button type="submit" class="btn-primary">Sign in</button>
                </form>

                <button type="button" class="btn-google" disabled aria-hidden="true">
                    <svg viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    Sign in with Google
                </button>
            </div>
        </div>
    </div>
</body>
</html>
