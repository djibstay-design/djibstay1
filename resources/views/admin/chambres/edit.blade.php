@extends('layouts.admin')

@section('title', 'Modifier la chambre')

@section('content')

<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Modifier la chambre</h1>
            <p class="mt-1 text-sm text-slate-500">Chambre n°{{ $chambre->numero }} — {{ $chambre->typeChambre->nom_type }}</p>
        </div>
        <a href="{{ route('admin.chambres.index') }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Retour aux chambres
        </a>
    </div>
</div>

<div class="max-w-xl">
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <form action="{{ route('admin.chambres.update', $chambre) }}" method="POST" class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="type_id" class="block text-sm font-semibold text-slate-700 mb-2">Type de chambre <span class="text-red-500">*</span></label>
                <select name="type_id" id="type_id" required
                    class="w-full px-4 py-3 border border-slate-200 rounded-xl text-slate-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow bg-white">
                    @foreach ($typesChambre as $t)
                        <option value="{{ $t->id }}" {{ old('type_id', $chambre->type_id) == $t->id ? 'selected' : '' }}>
                            {{ $t->nom_type }} — {{ $t->hotel->nom }}
                        </option>
                    @endforeach
                </select>
                @error('type_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="numero" class="block text-sm font-semibold text-slate-700 mb-2">Numéro de chambre <span class="text-red-500">*</span></label>
                <input type="text" name="numero" id="numero" required value="{{ old('numero', $chambre->numero) }}" placeholder="Ex : 101"
                    class="w-full px-4 py-3 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow">
                @error('numero')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="etat" class="block text-sm font-semibold text-slate-700 mb-2">État <span class="text-red-500">*</span></label>
                <select name="etat" id="etat"
                    class="w-full px-4 py-3 border border-slate-200 rounded-xl text-slate-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow bg-white">
                    <option value="DISPONIBLE" {{ old('etat', $chambre->etat) == 'DISPONIBLE' ? 'selected' : '' }}>Disponible</option>
                    <option value="OCCUPEE" {{ old('etat', $chambre->etat) == 'OCCUPEE' ? 'selected' : '' }}>Occupée</option>
                    <option value="MAINTENANCE" {{ old('etat', $chambre->etat) == 'MAINTENANCE' ? 'selected' : '' }}>Maintenance</option>
                </select>
                @error('etat')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-wrap gap-3 pt-4 border-t border-slate-100">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Enregistrer
                </button>
                <a href="{{ route('admin.chambres.index') }}"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-xl font-semibold text-slate-600 bg-slate-100 border border-slate-200 hover:bg-slate-200 hover:text-slate-800 hover:border-slate-300 transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
