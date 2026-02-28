<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name')) - Gestion Réservation</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] min-h-screen">
    <nav class="border-b border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615]">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="font-semibold text-lg">Réservation Hôtel</a>
            <div class="flex items-center gap-4">
                <a href="{{ route('hotels.index') }}" class="text-sm hover:underline">Hôtels</a>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="text-sm px-4 py-2 rounded border border-[#e3e3e0] dark:border-[#3E3E3A] hover:bg-gray-100 dark:hover:bg-[#3E3E3A]">Admin</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:underline">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm px-4 py-2 rounded border border-[#e3e3e0] dark:border-[#3E3E3A] hover:bg-gray-100 dark:hover:bg-[#3E3E3A]">Connexion Admin</a>
                @endauth
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
