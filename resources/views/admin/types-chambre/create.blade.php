@extends('layouts.admin')

@section('title', 'Nouveau type de chambre')

@section('content')
<h1 class="text-2xl font-semibold mb-6">Nouveau type de chambre</h1>

<form action="{{ route('admin.types-chambre.store') }}" method="POST" class="max-w-xl space-y-4">
    @csrf
    <div>
        <label for="hotel_id" class="block text-sm font-medium mb-1">Hôtel *</label>
        <select name="hotel_id" id="hotel_id" required class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
            <option value="">-- Sélectionner --</option>
            @foreach ($hotels as $h)
                <option value="{{ $h->id }}" {{ old('hotel_id') == $h->id ? 'selected' : '' }}>{{ $h->nom }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="nom_type" class="block text-sm font-medium mb-1">Nom du type *</label>
        <input type="text" name="nom_type" id="nom_type" required value="{{ old('nom_type') }}"
            class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
    </div>
    <div>
        <label for="capacite" class="block text-sm font-medium mb-1">Capacité (personnes) *</label>
        <input type="number" name="capacite" id="capacite" required min="1" value="{{ old('capacite') }}"
            class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
    </div>
    <div>
        <label for="prix_par_nuit" class="block text-sm font-medium mb-1">Prix par nuit (DJF) *</label>
        <input type="number" name="prix_par_nuit" id="prix_par_nuit" required min="0" step="0.01" value="{{ old('prix_par_nuit') }}"
            class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
    </div>
    <div>
        <label for="description" class="block text-sm font-medium mb-1">Description</label>
        <textarea name="description" id="description" rows="3" class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">{{ old('description') }}</textarea>
    </div>
    <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md transition-colors">Créer</button>
</form>
@endsection
