@extends('layouts.hotel_admin')
@section('page_title', 'Détail réservation')
@section('title', 'Détail réservation')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h1 class="page-title">📋 {{ $reservation->code_reservation }}</h1>
        <p class="page-sub">Détails de la réservation</p>
    </div>
    <a href="{{ route('hoteladmin.reservations.index') }}" class="btn-ha-outline">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">

        {{-- Infos client --}}
        <div class="card-admin p-4 mb-4">
            <h5 style="font-size:14px;font-weight:800;color:#003580;margin-bottom:16px;">
                <i class="bi bi-person me-2"></i>Client
            </h5>
            @php
                $rows = [
                    ['Nom complet', $reservation->prenom_client.' '.$reservation->nom_client],
                    ['Email', $reservation->email_client],
                    ['Téléphone', $reservation->telephone_client ?? '—'],
                    ['Pièce d\'identité', $reservation->code_identite],
                ];
            @endphp
            @foreach($rows as [$label, $value])
            <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid #f1f5f9;font-size:14px;">
                <span style="color:#64748b;">{{ $label }}</span>
                <span style="font-weight:700;">{{ $value }}</span>
            </div>
            @endforeach
        </div>

        {{-- Infos séjour --}}
        <div class="card-admin p-4 mb-4">
            <h5 style="font-size:14px;font-weight:800;color:#003580;margin-bottom:16px;">
                <i class="bi bi-calendar me-2"></i>Séjour
            </h5>
            @php
                $nuits = $reservation->date_debut->diffInDays($reservation->date_fin);
                $rows2 = [
                    ['Chambre', $reservation->chambre->typeChambre->nom_type.' · N° '.$reservation->chambre->numero],
                    ['Arrivée', $reservation->date_debut->format('d/m/Y')],
                    ['Départ', $reservation->date_fin->format('d/m/Y')],
                    ['Durée', $nuits.' nuit(s)'],
                    ['Chambres', $reservation->quantite],
                    ['Prix/nuit', number_format($reservation->prix_unitaire,0,',',' ').' DJF'],
                    ['Total', number_format($reservation->montant_total,0,',',' ').' DJF'],
                ];
            @endphp
            @foreach($rows2 as [$label, $value])
            <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid #f1f5f9;font-size:14px;">
                <span style="color:#64748b;">{{ $label }}</span>
                <span style="font-weight:700;">{{ $value }}</span>
            </div>
            @endforeach
        </div>

        {{-- Photos identité --}}
        @if($reservation->photo_carte || $reservation->photo_visage)
        <div class="card-admin p-4">
            <h5 style="font-size:14px;font-weight:800;color:#003580;margin-bottom:16px;">
                <i class="bi bi-card-image me-2"></i>Documents d'identité
            </h5>
            <div style="display:flex;gap:16px;flex-wrap:wrap;">
                @if($reservation->photo_carte)
                <div>
                    <div style="font-size:12px;color:#64748b;margin-bottom:6px;font-weight:600;">Pièce d'identité</div>
                    <img src="{{ asset('storage/'.$reservation->photo_carte) }}"
                         alt="CNI" style="width:180px;height:120px;object-fit:cover;border-radius:8px;border:2px solid #e2e8f0;">
                </div>
                @endif
                @if($reservation->photo_visage)
                <div>
                    <div style="font-size:12px;color:#64748b;margin-bottom:6px;font-weight:600;">Photo visage</div>
                    <img src="{{ asset('storage/'.$reservation->photo_visage) }}"
                         alt="Visage" style="width:120px;height:120px;object-fit:cover;border-radius:8px;border:2px solid #e2e8f0;">
                </div>
                @endif
            </div>
        </div>
        @endif

    </div>

    <div class="col-lg-4">

        {{-- Statut --}}
        <div class="card-admin p-4 mb-4">
            <h5 style="font-size:14px;font-weight:800;color:#003580;margin-bottom:16px;">
                <i class="bi bi-flag me-2"></i>Statut
            </h5>
            <div class="text-center mb-4">
                <span class="badge-{{ strtolower($reservation->statut) }}" style="font-size:14px;padding:8px 20px;">
                    @if($reservation->statut==='CONFIRMEE') ✅ Confirmée
                    @elseif($reservation->statut==='EN_ATTENTE') ⏳ En attente
                    @else ❌ Annulée
                    @endif
                </span>
            </div>

            @if($reservation->statut === 'EN_ATTENTE')
            <div class="d-flex flex-column gap-2">
                <form method="POST" action="{{ route('hoteladmin.reservations.statut', $reservation) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="statut" value="CONFIRMEE">
                    <button type="submit" class="btn-ha-primary w-100" style="justify-content:center;">
                        <i class="bi bi-check-circle"></i> Confirmer la réservation
                    </button>
                </form>
                <form method="POST" action="{{ route('hoteladmin.reservations.statut', $reservation) }}"
                      onsubmit="return confirm('Annuler cette réservation ?')">
                    @csrf @method('PATCH')
                    <input type="hidden" name="statut" value="ANNULEE">
                    <button type="submit" class="btn-ha-danger w-100" style="justify-content:center;">
                        <i class="bi bi-x-circle"></i> Annuler la réservation
                    </button>
                </form>
            </div>
            @endif

            @if($reservation->statut === 'CONFIRMEE')
            <form method="POST" action="{{ route('hoteladmin.reservations.statut', $reservation) }}"
                  onsubmit="return confirm('Annuler cette réservation confirmée ?')">
                @csrf @method('PATCH')
                <input type="hidden" name="statut" value="ANNULEE">
                <button type="submit" class="btn-ha-danger w-100" style="justify-content:center;">
                    <i class="bi bi-x-circle"></i> Annuler
                </button>
            </form>
            @endif
        </div>

        {{-- Paiement --}}
        <div class="card-admin p-4">
            <h5 style="font-size:14px;font-weight:800;color:#003580;margin-bottom:16px;">
                <i class="bi bi-credit-card me-2"></i>Paiement
            </h5>
            @php $deposit = $reservation->payments->where('payment_kind','acompte')->where('status','accepted')->first(); @endphp
            @if($deposit)
            <div style="background:#dcfce7;border-radius:10px;padding:14px;text-align:center;margin-bottom:12px;">
                <div style="font-size:11px;font-weight:700;color:#14532d;">✅ Acompte reçu</div>
                <div style="font-size:22px;font-weight:900;color:#14532d;">{{ number_format($deposit->amount,0,',',' ') }} DJF</div>
                <div style="font-size:11px;color:#166534;">{{ strtoupper($deposit->payment_method) }} · {{ $deposit->paid_at?->format('d/m/Y') }}</div>
            </div>
            @php $solde = max(0, $reservation->montant_total - $deposit->amount); @endphp
            <div style="display:flex;justify-content:space-between;font-size:13px;padding:8px 0;border-top:1px solid #f1f5f9;">
                <span style="color:#64748b;">Solde restant</span>
                <span style="font-weight:700;color:#003580;">{{ number_format($solde,0,',',' ') }} DJF</span>
            </div>
            @else
            <div style="background:#fef3c7;border-radius:10px;padding:14px;text-align:center;">
                <div style="font-size:11px;font-weight:700;color:#92400e;">⏳ Acompte non payé</div>
                <div style="font-size:22px;font-weight:900;color:#92400e;">{{ number_format($reservation->depositDueAmount(),0,',',' ') }} DJF</div>
                <div style="font-size:11px;color:#92400e;">{{ \App\Models\Reservation::DEPOSIT_PERCENT }}% du total</div>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection