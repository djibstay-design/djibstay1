@extends('layouts.hotel_admin')
@section('page_title', 'Photos')
@section('title', 'Photos de mon hôtel')

@push('styles')
<style>
    .photo-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:16px; }
    .photo-card { border-radius:12px; overflow:hidden; border:1px solid #e2e8f0; background:#fff; box-shadow:0 2px 8px rgba(0,53,128,0.07); transition:all .2s; }
    .photo-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(0,53,128,0.13); }
    .photo-card img { width:100%; height:160px; object-fit:cover; display:block; }
    .photo-card-body { padding:10px 12px; }
    .upload-zone { border:2px dashed #cbd5e1; border-radius:12px; padding:32px; text-align:center; cursor:pointer; transition:all .2s; background:#f8fafc; position:relative; }
    .upload-zone:hover { border-color:#0071c2; background:#f0f7ff; }
    .upload-zone input { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h1 class="page-title">🖼️ Photos de {{ $hotel->nom }}</h1>
        <p class="page-sub">{{ $hotel->images->count() }} photo(s) · Gérez la galerie de votre hôtel</p>
    </div>
</div>

{{-- Upload --}}
<div class="card-admin p-4 mb-4">
    <h5 style="font-size:14px;font-weight:800;color:#003580;margin-bottom:16px;">
        <i class="bi bi-cloud-upload me-2"></i>Ajouter des photos
    </h5>
    <form method="POST" action="{{ route('hoteladmin.photos.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="upload-zone mb-3">
            <input type="file" name="photos[]" multiple accept="image/jpeg,image/png,image/webp">
            <div style="font-size:32px;color:#94a3b8;margin-bottom:8px;">📷</div>
            <div style="font-size:14px;font-weight:700;color:#475569;">Cliquez ou glissez vos photos ici</div>
            <div style="font-size:12px;color:#94a3b8;margin-top:4px;">JPG, PNG, WEBP — Max 5 Mo par photo</div>
        </div>
        <button type="submit" class="btn-ha-primary">
            <i class="bi bi-upload"></i> Uploader les photos
        </button>
    </form>
</div>

{{-- Galerie --}}
@if($hotel->images->count() > 0)
<div class="card-admin p-4">
    <h5 style="font-size:14px;font-weight:800;color:#003580;margin-bottom:16px;">
        <i class="bi bi-images me-2"></i>Galerie actuelle
    </h5>
    <div class="photo-grid">
        @foreach($hotel->images as $image)
        <div class="photo-card">
            <div style="position:relative;">
                <img src="{{ $image->url }}" alt="Photo hôtel"
                     onerror="this.src='{{ asset('images/ayla.jpg') }}'">
                @if($image->is_main)
                <span style="position:absolute;top:8px;left:8px;background:#febb02;color:#003580;font-size:10px;font-weight:800;padding:2px 8px;border-radius:10px;">
                    ⭐ Principale
                </span>
                @endif
            </div>
            <div class="photo-card-body">
                <div class="d-flex gap-2">
                    @if(!$image->is_main)
                    <form method="POST" action="{{ route('hoteladmin.photos.setMain', $image) }}" style="flex:1;">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-ha-outline w-100" style="font-size:11px;padding:5px 8px;justify-content:center;">
                            <i class="bi bi-star"></i> Principale
                        </button>
                    </form>
                    @endif
                    <form method="POST" action="{{ route('hoteladmin.photos.destroy', $image) }}"
                          onsubmit="return confirm('Supprimer cette photo ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-ha-danger" style="font-size:11px;padding:5px 8px;">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@else
<div class="card-admin p-5 text-center">
    <div style="font-size:48px;margin-bottom:12px;">📷</div>
    <h3 style="color:#003580;font-weight:700;">Aucune photo</h3>
    <p style="color:#64748b;">Ajoutez des photos pour attirer plus de clients.</p>
</div>
@endif
@endsection