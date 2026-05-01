@extends('layouts.admin')
@section('page_title', 'Espace Partenaire')
@section('title', 'Espace Partenaire — '.(\App\Models\SiteSetting::get('app_name','DjibStay')))

@push('styles')
<style>
.partner-wrap { background:#f2f6fc; min-height:100vh; }
.stats-row { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px; }
@media(max-width:900px){ .stats-row { grid-template-columns:repeat(2,1fr); } }
.stat-card {
    background:#fff; border-radius:14px; border:1px solid #e2e8f0;
    padding:18px 20px; display:flex; align-items:center; gap:14px;
    box-shadow:0 2px 10px rgba(0,53,128,0.07);
    text-decoration:none; transition:all .2s;
}
.stat-card:hover { transform:translateY(-2px); border-color:#0071c2; }
.stat-icon { width:46px; height:46px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0; }
.stat-num { font-size:24px; font-weight:900; color:#1e293b; line-height:1; }
.stat-lbl { font-size:11px; color:#64748b; font-weight:600; margin-top:3px; text-transform:uppercase; letter-spacing:.4px; }
.filter-bar { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:20px; }
.filter-btn { padding:7px 16px; border-radius:20px; font-size:13px; font-weight:600; border:2px solid #e2e8f0; background:#fff; color:#64748b; text-decoration:none; transition:all .2s; }
.filter-btn:hover, .filter-btn.active { border-color:#003580; background:#003580; color:#fff; }
.partner-table { width:100%; border-collapse:collapse; font-size:13px; }
.partner-table th { background:#f8fafc; padding:11px 16px; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:.5px; border-bottom:2px solid #e2e8f0; text-align:left; }
.partner-table td { padding:13px 16px; border-bottom:1px solid #f1f5f9; color:#1e293b; vertical-align:middle; }
.partner-table tr:last-child td { border-bottom:none; }
.partner-table tr:hover td { background:#f8fafc; }
.statut-badge { padding:4px 12px; border-radius:20px; font-size:11px; font-weight:700; white-space:nowrap; display:inline-flex; align-items:center; gap:5px; }
.statut-en_attente    { background:#fef3c7; color:#92400e; }
.statut-en_discussion { background:#dbeafe; color:#1e40af; }
.statut-valide        { background:#dcfce7; color:#14532d; }
.statut-refuse        { background:#fee2e2; color:#991b1b; }
</style>
@endpush

@section('content')
@php $appName = \App\Models\SiteSetting::get('app_name','DjibStay'); @endphp

<div class="partner-wrap">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
        <div>
            <h1 style="font-size:24px;font-weight:900;color:#003580;margin:0;">🤝 Espace Partenaire</h1>
            <div style="font-size:13px;color:#64748b;margin-top:3px;">Gestion des demandes de partenariat hôtelier</div>
        </div>
        <a href="{{ route('admin.partenaires.create') }}" 
           style="background:linear-gradient(135deg,#003580,#0071c2);color:#fff;padding:10px 20px;border-radius:10px;text-decoration:none;font-weight:800;font-size:14px;display:inline-flex;align-items:center;gap:8px;box-shadow:0 4px 12px rgba(0,53,128,0.15);">
            <i class="bi bi-plus-circle"></i> Ajouter manuellement
        </a>
    </div>

    {{-- Stats --}}
    <div class="stats-row">
        <a href="{{ route('admin.partenaires.index') }}" class="stat-card">
            <div class="stat-icon" style="background:#f1f5f9;">📋</div>
            <div><div class="stat-num">{{ $counts['total'] }}</div><div class="stat-lbl">Total demandes</div></div>
        </a>
        <a href="{{ route('admin.partenaires.index',['statut'=>'en_attente']) }}" class="stat-card">
            <div class="stat-icon" style="background:#fef3c7;">⏳</div>
            <div><div class="stat-num" style="{{ $counts['en_attente']>0?'color:#f59e0b;':'' }}">{{ $counts['en_attente'] }}</div><div class="stat-lbl">En attente</div></div>
        </a>
        <a href="{{ route('admin.partenaires.index',['statut'=>'en_discussion']) }}" class="stat-card">
            <div class="stat-icon" style="background:#dbeafe;">💬</div>
            <div><div class="stat-num" style="color:#0071c2;">{{ $counts['en_discussion'] }}</div><div class="stat-lbl">En discussion</div></div>
        </a>
        <a href="{{ route('admin.partenaires.index',['statut'=>'valide']) }}" class="stat-card">
            <div class="stat-icon" style="background:#dcfce7;">✅</div>
            <div><div class="stat-num" style="color:#16a34a;">{{ $counts['valide'] }}</div><div class="stat-lbl">Validés</div></div>
        </a>
    </div>

    {{-- Filtres --}}
    <div class="filter-bar">
        <a href="{{ route('admin.partenaires.index') }}"
           class="filter-btn {{ !$statut ? 'active' : '' }}">Tous</a>
        <a href="{{ route('admin.partenaires.index',['statut'=>'en_attente']) }}"
           class="filter-btn {{ $statut==='en_attente' ? 'active' : '' }}">⏳ En attente</a>
        <a href="{{ route('admin.partenaires.index',['statut'=>'en_discussion']) }}"
           class="filter-btn {{ $statut==='en_discussion' ? 'active' : '' }}">💬 En discussion</a>
        <a href="{{ route('admin.partenaires.index',['statut'=>'valide']) }}"
           class="filter-btn {{ $statut==='valide' ? 'active' : '' }}">✅ Validés</a>
        <a href="{{ route('admin.partenaires.index',['statut'=>'refuse']) }}"
           class="filter-btn {{ $statut==='refuse' ? 'active' : '' }}">❌ Refusés</a>
    </div>

    {{-- Table --}}
    <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 10px rgba(0,53,128,0.07);overflow:hidden;">
        <div style="padding:14px 20px;border-bottom:1px solid #f1f5f9;background:#f8fafc;display:flex;align-items:center;justify-content:space-between;">
            <h3 style="font-size:14px;font-weight:800;color:#003580;margin:0;">
                📋 Demandes de partenariat
            </h3>
            <span style="font-size:12px;color:#64748b;">{{ $demandes->total() }} demande(s)</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="partner-table">
                <thead>
                    <tr>
                        <th>Contact</th>
                        <th>Hôtel</th>
                        <th>Ville</th>
                        <th>Chambres</th>
                        <th>Statut</th>
                        <th>Formulaire</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($demandes as $d)
                    <tr>
                        <td>
                            <div style="font-weight:700;color:#1e293b;">{{ $d->nom_contact }}</div>
                            <div style="font-size:11px;color:#64748b;">{{ $d->email_contact }}</div>
                            @if($d->telephone)
                            <div style="font-size:11px;color:#94a3b8;">{{ $d->telephone }}</div>
                            @endif
                        </td>
                        <td style="font-weight:600;">{{ $d->nom_hotel }}</td>
                        <td style="color:#64748b;">{{ $d->ville ?? '—' }}</td>
                        <td style="text-align:center;">
                            {{ $d->nombre_chambres ?? '—' }}
                        </td>
                        <td>
                            <span class="statut-badge statut-{{ $d->statut }}">
                                {{ $d->statutLabel() }}
                            </span>
                        </td>
                        <td style="text-align:center;">
                            @if($d->formulaire_rempli)
                                <span style="background:#dcfce7;color:#14532d;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">✅ Rempli</span>
                            @elseif($d->token_invitation)
                                <span style="background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">⏳ Envoyé</span>
                            @else
                                <span style="background:#f1f5f9;color:#64748b;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">—</span>
                            @endif
                        </td>
                        <td style="font-size:12px;color:#64748b;white-space:nowrap;">
                            {{ $d->created_at->format('d/m/Y') }}
                        </td>
                        <td>
                            <a href="{{ route('admin.partenaires.show',$d) }}"
                               style="background:#003580;color:#fff;padding:6px 14px;border-radius:7px;text-decoration:none;font-size:12px;font-weight:700;white-space:nowrap;">
                                Voir →
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:48px;color:#94a3b8;">
                            <div style="font-size:48px;margin-bottom:12px;">🤝</div>
                            <div style="font-size:15px;font-weight:600;">Aucune demande de partenariat</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($demandes->hasPages())
        <div style="padding:16px 20px;border-top:1px solid #f1f5f9;">
            {{ $demandes->links() }}
        </div>
        @endif
    </div>
</div>
@endsection