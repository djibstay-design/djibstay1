@extends('layouts.admin')

@section('title', 'Nouvelle chambre')

@section('content')
<h1 class="text-2xl font-semibold mb-6">Nouvelle chambre</h1>

<form action="{{ route('admin.chambres.store') }}" method="POST" class="max-w-xl space-y-4">
    @csrf
    <div>
        <label for="type_id" class="block text-sm font-medium mb-1">Type de chambre *</label>
        <select name="type_id" id="type_id" required class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
            <option value="">-- Sélectionner --</option>
            @foreach ($typesChambre as $t)
                <option value="{{ $t->id }}" {{ old('type_id') == $t->id ? 'selected' : '' }}>
                    {{ $t->nom_type }} - {{ $t->hotel->nom }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="numero" class="block text-sm font-medium mb-1">Numéro de chambre *</label>
        <input type="text" name="numero" id="numero" required value="{{ old('numero') }}"
            class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
    </div>
    <div>
        <label for="etat" class="block text-sm font-medium mb-1">État *</label>
        <select name="etat" id="etat" class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
            <option value="DISPONIBLE" {{ old('etat', 'DISPONIBLE') == 'DISPONIBLE' ? 'selected' : '' }}>Disponible</option>
            <option value="OCCUPEE" {{ old('etat') == 'OCCUPEE' ? 'selected' : '' }}>Occupée</option>
            <option value="MAINTENANCE" {{ old('etat') == 'MAINTENANCE' ? 'selected' : '' }}>Maintenance</option>
        </select>
    </div>
    <button type="submit" class="px-4 py-2 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] rounded font-medium">Créer</button>
</form>
@endsection
