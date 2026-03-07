@extends('layouts.admin')
@section('title', 'Ajouter des images')

@push('styles')
<style>
    .upload-dropzone {
        position: relative;
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 48px 24px;
        text-align: center;
        transition: all 0.25s ease;
        cursor: pointer;
        background: #f8fafc;
    }
    .upload-dropzone:hover,
    .upload-dropzone.dragover {
        border-color: #3b82f6;
        background: #eff6ff;
    }
    .upload-dropzone.dragover {
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }
    .upload-dropzone input[type="file"] {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    .preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
        gap: 12px;
        margin-top: 16px;
    }
    .preview-item {
        position: relative;
        aspect-ratio: 1;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid #e2e8f0;
        background: #f8fafc;
        transition: border-color 0.2s;
    }
    .preview-item:hover { border-color: #3b82f6; }
    .preview-item img { width: 100%; height: 100%; object-fit: cover; }
    .preview-item .preview-index {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(0deg, rgba(0,0,0,0.6), transparent);
        color: #fff;
        font-size: 11px;
        font-weight: 600;
        padding: 12px 8px 6px;
        text-align: center;
    }
    .section-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .step-number {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="max-w-3xl">
    <div class="mb-8">
        <a href="{{ route('admin.images.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-blue-600 mb-3 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Retour aux images
        </a>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Ajouter des images</h1>
        <p class="text-sm text-slate-500 mt-1">Sélectionnez un hôtel et téléversez des photos pour enrichir sa galerie.</p>
    </div>

    <form action="{{ route('admin.images.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
        @csrf

        {{-- Step 1 : Hotel --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden mb-5">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                <div class="step-number bg-blue-600 text-white">1</div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Choisir l'hôtel</h2>
                    <p class="text-xs text-slate-400">Sélectionnez l'hôtel auquel ajouter des images</p>
                </div>
            </div>
            <div class="p-6">
                <select name="hotel_id" id="hotel_id" required
                    class="w-full px-4 py-3 border border-slate-200 rounded-xl text-slate-800 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow text-sm font-medium">
                    <option value="">-- Sélectionner un hôtel --</option>
                    @foreach ($hotels as $h)
                        <option value="{{ $h->id }}" {{ old('hotel_id') == $h->id ? 'selected' : '' }}>{{ $h->nom }}</option>
                    @endforeach
                </select>
                @error('hotel_id')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5">
                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        {{-- Step 2 : Images --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden mb-5">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                <div class="step-number bg-blue-600 text-white">2</div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Téléverser les images</h2>
                    <p class="text-xs text-slate-400">PNG, JPG, GIF ou WEBP &middot; 5 Mo max par fichier</p>
                </div>
            </div>
            <div class="p-6">
                <div class="upload-dropzone" id="dropzone">
                    <input type="file" name="images[]" id="fileInput" multiple
                           accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" required>
                    <div class="pointer-events-none">
                        <div class="w-16 h-16 rounded-2xl bg-blue-100 text-blue-500 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-700 mb-1">Glissez-déposez vos images ici</p>
                        <p class="text-xs text-slate-400">ou <span class="text-blue-600 font-medium">parcourez vos fichiers</span></p>
                    </div>
                </div>

                <div class="preview-grid" id="previewGrid" style="display:none;"></div>
                <p id="fileCount" class="mt-3 text-sm text-slate-500 font-medium hidden"></p>

                @error('images')
                    <p class="mt-3 text-sm text-red-600 flex items-center gap-1.5">
                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
                @error('images.*')
                    <p class="mt-3 text-sm text-red-600 flex items-center gap-1.5">
                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        {{-- Step 3 : Options --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                <div class="step-number bg-slate-200 text-slate-600">3</div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Options</h2>
                    <p class="text-xs text-slate-400">Optionnel — définir une image principale</p>
                </div>
            </div>
            <div class="p-6">
                <label for="is_main_index" class="block text-sm font-semibold text-slate-700 mb-1.5">Image principale</label>
                <p class="text-xs text-slate-400 mb-3">Indiquez le numéro de l'image qui sera affichée en couverture (1 = première, 2 = deuxième, etc.). Laissez vide pour ne pas modifier.</p>
                <input type="number" name="is_main_index" id="is_main_index" min="0"
                       value="{{ old('is_main_index') }}" placeholder="Ex : 1"
                       class="w-full max-w-[200px] px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-wrap items-center gap-3">
            <button type="submit" id="submitBtn" disabled
                class="inline-flex items-center gap-2 px-7 py-3 bg-blue-600 hover:bg-blue-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-semibold rounded-xl shadow-sm hover:shadow-md transition-all text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Enregistrer les images
            </button>
            <a href="{{ route('admin.images.index') }}"
               class="inline-flex items-center gap-2 px-5 py-3 rounded-xl font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 transition-colors text-sm">
                Annuler
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
(function() {
    var dropzone = document.getElementById('dropzone');
    var fileInput = document.getElementById('fileInput');
    var previewGrid = document.getElementById('previewGrid');
    var fileCount = document.getElementById('fileCount');
    var submitBtn = document.getElementById('submitBtn');

    if (!dropzone || !fileInput) return;

    ['dragenter','dragover'].forEach(function(evt) {
        dropzone.addEventListener(evt, function(e) {
            e.preventDefault();
            dropzone.classList.add('dragover');
        });
    });
    ['dragleave','drop'].forEach(function(evt) {
        dropzone.addEventListener(evt, function(e) {
            e.preventDefault();
            dropzone.classList.remove('dragover');
        });
    });
    dropzone.addEventListener('drop', function(e) {
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            fileInput.dispatchEvent(new Event('change'));
        }
    });

    fileInput.addEventListener('change', function() {
        var files = this.files;
        previewGrid.innerHTML = '';
        if (files.length === 0) {
            previewGrid.style.display = 'none';
            fileCount.classList.add('hidden');
            submitBtn.disabled = true;
            return;
        }
        previewGrid.style.display = 'grid';
        submitBtn.disabled = false;
        fileCount.textContent = files.length + ' fichier' + (files.length > 1 ? 's' : '') + ' sélectionné' + (files.length > 1 ? 's' : '');
        fileCount.classList.remove('hidden');

        Array.from(files).forEach(function(file, idx) {
            if (!file.type.startsWith('image/')) return;
            var reader = new FileReader();
            reader.onload = function(e) {
                var div = document.createElement('div');
                div.className = 'preview-item';
                div.innerHTML = '<img src="' + e.target.result + '" alt="Aperçu"><div class="preview-index">#' + (idx + 1) + '</div>';
                previewGrid.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    });
})();
</script>
@endpush
@endsection
