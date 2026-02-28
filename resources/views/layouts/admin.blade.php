<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') - Gestion Réservation</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] min-h-screen">
    <nav class="border-b border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615]">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('admin.dashboard') }}" class="font-semibold text-lg">Admin</a>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('home') }}" class="text-sm px-3 py-1.5 rounded hover:bg-gray-100 dark:hover:bg-[#3E3E3A]">Site</a>
                <a href="{{ route('admin.hotels.index') }}" class="text-sm px-3 py-1.5 rounded hover:bg-gray-100 dark:hover:bg-[#3E3E3A]">Hôtels</a>
                <a href="{{ route('admin.types-chambre.index') }}" class="text-sm px-3 py-1.5 rounded hover:bg-gray-100 dark:hover:bg-[#3E3E3A]">Types chambre</a>
                <a href="{{ route('admin.chambres.index') }}" class="text-sm px-3 py-1.5 rounded hover:bg-gray-100 dark:hover:bg-[#3E3E3A]">Chambres</a>
                <a href="{{ route('admin.reservations.index') }}" class="text-sm px-3 py-1.5 rounded hover:bg-gray-100 dark:hover:bg-[#3E3E3A]">Réservations</a>
                <a href="{{ route('admin.avis.index') }}" class="text-sm px-3 py-1.5 rounded hover:bg-gray-100 dark:hover:bg-[#3E3E3A]">Avis</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:underline px-3 py-1.5">Déconnexion</button>
                </form>
            </div>
        </div>
    </nav>

    @if (session('success'))
        <div class="max-w-6xl mx-auto px-4 py-2">
            <div class="bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 px-4 py-2 rounded">{{ session('success') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="max-w-6xl mx-auto px-4 py-2">
            <div class="bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 px-4 py-2 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <main class="max-w-6xl mx-auto px-4 py-8">
        @yield('content')
    </main>
</body>
</html>
