@extends('layouts.admin')

@section('title', 'Modifier l\'hôtel')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Modifier {{ $hotel->nom }}</h1>
    <a href="{{ route('admin.hotels.images.index', $hotel) }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        Gérer les images
    </a>
</div>

<form action="{{ route('admin.hotels.update', $hotel) }}" method="POST" class="max-w-xl space-y-4">
    @csrf
    @method('PUT')
    <div>
        <label for="nom" class="block text-sm font-medium mb-1">Nom *</label>
        <input type="text" name="nom" id="nom" required value="{{ old('nom', $hotel->nom) }}"
            class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
    </div>
    <div>
        <label for="adresse" class="block text-sm font-medium mb-1">Adresse</label>
        <input type="text" name="adresse" id="adresse" value="{{ old('adresse', $hotel->adresse) }}"
            class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
    </div>
    <div>
        <label for="ville" class="block text-sm font-medium mb-1">Ville</label>
        <input type="text" name="ville" id="ville" value="{{ old('ville', $hotel->ville) }}"
            class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
    </div>
    <div>
        <label for="description" class="block text-sm font-medium mb-1">Description</label>
        <textarea name="description" id="description" rows="4" class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">{{ old('description', $hotel->description) }}</textarea>
    </div>
    <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md transition-colors">Enregistrer</button>
</form>
@endsection
