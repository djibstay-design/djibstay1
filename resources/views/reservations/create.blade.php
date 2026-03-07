@extends('layouts.reservation')

@section('title', 'Réservation')

@section('content')
<div class="reservation-card bg-white rounded-2xl shadow-xl p-6 md:p-8">
    {{-- Titre --}}
    <h1 class="reservation-title text-2xl md:text-3xl font-bold text-[#0B3D91]">Réservation</h1>
    <p class="text-gray-500 mt-1 text-sm">
        {{ $chambre->typeChambre->hotel->nom }} — Chambre {{ $chambre->numero }} ({{ $chambre->typeChambre->nom_type }}) · {{ number_format($chambre->typeChambre->prix_par_nuit, 0, ',', ' ') }} DJF / nuit
    </p>

    <form action="{{ route('reservations.store') }}" method="POST" enctype="multipart/form-data" class="mt-8 space-y-6" id="reservation-form">
        @csrf
        <input type="hidden" name="chambre_id" value="{{ $chambre->id }}">

        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <label for="nom_client" class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                <input type="text" name="nom_client" id="nom_client" required value="{{ old('nom_client') }}"
                    class="reservation-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#0B3D91] focus:ring-2 focus:ring-[#0B3D91]/20 outline-none transition">
            </div>
            <div>
                <label for="prenom_client" class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
                <input type="text" name="prenom_client" id="prenom_client" required value="{{ old('prenom_client') }}"
                    class="reservation-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#0B3D91] focus:ring-2 focus:ring-[#0B3D91]/20 outline-none transition">
            </div>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <label for="email_client" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                <input type="email" name="email_client" id="email_client" required value="{{ old('email_client') }}"
                    class="reservation-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#0B3D91] focus:ring-2 focus:ring-[#0B3D91]/20 outline-none transition">
            </div>
            <div>
                <label for="telephone_client" class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                <input type="tel" name="telephone_client" id="telephone_client" value="{{ old('telephone_client') }}" placeholder="Optionnel"
                    class="reservation-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#0B3D91] focus:ring-2 focus:ring-[#0B3D91]/20 outline-none transition">
            </div>
        </div>

        <div>
            <label for="code_identite" class="block text-sm font-medium text-gray-700 mb-2">N° de pièce d'identité *</label>
            <input type="text" name="code_identite" id="code_identite" required value="{{ old('code_identite') }}" placeholder="Ex. numéro CNI ou passeport"
                class="reservation-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#0B3D91] focus:ring-2 focus:ring-[#0B3D91]/20 outline-none transition">
        </div>

        <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3">
            <div>
                <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-2">Arrivée *</label>
                <input type="date" name="date_debut" id="date_debut" required value="{{ old('date_debut') }}"
                    class="reservation-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#0B3D91] focus:ring-2 focus:ring-[#0B3D91]/20 outline-none transition">
            </div>
            <div>
                <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-2">Départ *</label>
                <input type="date" name="date_fin" id="date_fin" required value="{{ old('date_fin') }}"
                    class="reservation-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#0B3D91] focus:ring-2 focus:ring-[#0B3D91]/20 outline-none transition">
            </div>
            <div>
                <label for="quantite" class="block text-sm font-medium text-gray-700 mb-2">Voyageurs *</label>
                <input type="number" name="quantite" id="quantite" required min="1" value="{{ old('quantite', 1) }}"
                    class="reservation-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#0B3D91] focus:ring-2 focus:ring-[#0B3D91]/20 outline-none transition">
            </div>
        </div>

        {{-- Uploads : 2 colonnes sur desktop pour éviter le scroll --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pièce d'identité (photo) *</label>
                <div class="reservation-upload upload-zone relative rounded-lg py-3 px-3 text-center transition cursor-pointer"
                     data-input="photo_carte"
                     data-preview="preview_carte">
                    <input type="file" name="photo_carte" id="photo_carte" required accept="image/jpeg,image/jpg,image/png" class="upload-input-file">
                    <div class="upload-placeholder upload-placeholder-box pointer-events-none">
                        <svg class="mx-auto h-7 w-7 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <p class="mt-0.5 text-xs text-gray-500">Cliquer ou glisser · JPG/PNG, 2 Mo max.</p>
                    </div>
                    <div id="preview_carte" class="upload-preview-box">
                        <div class="preview-img-wrap">
                            <img src="" alt="Aperçu pièce d'identité">
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">Fichier ajouté</p>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Photo du visage *</label>
                <div class="reservation-upload upload-zone relative rounded-lg py-3 px-3 text-center transition cursor-pointer"
                     data-input="photo_visage"
                     data-preview="preview_visage">
                    <input type="file" name="photo_visage" id="photo_visage" required accept="image/jpeg,image/jpg,image/png" class="upload-input-file">
                    <div class="upload-placeholder upload-placeholder-box pointer-events-none">
                        <svg class="mx-auto h-7 w-7 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <p class="mt-0.5 text-xs text-gray-500">Cliquer ou glisser · JPG/PNG, 2 Mo max.</p>
                    </div>
                    <div id="preview_visage" class="upload-preview-box">
                        <div class="preview-img-wrap">
                            <img src="" alt="Aperçu visage">
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">Fichier ajouté</p>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="reservation-submit w-full py-4 px-6 bg-[#0B3D91] text-white font-semibold rounded-xl hover:bg-[#092d6d] focus:ring-2 focus:ring-[#0B3D91]/30 focus:ring-offset-2 transition shadow-md">
            Valider la réservation
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.upload-zone').forEach(function(zone) {
        var inputId = zone.getAttribute('data-input');
        var previewId = zone.getAttribute('data-preview');
        var input = document.getElementById(inputId);
        var previewEl = document.getElementById(previewId);
        var placeholder = zone.querySelector('.upload-placeholder');
        var img = previewEl ? previewEl.querySelector('img') : null;

        function showPreview(file) {
            if (!file || !file.type.startsWith('image/')) return;
            var reader = new FileReader();
            reader.onload = function(e) {
                if (img) img.src = e.target.result;
                if (placeholder) { placeholder.classList.add('is-hidden'); placeholder.style.display = 'none'; }
                if (previewEl) { previewEl.classList.add('is-visible'); previewEl.style.display = 'block'; }
                zone.classList.add('has-preview');
            };
            reader.readAsDataURL(file);
        }

        function resetPreview() {
            if (img) img.src = '';
            if (placeholder) { placeholder.classList.remove('is-hidden'); placeholder.style.display = ''; }
            if (previewEl) { previewEl.classList.remove('is-visible'); previewEl.style.display = 'none'; }
            zone.classList.remove('has-preview');
        }

        if (previewEl) previewEl.style.display = 'none';

        input.addEventListener('change', function() {
            if (this.files && this.files[0]) showPreview(this.files[0]);
            else resetPreview();
        });

        zone.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            zone.style.borderColor = 'rgba(11, 61, 145, 0.6)';
            zone.style.background = 'rgba(239, 246, 255, 0.8)';
        });
        zone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            zone.style.borderColor = '';
            zone.style.background = '';
        });
        zone.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            zone.style.borderColor = '';
            zone.style.background = '';
            var file = e.dataTransfer && e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                var dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
                showPreview(file);
            }
        });
    });
});
</script>
@endpush
