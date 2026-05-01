@extends('layouts.app')
@section('title', 'Confirmation de Réservation — '.(\App\Models\SiteSetting::get('app_name','DjibStay')))

@push('styles')
<style>
    @media print {
        .no-print { display: none !important; }
        .confirm-hero { display: none !important; }
        .container { width: 100% !important; max-width: none !important; padding: 0 !important; }
        .receipt-card { box-shadow: none !important; border: 1px solid #eee !important; margin: 0 !important; }
        body { background: #fff !important; }
    }

    .confirm-hero { 
        background: linear-gradient(135deg, #003580 0%, #0071c2 100%); 
        padding: 48px 0; 
        color: #fff; 
        text-align: center;
        border-bottom: 5px solid #febb02;
    }
    
    .receipt-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,53,128,0.12);
        overflow: hidden;
        margin-top: -40px;
        position: relative;
        z-index: 10;
        border: 1px solid #e2e8f0;
    }

    .receipt-header {
        background: #f8fafc;
        padding: 32px;
        border-bottom: 2px dashed #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .receipt-body { padding: 32px; }

    .qr-code-box {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px;
        text-align: center;
        width: 140px;
    }

    .receipt-label { font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
    .receipt-value { font-size: 15px; font-weight: 700; color: #1e293b; }

    .status-badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-transform: uppercase;
    }
    .status-confirmed { background: #dcfce7; color: #166534; }

    .table-receipt { width: 100%; margin-top: 24px; border-collapse: collapse; }
    .table-receipt th { font-size: 12px; font-weight: 800; color: #64748b; text-transform: uppercase; padding: 12px 0; border-bottom: 2px solid #f1f5f9; text-align: left; }
    .table-receipt td { padding: 16px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #1e293b; }
    
    .total-box {
        background: #f0f7ff;
        border-radius: 12px;
        padding: 24px;
        margin-top: 24px;
    }
    .total-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
    .total-row:last-child { margin-bottom: 0; padding-top: 12px; border-top: 1px solid #bfdbfe; }
    .total-row.grand { font-size: 18px; font-weight: 900; color: #003580; }

    .btn-action {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 800;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-primary-djib { background: #003580; color: #fff; }
    .btn-primary-djib:hover { background: #0071c2; color: #fff; }
    .btn-outline-djib { border: 2px solid #e2e8f0; color: #475569; }
    .btn-outline-djib:hover { background: #f8fafc; }
</style>
@endpush

@section('content')
@php
    $appName  = \App\Models\SiteSetting::get('app_name','DjibStay');
    $logoPath = \App\Models\SiteSetting::get('app_logo','');
    $devise   = \App\Models\SiteSetting::get('app_devise','DJF');
    $type     = $reservation->chambre->typeChambre;
    $hotel    = $type->hotel;
    $nuits    = $reservation->date_debut->diffInDays($reservation->date_fin);
    
    // Calculs basés sur le paiement réel
    $payment = $reservation->payments()->where('payment_kind', \App\Models\Payment::KIND_DEPOSIT)->first();
    $depositPaid = $payment ? $payment->amount : 0;
    $balanceDue  = $reservation->montant_total - $depositPaid;

    // QR Code
    $qrData = route('reservations.status', ['code' => $reservation->code_reservation]);
    $qrUrl  = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrData);
@endphp

{{-- HERO --}}
<section class="confirm-hero no-print">
    <div class="container">
        <div style="width:60px;height:60px;background:#22c55e;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:30px;color:#fff;">
            <i class="bi bi-check-lg"></i>
        </div>
        <h1 style="font-size:32px;font-weight:900;margin-bottom:8px;">Réservation Confirmée !</h1>
        <p style="color:rgba(255,255,255,0.85);font-size:16px;">Votre séjour à <strong>{{ $hotel->nom }}</strong> est validé.</p>
    </div>
</section>

<div class="container py-5" style="max-width:900px;">
    
    <div class="receipt-card">
        {{-- Header du reçu --}}
        <div class="receipt-header">
            <div>
                @if($logoPath)
                    <img src="{{ asset('storage/'.$logoPath) }}" alt="{{ $appName }}" style="height:44px;object-fit:contain;margin-bottom:12px;">
                @else
                    <div style="font-size:24px;font-weight:900;color:#003580;margin-bottom:12px;">🏨 {{ $appName }}</div>
                @endif
                <div class="status-badge status-confirmed">
                    <i class="bi bi-check-circle-fill"></i> Paiement Accepté
                </div>
            </div>
            <div class="text-end no-print">
                <div class="qr-code-box">
                    <img src="{{ $qrUrl }}" alt="QR Code" style="width:100%;height:auto;margin-bottom:4px;">
                    <div style="font-size:9px;color:#94a3b8;font-weight:700;">SCANNEZ POUR LE SUIVI</div>
                </div>
            </div>
        </div>

        <div class="receipt-body">
            <div class="row g-4 mb-5">
                <div class="col-md-3 col-6">
                    <div class="receipt-label">Numéro de reçu</div>
                    <div class="receipt-value">#{{ str_replace('DJ-','',$reservation->code_reservation) }}</div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="receipt-label">Date</div>
                    <div class="receipt-value">{{ now()->format('d/m/Y') }}</div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="receipt-label">Code Réservation</div>
                    <div class="receipt-value" style="color:#0071c2;letter-spacing:1px;">{{ $reservation->code_reservation }}</div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="receipt-label">Client</div>
                    <div class="receipt-value">{{ $reservation->prenom_client }} {{ $reservation->nom_client }}</div>
                </div>
            </div>

            <div style="font-size:14px;font-weight:800;color:#003580;text-transform:uppercase;letter-spacing:1px;margin-bottom:16px;">
                Détails du séjour
            </div>
            
            <table class="table-receipt">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Détails</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div style="font-weight:800;color:#003580;">{{ $hotel->nom }}</div>
                            <div style="font-size:12px;color:#64748b;">{{ $hotel->ville }}, Djibouti</div>
                        </td>
                        <td>
                            <div>{{ $type->nom_type }}</div>
                            <div style="font-size:12px;color:#64748b;">{{ $nuits }} nuit(s) · du {{ $reservation->date_debut->format('d M') }} au {{ $reservation->date_fin->format('d M Y') }}</div>
                        </td>
                        <td class="text-end" style="font-weight:800;">
                            {{ number_format($reservation->montant_total,0,',',' ') }} {{ $devise }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="row">
                <div class="col-md-6 offset-md-6">
                    <div class="total-box">
                        <div class="total-row">
                            <span style="color:#64748b;">Montant Total</span>
                            <span style="font-weight:700;">{{ number_format($reservation->montant_total,0,',',' ') }} {{ $devise }}</span>
                        </div>
                        <div class="total-row">
                            <span style="color:#64748b;">Acompte payé</span>
                            <span style="font-weight:800;color:#16a34a;">- {{ number_format($depositPaid,0,',',' ') }} {{ $devise }}</span>
                        </div>
                        <div class="total-row grand">
                            <span>Solde à payer à l'hôtel</span>
                            <span>{{ number_format($balanceDue,0,',',' ') }} {{ $devise }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div style="margin-top:40px;padding-top:24px;border-top:1px solid #f1f5f9;display:flex;gap:32px;">
                <div style="flex:1;">
                    <div class="receipt-label">Note importante</div>
                    <p style="font-size:12px;color:#64748b;line-height:1.6;margin:0;">
                        Veuillez présenter ce reçu (digital ou imprimé) lors de votre check-in à l'hôtel. 
                        Le solde restant de <strong>{{ number_format($balanceDue,0,',',' ') }} {{ $devise }}</strong> devra être réglé directement à la réception de l'établissement.
                    </p>
                </div>
                <div class="text-center no-print" style="width:100px;">
                    <div style="font-size:10px;font-weight:800;color:#003580;margin-bottom:4px;">CERTIFIÉ</div>
                    <i class="bi bi-patch-check-fill text-primary fs-1"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Boutons d'actions --}}
    <div class="mt-5 d-flex justify-content-center gap-3 no-print">
        <button onclick="window.print()" class="btn-action btn-primary-djib">
            <i class="bi bi-printer"></i> Imprimer le reçu
        </button>
        <a href="{{ route('reservations.status') }}?code={{ $reservation->code_reservation }}" class="btn-action btn-outline-djib">
            <i class="bi bi-search"></i> Suivre ma réservation
        </a>
        <a href="{{ route('home') }}" class="btn-action btn-outline-djib">
            <i class="bi bi-house"></i> Retour à l'accueil
        </a>
    </div>

</div>
@endsection