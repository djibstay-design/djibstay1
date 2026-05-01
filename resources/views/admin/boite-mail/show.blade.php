@extends('layouts.admin')
@section('page_title', 'Message de '.$message->nom)
@section('title', 'Message — DjibStay')

@push('styles')
<style>
    .msg-wrap { max-width:860px; }
    .msg-card { background:#fff; border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 2px 10px rgba(0,53,128,0.07); overflow:hidden; margin-bottom:20px; }
    .msg-header { padding:20px 24px; border-bottom:1px solid #f1f5f9; background:#f8fafc; display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap; }
    .msg-avatar { width:48px; height:48px; border-radius:50%; background:linear-gradient(135deg,#003580,#0071c2); display:flex; align-items:center; justify-content:center; color:#fff; font-size:20px; font-weight:900; flex-shrink:0; }
    .msg-body { padding:24px; font-size:14px; color:#475569; line-height:1.8; white-space:pre-line; }
    .action-btn { display:inline-flex; align-items:center; gap:7px; padding:9px 18px; border-radius:9px; font-size:13px; font-weight:700; border:none; cursor:pointer; text-decoration:none; transition:all .2s; }
    .btn-blue  { background:#003580; color:#fff; }
    .btn-blue:hover { background:#0071c2; color:#fff; }
    .btn-gray  { background:#f1f5f9; color:#64748b; }
    .btn-gray:hover { background:#e2e8f0; color:#475569; }
    .btn-red   { background:#fee2e2; color:#dc2626; }
    .btn-red:hover { background:#fecaca; color:#b91c1c; }
    .btn-archive { background:#fef3c7; color:#92400e; }
    .btn-archive:hover { background:#fde68a; color:#78350f; }
    .reply-area { border:2px solid #e2e8f0; border-radius:10px; padding:13px; font-size:14px; width:100%; resize:vertical; font-family:inherit; min-height:140px; transition:border-color .2s; }
    .reply-area:focus { border-color:#0071c2; outline:none; box-shadow:0 0 0 3px rgba(0,113,194,0.1); }
</style>
@endpush

@section('content')
<div class="msg-wrap">

    {{-- Navigation --}}
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
        <a href="{{ route('admin.boite-mail.index') }}" class="action-btn btn-gray">
            ← Retour à la boîte mail
        </a>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">

            {{-- Marquer lu/non lu --}}
            <form method="POST" action="{{ route('admin.boite-mail.lu',$message) }}">
                @csrf @method('PATCH')
                <button type="submit" class="action-btn btn-gray">
                    {{ $message->lu ? '🔵 Marquer non lu' : '✅ Marquer lu' }}
                </button>
            </form>

            {{-- Archiver --}}
            @if(!$message->archive)
            <form method="POST" action="{{ route('admin.boite-mail.archiver',$message) }}">
                @csrf @method('PATCH')
                <button type="submit" class="action-btn btn-archive">
                    📁 Archiver
                </button>
            </form>
            @endif

            {{-- Supprimer --}}
            <form method="POST" action="{{ route('admin.boite-mail.destroy',$message) }}"
                  onsubmit="return confirm('Supprimer ce message définitivement ?')">
                @csrf @method('DELETE')
                <button type="submit" class="action-btn btn-red">
                    🗑️ Supprimer
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div style="background:#dcfce7;border:1px solid #bbf7d0;border-radius:10px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#14532d;font-weight:600;">
        {{ session('success') }}
    </div>
    @endif

    {{-- Message --}}
    <div class="msg-card">
        <div class="msg-header">
            <div style="display:flex;align-items:center;gap:14px;">
                <div class="msg-avatar">{{ strtoupper(substr($message->nom,0,1)) }}</div>
                <div>
                    <div style="font-size:16px;font-weight:800;color:#1e293b;">{{ $message->nom }}</div>
                    <div style="font-size:13px;color:#64748b;">
                        <a href="mailto:{{ $message->email }}" style="color:#0071c2;">{{ $message->email }}</a>
                        @if($message->telephone)
                            · {{ $message->telephone }}
                        @endif
                    </div>
                    <div style="font-size:12px;color:#94a3b8;margin-top:3px;">
                        {{ $message->created_at->format('d/m/Y à H:i') }}
                        · <span style="background:{{ $message->source==='gmail'?'#fee2e2':'#dbeafe' }};color:{{ $message->source==='gmail'?'#991b1b':'#1e40af' }};padding:2px 8px;border-radius:8px;font-size:11px;font-weight:700;">
                            {{ $message->source==='gmail'?'📧 Gmail':'📝 Formulaire' }}
                          </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sujet --}}
        @if($message->sujet)
        <div style="padding:14px 24px;border-bottom:1px solid #f1f5f9;background:#fafafa;">
            <span style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.4px;">Sujet : </span>
            <span style="font-size:14px;font-weight:700;color:#1e293b;">{{ $message->sujet }}</span>
        </div>
        @endif

        {{-- Corps du message --}}
        <div class="msg-body">{{ $message->message }}</div>
    </div>

    {{-- Réponse existante --}}
    @if($message->reponse)
    <div class="msg-card">
        <div style="padding:14px 20px;border-bottom:1px solid #f1f5f9;background:#f0fdf4;display:flex;align-items:center;gap:10px;">
            <span style="font-size:14px;">✅</span>
            <div>
                <div style="font-size:13px;font-weight:800;color:#14532d;">Réponse envoyée</div>
                <div style="font-size:11px;color:#64748b;">
                    le {{ $message->repondu_le?->format('d/m/Y à H:i') }}
                </div>
            </div>
        </div>
        <div class="msg-body" style="background:#f0fdf4;">{{ $message->reponse }}</div>
    </div>
    @endif

    {{-- Formulaire réponse --}}
    <div class="msg-card">
        <div style="padding:14px 20px;border-bottom:1px solid #f1f5f9;background:#f8fafc;">
            <h3 style="font-size:14px;font-weight:800;color:#003580;margin:0;">
                ↩️ {{ $message->reponse ? 'Envoyer une nouvelle réponse' : 'Répondre' }}
            </h3>
        </div>
        <div style="padding:20px 24px;">
            <form method="POST" action="{{ route('admin.boite-mail.repondre',$message) }}">
                @csrf
                <div style="margin-bottom:8px;font-size:12px;color:#64748b;">
                    À : <strong>{{ $message->nom }}</strong> &lt;{{ $message->email }}&gt;
                </div>
                @error('reponse')
                <div style="background:#fee2e2;border-radius:8px;padding:10px 14px;margin-bottom:12px;font-size:13px;color:#dc2626;">
                    {{ $message }}
                </div>
                @enderror
                <textarea name="reponse" class="reply-area"
                    placeholder="Écrivez votre réponse ici...">{{ old('reponse') }}</textarea>
                <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:14px;">
                    <button type="submit" class="action-btn btn-blue">
                        <i class="bi bi-send-fill"></i> Envoyer la réponse
                    </button>
                </div>
            </form>
            
            <hr style="border:none;border-top:1px solid #f1f5f9;margin:20px 0;">
            
            <div style="background:#fff8f1;border:1px solid #fed7aa;border-radius:10px;padding:16px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                    <div>
                        <div style="font-size:14px;font-weight:800;color:#9a3412;">Devenir Partenaire</div>
                        <div style="font-size:12px;color:#c2410c;">Gérer l'adhésion de cet utilisateur.</div>
                    </div>
                </div>
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    {{-- Conversion Directe --}}
                    <a href="{{ route('admin.partenaires.create', [
                        'nom'   => $message->nom,
                        'email' => $message->email,
                        'tel'   => $message->telephone
                    ]) }}" 
                    class="action-btn" style="background:#003580;color:#fff;flex:1;justify-content:center;">
                        <i class="bi bi-magic"></i> Création Directe
                    </a>

                    {{-- Invitation Classique --}}
                    <form method="POST" action="{{ route('admin.boite-mail.inviter-partenaire', $message) }}" style="flex:1;">
                        @csrf
                        <button type="submit" class="action-btn" style="background:#f97316;color:#fff;width:100%;justify-content:center;">
                            <i class="bi bi-envelope-fill"></i> Envoyer Invitation
                        </button>
                    </form>
                </div>
                <p style="font-size:10px;color:#9a3412;margin-top:8px;text-align:center;font-style:italic;">
                    La "Création Directe" ouvre le formulaire pré-rempli pour inscrire le partenaire immédiatement.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection