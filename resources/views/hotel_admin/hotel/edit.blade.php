@extends('layouts.hotel_admin')
@section('page_title', 'Mon hôtel')
@section('title', 'Modifier mon hôtel')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h1 class="page-title">🏨 Mon hôtel</h1>
        <p class="page-sub">Modifiez les informations de votre établissement</p>
    </div>
    <a href="{{ route('hotels.show', $hotel) }}" target="_blank" class="btn-ha-outline">
        <i class="bi bi-eye"></i> Voir sur le site
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-admin p-4">
            <form method="POST" action="{{ route('hoteladmin.hotel.update') }}" class="form-ha">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-12">
                        <label>Nom de l'hôtel *</label>
                        <input type="text" name="nom" class="form-control"
                               value="{{ old('nom', $hotel->nom) }}" required>
                    </div>
                    <div class="col-sm-6">
                        <label>Adresse</label>
                        <input type="text" name="adresse" class="form-control"
                               value="{{ old('adresse', $hotel->adresse) }}"
                               placeholder="Ex : Plateau du Serpent">
                    </div>
                    <div class="col-sm-6">
                        <label>Ville</label>
                        <input type="text" name="ville" class="form-control"
                               value="{{ old('ville', $hotel->ville) }}"
                               placeholder="Ex : Djibouti-Ville">
                    </div>
                    <div class="col-12">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="6"
                                  placeholder="Décrivez votre hôtel...">{{ old('description', $hotel->description) }}</textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn-ha-primary">
                            <i class="bi bi-check-lg"></i> Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Infos --}}
        <div class="card-admin p-4 mb-4">
            <h5 style="font-size:14px;font-weight:800;color:#003580;margin-bottom:16px;">
                <i class="bi bi-info-circle me-2"></i>Informations
            </h5>
            <div style="font-size:13px;color:#475569;line-height:1.8;">
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span>Chambres</span>
                    <strong>{{ $hotel->typesChambre->sum(fn($t) => $t->chambres->count()) }}</strong>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span>Types de chambre</span>
                    <strong>{{ $hotel->typesChambre->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span>Photos</span>
                    <strong>{{ $hotel->images->count() }}</strong>
                </div>
            </div>
            <div class="mt-3 d-flex flex-column gap-2">
                <a href="{{ route('hoteladmin.photos.index') }}" class="btn-ha-outline" style="justify-content:center;">
                    <i class="bi bi-images"></i> Gérer les photos
                </a>
                <a href="{{ route('hoteladmin.types-chambre.index') }}" class="btn-ha-outline" style="justify-content:center;">
                    <i class="bi bi-grid"></i> Types de chambre
                </a>
            </div>
        </div>

        {{-- Photo principale --}}
        @if($hotel->mainImage)
        <div class="card-admin overflow-hidden">
            <img src="{{ $hotel->mainImage->url }}"
                 alt="{{ $hotel->nom }}"
                 style="width:100%;height:180px;object-fit:cover;"
                 onerror="this.src='{{ asset('images/ayla.jpg') }}'">
            <div class="p-3">
                <div style="font-size:12px;color:#64748b;">📷 Photo principale actuelle</div>
                <a href="{{ route('hoteladmin.photos.index') }}" style="font-size:13px;color:#0071c2;font-weight:600;text-decoration:none;">
                    Changer les photos →
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection