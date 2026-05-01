@extends('layouts.app')
@section('title', 'Suivi réservation — '.(\App\Models\SiteSetting::get('app_name','DjibStay')))

@push('styles')
<style>
.status-hero { background:linear-gradient(135deg,#003580,#0071c2); padding:50px 0; color:#fff; text-align:center; }
.status-hero h1 { font-size:clamp(22px,4vw,36px); font-weight:900; }
.search-box { background:#fff; border-radius:14px; padding:8px; display:flex; gap:8px; max-width:580px; margin:20px auto 0; box-shadow:0 8px 32px rgba(0,0,0,0.15); }
.search-box input { flex:1; border:none; outline:none; font-size:15px; padding:8px 12px; color:#1e293b; border-radius:8px; }
.search-box button { background:linear-gradient(135deg,#003580,#0071c2); color:#fff; border:none; border-radius:8px; padding:10px 22px; font-weight:800; font-size:14px; cursor:pointer; white-space:nowrap; transition:all .2s; }
.search-box button:hover { transform:scale(1.03); }
.status-card { background:#fff; border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 4px 24px rgba(0,53,128,0.1); overflow:hidden; max-width:800px; margin:0 auto; }
.timeline { position:relative; padding-left:32px; }
.timeline::before { content:''; position:absolute; left:11px; top:0; bottom:0; width:2px; background:#e2e8f0; }
.timeline-step { position:relative; padding-bottom:22px; }
.timeline-step:last-child { padding-bottom:0; }
.timeline-dot { position:absolute; left:-32px; width:24px; height:24px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:800; border:3px solid #fff; box-shadow:0 0 0 2px #e2e8f0; }
.timeline-dot.done    { background:#22c55e; color:#fff; box-shadow:0 0 0 2px #22c55e; }
.timeline-dot.active  { background:#003580; color:#fff; box-shadow:0 0 0 2px #003580; animation:pulseDot 1.5s infinite; }
.timeline-dot.pending { background:#f1f5f9; color:#94a3b8; }
@keyframes pulseDot { 0%,100%{box-shadow:0 0 0 2px #003580;} 50%{box-shadow:0 0 0 5px rgba(0,53,128,0.2);} }
.timeline-content { font-size:14px; }
.timeline-content .title { font-weight:700; color:#1e293b; }
.timeline-content .desc  { font-size:12px; color:#64748b; margin-top:2px; }
.receipt-row { display:flex; justify-content:space-between; align-items:center; padding:9px 0; border-bottom:1px solid #f1f5f9; font-size:14px; }
.receipt-row:last-child { border-bottom:none; }
</style>
@endpush

@section('content')
@php
    $appName  = \App\Models\SiteSetting::get('app_name','DjibStay');
    $logoPath = \App\Models\SiteSetting::get('app_logo','');
    $telephone= \App\Models\SiteSetting::get('contact_telephone','+253 77 00 00 00');
    $whatsapp = \App\Models\SiteSetting::get('contact_whatsapp','+253 77 00 00 00');
@endphp

{{-- HERO --}}
<section class="status-hero">
    <div class="container" style="max-width:700px;">
        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;margin-bottom:16px;">
            @if($logoPath)
                <img src="{{ asset('storage/'.$logoPath) }}" alt="{{ $appName }}"
                     style="height:52px;object-fit:contain;display:block;margin:0 auto;">
            @else
                <div style="font-size:44px;">🔍</div>
            @endif
        </div>
        <h1>Suivre ma réservation</h1>
        <p style="color:rgba(255,255,255,0.82);font-size:15px;margin-top:6px;">
            Entrez votre code de réservation pour consulter le statut
        </p>
        <form method="GET" action="{{ route('reservations.status') }}" class="search-box">
            <input type="text" name="code" placeholder="Ex : RES-20260422-XXXX"
                   value="{{ request('code') }}" autocomplete="off"
                   style="font-family:monospace;font-weight:700;font-size:14px;">
            <button type="submit"><i class="bi bi-search me-1"></i>Rechercher</button>
        </form>
    </div>
</section>

<div class="container py-5" style="max-width:860px;">

    @if(request('code') && !isset($reservation))
    {{-- Pas trouvé --}}
    <div class="status-card p-5" style="text-align:center;">
        <div style="font-size:56px;margin-bottom:14px;">😕</div>
        <h2 style="color:#003580;font-weight:800;margin-bottom:8px;">Réservation introuvable</h2>
        <p style="color:#64748b;margin-bottom:16px;">
            Aucune réservation trouvée pour le code
            <strong style="color:#003580;font-family:monospace;">{{ request('code') }}</strong>.
        </p>
        <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('reservations.status') }}"
               style="background:#003580;color:#fff;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:700;font-size:14px;">
                Réessayer
            </a>
            <a href="{{ route('pages.contact') }}"
               style="background:#f1f5f9;color:#003580;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:700;font-size:14px;">
                Contacter le support
            </a>
        </div>
    </div>

    @elseif(isset($reservation))
    @php
        $type           = $reservation->chambre->typeChambre;
        $hotel          = $type->hotel;
        $nuits          = $reservation->date_debut->diffInDays($reservation->date_fin);
        $acomptePercent = \App\Models\SiteSetting::get('resa_acompte_percent','30');
        $depositAmount  = round($reservation->montant_total * $acomptePercent / 100);
        $balanceAmount  = $reservation->montant_total - $depositAmount;
        $hasPaid        = $reservation->hasPaidDeposit();

        $steps = [
            [
                'done',
                'check',
                'Réservation créée',
                'Votre demande a bien été enregistrée · '.$reservation->date_reservation->format('d/m/Y')
            ],
            [
                $hasPaid ? 'done' : 'active',
                $hasPaid ? 'check' : 'clock',
                'Acompte payé',
                $hasPaid
                    ? 'Acompte de '.number_format($depositAmount,0,',',' ').' ' . \App\Models\SiteSetting::get('app_devise','DJF') . ' reçu'
                    : 'En attente de paiement'
            ],
            [
                $reservation->statut === 'CONFIRMEE' ? 'done' : ($reservation->statut === 'EN_ATTENTE' ? 'active' : 'pending'),
                $reservation->statut === 'CONFIRMEE' ? 'check' : ($reservation->statut === 'EN_ATTENTE' ? 'hourglass-split' : 'x-circle'),
                'Confirmation',
                $reservation->statut === 'CONFIRMEE'
                    ? 'Réservation confirmée par l\'hôtel'
                    : ($reservation->statut === 'EN_ATTENTE' ? 'En attente de confirmation' : 'Annulée')
            ],
            [
                $reservation->statut === 'CONFIRMEE' && now() < $reservation->date_debut
                    ? 'active'
                    : ($reservation->statut === 'CONFIRMEE' && now() >= $reservation->date_debut ? 'done' : 'pending'),
                'door-open',
                'Arrivée à l\'hôtel',
                'Prévue le '.$reservation->date_debut->format('d/m/Y')
            ],
            [
                $reservation->statut === 'CONFIRMEE' && now() > $reservation->date_fin ? 'done' : 'pending',
                'check-all',
                'Séjour terminé',
                'Départ le '.$reservation->date_fin->format('d/m/Y')
            ],
        ];
    @endphp

    {{-- Code réservation --}}
    <div style="background:linear-gradient(135deg,#003580,#0071c2);border-radius:14px;padding:20px 24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;color:#fff;margin-bottom:24px;">
        <div>
            <div style="font-size:12px;color:rgba(255,255,255,0.7);text-transform:uppercase;letter-spacing:.5px;">Code réservation</div>
            <div style="font-size:24px;font-weight:900;color:#febb02;font-family:monospace;letter-spacing:2px;">
                {{ $reservation->code_reservation }}
            </div>
        </div>
        <div>
            @if($reservation->statut === 'CONFIRMEE')
                <span style="background:#22c55e;color:#fff;padding:8px 18px;border-radius:20px;font-size:14px;font-weight:800;">✅ Confirmée</span>
            @elseif($reservation->statut === 'EN_ATTENTE')
                <span style="background:#febb02;color:#003580;padding:8px 18px;border-radius:20px;font-size:14px;font-weight:800;">⏳ En attente</span>
            @else
                <span style="background:#f87171;color:#fff;padding:8px 18px;border-radius:20px;font-size:14px;font-weight:800;">❌ Annulée</span>
            @endif
        </div>
    </div>

    <div class="row g-4">

        {{-- Timeline --}}
        <div class="col-lg-6">
            <div class="status-card mb-4">
                <div style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:14px 22px;">
                    <div style="font-size:15px;font-weight:800;color:#003580;">
                        <i class="bi bi-list-check me-2"></i>Suivi de statut
                    </div>
                </div>
                <div style="padding:22px 22px 22px 54px;">
                    <div class="timeline">
                        @foreach($steps as [$state,$icon,$title,$desc])
                        <div class="timeline-step">
                            <div class="timeline-dot {{ $state }}">
                                <i class="bi bi-{{ $icon }}"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="title" style="color:{{ $state==='pending'?'#94a3b8':'#1e293b' }}">
                                    {{ $title }}
                                </div>
                                <div class="desc">{{ $desc }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Détails --}}
        <div class="col-lg-6">
            <div class="status-card mb-4">
                <div style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:14px 22px;">
                    <div style="font-size:15px;font-weight:800;color:#003580;">
                        <i class="bi bi-receipt me-2"></i>Détails
                    </div>
                </div>
                <div style="padding:18px 22px;">
                    @foreach([
                        ['Hôtel',    $hotel->nom],
                        ['Chambre',  $type->nom_type.' · N° '.$reservation->chambre->numero],
                        ['Client',   $reservation->prenom_client.' '.$reservation->nom_client],
                        ['Email',    $reservation->email_client],
                        ['Arrivée',  $reservation->date_debut->format('d/m/Y')],
                        ['Départ',   $reservation->date_fin->format('d/m/Y')],
                        ['Durée',    $nuits.' nuit(s)'],
                        ['Total',    number_format($reservation->montant_total,0,',',' ').' ' . \App\Models\SiteSetting::get('app_devise','DJF')],
                        ['Acompte',  number_format($depositAmount,0,',',' ').' ' . \App\Models\SiteSetting::get('app_devise','DJF') . ' (' . ($hasPaid ? '✅ Payé' : '⏳ En attente') . ')'],
                        ['Solde',    number_format($balanceAmount,0,',',' ').' ' . \App\Models\SiteSetting::get('app_devise','DJF') . ' (à l\'hôtel)'],
                    ] as [$l,$v])
                    <div class="receipt-row">
                        <span style="color:#64748b;">{{ $l }}</span>
                        <span style="font-weight:700;color:#1e293b;text-align:right;max-width:240px;">{{ $v }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div style="display:flex;gap:10px;flex-wrap:wrap;justify-content:center;margin-bottom:16px;">
        @if($reservation->statut === 'EN_ATTENTE' && !$hasPaid)
        <a href="{{ route('reservations.payment.show', $reservation) }}"
           style="background:#16a34a;color:#fff;padding:12px 22px;border-radius:8px;text-decoration:none;font-weight:700;font-size:14px;display:inline-flex;align-items:center;gap:7px;">
            <i class="bi bi-credit-card"></i> Payer l'acompte
        </a>
        @endif
        <a href="{{ route('hotels.show',$hotel) }}"
           style="background:#003580;color:#fff;padding:12px 22px;border-radius:8px;text-decoration:none;font-weight:700;font-size:14px;display:inline-flex;align-items:center;gap:7px;">
            <i class="bi bi-building"></i> Voir l'hôtel
        </a>
        <a href="{{ route('pages.contact') }}"
           style="background:#f1f5f9;color:#003580;padding:12px 22px;border-radius:8px;text-decoration:none;font-weight:700;font-size:14px;display:inline-flex;align-items:center;gap:7px;">
            <i class="bi bi-headset"></i> Contacter le support
        </a>
        <button onclick="window.print()"
                style="background:#fff;color:#003580;border:2px solid #003580;padding:12px 22px;border-radius:8px;font-weight:700;font-size:14px;cursor:pointer;display:inline-flex;align-items:center;gap:7px;">
            <i class="bi bi-printer"></i> Imprimer
        </button>
    </div>

    {{-- Contact urgence --}}
    <div style="background:#f0f7ff;border-radius:12px;border:1px solid #bfdbfe;padding:16px 20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;max-width:800px;margin:0 auto 24px;">
        <div>
            <div style="font-size:13px;font-weight:700;color:#003580;margin-bottom:3px;">
                <i class="bi bi-headset me-1"></i>Besoin d'aide ?
            </div>
            <div style="font-size:12px;color:#64748b;">Notre équipe est disponible pour vous assister</div>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="tel:{{ preg_replace('/\s/','',$telephone) }}"
               style="background:#003580;color:#fff;padding:8px 16px;border-radius:7px;text-decoration:none;font-size:13px;font-weight:700;">
                <i class="bi bi-telephone me-1"></i>{{ $telephone }}
            </a>
            <a href="https://wa.me/{{ preg_replace('/\D/','',$whatsapp) }}" target="_blank"
               style="background:#25d366;color:#fff;padding:8px 16px;border-radius:7px;text-decoration:none;font-size:13px;font-weight:700;">
                <i class="bi bi-whatsapp me-1"></i>WhatsApp
            </a>
        </div>
    </div>
{{-- Annulation --}}
    @if($reservation->statut !== 'ANNULEE')
    <div style="max-width:500px;margin:0 auto 32px;background:#fff;border:1px solid #fee2e2;border-radius:14px;padding:20px 24px;text-align:center;">
        <div style="font-size:15px;font-weight:800;color:#dc2626;margin-bottom:6px;">
            <i class="bi bi-x-circle me-1"></i> Annuler cette réservation
        </div>
        <p style="font-size:13px;color:#64748b;margin-bottom:16px;line-height:1.6;">
            Vous souhaitez annuler ? Notez que l'acompte peut ne pas être remboursable selon nos conditions.
        </p>

        <button onclick="document.getElementById('cancelConfirmStatus').style.display='block';this.style.display='none';"
                style="background:#fee2e2;color:#dc2626;border:2px solid #fecaca;padding:10px 24px;border-radius:8px;font-weight:700;font-size:13px;cursor:pointer;display:inline-flex;align-items:center;gap:7px;">
            <i class="bi bi-x-circle"></i> Demander l'annulation
        </button>

        <div id="cancelConfirmStatus" style="display:none;margin-top:16px;background:#fef2f2;border-radius:10px;padding:16px;">
            <div style="font-size:14px;font-weight:700;color:#dc2626;margin-bottom:10px;">
                ⚠️ Confirmer l'annulation ?
            </div>
            <p style="font-size:12px;color:#64748b;margin-bottom:14px;">
                Cette action est irréversible. Votre réservation
                <strong style="color:#003580;">{{ $reservation->code_reservation }}</strong>
                sera annulée.
            </p>
            <form method="POST" action="{{ route('reservations.annuler', $reservation) }}">
                @csrf
                @method('PATCH')
                <div style="margin-bottom:12px;">
                    <label style="font-size:12px;font-weight:700;color:#64748b;display:block;margin-bottom:6px;text-align:left;">
                        Motif d'annulation (optionnel)
                    </label>
                    <textarea name="motif" rows="2"
                        style="width:100%;border:2px solid #fecaca;border-radius:8px;padding:8px 12px;font-size:13px;resize:none;font-family:inherit;"
                        placeholder="Précisez la raison..."></textarea>
                </div>
                <div style="display:flex;gap:10px;justify-content:center;">
                    <button type="button"
                            onclick="document.getElementById('cancelConfirmStatus').style.display='none';document.querySelector('[onclick*=cancelConfirmStatus]').style.display='inline-flex';"
                            style="background:#f1f5f9;color:#64748b;border:none;padding:9px 20px;border-radius:8px;font-weight:700;font-size:13px;cursor:pointer;">
                        Non, garder
                    </button>
                    <button type="submit"
                            style="background:#dc2626;color:#fff;border:none;padding:9px 20px;border-radius:8px;font-weight:700;font-size:13px;cursor:pointer;display:inline-flex;align-items:center;gap:6px;">
                        <i class="bi bi-x-circle-fill"></i> Oui, annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
    @else
    <div style="max-width:500px;margin:0 auto 32px;background:#fee2e2;border:1px solid #fecaca;border-radius:14px;padding:20px 24px;text-align:center;">
        <div style="font-size:15px;font-weight:800;color:#dc2626;margin-bottom:4px;">
            <i class="bi bi-x-circle-fill me-1"></i> Réservation annulée
        </div>
        <p style="font-size:13px;color:#64748b;margin:0;">
            Cette réservation a été annulée. Contactez-nous pour toute question.
        </p>
    </div>
    @endif
    @else
    {{-- État initial --}}
    <div style="max-width:600px;margin:0 auto;text-align:center;padding:40px 20px;">
        <div style="font-size:64px;margin-bottom:16px;">🔍</div>
        <h2 style="color:#003580;font-weight:800;margin-bottom:8px;">Entrez votre code</h2>
        <p style="color:#64748b;font-size:15px;margin-bottom:24px;">
            Utilisez le formulaire ci-dessus pour retrouver votre réservation.
        </p>
        <div style="background:#f0f7ff;border-radius:12px;border:1px solid #bfdbfe;padding:16px 20px;text-align:left;">
            <div style="font-size:13px;font-weight:700;color:#003580;margin-bottom:8px;">
                <i class="bi bi-info-circle me-1"></i>Où trouver mon code ?
            </div>
            <ul style="font-size:13px;color:#64748b;margin:0;padding-left:18px;line-height:1.9;">
                <li>Dans l'email de confirmation reçu après réservation</li>
                <li>Dans votre espace client (<a href="{{ route('client.compte') }}" style="color:#0071c2;">Mon compte</a>)</li>
                <li>Format : <strong style="font-family:monospace;color:#003580;">RES-AAAAMMJJ-XXXX</strong></li>
            </ul>
        </div>
    </div>
    @endif

</div>
@endsection