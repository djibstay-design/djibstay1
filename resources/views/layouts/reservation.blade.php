<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="site-theme-{{ $siteTheme ?? 'default' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Réservation') - Réservation Hôtel</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
        /* Styles critiques réservation – visibles même si Tailwind tarde à charger */
        .reservation-page { min-height: 100vh; background: linear-gradient(to bottom, #eff6ff 0%, #ffffff 100%); color: #111827; }
        .reservation-nav { background-color: #0B3D91; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); }
        .reservation-nav a:not(.btn-admin) { color: rgba(255,255,255,0.9); }
        .reservation-nav a:not(.btn-admin):hover { color: #fff; }
        .reservation-nav .logo { color: #fff; font-weight: 700; font-size: 1.25rem; }
        .reservation-btn-admin { background: #fff; color: #0B3D91; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 500; }
        .reservation-btn-admin:hover { background: #eff6ff; }
        .reservation-card { background: #fff; border-radius: 1rem; box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1); padding: 1.5rem; }
        @media (min-width: 768px) { .reservation-card { padding: 2rem; } }
        .reservation-title { color: #0B3D91; font-weight: 700; font-size: 1.5rem; }
        @media (min-width: 768px) { .reservation-title { font-size: 1.875rem; } }
        .reservation-input { width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 0.75rem; }
        .reservation-input:focus { outline: none; border-color: #0B3D91; box-shadow: 0 0 0 2px rgba(11, 61, 145, 0.2); }
        .reservation-submit { width: 100%; padding: 1rem 1.5rem; background: #0B3D91; color: #fff; font-weight: 600; border-radius: 0.75rem; border: none; cursor: pointer; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); }
        .reservation-submit:hover { background: #092d6d; }
        .reservation-upload { border: 2px dashed #9ca3af; border-radius: 0.5rem; padding: 0.5rem 0.75rem; text-align: center; cursor: pointer; transition: border-color 0.2s, background 0.2s; }
        .reservation-upload:not(.has-preview) { min-height: 56px; }
        .reservation-upload:hover { border-color: rgba(11, 61, 145, 0.6); background: rgba(239, 246, 255, 0.6); }
        .reservation-upload.has-preview { min-height: 0; padding: 0.5rem; }
        .upload-preview-box { display: none; }
        .upload-preview-box.is-visible { display: block !important; }
        .upload-placeholder-box.is-hidden { display: none !important; }
        .upload-preview-box .preview-img-wrap { max-height: 120px; width: auto; margin: 0 auto; display: inline-block; }
        .upload-preview-box .preview-img-wrap img { max-height: 120px; width: auto; display: block; border-radius: 0.5rem; border: 1px solid #e5e7eb; }
        .upload-input-file { position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; font-size: 0; }
        .reservation-main { max-width: 56rem; margin-left: auto; margin-right: auto; padding: 2rem 1rem; }
        .reservation-card .grid { display: grid; gap: 1.5rem; }
        .reservation-card .sm\:grid-cols-2 { grid-template-columns: repeat(1, 1fr); }
        @media (min-width: 640px) { .reservation-card .sm\:grid-cols-2 { grid-template-columns: repeat(2, 1fr); } }
        .reservation-card .md\:grid-cols-3 { grid-template-columns: repeat(1, 1fr); }
        @media (min-width: 768px) { .reservation-card .md\:grid-cols-3 { grid-template-columns: repeat(3, 1fr); } }
    </style>
    @include('partials.public-site-theme')
</head>
<body class="reservation-page">
    {{-- Navbar professionnelle --}}
    <nav class="reservation-nav">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between flex-wrap gap-2">
            <a href="{{ route('home') }}" class="logo">Réservation Hôtel</a>
            <div class="flex items-center gap-6">
                <a href="{{ route('hotels.index') }}" class="text-sm font-medium">Hôtels</a>
                <a href="{{ route('reservations.status') }}" class="text-sm font-medium">Suivi réservation</a>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium px-4 py-2 rounded-lg text-white opacity-90 hover:opacity-100">Admin</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-white/80 hover:text-white">Déconnexion</button>
                    </form>
                @endauth
            </div>
        </div>
    </nav>

    @if (session('success'))
        <div class="max-w-4xl mx-auto px-4 pt-4">
            <div class="bg-green-100 text-green-800 px-4 py-3 rounded-xl">{{ session('success') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="max-w-4xl mx-auto px-4 pt-4">
            <div class="bg-red-50 text-red-800 px-4 py-3 rounded-xl border border-red-200">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <main class="reservation-main max-w-4xl mx-auto px-4 py-8 md:py-12">
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>
