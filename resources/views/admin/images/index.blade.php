@extends('layouts.admin')
@section('title', 'Gérer les images')

@push('styles')
<style>
    .img-card {
        position: relative;
        border-radius: 14px;
        overflow: hidden;
        background: #fff;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .img-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border-color: #cbd5e1;
        transform: translateY(-2px);
    }
    .img-card .img-wrap {
        aspect-ratio: 4/3;
        overflow: hidden;
        background: #f1f5f9;
        position: relative;
    }
    .img-card .img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .img-card:hover .img-wrap img { transform: scale(1.05); }

    .img-card .overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(0,0,0,0) 30%, rgba(0,0,0,0.5) 100%);
        opacity: 0;
        transition: opacity 0.3s;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        padding-bottom: 14px;
        gap: 8px;
    }
    .img-card:hover .overlay { opacity: 1; }
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
        text-decoration: none;
    }
    .overlay-btn-view { background: rgba(255,255,255,0.92); color: #475569; }
    .overlay-btn-view:hover { background: #fff; color: #1e293b; }
    .overlay-btn-edit { background: rgba(255,255,255,0.92); color: #3b82f6; }
    .overlay-btn-edit:hover { background: #eff6ff; }
    .overlay-btn-delete { background: rgba(255,255,255,0.92); color: #ef4444; }
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
        padding: 12px 14px;
        border-top: 1px solid #f1f5f9;
    }
    .empty-state {
        padding: 64px 24px;
        text-align: center;
    }
    .empty-icon {
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
    .stat-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Gérer les images</h1>
            <p class="mt-1 text-sm text-slate-500">Ajoutez, modifiez ou supprimez les photos de vos hôtels.</p>
        </div>
        <div class="flex items-center gap-3">
            @if(!$images->isEmpty())
                <div class="stat-pill bg-blue-50 text-blue-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $images->total() }} image{{ $images->total() > 1 ? 's' : '' }}
                </div>
            @endif
            <a href="{{ route('admin.images.create') }}"
               class="inline-flex items-center gap-2 px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow-md transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Ajouter des images
            </a>
        </div>
    </div>
</div>

@if ($images->isEmpty())
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="empty-state">
            <div class="empty-icon">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M2.25 5.25h19.5M3.75 19.5h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z"/>
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-slate-700 mb-2">Aucune image pour le moment</h2>
            <p class="text-sm text-slate-400 max-w-md mx-auto mb-6">Téléversez des photos pour mettre en valeur vos hôtels. Définissez une image principale pour chaque hôtel.</p>
            <a href="{{ route('admin.images.create') }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Ajouter des images
            </a>
        </div>
    </div>
@else
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-5">
                @foreach ($images as $img)
                    <div class="img-card">
                        <div class="img-wrap">
                            <a href="{{ $img->url }}" target="_blank" rel="noopener">
                                <img src="{{ $img->url }}" alt="{{ $img->hotel->nom ?? '' }}" loading="lazy">
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
                                <a href="{{ $img->url }}" target="_blank" rel="noopener"
                                   class="overlay-btn overlay-btn-view" title="Voir en taille réelle">
                                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.images.edit', $img) }}"
                                   class="overlay-btn overlay-btn-edit" title="Modifier">
                                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <button type="button" class="overlay-btn overlay-btn-delete" title="Supprimer"
                                        data-delete-url="{{ route('admin.images.destroy', $img) }}"
                                        data-delete-label="cette image">
                                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="card-footer">
                            <p class="text-sm font-semibold text-slate-800 truncate" title="{{ $img->hotel->nom ?? '' }}">{{ $img->hotel->nom ?? '' }}</p>
                            <div class="flex items-center justify-between mt-1.5">
                                <span class="text-xs text-slate-400 font-medium">#{{ $img->sort_order }}</span>
                                @if($img->is_main)
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-600">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        Couverture
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @if ($images->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
                {{ $images->links() }}
            </div>
        @endif
    </div>
@endif
@endsection
