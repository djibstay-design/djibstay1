@extends('layouts.admin')

@section('title', 'Nouvel hôtel')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Nouvel hôtel</h1>
    <p class="mt-1 text-sm text-slate-500">Créez un nouvel hôtel et ajoutez une image principale (optionnel).</p>
</div>

<form action="{{ route('admin.hotels.store') }}" method="POST" enctype="multipart/form-data" class="max-w-xl space-y-6">
    @csrf
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
        <div>
            <label for="admin_id" class="block text-sm font-semibold text-slate-700 mb-2">Administrateur de l'hôtel <span class="text-red-500">*</span></label>
            <select name="admin_id" id="admin_id" required
                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                <option value="">-- Sélectionner un administrateur --</option>
                @foreach($admins as $admin)
                    <option value="{{ $admin->id }}" {{ old('admin_id') == $admin->id ? 'selected' : '' }}>
                        {{ $admin->name }} {{ $admin->prenom }} ({{ $admin->email }})
                    </option>
                @endforeach
            </select>
            @error('admin_id')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="nom" class="block text-sm font-semibold text-slate-700 mb-2">Nom <span class="text-red-500">*</span></label>
            <input type="text" name="nom" id="nom" required value="{{ old('nom') }}"
                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label for="adresse" class="block text-sm font-semibold text-slate-700 mb-2">Adresse</label>
            <input type="text" name="adresse" id="adresse" value="{{ old('adresse') }}"
                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label for="ville" class="block text-sm font-semibold text-slate-700 mb-2">Ville</label>
            <input type="text" name="ville" id="ville" value="{{ old('ville') }}"
                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
            <textarea name="description" id="description" rows="4" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
        </div>
        <div>
            <label for="main_image" class="block text-sm font-semibold text-slate-700 mb-2">Image principale (optionnel)</label>
            <p class="text-xs text-slate-500 mb-2">Cette image sera affichée en premier sur la fiche hôtel. Vous pourrez en ajouter d'autres après la création.</p>
            <input type="file" name="main_image" id="main_image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            @error('main_image')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="pt-4 border-t border-slate-100 flex gap-3">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md transition-colors">
                Créer l'hôtel
            </button>
            <a href="{{ route('admin.hotels.index') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl font-semibold text-slate-600 bg-slate-100 border border-slate-200 hover:bg-slate-200 hover:text-slate-800 hover:border-slate-300 transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                Annuler
            </a>
        </div>
    </div>
</form>
@endsection
