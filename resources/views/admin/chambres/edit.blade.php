@extends('layouts.admin')
@section('page_title', 'Modifier chambre')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
    <div>
        <h1 style="font-size:22px;font-weight:900;color:#1e293b;margin:0;">✏️ Chambre N° {{ $chambre->numero }}</h1>
        <p style="font-size:13px;color:#64748b;margin:4px 0 0;">{{ $chambre->typeChambre->hotel->nom ?? '' }}</p>
    </div>
    <a href="{{ route('admin.chambres.index') }}"
       style="background:#f1f5f9;color:#475569;padding:9px 16px;border-radius:8px;text-decoration:none;font-weight:700;font-size:13px;display:inline-flex;align-items:center;gap:6px;">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 12px rgba(0,53,128,0.07);overflow:hidden;">
            <div style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:14px 22px;">
                <span style="font-size:15px;font-weight:800;color:#003580;"><i class="bi bi-pencil-square me-2"></i>Modifier</span>
            </div>
            <div style="padding:24px;">
                <form method="POST" action="{{ route('admin.chambres.update',$chambre) }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">N° de chambre *</label>
                            <input type="text" name="numero" value="{{ old('numero',$chambre->numero) }}"
                                   style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required>
                        </div>
                        <div class="col-12">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">Type de chambre *</label>
                            <select name="type_id" style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required>
                                @foreach($types as $type)
                                <option value="{{ $type->id }}" {{ old('type_id',$chambre->type_id)==$type->id?'selected':'' }}>
                                    {{ $type->hotel->nom ?? '' }} — {{ $type->nom_type }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label style="font-size:12px;font-weight:700;color:#003580;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px;display:block;">État *</label>
                            <select name="etat" style="border:2px solid #e2e8f0;border-radius:8px;padding:10px 13px;font-size:14px;width:100%;" required>
                                <option value="DISPONIBLE"  {{ old('etat',$chambre->etat)==='DISPONIBLE' ?'selected':'' }}>✅ Disponible</option>
                                <option value="OCCUPEE"     {{ old('etat',$chambre->etat)==='OCCUPEE'    ?'selected':'' }}>🔴 Occupée</option>
                                <option value="MAINTENANCE" {{ old('etat',$chambre->etat)==='MAINTENANCE'?'selected':'' }}>🔧 Maintenance</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit"
                                    style="background:linear-gradient(135deg,#003580,#0071c2);color:#fff;border:none;border-radius:8px;font-weight:700;font-size:14px;padding:12px 24px;cursor:pointer;display:inline-flex;align-items:center;gap:7px;">
                                <i class="bi bi-check-lg"></i> Enregistrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:18px 20px;">
            <div style="font-size:14px;font-weight:800;color:#003580;margin-bottom:12px;"><i class="bi bi-info-circle me-1"></i>Infos actuelles</div>
            @foreach([
                ['Hôtel', $chambre->typeChambre->hotel->nom ?? '—'],
                ['Type', $chambre->typeChambre->nom_type ?? '—'],
                ['Prix/nuit', number_format($chambre->typeChambre->prix_par_nuit ?? 0,0,',',' ').' DJF'],
                ['Capacité', ($chambre->typeChambre->capacite ?? '—').' pers.'],
                ['État actuel', $chambre->etat],
            ] as [$l,$v])
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f1f5f9;font-size:13px;">
                <span style="color:#64748b;">{{ $l }}</span>
                <span style="font-weight:700;color:#1e293b;">{{ $v }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection