@extends('layouts.admin')

@section('title', 'Nouvelle chambre')

@push('styles')
<style>
    .room-form-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .room-form-header {
        background: linear-gradient(135deg, #003580 0%, #0057b8 100%);
        padding: 28px 32px;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .room-form-header-icon {
        width: 48px;
        height: 48px;
        background: rgba(255,255,255,0.15);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .room-form-header h2 {
        color: #fff;
        font-size: 20px;
        font-weight: 700;
        margin: 0;
    }
    .room-form-header p {
        color: rgba(255,255,255,0.7);
        font-size: 13px;
        margin: 4px 0 0;
    }
    .room-form-body {
        padding: 32px;
    }
    .room-field {
        margin-bottom: 24px;
    }
    .room-field:last-child { margin-bottom: 0; }
    .room-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 8px;
    }
    .room-label .required { color: #ef4444; margin-left: 2px; }
    .room-label .optional { color: #94a3b8; font-weight: 400; font-size: 12px; }
    .room-input,
    .room-select {
        width: 100%;
        padding: 12px 16px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        color: #1e293b;
        background: #fff;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
    }
    .room-input:focus,
    .room-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
    }
    .room-input::placeholder { color: #94a3b8; }
    .room-select { appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        padding-right: 40px;
    }
    .room-field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .room-divider { border: none; border-top: 1px solid #f1f5f9; margin: 28px 0; }
    .room-upload-zone {
        border: 2px dashed #e2e8f0;
        border-radius: 14px;
        padding: 24px;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
    }
    .room-upload-zone:hover {
        border-color: #93c5fd;
        background: #eff6ff;
    }
    .room-upload-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 12px;
        background: #eff6ff;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .room-actions {
        display: flex;
        gap: 12px;
        padding: 24px 32px;
        border-top: 1px solid #f1f5f9;
        background: #fafbfc;
    }
    .room-btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 28px;
        background: linear-gradient(135deg, #003580 0%, #0057b8 100%);
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.2s;
        box-shadow: 0 2px 8px rgba(0,53,128,0.25);
    }
    .room-btn-submit:hover { opacity: 0.9; transform: translateY(-1px); }
    .room-btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: #fff;
        color: #64748b;
        font-size: 14px;
        font-weight: 600;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.2s, border-color 0.2s;
    }
    .room-btn-cancel:hover { background: #f8fafc; border-color: #cbd5e1; color: #334155; }
    .room-error { font-size: 12px; color: #ef4444; margin-top: 6px; }
    @media (max-width: 640px) {
        .room-field-row { grid-template-columns: 1fr; }
        .room-form-body { padding: 24px 20px; }
        .room-actions { padding: 20px; }
    }
</style>
@endpush

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.chambres.index') }}"
       class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-[#003580] transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Retour aux chambres
    </a>
</div>

<div class="max-w-2xl">
    <form action="{{ route('admin.chambres.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="room-form-card">
            <div class="room-form-header">
                <div class="room-form-header-icon">
                    <svg width="24" height="24" fill="none" stroke="#fff" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v11a2 2 0 002 2h14a2 2 0 002-2V7"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12H3M3 12V9a2 2 0 012-2h2a2 2 0 012 2v3m12 0V9a2 2 0 00-2-2h-2a2 2 0 00-2 2v3"/><path stroke-linecap="round" d="M1 20h22"/></svg>
                </div>
                <div>
                    <h2>Nouvelle chambre</h2>
                    <p>Remplissez les informations pour créer une chambre</p>
                </div>
            </div>

            <div class="room-form-body">
                <div class="room-field">
                    <label class="room-label">Type de chambre <span class="required">*</span></label>
                    <select name="type_id" id="type_id" required class="room-select">
                        <option value="">— Sélectionner un type —</option>
                        @foreach ($typesChambre as $t)
                            <option value="{{ $t->id }}" {{ old('type_id') == $t->id ? 'selected' : '' }}>
                                {{ $t->nom_type }} — {{ $t->hotel->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('type_id')<p class="room-error">{{ $message }}</p>@enderror
                </div>

                <div class="room-field-row">
                    <div class="room-field">
                        <label class="room-label">Numéro de chambre <span class="required">*</span></label>
                        <input type="text" name="numero" id="numero" required value="{{ old('numero') }}" placeholder="Ex : 101" class="room-input">
                        @error('numero')<p class="room-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="room-field">
                        <label class="room-label">État <span class="required">*</span></label>
                        <select name="etat" id="etat" class="room-select">
                            <option value="DISPONIBLE" {{ old('etat', 'DISPONIBLE') == 'DISPONIBLE' ? 'selected' : '' }}>Disponible</option>
                            <option value="OCCUPEE" {{ old('etat') == 'OCCUPEE' ? 'selected' : '' }}>Occupée</option>
                            <option value="MAINTENANCE" {{ old('etat') == 'MAINTENANCE' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                        @error('etat')<p class="room-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <hr class="room-divider">

                <div class="room-field">
                    <label class="room-label">Images <span class="optional">(optionnel)</span></label>
                    <label class="room-upload-zone" id="upload-zone">
                        <div class="room-upload-icon">
                            <svg width="24" height="24" fill="none" stroke="#3b82f6" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <p style="font-size:13px;font-weight:600;color:#334155;margin:0 0 4px;">Cliquez pour sélectionner des images</p>
                        <p style="font-size:12px;color:#94a3b8;margin:0;">JPG, PNG ou WEBP &middot; 5 Mo max par fichier</p>
                        <input type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple class="sr-only" id="images-input">
                    </label>
                    <p class="text-xs text-slate-500 mt-2" id="images-count"></p>
                    @error('images')<p class="room-error">{{ $message }}</p>@enderror
                    @error('images.*')<p class="room-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="room-actions">
                <button type="submit" class="room-btn-submit">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Créer la chambre
                </button>
                <a href="{{ route('admin.chambres.index') }}" class="room-btn-cancel">Annuler</a>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('images-input').addEventListener('change', function() {
    var n = this.files.length;
    var el = document.getElementById('images-count');
    el.textContent = n === 0 ? '' : (n === 1 ? '1 fichier sélectionné' : n + ' fichiers sélectionnés');
    document.getElementById('upload-zone').style.borderColor = n > 0 ? '#3b82f6' : '';
    document.getElementById('upload-zone').style.background = n > 0 ? '#eff6ff' : '';
});
</script>
@endpush
@endsection
