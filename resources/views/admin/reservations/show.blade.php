@extends('layouts.admin')
@section('page_title', 'Détail réservation')

@section('content')
@php
    $type  = $reservation->chambre->typeChambre;
    $hotel = $type->hotel;
    $nuits = $reservation->date_debut->diffInDays($reservation->date_fin);
    $acomptePercent = \App\Models\SiteSetting::get('resa_acompte_percent','30');
    $depositAmount  = round($reservation->montant_total * $acomptePercent / 100);
    $balanceAmount  = $reservation->montant_total - $depositAmount;
    $hasPaid        = $reservation->hasPaidDeposit();
    $estExpiree     = $reservation->statut === 'ANNULEE' && $reservation->date_debut->isPast();
@endphp

<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
    <div>
        <h1 style="font-size:22px;font-weight:900;color:#1e293b;margin:0;">📋 {{ $reservation->code_reservation }}</h1>
        <p style="font-size:13px;color:#64748b;margin:4px 0 0;">Détail de la réservation</p>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        {{-- Bouton Modifier grisé si expirée --}}
        @if($estExpiree)
            <span style="background:#e2e8f0;color:#94a3b8;padding:9px 16px;border-radius:8px;font-weight:700;font-size:13px;display:inline-flex;align-items:center;gap:6px;cursor:not-allowed;">
                <i class="bi bi-pencil"></i> Modifier
            </span>
        @else
            <a href="{{ route('admin.reservations.edit',$reservation) }}"
               style="background:#fef3c7;color:#92400e;padding:9px 16px;border-radius:8px;text-decoration:none;font-weight:700;font-size:13px;display:inline-flex;align-items:center;gap:6px;">
                <i class="bi bi-pencil"></i> Modifier
            </a>
        @endif
        <a href="{{ route('admin.reservations.index') }}"
           style="background:#f1f5f9;color:#475569;padding:9px 16px;border-radius:8px;text-decoration:none;font-weight:700;font-size:13px;display:inline-flex;align-items:center;gap:6px;">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

{{-- Statut banner --}}
@if($estExpiree)
<div style="background:#f3e8ff;border-radius:12px;padding:16px 22px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px;border:1px solid #d8b4fe;">
    <div style="display:flex;align-items:center;gap:10px;">
        <div style="font-size:28px;">🕐</div>
        <div>
            <div style="font-size:16px;font-weight:800;color:#6b21a8;">Réservation Expirée</div>
            <div style="font-size:12px;color:#64748b;">Créée le {{ $reservation->date_reservation->format('d/m/Y à H:i') }} — date d'arrivée dépassée</div>
        </div>
    </div>
</div>
@else
<div style="background:{{ $reservation->statut==='CONFIRMEE'?'#dcfce7':($reservation->statut==='EN_ATTENTE'?'#fef3c7':'#fee2e2') }};border-radius:12px;padding:16px 22px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px;border:1px solid {{ $reservation->statut==='CONFIRMEE'?'#86efac':($reservation->statut==='EN_ATTENTE'?'#fde68a':'#fca5a5') }};">
    <div style="display:flex;align-items:center;gap:10px;">
        <div style="font-size:28px;">
            @if($reservation->statut==='CONFIRMEE') ✅
            @elseif($reservation->statut==='EN_ATTENTE') ⏳
            @else ❌
            @endif
        </div>
        <div>
            <div style="font-size:16px;font-weight:800;color:{{ $reservation->statut==='CONFIRMEE'?'#14532d':($reservation->statut==='EN_ATTENTE'?'#92400e':'#991b1b') }};">
                Réservation {{ $reservation->statut==='CONFIRMEE'?'Confirmée':($reservation->statut==='EN_ATTENTE'?'En attente':'Annulée') }}
            </div>
            <div style="font-size:12px;color:#64748b;">Créée le {{ $reservation->date_reservation->format('d/m/Y à H:i') }}</div>
        </div>
    </div>
    <div style="display:flex;gap:8px;">
        @if($reservation->statut === 'EN_ATTENTE')
        <form method="POST" action="{{ route('admin.reservations.update',$reservation) }}">
            @csrf @method('PUT')
            <input type="hidden" name="statut" value="CONFIRMEE">
            <input type="hidden" name="nom_client" value="{{ $reservation->nom_client }}">
            <input type="hidden" name="prenom_client" value="{{ $reservation->prenom_client }}">
            <input type="hidden" name="email_client" value="{{ $reservation->email_client }}">
            <input type="hidden" name="chambre_id" value="{{ $reservation->chambre_id }}">
            <input type="hidden" name="date_debut" value="{{ $reservation->date_debut->format('Y-m-d') }}">
            <input type="hidden" name="date_fin" value="{{ $reservation->date_fin->format('Y-m-d') }}">
            <button type="submit" style="background:#16a34a;color:#fff;border:none;border-radius:8px;padding:9px 16px;font-weight:700;font-size:13px;cursor:pointer;">
                <i class="bi bi-check-circle me-1"></i>Confirmer
            </button>
        </form>
        @endif
        @if($reservation->statut !== 'ANNULEE')
        <form method="POST" action="{{ route('admin.reservations.update',$reservation) }}" onsubmit="return confirm('Annuler cette réservation ?')">
            @csrf @method('PUT')
            <input type="hidden" name="statut" value="ANNULEE">
            <input type="hidden" name="nom_client" value="{{ $reservation->nom_client }}">
            <input type="hidden" name="prenom_client" value="{{ $reservation->prenom_client }}">
            <input type="hidden" name="email_client" value="{{ $reservation->email_client }}">
            <input type="hidden" name="chambre_id" value="{{ $reservation->chambre_id }}">
            <input type="hidden" name="date_debut" value="{{ $reservation->date_debut->format('Y-m-d') }}">
            <input type="hidden" name="date_fin" value="{{ $reservation->date_fin->format('Y-m-d') }}">
            <button type="submit" style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:9px 16px;font-weight:700;font-size:13px;cursor:pointer;">
                <i class="bi bi-x-circle me-1"></i>Annuler
            </button>
        </form>
        @endif
    </div>
</div>
@endif

<div class="row g-4">
    <div class="col-lg-8">

        {{-- Client --}}
        <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 12px rgba(0,53,128,0.07);overflow:hidden;margin-bottom:20px;">
            <div style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:14px 22px;display:flex;align-items:center;gap:8px;">
                <i class="bi bi-person-circle text-primary fs-5"></i>
                <span style="font-size:15px;font-weight:800;color:#003580;">Informations client</span>
            </div>
            <div style="padding:20px 22px;">
                <div class="row g-3">
                    @foreach([
                        ['Nom complet', $reservation->prenom_client.' '.$reservation->nom_client, 'bi-person'],
                        ['Email', $reservation->email_client, 'bi-envelope'],
                        ['Téléphone', $reservation->telephone_client ?? '—', 'bi-telephone'],
                        ['Pièce d\'identité', $reservation->code_identite, 'bi-card-text'],
                    ] as [$label,$val,$icon])
                    <div class="col-sm-6">
                        <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.4px;margin-bottom:4px;">
                            <i class="bi {{ $icon }} me-1"></i>{{ $label }}
                        </div>
                        <div style="font-size:14px;font-weight:700;color:#1e293b;">{{ $val }}</div>
                    </div>
                    @endforeach
                </div>
                @if($reservation->photo_carte || $reservation->photo_visage)
                <div style="margin-top:16px;padding-top:16px;border-top:1px solid #f1f5f9;">
                    <div style="font-size:13px;font-weight:700;color:#003580;margin-bottom:10px;"><i class="bi bi-card-image me-1"></i>Documents d'identité</div>
                    <div style="display:flex;gap:14px;flex-wrap:wrap;">
                        @if($reservation->photo_carte)
                        <div>
                            <div style="font-size:11px;color:#64748b;font-weight:600;margin-bottom:5px;">Pièce d'identité</div>
                            <img src="{{ asset('storage/'.$reservation->photo_carte) }}"
                                 alt="CNI" style="width:180px;height:120px;object-fit:cover;border-radius:8px;border:2px solid #e2e8f0;">
                        </div>
                        @endif
                        @if($reservation->photo_visage)
                        <div>
                            <div style="font-size:11px;color:#64748b;font-weight:600;margin-bottom:5px;">Photo visage</div>
                            <img src="{{ asset('storage/'.$reservation->photo_visage) }}"
                                 alt="Visage" style="width:120px;height:120px;object-fit:cover;border-radius:8px;border:2px solid #e2e8f0;">
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Séjour --}}
        <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 12px rgba(0,53,128,0.07);overflow:hidden;margin-bottom:20px;">
            <div style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:14px 22px;display:flex;align-items:center;gap:8px;">
                <i class="bi bi-calendar3 text-primary fs-5"></i>
                <span style="font-size:15px;font-weight:800;color:#003580;">Détails du séjour</span>
            </div>
            <div style="padding:20px 22px;">
                <div class="row g-3">
                    @foreach([
                        ['Hôtel', $hotel->nom, 'bi-building'],
                        ['Type de chambre', $type->nom_type, 'bi-grid'],
                        ['N° Chambre', $reservation->chambre->numero, 'bi-door-open'],
                        ['Capacité', $type->capacite.' pers.', 'bi-people'],
                        ['Arrivée', $reservation->date_debut->format('d/m/Y'), 'bi-calendar-event'],
                        ['Départ', $reservation->date_fin->format('d/m/Y'), 'bi-calendar-check'],
                        ['Durée', $nuits.' nuit(s)', 'bi-moon-stars'],
                        ['Quantité', $reservation->quantite.' chambre(s)', 'bi-tag'],
                    ] as [$label,$val,$icon])
                    <div class="col-sm-6">
                        <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.4px;margin-bottom:4px;">
                            <i class="bi {{ $icon }} me-1"></i>{{ $label }}
                        </div>
                        <div style="font-size:14px;font-weight:700;color:#1e293b;">{{ $val }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Paiements --}}
        <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 12px rgba(0,53,128,0.07);overflow:hidden;margin-bottom:20px;">
            <div style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:14px 22px;display:flex;align-items:center;gap:8px;">
                <i class="bi bi-cash-coin text-primary fs-5"></i>
                <span style="font-size:15px;font-weight:800;color:#003580;">Historique des paiements</span>
            </div>
            <div style="padding:20px 22px;">
                @if($reservation->payments->isEmpty())
                    <div style="text-align:center;padding:20px;color:#64748b;font-size:13px;background:#f8fafc;border-radius:10px;">
                        <i class="bi bi-info-circle me-1"></i>Aucune preuve de paiement soumise pour le moment.
                    </div>
                @else
                    @foreach($reservation->payments as $payment)
                    <div style="border:1px solid #f1f5f9;border-radius:12px;padding:16px;margin-bottom:16px;background:#fcfdfe;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:12px;padding-bottom:12px;border-bottom:1px dashed #e2e8f0;">
                            <div>
                                <span style="font-size:13px;font-weight:800;color:#003580;">{{ strtoupper($payment->payment_method) }}</span>
                                <span style="font-size:11px;color:#64748b;margin-left:8px;">{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : 'Date inconnue' }}</span>
                            </div>
                            <span style="background:{{ $payment->status==='accepted'?'#dcfce7':'#fef3c7' }};color:{{ $payment->status==='accepted'?'#166534':'#92400e' }};padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;">
                                {{ $payment->status==='accepted'?'Accepté':'En attente' }}
                            </span>
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-4">
                                <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;font-weight:700;">Envoyeur</div>
                                <div style="font-size:13px;font-weight:700;color:#1e293b;">{{ $payment->sender_name ?? '—' }}</div>
                                <div style="font-size:12px;color:#003580;font-weight:600;">{{ $payment->sender_phone ?? '—' }}</div>
                            </div>
                            <div class="col-sm-4">
                                <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;font-weight:700;">Code SMS / Transaction</div>
                                <div style="font-size:13px;font-weight:700;color:#003580;">{{ $payment->transaction_sms_code ?? '—' }}</div>
                            </div>
                            <div class="col-sm-4">
                                <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;font-weight:700;">Montant</div>
                                <div style="font-size:14px;font-weight:800;color:#16a34a;">{{ number_format($payment->amount,0,',',' ') }} {{ $payment->currency }}</div>
                            </div>
                        </div>
                        @if($payment->screenshot)
                        <div style="margin-top:14px;">
                            <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;font-weight:700;margin-bottom:6px;">Reçu / Capture d'écran</div>
                            <a href="{{ asset('storage/'.$payment->screenshot) }}" target="_blank">
                                <img src="{{ asset('storage/'.$payment->screenshot) }}" style="max-width:100%;height:100px;border-radius:8px;border:1px solid #e2e8f0;object-fit:cover;">
                            </a>
                        </div>
                        @endif
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

    </div>

    <div class="col-lg-4">

        {{-- Paiement --}}
        <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 12px rgba(0,53,128,0.07);overflow:hidden;margin-bottom:16px;">
            <div style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:14px 22px;display:flex;align-items:center;gap:8px;">
                <i class="bi bi-credit-card text-primary fs-5"></i>
                <span style="font-size:15px;font-weight:800;color:#003580;">Paiement</span>
            </div>
            <div style="padding:18px 20px;">
                <div style="text-align:center;background:linear-gradient(135deg,#003580,#0071c2);border-radius:10px;padding:16px;color:#fff;margin-bottom:14px;">
                    <div style="font-size:11px;color:rgba(255,255,255,0.75);text-transform:uppercase;letter-spacing:.4px;">Total séjour</div>
                    <div style="font-size:28px;font-weight:900;color:#febb02;line-height:1;">{{ number_format($reservation->montant_total,0,',',' ') }}</div>
                    <div style="font-size:12px;color:rgba(255,255,255,0.75);">{{ \App\Models\SiteSetting::get('app_devise','DJF') }}</div>
                </div>
                @foreach([
                    ['Acompte ('.$acomptePercent.'%)', number_format($depositAmount,0,',',' ').' '. \App\Models\SiteSetting::get('app_devise','DJF'), $hasPaid?'#dcfce7':'#fef3c7', $hasPaid?'#14532d':'#92400e', $hasPaid?'✅ Payé':'⏳ En attente'],
                    ['Solde restant', number_format($balanceAmount,0,',',' ').' '. \App\Models\SiteSetting::get('app_devise','DJF'), '#f1f5f9', '#475569', 'À l\'hôtel'],
                ] as [$l,$v,$bg,$c,$tag])
                <div style="background:{{ $bg }};border-radius:8px;padding:12px 14px;margin-bottom:8px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <div>
                            <div style="font-size:12px;font-weight:700;color:{{ $c }};text-transform:uppercase;letter-spacing:.3px;">{{ $l }}</div>
                            <div style="font-size:16px;font-weight:900;color:{{ $c }};">{{ $v }}</div>
                        </div>
                        <span style="font-size:11px;font-weight:700;color:{{ $c }};">{{ $tag }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Actions rapides --}}
        <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;padding:18px 20px;">
            <div style="font-size:14px;font-weight:800;color:#003580;margin-bottom:12px;"><i class="bi bi-lightning me-1"></i>Actions</div>
            <div style="display:flex;flex-direction:column;gap:8px;">

                {{-- Modifier grisé si expirée --}}
                @if($estExpiree)
                    <span style="background:#e2e8f0;color:#94a3b8;padding:10px 14px;border-radius:8px;font-weight:700;font-size:13px;display:flex;align-items:center;gap:8px;cursor:not-allowed;">
                        <i class="bi bi-pencil"></i> Modifier la réservation
                    </span>
                @else
                    <a href="{{ route('admin.reservations.edit',$reservation) }}"
                       style="background:#fef3c7;color:#92400e;padding:10px 14px;border-radius:8px;text-decoration:none;font-weight:700;font-size:13px;display:flex;align-items:center;gap:8px;">
                        <i class="bi bi-pencil"></i> Modifier la réservation
                    </a>
                @endif

                <a href="{{ route('hotels.show',$hotel) }}" target="_blank"
                   style="background:#dbeafe;color:#1e40af;padding:10px 14px;border-radius:8px;text-decoration:none;font-weight:700;font-size:13px;display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-eye"></i> Voir l'hôtel
                </a>
                <form method="POST" action="{{ route('admin.reservations.destroy',$reservation) }}" onsubmit="return confirm('Supprimer définitivement ?')">
                    @csrf @method('DELETE')
                    <button type="submit" style="background:#fee2e2;color:#991b1b;padding:10px 14px;border-radius:8px;border:none;font-weight:700;font-size:13px;cursor:pointer;width:100%;display:flex;align-items:center;gap:8px;">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection