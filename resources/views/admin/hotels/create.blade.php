@extends('layouts.admin')
@section('page_title', 'Ajouter un hôtel')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap');

    .hotel-form-wrapper {
        font-family: 'DM Sans', sans-serif;
        min-height: 100vh;
        background: #f7f6f3;
        padding: 2.5rem 1.5rem 4rem;
    }

    /* ── Page Header ───────────────────────────── */
    .page-header {
        max-width: 760px;
        margin: 0 auto 2.5rem;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .page-header-left {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }
    .header-icon {
        width: 52px;
        height: 52px;
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 14px rgba(26,26,46,.25);
    }
    .header-icon svg { color: #c9a84c; }
    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: #1a1a2e;
        line-height: 1.2;
        margin: 0;
    }
    .page-subtitle {
        font-size: .8125rem;
        color: #8a8a9a;
        margin: .25rem 0 0;
        font-weight: 400;
    }
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        font-size: .8125rem;
        font-weight: 500;
        color: #6b6b80;
        text-decoration: none;
        padding: .5rem .875rem;
        border: 1px solid #e0ddd8;
        border-radius: 8px;
        background: #fff;
        transition: all .18s ease;
    }
    .back-link:hover {
        color: #1a1a2e;
        border-color: #c9a84c;
        background: #fffbf2;
    }

    /* ── Card ───────────────────────────────────── */
    .form-card {
        max-width: 760px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e8e4de;
        box-shadow: 0 2px 24px rgba(0,0,0,.06), 0 1px 4px rgba(0,0,0,.04);
        overflow: hidden;
    }

    /* ── Card Sections ──────────────────────────── */
    .card-section {
        padding: 2rem 2.25rem;
        border-bottom: 1px solid #f0ede8;
    }
    .card-section:last-of-type { border-bottom: none; }

    .section-label {
        display: flex;
        align-items: center;
        gap: .625rem;
        font-size: .6875rem;
        font-weight: 600;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: #c9a84c;
        margin-bottom: 1.5rem;
    }
    .section-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #f0ede8;
    }

    /* ── Two-column grid ────────────────────────── */
    .field-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
    .field-full  { grid-column: 1 / -1; }

    /* ── Form Fields ────────────────────────────── */
    .field-group { display: flex; flex-direction: column; gap: .375rem; }

    .field-label {
        font-size: .8125rem;
        font-weight: 600;
        color: #2d2d3d;
        display: flex;
        align-items: center;
        gap: .375rem;
    }
    .field-label .req { color: #e05050; font-size: .875rem; line-height: 1; }
    .field-hint { font-size: .75rem; color: #9a98a8; margin-top: .125rem; }

    .field-input,
    .field-select,
    .field-textarea {
        width: 100%;
        padding: .75rem 1rem;
        background: #fafaf8;
        border: 1.5px solid #e4e0d8;
        border-radius: 10px;
        font-family: 'DM Sans', sans-serif;
        font-size: .9rem;
        font-weight: 400;
        color: #1a1a2e;
        transition: border-color .18s, box-shadow .18s, background .18s;
        outline: none;
        box-sizing: border-box;
    }
    .field-input::placeholder,
    .field-textarea::placeholder { color: #b8b6c4; }

    .field-input:focus,
    .field-select:focus,
    .field-textarea:focus {
        border-color: #c9a84c;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(201,168,76,.12);
    }

    .field-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%238a8a9a' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 2.5rem;
    }

    .field-textarea { resize: vertical; min-height: 110px; line-height: 1.6; }

    .field-error {
        font-size: .75rem;
        color: #e05050;
        margin-top: .25rem;
        display: flex;
        align-items: center;
        gap: .3rem;
    }

    /* ── File Upload ────────────────────────────── */
    .upload-zone {
        border: 2px dashed #ddd9d0;
        border-radius: 12px;
        background: #fafaf8;
        padding: 2rem 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all .2s ease;
        position: relative;
    }
    .upload-zone:hover,
    .upload-zone.drag-over {
        border-color: #c9a84c;
        background: #fffbf0;
    }
    .upload-zone input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }
    .upload-icon {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #1a1a2e, #16213e);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto .875rem;
        box-shadow: 0 4px 10px rgba(26,26,46,.2);
    }
    .upload-title {
        font-size: .9rem;
        font-weight: 600;
        color: #2d2d3d;
        margin-bottom: .25rem;
    }
    .upload-sub {
        font-size: .78rem;
        color: #9a98a8;
    }
    .upload-formats {
        display: inline-flex;
        gap: .375rem;
        margin-top: .875rem;
        flex-wrap: wrap;
        justify-content: center;
    }
    .fmt-badge {
        font-size: .65rem;
        font-weight: 600;
        letter-spacing: .06em;
        padding: .2rem .5rem;
        border-radius: 5px;
        background: #f0ede8;
        color: #7a7880;
        text-transform: uppercase;
    }
    .upload-preview {
        display: none;
        margin-top: 1rem;
        align-items: center;
        gap: .75rem;
        padding: .75rem 1rem;
        background: #fff;
        border: 1.5px solid #e4e0d8;
        border-radius: 10px;
    }
    .upload-preview.show { display: flex; }
    .preview-thumb {
        width: 52px;
        height: 52px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #e4e0d8;
    }
    .preview-name {
        font-size: .8125rem;
        font-weight: 500;
        color: #2d2d3d;
        word-break: break-all;
    }
    .preview-size { font-size: .75rem; color: #9a98a8; }
    .preview-remove {
        margin-left: auto;
        background: none;
        border: none;
        cursor: pointer;
        color: #bbb;
        padding: .25rem;
        border-radius: 6px;
        transition: color .15s;
    }
    .preview-remove:hover { color: #e05050; }

    /* ── Actions ────────────────────────────────── */
    .card-actions {
        padding: 1.5rem 2.25rem;
        background: #fafaf8;
        border-top: 1px solid #f0ede8;
        display: flex;
        align-items: center;
        gap: .875rem;
        flex-wrap: wrap;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .8125rem 1.75rem;
        background: linear-gradient(135deg, #1a1a2e 0%, #0f3460 100%);
        color: #fff;
        font-family: 'DM Sans', sans-serif;
        font-size: .875rem;
        font-weight: 600;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: all .2s ease;
        box-shadow: 0 4px 14px rgba(26,26,46,.28);
        letter-spacing: .01em;
    }
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(26,26,46,.36);
    }
    .btn-primary:active { transform: translateY(0); }
    .btn-primary .accent-dot {
        width: 6px; height: 6px;
        background: #c9a84c;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .8125rem 1.375rem;
        background: #fff;
        color: #5a5870;
        font-family: 'DM Sans', sans-serif;
        font-size: .875rem;
        font-weight: 500;
        border: 1.5px solid #e0ddd8;
        border-radius: 10px;
        text-decoration: none;
        transition: all .18s ease;
    }
    .btn-secondary:hover {
        border-color: #c0bbb0;
        color: #2d2d3d;
        background: #fafaf8;
    }

    .form-tip {
        margin-left: auto;
        font-size: .75rem;
        color: #b0aebe;
        font-style: italic;
    }

    /* ── Responsive ─────────────────────────────── */
    @media (max-width: 600px) {
        .card-section { padding: 1.5rem 1.25rem; }
        .card-actions { padding: 1.25rem 1.25rem; }
        .field-grid { grid-template-columns: 1fr; }
        .page-header { flex-direction: column; align-items: flex-start; }
        .page-title { font-size: 1.4rem; }
    }
</style>
@endpush

@section('content')
<div class="hotel-form-wrapper">

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div class="page-header-left">
            <div class="header-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 21V7a2 2 0 012-2h14a2 2 0 012 2v14M9 21v-6h6v6M3 10h18"/>
                </svg>
            </div>
            <div>
                <h1 class="page-title">Nouvel hôtel</h1>
                <p class="page-subtitle">Remplissez les informations pour créer un établissement</p>
            </div>
        </div>
        <a href="{{ route('admin.hotels.index') }}" class="back-link">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Retour à la liste
        </a>
    </div>

    {{-- ── Form Card ── --}}
    <form action="{{ route('admin.hotels.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-card">

            {{-- Section 1 : Informations générales --}}
            <div class="card-section">
                <div class="section-label">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/>
                    </svg>
                    Informations générales
                </div>

                <div class="field-grid">
                    {{-- Nom --}}
                    <div class="field-group field-full">
                        <label for="nom" class="field-label">
                            Nom de l'hôtel <span class="req">*</span>
                        </label>
                        <input type="text" name="nom" id="nom" required
                               value="{{ old('nom') }}"
                               placeholder="ex. Grand Hôtel du Lac"
                               class="field-input @error('nom') border-red-400 @enderror">
                        @error('nom')
                            <p class="field-error">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Adresse --}}
                    <div class="field-group">
                        <label for="adresse" class="field-label">Adresse</label>
                        <input type="text" name="adresse" id="adresse"
                               value="{{ old('adresse') }}"
                               placeholder="12 rue de la Paix"
                               class="field-input">
                    </div>

                    {{-- Ville --}}
                    <div class="field-group">
                        <label for="ville" class="field-label">Ville</label>
                        <input type="text" name="ville" id="ville"
                               value="{{ old('ville') }}"
                               placeholder="Djibouti"
                               class="field-input">
                    </div>

                    {{-- Description --}}
                    <div class="field-group field-full">
                        <label for="description" class="field-label">Description</label>
                        <textarea name="description" id="description"
                                  placeholder="Décrivez l'hôtel, ses prestations, son ambiance…"
                                  class="field-textarea">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Section 2 : Administrateur --}}
            <div class="card-section">
                <div class="section-label">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87M12 12a4 4 0 100-8 4 4 0 000 8z"/>
                    </svg>
                    Responsable de l'hôtel
                </div>

                <div class="field-group">
                    <label for="admin_id" class="field-label">
                        Compte administrateur <span class="req">*</span>
                    </label>
                    <p class="field-hint">Sélectionnez le compte qui aura la gestion de cet établissement.</p>
                    <select name="admin_id" id="admin_id" required
                            class="field-select @error('admin_id') border-red-400 @enderror">
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}"
                                @selected((string) old('admin_id', $admins->first()->id) === (string) $admin->id)>
                                {{ $admin->name }} {{ $admin->prenom }} — {{ $admin->email }}
                            </option>
                        @endforeach
                    </select>
                    @error('admin_id')
                        <p class="field-error">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Section 3 : Image principale --}}
            <div class="card-section">
                <div class="section-label">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/>
                        <path d="M21 15l-5-5L5 21"/>
                    </svg>
                    Image principale
                </div>

                <div class="upload-zone" id="uploadZone">
                    <input type="file" name="main_image" id="main_image"
                           accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                           id="fileInput">
                    <div class="upload-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#c9a84c" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4-4 4 4m4-4l-4 4M12 12V4"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 16v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2"/>
                        </svg>
                    </div>
                    <p class="upload-title">Glissez une image ici ou cliquez pour parcourir</p>
                    <p class="upload-sub">Optionnel — vous pourrez en ajouter d'autres après la création</p>
                    <div class="upload-formats">
                        <span class="fmt-badge">JPG</span>
                        <span class="fmt-badge">PNG</span>
                        <span class="fmt-badge">WEBP</span>
                        <span class="fmt-badge">GIF</span>
                    </div>
                </div>

                <div class="upload-preview" id="uploadPreview">
                    <img class="preview-thumb" id="previewThumb" src="" alt="Aperçu">
                    <div>
                        <p class="preview-name" id="previewName">—</p>
                        <p class="preview-size" id="previewSize">—</p>
                    </div>
                    <button type="button" class="preview-remove" id="removeFile" title="Retirer l'image">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                @error('main_image')
                    <p class="field-error mt-2">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="card-actions">
                <button type="submit" class="btn-primary">
                    <span class="accent-dot"></span>
                    Créer l'hôtel
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </button>
                <a href="{{ route('admin.hotels.index') }}" class="btn-secondary">
                    Annuler
                </a>
                <span class="form-tip">* Champ obligatoire</span>
            </div>

        </div>
    </form>
</div>

@push('scripts')
<script>
(function () {
    const zone    = document.getElementById('uploadZone');
    const input   = document.getElementById('main_image');
    const preview = document.getElementById('uploadPreview');
    const thumb   = document.getElementById('previewThumb');
    const name    = document.getElementById('previewName');
    const size    = document.getElementById('previewSize');
    const remove  = document.getElementById('removeFile');

    function formatBytes(b) {
        if (b < 1024) return b + ' B';
        if (b < 1048576) return (b/1024).toFixed(1) + ' KB';
        return (b/1048576).toFixed(1) + ' MB';
    }

    function showPreview(file) {
        if (!file || !file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = e => {
            thumb.src = e.target.result;
            name.textContent = file.name;
            size.textContent = formatBytes(file.size);
            preview.classList.add('show');
        };
        reader.readAsDataURL(file);
    }

    input.addEventListener('change', () => {
        if (input.files[0]) showPreview(input.files[0]);
    });

    remove.addEventListener('click', () => {
        input.value = '';
        preview.classList.remove('show');
        thumb.src = '';
    });

    // Drag & drop
    ['dragenter','dragover'].forEach(ev =>
        zone.addEventListener(ev, e => { e.preventDefault(); zone.classList.add('drag-over'); }));
    ['dragleave','drop'].forEach(ev =>
        zone.addEventListener(ev, e => { e.preventDefault(); zone.classList.remove('drag-over'); }));
    zone.addEventListener('drop', e => {
        const file = e.dataTransfer.files[0];
        if (file) {
            // Update the input via DataTransfer
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            showPreview(file);
        }
    });
})();
</script>
@endpush
@endsection