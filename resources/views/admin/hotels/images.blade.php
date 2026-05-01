@extends('layouts.admin')
@section('page_title', 'Photos de l\'hôtel')

@push('styles')
<style>
    .dropzone {
        position:relative; border:2px dashed #cbd5e1; border-radius:14px;
        padding:40px 24px; text-align:center; transition:all .25s;
        cursor:pointer; background:#f8fafc;
    }
    .dropzone:hover, .dropzone.dragover {
        border-color:#0071c2; background:#f0f7ff;
    }
    .dropzone input[type="file"] {
        position:absolute; inset:0; width:100%; height:100%; opacity:0; cursor:pointer;
    }
    .preview-grid {
        display:grid; grid-template-columns:repeat(auto-fill,minmax(100px,1fr));
        gap:10px; margin-top:16px;
    }
    .preview-item {
        position:relative; aspect-ratio:1; border-radius:10px;
        overflow:hidden; border:2px solid #e2e8f0;
    }
    .preview-item img { width:100%; height:100%; object-fit:cover; }
    .gallery-card {
        border-radius:12px; overflow:hidden; background:#fff;
        border:2px solid #e2e8f0; transition:all .25s;
        box-shadow:0 2px 8px rgba(0,53,128,0.07);
    }
    .gallery-card:hover {
        box-shadow:0 8px 24px rgba(0,53,128,0.13);
        border-color:#0071c2; transform:translateY(-2px);
    }
    .gallery-card .img-wrap {
        aspect-ratio:4/3; overflow:hidden; background:#f1f5f9; position:relative;
    }
    .gallery-card .img-wrap img {
        width:100%; height:100%; object-fit:cover; transition:transform .4s;
    }
    .gallery-card:hover .img-wrap img { transform:scale(1.05); }
    .main-badge {
        position:absolute; top:8px; left:8px;
        background:linear-gradient(135deg,#febb02,#f5a623);
        color:#003580; font-size:10px; font-weight:800;
        padding:3px 10px; border-radius:8px;
        box-shadow:0 2px 8px rgba(245,158,11,0.3);
    }
    .overlay {
        position:absolute; inset:0;
        background:linear-gradient(180deg,rgba(0,0,0,0) 40%,rgba(0,0,0,0.45) 100%);
        opacity:0; transition:opacity .3s;
        display:flex; align-items:flex-end; justify-content:center;
        padding-bottom:12px; gap:6px;
    }
    .gallery-card:hover .overlay { opacity:1; }
    .overlay-btn {
        width:36px; height:36px; border-radius:9px; border:none;
        display:inline-flex; align-items:center; justify-content:center;
        cursor:pointer; transition:all .2s; font-size:15px;
    }
    .btn-star   { background:rgba(255,255,255,0.92); color:#f59e0b; }
    .btn-star:hover { background:#fffbeb; }
    .btn-delete { background:rgba(255,255,255,0.92); color:#ef4444; }
    .btn-delete:hover { background:#fef2f2; }
    .card-footer {
        padding:9px 12px; display:flex; align-items:center;
        justify-content:space-between; border-top:1px solid #f1f5f9;
    }
</style>
@endpush

@section('content')

{{-- Header --}}
<div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
    <div>
        <a href="{{ route('admin.hotels.edit', $hotel) }}"
           style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:#64748b;text-decoration:none;margin-bottom:8px;font-weight:600;">
            ← Retour à l'hôtel
        </a>
        <h1 style="font-size:24px;font-weight:900;color:#003580;margin:0;">🖼️ Photos de l'hôtel</h1>
        <p style="font-size:14px;color:#64748b;margin:4px 0 0;">{{ $hotel->nom }}</p>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <div style="background:#dbeafe;color:#1e40af;padding:7px 14px;border-radius:10px;font-size:13px;font-weight:700;display:flex;align-items:center;gap:6px;">
            🖼️ {{ $hotel->images->count() }} image(s)
        </div>
        @if($hotel->images->where('is_main',true)->count())
        <div style="background:#fef3c7;color:#92400e;padding:7px 14px;border-radius:10px;font-size:13px;font-weight:700;display:flex;align-items:center;gap:6px;">
            ⭐ Principale définie
        </div>
        @endif
    </div>
</div>

@if(session('success'))
<div style="background:#dcfce7;border:1px solid #bbf7d0;border-radius:10px;padding:12px 18px;margin-bottom:20px;font-size:13px;color:#14532d;font-weight:600;display:flex;align-items:center;gap:8px;">
    ✅ {{ session('success') }}
</div>
@endif

{{-- Upload --}}
<div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 10px rgba(0,53,128,0.07);overflow:hidden;margin-bottom:24px;">
    <div style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:14px 22px;display:flex;align-items:center;gap:10px;">
        <div style="width:36px;height:36px;background:#dbeafe;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:18px;">📤</div>
        <div>
            <div style="font-size:14px;font-weight:800;color:#003580;">Ajouter des images</div>
            <div style="font-size:12px;color:#94a3b8;">PNG, JPG, GIF ou WEBP · 5 Mo max par fichier</div>
        </div>
    </div>
    <div style="padding:22px;">
        <form action="{{ route('admin.hotels.images.store', $hotel) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf

            <div class="dropzone" id="dropzone">
                <input type="file" name="images[]" id="fileInput" multiple accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" required>
                <div style="pointer-events:none;">
                    <div style="font-size:40px;margin-bottom:10px;">☁️</div>
                    <div style="font-size:14px;font-weight:700;color:#475569;margin-bottom:4px;">Glissez-déposez vos images ici</div>
                    <div style="font-size:12px;color:#94a3b8;">ou <span style="color:#0071c2;font-weight:600;">parcourez vos fichiers</span></div>
                </div>
            </div>

            <div class="preview-grid" id="previewGrid" style="display:none;"></div>

            @error('images')
            <div style="background:#fee2e2;border-radius:8px;padding:10px 14px;margin-top:10px;font-size:13px;color:#dc2626;">
                ⚠️ {{ $message }}
            </div>
            @enderror

            <div style="margin-top:16px;padding-top:16px;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;color:#475569;font-weight:600;">
                    <input type="checkbox" name="set_as_main" value="1" {{ old('set_as_main')?'checked':'' }}
                           style="width:16px;height:16px;accent-color:#f59e0b;">
                    Définir comme image principale
                </label>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span id="fileCount" style="font-size:12px;color:#94a3b8;display:none;"></span>
                    <button type="submit" id="uploadBtn" disabled
                            style="background:#003580;color:#fff;border:none;border-radius:9px;padding:10px 22px;font-weight:700;font-size:14px;cursor:pointer;display:inline-flex;align-items:center;gap:8px;opacity:.5;transition:all .2s;">
                        📤 Téléverser
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Galerie --}}
<div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 10px rgba(0,53,128,0.07);overflow:hidden;">
    <div style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:14px 22px;display:flex;align-items:center;justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:36px;height:36px;background:#f1f5f9;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:18px;">🖼️</div>
            <span style="font-size:14px;font-weight:800;color:#003580;">Galerie</span>
        </div>
        @if($hotel->images->isNotEmpty())
        <span style="font-size:12px;color:#94a3b8;font-weight:600;">{{ $hotel->images->count() }} image(s)</span>
        @endif
    </div>

    @if($hotel->images->isEmpty())
    <div style="text-align:center;padding:60px 24px;">
        <div style="font-size:56px;margin-bottom:12px;">📷</div>
        <h3 style="font-size:16px;font-weight:700;color:#1e293b;margin-bottom:6px;">Aucune image pour le moment</h3>
        <p style="font-size:13px;color:#64748b;max-width:340px;margin:0 auto;line-height:1.6;">
            Utilisez la zone ci-dessus pour ajouter des photos. La première image définie comme principale sera affichée en couverture.
        </p>
    </div>
    @else
    <div style="padding:22px;">
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;">
            @foreach($hotel->images as $img)
            <div class="gallery-card">
                <div class="img-wrap">
                    <a href="{{ $img->url }}" target="_blank">
                        <img src="{{ $img->url }}" alt="Image {{ $hotel->nom }}" loading="lazy">
                    </a>
                    @if($img->is_main)
                    <div class="main-badge">⭐ Principale</div>
                    @endif
                    <div class="overlay">
                        @if(!$img->is_main)
                        <form action="{{ route('admin.hotels.images.set-main',[$hotel,$img]) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="overlay-btn btn-star" title="Définir comme principale">⭐</button>
                        </form>
                        @endif
                        <form action="{{ route('admin.hotels.images.destroy',[$hotel,$img]) }}" method="POST"
                              onsubmit="return confirm('Supprimer cette image ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="overlay-btn btn-delete" title="Supprimer">🗑️</button>
                        </form>
                    </div>
                </div>
                <div class="card-footer">
                    <span style="font-size:11px;color:#94a3b8;">Ordre #{{ $img->sort_order }}</span>
                    @if($img->is_main)
                    <span style="font-size:11px;font-weight:700;color:#f59e0b;">⭐ Couverture</span>
                    @else
                    <span style="font-size:11px;color:#cbd5e1;">Image</span>
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
    const dropzone  = document.getElementById('dropzone');
    const fileInput = document.getElementById('fileInput');
    const preview   = document.getElementById('previewGrid');
    const uploadBtn = document.getElementById('uploadBtn');
    const fileCount = document.getElementById('fileCount');

    if (!dropzone || !fileInput) return;

    ['dragenter','dragover'].forEach(e => {
        dropzone.addEventListener(e, ev => { ev.preventDefault(); dropzone.classList.add('dragover'); });
    });
    ['dragleave','drop'].forEach(e => {
        dropzone.addEventListener(e, ev => { ev.preventDefault(); dropzone.classList.remove('dragover'); });
    });
    dropzone.addEventListener('drop', e => {
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            fileInput.dispatchEvent(new Event('change'));
        }
    });

    fileInput.addEventListener('change', function() {
        const files = this.files;
        preview.innerHTML = '';
        if (!files.length) {
            preview.style.display = 'none';
            uploadBtn.disabled = true;
            uploadBtn.style.opacity = '.5';
            fileCount.style.display = 'none';
            return;
        }
        preview.style.display = 'grid';
        uploadBtn.disabled = false;
        uploadBtn.style.opacity = '1';
        fileCount.style.display = 'inline';
        fileCount.textContent = files.length + ' fichier(s) sélectionné(s)';

        Array.from(files).forEach(file => {
            if (!file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = e => {
                const div = document.createElement('div');
                div.className = 'preview-item';
                div.innerHTML = '<img src="' + e.target.result + '" alt="Aperçu">';
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    });
})();
</script>
@endpush