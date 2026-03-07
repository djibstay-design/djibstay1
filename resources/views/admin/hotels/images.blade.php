@extends('layouts.admin')

@section('title', 'Images — ' . $hotel->nom)

@push('styles')
<style>
    .dropzone {
        position: relative;
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 40px 24px;
        text-align: center;
        transition: all 0.25s ease;
        cursor: pointer;
        background: #f8fafc;
    }
    .dropzone:hover,
    .dropzone.dragover {
        border-color: #3b82f6;
        background: #eff6ff;
    }
    .dropzone.dragover {
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }
    .dropzone input[type="file"] {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    .preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 10px;
        margin-top: 16px;
    }
    .preview-item {
        position: relative;
        aspect-ratio: 1;
        border-radius: 10px;
        overflow: hidden;
        border: 2px solid #e2e8f0;
    }
    .preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .preview-item .remove-btn {
        position: absolute;
        top: 4px;
        right: 4px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: rgba(0,0,0,0.6);
        color: #fff;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        line-height: 1;
        transition: background 0.2s;
    }
    .preview-item .remove-btn:hover { background: #dc2626; }

    .gallery-card {
        position: relative;
        border-radius: 14px;
        overflow: hidden;
        background: #fff;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .gallery-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border-color: #cbd5e1;
        transform: translateY(-2px);
    }
    .gallery-card .img-wrap {
        aspect-ratio: 4/3;
        overflow: hidden;
        background: #f1f5f9;
        position: relative;
    }
    .gallery-card .img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .gallery-card:hover .img-wrap img {
        transform: scale(1.05);
    }
    .gallery-card .overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(0,0,0,0) 40%, rgba(0,0,0,0.4) 100%);
        opacity: 0;
        transition: opacity 0.3s;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        padding-bottom: 14px;
        gap: 8px;
    }
    .gallery-card:hover .overlay { opacity: 1; }
    .overlay-btn {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        backdrop-filter: blur(4px);
    }
    .overlay-btn-view {
        background: rgba(255,255,255,0.92);
        color: #475569;
    }
    .overlay-btn-view:hover { background: #fff; color: #1e293b; }
    .overlay-btn-star {
        background: rgba(255,255,255,0.92);
        color: #f59e0b;
    }
    .overlay-btn-star:hover { background: #fffbeb; }
    .overlay-btn-delete {
        background: rgba(255,255,255,0.92);
        color: #ef4444;
    }
    .overlay-btn-delete:hover { background: #fef2f2; }

    .main-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        border-radius: 8px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.02em;
        box-shadow: 0 2px 8px rgba(245,158,11,0.3);
        z-index: 2;
    }
    .card-footer {
        padding: 10px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px solid #f1f5f9;
    }
    .card-order {
        font-size: 12px;
        color: #94a3b8;
        font-weight: 500;
    }
    .card-size {
        font-size: 11px;
        color: #cbd5e1;
    }

    .stat-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
    }

    .empty-state {
        padding: 60px 24px;
        text-align: center;
    }
    .empty-state-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
        border-radius: 20px;
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #3b82f6;
    }
</style>
@endpush

@section('content')
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('admin.hotels.edit', $hotel) }}"
               class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-blue-600 mb-3 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour à l'hôtel
            </a>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Gérer les images</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $hotel->nom }}</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="stat-pill bg-blue-50 text-blue-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ $hotel->images->count() }} image{{ $hotel->images->count() > 1 ? 's' : '' }}
            </div>
            @if($hotel->images->where('is_main', true)->count())
                <div class="stat-pill bg-amber-50 text-amber-700">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    Principale définie
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Upload Section --}}
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
        </div>
        <div>
            <h2 class="text-sm font-bold text-slate-800">Ajouter des images</h2>
            <p class="text-xs text-slate-400 mt-0.5">PNG, JPG, GIF ou WEBP &middot; 5 Mo max par fichier</p>
        </div>
    </div>

    <form action="{{ route('admin.hotels.images.store', $hotel) }}" method="POST" enctype="multipart/form-data" id="uploadForm" class="p-6">
        @csrf

        <div class="dropzone" id="dropzone">
            <input type="file" name="images[]" id="fileInput" multiple accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" required>
            <div class="pointer-events-none">
                <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-500 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-slate-700 mb-1">Glissez-déposez vos images ici</p>
                <p class="text-xs text-slate-400">ou <span class="text-blue-600 font-medium">parcourez vos fichiers</span></p>
            </div>
        </div>

        <div class="preview-grid" id="previewGrid" style="display: none;"></div>

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

        <div class="mt-5 flex flex-col sm:flex-row items-start sm:items-center gap-4 pt-5 border-t border-slate-100">
            <label class="flex items-center gap-2.5 cursor-pointer select-none group">
                <input type="checkbox" name="set_as_main" value="1" {{ old('set_as_main') ? 'checked' : '' }}
                    class="w-[18px] h-[18px] rounded border-slate-300 text-amber-500 focus:ring-amber-500 focus:ring-offset-0">
                <span class="text-sm font-medium text-slate-600 group-hover:text-slate-800 transition-colors">Définir comme image principale</span>
            </label>
            <div class="sm:ml-auto flex items-center gap-3">
                <span id="fileCount" class="text-xs text-slate-400 hidden">0 fichier(s) sélectionné(s)</span>
                <button type="submit" id="uploadBtn" disabled
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-semibold rounded-xl shadow-sm hover:shadow-md transition-all text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Téléverser
                </button>
            </div>
        </div>
    </form>
</div>

{{-- Gallery Section --}}
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h2 class="text-sm font-bold text-slate-800">Galerie</h2>
        </div>
        @if($hotel->images->isNotEmpty())
            <span class="text-xs text-slate-400 font-medium">{{ $hotel->images->count() }} image{{ $hotel->images->count() > 1 ? 's' : '' }}</span>
        @endif
    </div>

    @if ($hotel->images->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M2.25 5.25h19.5M3.75 19.5h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-slate-700 mb-1">Aucune image pour le moment</h3>
            <p class="text-sm text-slate-400 max-w-sm mx-auto">Utilisez la zone ci-dessus pour ajouter des photos de votre hôtel. La première image définie comme principale sera affichée en couverture.</p>
        </div>
    @else
        <div class="p-6">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-5">
                @foreach ($hotel->images as $img)
                    <div class="gallery-card">
                        <div class="img-wrap">
                            <a href="{{ $img->url }}" target="_blank" rel="noopener">
                                <img src="{{ $img->url }}" alt="Image hôtel {{ $hotel->nom }}" loading="lazy">
                            </a>

                            @if ($img->is_main)
                                <div class="main-badge">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    Principale
                                </div>
                            @endif

                            <div class="overlay">
                                <a href="{{ $img->url }}" target="_blank" rel="noopener" class="overlay-btn overlay-btn-view" title="Voir en taille réelle">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                    </svg>
                                </a>

                                @if (!$img->is_main)
                                    <form action="{{ route('admin.hotels.images.set-main', [$hotel, $img]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="overlay-btn overlay-btn-star" title="Définir comme principale">
                                            <svg class="w-4.5 h-4.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif

                                <button type="button" class="overlay-btn overlay-btn-delete"
                                        title="Supprimer"
                                        data-delete-url="{{ route('admin.hotels.images.destroy', [$hotel, $img]) }}"
                                        data-delete-label="cette image">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="card-footer">
                            <span class="card-order">
                                #{{ $img->sort_order }}
                            </span>
                            @if($img->is_main)
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-600">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    Couverture
                                </span>
                            @else
                                <span class="card-size">Image</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
(function() {
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('fileInput');
    const previewGrid = document.getElementById('previewGrid');
    const uploadBtn = document.getElementById('uploadBtn');
    const fileCount = document.getElementById('fileCount');

    if (!dropzone || !fileInput) return;

    ['dragenter', 'dragover'].forEach(evt => {
        dropzone.addEventListener(evt, function(e) {
            e.preventDefault();
            dropzone.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(evt => {
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
        const files = this.files;
        previewGrid.innerHTML = '';

        if (files.length === 0) {
            previewGrid.style.display = 'none';
            uploadBtn.disabled = true;
            fileCount.classList.add('hidden');
            return;
        }

        previewGrid.style.display = 'grid';
        uploadBtn.disabled = false;
        fileCount.textContent = files.length + ' fichier' + (files.length > 1 ? 's' : '') + ' sélectionné' + (files.length > 1 ? 's' : '');
        fileCount.classList.remove('hidden');

        Array.from(files).forEach(function(file, idx) {
            if (!file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'preview-item';
                div.innerHTML = '<img src="' + e.target.result + '" alt="Aperçu">';
                previewGrid.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    });
})();
</script>
@endpush
