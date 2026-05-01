@extends('layouts.admin')
@section('page_title', 'Galerie photos')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
    <div>
        <h1 style="font-size:22px;font-weight:900;color:#1e293b;margin:0;">🖼️ Galerie photos</h1>
        <p style="font-size:13px;color:#64748b;margin:4px 0 0;">
            {{ $hotels->count() }} hôtel(s) — {{ $images->total() }} photo(s)
        </p>
    </div>
    <a href="{{ route('admin.hotels.index') }}"
       style="background:#f1f5f9;color:#475569;padding:9px 16px;border-radius:8px;text-decoration:none;font-weight:700;font-size:13px;display:inline-flex;align-items:center;gap:6px;">
        <i class="bi bi-arrow-left"></i> Retour aux hôtels
    </a>
</div>

{{-- Upload --}}
<div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 12px rgba(0,53,128,0.07);overflow:hidden;margin-bottom:24px;">
    <div style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:14px 22px;display:flex;align-items:center;gap:8px;">
        <i class="bi bi-cloud-upload text-primary fs-5"></i>
        <span style="font-size:15px;font-weight:800;color:#003580;">Ajouter des photos</span>
    </div>
    <div style="padding:22px;">
        <form method="POST" action="{{ route('admin.images.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Sélection hôtel --}}
            <div style="margin-bottom:16px;">
                <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;display:block;margin-bottom:6px;">
                    Hôtel *
                </label>
                <select name="hotel_id" required
                        style="width:100%;border:2px solid #e2e8f0;border-radius:9px;padding:10px 14px;font-size:14px;color:#1e293b;">
                    <option value="">-- Choisir un hôtel --</option>
                    @foreach($hotels as $h)
                    <option value="{{ $h->id }}" {{ old('hotel_id')==$h->id?'selected':'' }}>
                        {{ $h->nom }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div style="border:2px dashed #cbd5e1;border-radius:10px;padding:28px;text-align:center;background:#f8fafc;position:relative;cursor:pointer;transition:all .2s;"
                 onmouseover="this.style.borderColor='#0071c2';this.style.background='#f0f7ff'"
                 onmouseout="this.style.borderColor='#cbd5e1';this.style.background='#f8fafc'">
                <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/webp"
                       style="position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;">
                <div style="font-size:36px;color:#94a3b8;margin-bottom:8px;"><i class="bi bi-images"></i></div>
                <div style="font-size:14px;font-weight:700;color:#475569;">Cliquez ou glissez vos photos ici</div>
                <div style="font-size:12px;color:#94a3b8;margin-top:4px;">JPG, PNG, WEBP — Max 5 Mo par photo</div>
            </div>
            <button type="submit"
                    style="margin-top:14px;background:#003580;color:#fff;border:none;border-radius:8px;padding:10px 22px;font-weight:700;font-size:14px;cursor:pointer;display:inline-flex;align-items:center;gap:7px;">
                <i class="bi bi-upload"></i> Uploader les photos
            </button>
        </form>
    </div>
</div>

{{-- Galerie --}}
@if($images->count() > 0)
<div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 12px rgba(0,53,128,0.07);overflow:hidden;">
    <div style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:14px 22px;display:flex;align-items:center;justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:8px;">
            <i class="bi bi-images text-primary fs-5"></i>
            <span style="font-size:15px;font-weight:800;color:#003580;">
                Photos actuelles ({{ $images->total() }})
            </span>
        </div>
    </div>
    <div style="padding:22px;">
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:14px;">
            @foreach($images as $image)
            <div style="border-radius:10px;overflow:hidden;border:2px solid {{ $image->is_main ? '#003580' : '#e2e8f0' }};background:#f8fafc;box-shadow:0 2px 8px rgba(0,53,128,0.07);transition:all .2s;"
                 onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 20px rgba(0,53,128,0.12)'"
                 onmouseout="this.style.transform='';this.style.boxShadow='0 2px 8px rgba(0,53,128,0.07)'">

                {{-- Badge hôtel --}}
                <div style="background:#003580;color:#fff;font-size:10px;font-weight:700;padding:3px 10px;text-align:center;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    🏨 {{ $image->hotel->nom ?? '—' }}
                </div>

                <div style="position:relative;">
                    <img src="{{ asset('storage/'.$image->path) }}" alt=""
                         style="width:100%;height:140px;object-fit:cover;display:block;"
                         onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                    @if($image->is_main)
                    <span style="position:absolute;top:6px;left:6px;background:#febb02;color:#003580;font-size:10px;font-weight:800;padding:2px 8px;border-radius:10px;">
                        ⭐ Principale
                    </span>
                    @endif
                </div>

                <div style="padding:10px 12px;display:flex;gap:6px;">
                    @if(!$image->is_main)
                    <form method="POST"
                          action="{{ route('admin.hotels.images.set-main',[$image->hotel_id,$image]) }}"
                          style="flex:1;">
                        @csrf @method('PATCH')
                        <button type="submit"
                                style="width:100%;background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:6px;padding:5px 8px;font-size:11px;font-weight:700;cursor:pointer;">
                            <i class="bi bi-star me-1"></i>Principale
                        </button>
                    </form>
                    @endif
                    <form method="POST"
                          action="{{ route('admin.hotels.images.destroy',[$image->hotel_id,$image]) }}"
                          onsubmit="return confirm('Supprimer cette photo ?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                style="background:#fee2e2;color:#991b1b;border:none;border-radius:6px;padding:5px 10px;font-size:11px;font-weight:700;cursor:pointer;">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($images->hasPages())
        <div style="margin-top:20px;">
            {{ $images->links() }}
        </div>
        @endif
    </div>
</div>
@else
<div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;padding:60px;text-align:center;">
    <div style="font-size:48px;margin-bottom:12px;">📷</div>
    <h3 style="color:#003580;font-weight:700;">Aucune photo</h3>
    <p style="color:#64748b;">Ajoutez des photos pour rendre les hôtels plus attractifs.</p>
</div>
@endif

@endsection