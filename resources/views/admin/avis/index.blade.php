@extends('layouts.admin')
@section('page_title', 'Avis clients')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
    <div>
        <h1 style="font-size:22px;font-weight:900;color:#1e293b;margin:0;">⭐ Avis clients</h1>
        <p style="font-size:13px;color:#64748b;margin:4px 0 0;">{{ $avis->total() }} avis au total</p>
    </div>
</div>

{{-- Stats --}}
@php
    $totalAvis = \App\Models\Avis::count();
    $avgNote   = round(\App\Models\Avis::avg('note') ?? 0, 1);
    $avec5     = \App\Models\Avis::where('note',5)->count();
    $sansReponse = \App\Models\Avis::whereNull('reponse_admin')->count();
@endphp
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;">
    <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:16px;text-align:center;box-shadow:0 2px 8px rgba(0,53,128,0.07);">
        <div style="font-size:24px;font-weight:900;color:#003580;">{{ $totalAvis }}</div>
        <div style="font-size:12px;color:#64748b;font-weight:600;margin-top:3px;">Total avis</div>
    </div>
    <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:16px;text-align:center;box-shadow:0 2px 8px rgba(0,53,128,0.07);">
        <div style="font-size:24px;font-weight:900;color:#f59e0b;">{{ $avgNote }}<span style="font-size:14px;color:#94a3b8;">/5</span></div>
        <div style="font-size:12px;color:#64748b;font-weight:600;margin-top:3px;">⭐ Note moyenne</div>
    </div>
    <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:16px;text-align:center;box-shadow:0 2px 8px rgba(0,53,128,0.07);">
        <div style="font-size:24px;font-weight:900;color:#16a34a;">{{ $avec5 }}</div>
        <div style="font-size:12px;color:#64748b;font-weight:600;margin-top:3px;">⭐⭐⭐⭐⭐ Notes 5/5</div>
    </div>
    <div style="background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:16px;text-align:center;box-shadow:0 2px 8px rgba(0,53,128,0.07);">
        <div style="font-size:24px;font-weight:900;color:#f59e0b;">{{ $sansReponse }}</div>
        <div style="font-size:12px;color:#64748b;font-weight:600;margin-top:3px;">💬 Sans réponse</div>
    </div>
</div>

{{-- Filtres --}}
<form method="GET" action="{{ route('admin.avis.index') }}" style="margin-bottom:20px;">
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <select name="hotel_id" style="border:2px solid #e2e8f0;border-radius:8px;padding:9px 14px;font-size:13px;font-weight:600;color:#003580;">
            <option value="">Tous les hôtels</option>
            @foreach(\App\Models\Hotel::orderBy('nom')->get() as $hotel)
            <option value="{{ $hotel->id }}" {{ request('hotel_id')==$hotel->id?'selected':'' }}>{{ $hotel->nom }}</option>
            @endforeach
        </select>
        <select name="note" style="border:2px solid #e2e8f0;border-radius:8px;padding:9px 14px;font-size:13px;font-weight:600;color:#003580;">
            <option value="">Toutes les notes</option>
            @for($i=5;$i>=1;$i--)
            <option value="{{ $i }}" {{ request('note')==$i?'selected':'' }}>{{ str_repeat('⭐',$i) }} {{ $i }}/5</option>
            @endfor
        </select>
        <select name="reponse" style="border:2px solid #e2e8f0;border-radius:8px;padding:9px 14px;font-size:13px;font-weight:600;color:#003580;">
            <option value="">Tous</option>
            <option value="0" {{ request('reponse')==='0'?'selected':'' }}>Sans réponse</option>
            <option value="1" {{ request('reponse')==='1'?'selected':'' }}>Avec réponse</option>
        </select>
        <button type="submit" style="background:#003580;color:#fff;border:none;border-radius:8px;padding:9px 18px;font-weight:700;font-size:13px;cursor:pointer;">
            <i class="bi bi-funnel"></i> Filtrer
        </button>
        @if(request()->hasAny(['hotel_id','note','reponse']))
        <a href="{{ route('admin.avis.index') }}" style="background:#f1f5f9;color:#64748b;border-radius:8px;padding:9px 14px;text-decoration:none;font-size:13px;font-weight:600;">
            <i class="bi bi-x"></i> Effacer
        </a>
        @endif
    </div>
</form>

@if($avis->isEmpty())
<div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;padding:60px;text-align:center;">
    <div style="font-size:48px;margin-bottom:12px;">⭐</div>
    <h3 style="color:#003580;font-weight:700;">Aucun avis</h3>
    <p style="color:#64748b;">Aucun avis ne correspond à vos filtres.</p>
</div>
@else
<div style="display:flex;flex-direction:column;gap:16px;">
    @foreach($avis as $av)
    <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 10px rgba(0,53,128,0.07);overflow:hidden;">
        <div style="padding:16px 22px;display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;border-bottom:1px solid #f1f5f9;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:44px;height:44px;background:linear-gradient(135deg,#003580,#0071c2);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;font-weight:800;flex-shrink:0;">
                    {{ strtoupper(substr($av->nom_client,0,1)) }}
                </div>
                <div>
                    <div style="font-weight:700;color:#1e293b;font-size:15px;">{{ $av->nom_client }}</div>
                    <div style="font-size:12px;color:#94a3b8;margin-top:1px;">
                        {{ $av->email_client }}
                        @if($av->hotel) · <strong style="color:#0071c2;">{{ $av->hotel->nom }}</strong> @endif
                        · {{ $av->date_avis?->format('d/m/Y') }}
                    </div>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="display:flex;gap:3px;">
                    @for($i=1;$i<=5;$i++)
                    <i class="bi bi-star-fill" style="font-size:16px;color:{{ $i<=$av->note?'#febb02':'#e2e8f0' }};"></i>
                    @endfor
                </div>
                <span style="font-size:13px;font-weight:800;color:#003580;">{{ $av->note }}/5</span>
                @if($av->reponse_admin)
                <span style="background:#dcfce7;color:#14532d;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">✅ Répondu</span>
                @else
                <span style="background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">⏳ En attente</span>
                @endif
            </div>
        </div>

        <div style="padding:16px 22px;">
            @if($av->commentaire)
            <p style="font-size:14px;color:#475569;line-height:1.7;margin:0 0 12px;">{{ $av->commentaire }}</p>
            @endif

            @if($av->reponse_admin)
            <div style="background:#f0f7ff;border-left:3px solid #003580;border-radius:0 8px 8px 0;padding:12px 16px;margin-bottom:12px;">
                <div style="font-size:11px;font-weight:800;color:#003580;margin-bottom:4px;">🏨 Réponse de l'administration</div>
                <p style="font-size:13px;color:#1e293b;margin:0;line-height:1.65;">{{ $av->reponse_admin }}</p>
                <div style="font-size:11px;color:#94a3b8;margin-top:4px;">{{ $av->reponse_admin_at?->format('d/m/Y à H:i') }}</div>
            </div>
            @endif

            {{-- Formulaire réponse --}}
            <div x-data="{ open: {{ $av->reponse_admin ? 'false' : 'true' }} }">
                @if(!$av->reponse_admin)
                <form method="POST" action="{{ route('admin.avis.repondre',$av) }}" style="display:flex;gap:10px;align-items:flex-end;">
                    @csrf
                    <textarea name="reponse" rows="2" placeholder="Répondre à cet avis..."
                              style="flex:1;border:2px solid #e2e8f0;border-radius:8px;padding:9px 12px;font-size:13px;resize:none;" required></textarea>
                    <button type="submit"
                            style="background:#003580;color:#fff;border:none;border-radius:8px;padding:10px 16px;font-weight:700;font-size:13px;cursor:pointer;white-space:nowrap;display:inline-flex;align-items:center;gap:5px;">
                        <i class="bi bi-send"></i> Répondre
                    </button>
                </form>
                @else
                <details>
                    <summary style="font-size:13px;color:#0071c2;font-weight:600;cursor:pointer;list-style:none;">✏️ Modifier la réponse</summary>
                    <form method="POST" action="{{ route('admin.avis.repondre',$av) }}" style="display:flex;gap:10px;align-items:flex-end;margin-top:10px;">
                        @csrf
                        <textarea name="reponse" rows="2"
                                  style="flex:1;border:2px solid #e2e8f0;border-radius:8px;padding:9px 12px;font-size:13px;resize:none;">{{ $av->reponse_admin }}</textarea>
                        <button type="submit"
                                style="background:#003580;color:#fff;border:none;border-radius:8px;padding:10px 16px;font-weight:700;font-size:13px;cursor:pointer;white-space:nowrap;display:inline-flex;align-items:center;gap:5px;">
                            <i class="bi bi-check"></i> Mettre à jour
                        </button>
                    </form>
                </details>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($avis->hasPages())
<div style="display:flex;justify-content:center;margin-top:24px;">{{ $avis->links() }}</div>
@endif
@endif
@endsection