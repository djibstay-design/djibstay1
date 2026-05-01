@extends('layouts.hotel_admin')
@section('page_title', 'Avis clients')
@section('title', 'Avis clients')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h1 class="page-title">⭐ Avis clients</h1>
        <p class="page-sub">{{ $hotel->nom }} · {{ $avis->total() }} avis</p>
    </div>
    @if($avg > 0)
    <div style="background:linear-gradient(135deg,#003580,#0071c2);color:#fff;padding:12px 20px;border-radius:12px;text-align:center;">
        <div style="font-size:28px;font-weight:900;color:#febb02;line-height:1;">{{ number_format($avg,1) }}</div>
        <div style="font-size:11px;color:rgba(255,255,255,0.8);text-transform:uppercase;letter-spacing:.5px;">Note moyenne</div>
    </div>
    @endif
</div>

@if($avis->isEmpty())
<div class="card-admin p-5 text-center">
    <div style="font-size:48px;margin-bottom:12px;">⭐</div>
    <h3 style="color:#003580;font-weight:700;">Aucun avis</h3>
    <p style="color:#64748b;">Les avis de vos clients apparaîtront ici.</p>
</div>
@else
@foreach($avis as $av)
<div class="card-admin p-4 mb-3">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:10px;margin-bottom:10px;">
        <div>
            <div style="font-weight:700;color:#1e293b;font-size:15px;">
                <i class="bi bi-person-circle me-1 text-primary"></i>
                {{ $av->nom_client }}
            </div>
            <div style="font-size:12px;color:#94a3b8;margin-top:2px;">
                {{ $av->email_client }} ·
                {{ $av->date_avis ? $av->date_avis->format('d/m/Y') : '' }}
            </div>
        </div>
        <div style="display:flex;gap:3px;">
            @for($i=1;$i<=5;$i++)
            <i class="bi bi-star-fill" style="color:{{ $i<=$av->note?'#febb02':'#e2e8f0' }};font-size:16px;"></i>
            @endfor
        </div>
    </div>

    @if($av->commentaire)
    <p style="font-size:14px;color:#475569;line-height:1.7;margin-bottom:12px;">
        {{ $av->commentaire }}
    </p>
    @endif

    @if($av->reponse_admin)
    <div style="background:#f0f7ff;border-left:3px solid #003580;border-radius:0 8px 8px 0;padding:12px 16px;margin-bottom:12px;">
        <div style="font-size:11px;font-weight:800;color:#003580;margin-bottom:4px;">🏨 Votre réponse</div>
        <p style="font-size:13px;color:#1e293b;margin:0;line-height:1.65;">{{ $av->reponse_admin }}</p>
        <div style="font-size:11px;color:#94a3b8;margin-top:4px;">{{ $av->reponse_admin_at?->format('d/m/Y') }}</div>
    </div>
    @endif

    @if(!$av->reponse_admin)
    <form method="POST" action="{{ route('hoteladmin.avis.repondre', $av) }}">
        @csrf
        <div style="display:flex;gap:10px;">
            <textarea name="reponse" rows="2" class="form-control"
                      placeholder="Répondez à cet avis..."
                      style="flex:1;border:2px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;resize:none;"></textarea>
            <button type="submit" class="btn-ha-primary" style="align-self:flex-end;padding:8px 16px;">
                <i class="bi bi-send"></i> Répondre
            </button>
        </div>
    </form>
    @else
    <button onclick="this.previousElementSibling.style.display='block';this.style.display='none';"
            style="background:none;border:none;color:#0071c2;font-size:13px;font-weight:600;cursor:pointer;padding:0;">
        ✏️ Modifier la réponse
    </button>
    <div style="display:none;">
        <form method="POST" action="{{ route('hoteladmin.avis.repondre', $av) }}">
            @csrf
            <div style="display:flex;gap:10px;margin-top:8px;">
                <textarea name="reponse" rows="2" class="form-control"
                          style="flex:1;border:2px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;resize:none;">{{ $av->reponse_admin }}</textarea>
                <button type="submit" class="btn-ha-primary" style="align-self:flex-end;padding:8px 16px;">
                    <i class="bi bi-check"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
    @endif
</div>
@endforeach

@if($avis->hasPages())
<div class="d-flex justify-content-center mt-4">{{ $avis->links() }}</div>
@endif
@endif
@endsection