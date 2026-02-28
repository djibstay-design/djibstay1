@extends('layouts.admin')

@section('title', 'Nouvel utilisateur')

@section('content')
<h1 class="text-2xl font-semibold mb-6">Nouvel administrateur</h1>

<form action="{{ route('admin.users.store') }}" method="POST" class="max-w-xl space-y-4">
    @csrf
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="name" class="block text-sm font-medium mb-1">Nom *</label>
            <input type="text" name="name" id="name" required value="{{ old('name') }}"
                class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
        </div>
        <div>
            <label for="prenom" class="block text-sm font-medium mb-1">Prénom</label>
            <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}"
                class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
        </div>
    </div>
    <div>
        <label for="email" class="block text-sm font-medium mb-1">Email *</label>
        <input type="email" name="email" id="email" required value="{{ old('email') }}"
            class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
    </div>
    <div>
        <label for="role" class="block text-sm font-medium mb-1">Rôle *</label>
        <select name="role" id="role" required class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
            <option value="ADMIN" {{ old('role') === 'ADMIN' ? 'selected' : '' }}>Admin (gestionnaire hôtel)</option>
            <option value="SUPER_ADMIN" {{ old('role') === 'SUPER_ADMIN' ? 'selected' : '' }}>Super Admin</option>
        </select>
    </div>
    <div>
        <label for="password" class="block text-sm font-medium mb-1">Mot de passe *</label>
        <input type="password" name="password" id="password" required
            class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
    </div>
    <div>
        <label for="password_confirmation" class="block text-sm font-medium mb-1">Confirmer le mot de passe *</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required
            class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
    </div>
    <button type="submit" class="px-4 py-2 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] rounded font-medium">Créer</button>
</form>
@endsection
