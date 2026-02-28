@extends('layouts.admin')

@section('title', 'Modifier réservation')

@section('content')
<h1 class="text-2xl font-semibold mb-6">Modifier le statut - {{ $reservation->code_reservation }}</h1>

<form action="{{ route('admin.reservations.update', $reservation) }}" method="POST" class="max-w-md">
    @csrf
    @method('PUT')
    <div>
        <label for="statut" class="block text-sm font-medium mb-1">Statut *</label>
        <select name="statut" id="statut" class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded">
            <option value="EN_ATTENTE" {{ old('statut', $reservation->statut) === 'EN_ATTENTE' ? 'selected' : '' }}>En attente</option>
            <option value="CONFIRMEE" {{ old('statut', $reservation->statut) === 'CONFIRMEE' ? 'selected' : '' }}>Confirmée</option>
            <option value="ANNULEE" {{ old('statut', $reservation->statut) === 'ANNULEE' ? 'selected' : '' }}>Annulée</option>
        </select>
    </div>
    <button type="submit" class="mt-4 px-4 py-2 bg-[#1b1b18] dark:bg-[#EDEDEC] text-white dark:text-[#1b1b18] rounded font-medium">Enregistrer</button>
</form>
@endsection
