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
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] min-h-screen flex">
    <aside class="w-56 shrink-0 border-r border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] min-h-screen flex flex-col">
        <a href="{{ route('admin.dashboard') }}" class="p-4 font-semibold text-lg border-b border-[#e3e3e0] dark:border-[#3E3E3A]">Admin</a>
        <nav class="flex-1 p-2 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-[#3E3E3A] text-sm">Site</a>
            @if (auth()->user()->role === 'SUPER_ADMIN')
                <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-[#3E3E3A] text-sm">Utilisateurs</a>
            @endif
            <a href="{{ route('admin.hotels.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-[#3E3E3A] text-sm">Hôtels</a>
            <a href="{{ route('admin.types-chambre.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-[#3E3E3A] text-sm">Types chambre</a>
            <a href="{{ route('admin.chambres.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-[#3E3E3A] text-sm">Chambres</a>
            <a href="{{ route('admin.reservations.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-[#3E3E3A] text-sm">Réservations</a>
            <a href="{{ route('admin.avis.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-[#3E3E3A] text-sm">Avis</a>
        </nav>
        <div class="p-2 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-[#3E3E3A] text-sm text-red-600 dark:text-red-400">Déconnexion</button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0">
        @if (session('success'))
            <div class="px-4 py-2">
                <div class="bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 px-4 py-2 rounded">{{ session('success') }}</div>
            </div>
        @endif
        @if (session('error'))
            <div class="px-4 py-2">
                <div class="bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 px-4 py-2 rounded">{{ session('error') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="px-4 py-2">
                <div class="bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 px-4 py-2 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <main class="flex-1 p-6 overflow-auto">
            @yield('content')
        </main>
    </div>
</body>
</html>
