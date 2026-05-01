@extends('layouts.admin')
@section('page_title', 'Modifier une photo')
@push('styles')
<style>
    .current-preview {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
    }
    .current-preview img {
        width: 100%;
        aspect-ratio: 4/3;
        object-fit: cover;
        display: block;
    }
    .current-preview .preview-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(0deg, rgba(0,0,0,0.4), transparent 50%);
        display: flex;
        align-items: flex-end;
        padding: 16px;
    }
    .current-preview .preview-overlay a {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 10px;
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(4px);
        color: #475569;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }
    .current-preview .preview-overlay a:hover {
        background: #fff;
        color: #1e293b;
    }
    .upload-replace {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.2s;
    }
    .upload-replace:hover {
        border-color: #3b82f6;
        background: #eff6ff;
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
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Modifier l'image</h1>
        <p class="text-sm text-slate-500 mt-1">Hôtel : <span class="font-semibold text-slate-700">{{ $image->hotel->nom }}</span></p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        {{-- Preview --}}
        <div class="md:col-span-2">
            <div class="current-preview">
                <img src="{{ $image->url }}" alt="Image actuelle">
                <div class="preview-overlay">
                    <a href="{{ $image->url }}" target="_blank" rel="noopener">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                        </svg>
                        Voir
                    </a>
                </div>
            </div>

            @if($image->is_main)
                <div class="mt-3 flex items-center gap-2 px-3 py-2 bg-amber-50 border border-amber-200 rounded-xl">
                    <svg class="w-4 h-4 text-amber-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span class="text-xs font-semibold text-amber-700">Image principale actuelle</span>
                </div>
            @endif
        </div>

        {{-- Form --}}
        <div class="md:col-span-3">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <form action="{{ route('admin.images.update', $image) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="p-6 space-y-6">
                        {{-- Replace image --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Remplacer l'image</label>
                            <label class="upload-replace">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-slate-700">Choisir un nouveau fichier</p>
                                    <p class="text-xs text-slate-400">PNG, JPG, GIF, WEBP &middot; 5 Mo max</p>
                                </div>
                                <input type="file" name="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" class="hidden">
                            </label>
                            @error('image')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Is main --}}
                        <div>
                            <label for="is_main" class="flex items-center gap-3 cursor-pointer p-3.5 rounded-xl border border-slate-100 hover:bg-slate-50/80 transition-colors">
                                <input type="checkbox" name="is_main" id="is_main" value="1"
                                       {{ old('is_main', $image->is_main) ? 'checked' : '' }}
                                       class="w-[18px] h-[18px] rounded border-slate-300 text-amber-500 focus:ring-amber-500 focus:ring-offset-0">
                                <div>
                                    <span class="text-sm font-semibold text-slate-700 block">Définir comme image principale</span>
                                    <span class="text-xs text-slate-400">Sera affichée en couverture sur la fiche hôtel</span>
                                </div>
                            </label>
                        </div>

                        {{-- Sort order --}}
                        <div>
                            <label for="sort_order" class="block text-sm font-semibold text-slate-700 mb-2">Ordre d'affichage</label>
                            <input type="number" name="sort_order" id="sort_order" min="0"
                                   value="{{ old('sort_order', $image->sort_order) }}"
                                   class="w-full max-w-[200px] px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            @error('sort_order')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30 flex flex-wrap items-center gap-3">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-sm hover:shadow-md transition-all text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Enregistrer
                        </button>
                        <a href="{{ route('admin.images.index') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 transition-colors text-sm">
                            Annuler
                        </a>
                        <button type="button"
                                data-delete-url="{{ route('admin.images.destroy', $image) }}"
                                data-delete-label="cette image"
                                class="ml-auto inline-flex items-center gap-2 px-4 py-2.5 border border-red-200 text-red-600 font-medium rounded-xl hover:bg-red-50 transition-colors text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Supprimer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
