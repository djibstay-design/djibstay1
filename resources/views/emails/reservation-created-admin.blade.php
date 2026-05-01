<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nouvelle réservation</title>
</head>
<body style="font-family:'Inter',sans-serif;background:#f2f6fc;margin:0;padding:20px;">
<div style="max-width:600px;margin:0 auto;">

    {{-- Header --}}
    <div style="background:linear-gradient(135deg,#003580,#0071c2);border-radius:14px 14px 0 0;padding:28px 32px;text-align:center;">
        @php $logoPath = \App\Models\SiteSetting::get('app_logo',''); $appName = \App\Models\SiteSetting::get('app_name','DjibStay'); @endphp
        @if($logoPath)
        <img src="{{ asset('storage/'.$logoPath) }}" alt="{{ $appName }}" style="height:44px;object-fit:contain;margin-bottom:12px;filter:brightness(0) invert(1);">
        @else
        <div style="font-size:22px;font-weight:900;color:#fff;margin-bottom:8px;">🏨 {{ $appName }}</div>
        @endif
        <div style="background:rgba(255,255,255,0.15);border-radius:8px;padding:8px 16px;display:inline-block;">
            <span style="color:#febb02;font-size:13px;font-weight:700;">🔔 Nouvelle réservation reçue</span>
        </div>
    </div>

    {{-- Body --}}
    <div style="background:#fff;padding:28px 32px;border:1px solid #e2e8f0;border-top:none;">

        <p style="font-size:15px;color:#1e293b;margin-bottom:20px;">
            Une nouvelle réservation a été effectuée pour votre hôtel
            <strong style="color:#003580;">{{ $reservation->chambre->typeChambre->hotel->nom }}</strong>.
        </p>

        {{-- Code --}}
        <div style="background:#003580;border-radius:10px;padding:16px;text-align:center;margin-bottom:24px;">
            <div style="font-size:11px;color:rgba(255,255,255,0.7);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Code de réservation</div>
            <div style="font-size:28px;font-weight:900;color:#febb02;font-family:monospace;letter-spacing:3px;">
                {{ $reservation->code_reservation }}
            </div>
        </div>

        {{-- Infos client --}}
        <div style="margin-bottom:20px;">
            <div style="font-size:12px;font-weight:800;color:#003580;text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px;padding-bottom:6px;border-bottom:2px solid #f1f5f9;">
                👤 Informations client
            </div>
            @foreach([
                ['Client', $reservation->prenom_client.' '.$reservation->nom_client],
                ['Email', $reservation->email_client],
                ['Téléphone', $reservation->telephone_client ?? 'Non renseigné'],
                ['Pièce d\'identité', $reservation->code_identite],
            ] as [$label, $val])
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f8fafc;font-size:13px;">
                <span style="color:#64748b;">{{ $label }}</span>
                <span style="font-weight:700;color:#1e293b;">{{ $val }}</span>
            </div>
            @endforeach
        </div>

        {{-- Infos séjour --}}
        <div style="margin-bottom:20px;">
            <div style="font-size:12px;font-weight:800;color:#003580;text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px;padding-bottom:6px;border-bottom:2px solid #f1f5f9;">
                🏨 Détails du séjour
            </div>
            @php $nuits = $reservation->date_debut->diffInDays($reservation->date_fin); @endphp
            @foreach([
                ['Hôtel', $reservation->chambre->typeChambre->hotel->nom],
                ['Chambre', 'N° '.$reservation->chambre->numero.' — '.$reservation->chambre->typeChambre->nom_type],
                ['Arrivée', $reservation->date_debut->format('d/m/Y')],
                ['Départ', $reservation->date_fin->format('d/m/Y')],
                ['Durée', $nuits.' nuit(s)'],
            ] as [$label, $val])
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f8fafc;font-size:13px;">
                <span style="color:#64748b;">{{ $label }}</span>
                <span style="font-weight:700;color:#1e293b;">{{ $val }}</span>
            </div>
            @endforeach
        </div>

        {{-- Montant --}}
        <div style="background:#f0f7ff;border-radius:10px;padding:16px;margin-bottom:24px;">
            <div style="font-size:12px;font-weight:800;color:#003580;text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px;">
                💰 Paiement
            </div>
            @php
                $acompte = $reservation->depositDueAmount();
                $solde   = max(0, round((float)$reservation->montant_total - $acompte, 2));
            @endphp
            <div style="display:flex;justify-content:space-between;padding:6px 0;font-size:13px;border-bottom:1px solid #bfdbfe;">
                <span style="color:#64748b;">Total séjour</span>
                <span style="font-weight:700;">{{ number_format($reservation->montant_total,0,',',' ') }} DJF</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:6px 0;font-size:13px;border-bottom:1px solid #bfdbfe;">
                <span style="color:#64748b;">Acompte reçu ({{ \App\Models\Reservation::DEPOSIT_PERCENT }}%)</span>
                <span style="font-weight:800;color:#16a34a;">✅ {{ number_format($acompte,0,',',' ') }} DJF</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:6px 0;font-size:13px;">
                <span style="color:#64748b;">Solde à percevoir</span>
                <span style="font-weight:800;color:#f59e0b;">{{ number_format($solde,0,',',' ') }} DJF</span>
            </div>
        </div>

        {{-- CTA --}}
        <div style="text-align:center;margin-bottom:20px;">
            <a href="{{ url(route('admin.reservations.show', $reservation)) }}"
               style="display:inline-block;background:linear-gradient(135deg,#003580,#0071c2);color:#fff;padding:13px 28px;border-radius:9px;text-decoration:none;font-weight:800;font-size:14px;">
                📋 Voir la réservation dans le dashboard
            </a>
        </div>

        <p style="font-size:12px;color:#94a3b8;text-align:center;margin:0;">
            Cet email a été envoyé automatiquement par {{ $appName }}
        </p>
    </div>

    {{-- Footer --}}
    <div style="background:#0f1729;border-radius:0 0 14px 14px;padding:16px 32px;text-align:center;">
        <p style="font-size:12px;color:rgba(255,255,255,0.45);margin:0;">© {{ date('Y') }} {{ $appName }} — Tous droits réservés</p>
    </div>

</div>
</body>
</html>