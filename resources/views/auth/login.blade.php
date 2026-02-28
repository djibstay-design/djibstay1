@extends('layouts.app')

@section('title', 'Connexion Admin')

@section('content')
<div class="max-w-md mx-auto">
    <h1 class="text-2xl font-semibold mb-6">Connexion administrateur</h1>
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
            <label for="email" class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded bg-white dark:bg-[#161615]">
        </div>
        <div>
            <label for="password" class="block text-sm font-medium mb-1">Mot de passe</label>
            <input type="password" name="password" id="password" required
                class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded bg-white dark:bg-[#161615]">
        </div>
        <div class="flex items-center">
            <input type="checkbox" name="remember" id="remember" class="rounded">
            <label for="remember" class="ml-2 text-sm">Se souvenir de moi</label>
        </div>
        <button type="submit" class="w-full px-4 py-2 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] rounded font-medium hover:opacity-90">
            Se connecter
        </button>
    </form>
</div>
@endsection
