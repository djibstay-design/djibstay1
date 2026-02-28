@extends('layouts.admin')

@section('title', 'Nouvel hôtel')

@section('content')
<h1 class="text-2xl font-semibold mb-6">Nouvel hôtel</h1>

<form action="{{ route('admin.hotels.store') }}" method="POST" class="max-w-xl space-y-4">
    @csrf
    <div>
        <label for="nom" class="block text-sm font-medium mb-1">Nom *</label>
        <input type="text" name="nom" id="nom" required value="{{ old('nom') }}"
            class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
    </div>
    <div>
        <label for="adresse" class="block text-sm font-medium mb-1">Adresse</label>
        <input type="text" name="adresse" id="adresse" value="{{ old('adresse') }}"
            class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
    </div>
    <div>
        <label for="ville" class="block text-sm font-medium mb-1">Ville</label>
        <input type="text" name="ville" id="ville" value="{{ old('ville') }}"
            class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
    </div>
    <div>
        <label for="description" class="block text-sm font-medium mb-1">Description</label>
        <textarea name="description" id="description" rows="4" class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">{{ old('description') }}</textarea>
    </div>
    <button type="submit" class="px-4 py-2 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] rounded font-medium">Créer</button>
</form>
@endsection
