<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="site-theme-{{ $siteTheme ?? 'default' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Réservation confirmée') - {{ \App\Models\SiteSetting::get('app_name', 'DjibStay') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    {{-- Styles critiques : design dark premium même si Vite/Tailwind ne charge pas --}}
    <style>
        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            min-height: 100vh;
            background: linear-gradient(to bottom, #0B1220 0%, #0F172A 50%, #020617 100%);
            color: #fff;
            -webkit-font-smoothing: antialiased;
            margin: 0;
        }
        .brand-header { background: #003580; padding: 0.75rem 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.2); }
        .brand-inner { max-width: 72rem; margin: 0 auto; padding: 0 1rem; display: flex; align-items: center; }
        .brand-link { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .brand-icon { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .brand-icon svg { width: 24px; height: 24px; }
        .brand-text { font-size: 20px; font-weight: 800; letter-spacing: -0.5px; color: #febb02; }
        .brand-tagline { font-size: 10px; font-weight: 600; color: rgba(255,255,255,0.8); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px; }
        .confirmation-nav a { color: #3B82F6; font-weight: 600; font-size: 1.125rem; text-decoration: none; }
        .confirmation-card {
            max-width: 32rem;
            margin-left: auto;
            margin-right: auto;
            margin-top: 4rem;
            background: linear-gradient(to bottom right, #1E293B 0%, #0F172A 100%);
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
            padding: 2rem;
            border: 1px solid rgba(255,255,255,0.05);
        }
        .confirmation-icon {
            width: 4rem;
            height: 4rem;
            border-radius: 9999px;
            background: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.5);
        }
        .confirmation-title { font-size: 1.875rem; font-weight: 700; color: #60a5fa; text-align: center; margin-bottom: 0.5rem; }
        .confirmation-subtitle { color: #9ca3af; font-size: 0.875rem; text-align: center; margin-bottom: 1.5rem; }
        .confirmation-ref {
            background: #1E293B;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .confirmation-ref .label { color: #9ca3af; }
        .confirmation-ref .num { color: #60a5fa; font-weight: 700; }
        .confirmation-detail {
            display: grid;
            grid-template-columns: 100px 1fr;
            gap: 0.75rem 1rem;
            align-items: start;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .confirmation-detail .label { color: #9ca3af; font-size: 0.875rem; flex-shrink: 0; }
        .confirmation-detail .value { color: #fff; font-weight: 500; word-break: break-word; text-align: right; min-width: 0; }
        .confirmation-status {
            display: inline-block;
            border-radius: 9999px;
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .confirmation-total-wrap { margin-top: 1.5rem; padding-top: 1rem; }
        .confirmation-total { display: flex; justify-content: space-between; align-items: baseline; }
        .confirmation-total .label { color: #9ca3af; font-size: 0.875rem; }
        .confirmation-total .price { font-size: 1.5rem; font-weight: 700; color: #60a5fa; }
        .confirmation-btns { margin-top: 2rem; display: flex; flex-wrap: wrap; gap: 0.75rem; justify-content: center; }
        .confirmation-btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            background: #2563eb;
            color: #fff;
            font-weight: 500;
            border-radius: 0.75rem;
            text-decoration: none;
            transition: background 0.3s;
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);
        }
        .confirmation-btn-primary:hover { background: #1d4ed8; }
        .confirmation-btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            background: #1E293B;
            color: #d1d5db;
            font-weight: 500;
            border-radius: 0.75rem;
            border: 1px solid rgba(255,255,255,0.1);
            text-decoration: none;
            transition: background 0.3s;
        }
        .confirmation-btn-secondary:hover { background: #243145; }
    </style>
    @include('partials.public-site-theme')
</head>
<body class="min-h-screen bg-gradient-to-b from-[#0B1220] via-[#0F172A] to-[#020617] text-white antialiased">
    {{-- En-tête professionnel : logo DjibStay (#febb02) --}}
    <header class="brand-header w-full">
        <div class="brand-inner">
            <a href="{{ route('home') }}" class="brand-link">
                <div class="brand-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#febb02" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
                <div>
                    <span class="brand-text">{{ \App\Models\SiteSetting::get('app_name', 'DjibStay') }}</span>
                    <div class="brand-tagline">OFFICIAL HOTEL BOOKING PLATFORM IN DJIBOUTI</div>
                </div>
            </a>
        </div>
    </header>

    <main class="w-full px-4 pb-12">
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>
