<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmation de réservation</title>
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
            <span style="color:#febb02;font-size:13px;font-weight:700;">✅ Réservation confirmée !</span>
        </div>
    </div>

    {{-- Body --}}
    <div style="background:#fff;padding:28px 32px;border:1px solid #e2e8f0;border-top:none;">

        <p style="font-size:15px;color:#1e293b;margin-bottom:8px;">
            Bonjour <strong>{{ $reservation->prenom_client }}</strong>,
        </p>
        <p style="font-size:14px;color:#475569;margin-bottom:24px;">
            Votre réservation a bien été enregistrée et votre acompte reçu. Voici le récapitulatif de votre séjour.
        </p>

        {{-- Code --}}
        <div style="background:#003580;border-radius:10px;padding:16px;text-align:center;margin-bottom:24px;">
            <div style="font-size:11px;color:rgba(255,255,255,0.7);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Votre code de réservation</div>
            <div style="font-size:28px;font-weight:900;color:#febb02;font-family:monospace;letter-spacing:3px;">
                {{ $reservation->code_reservation }}
            </div>
            <div style="font-size:11px;color:rgba(255,255,255,0.6);margin-top:6px;">Conservez ce code — il vous sera demandé à l'hôtel</div>
        </div>

        {{-- Séjour --}}
        <div style="margin-bottom:20px;">
            <div style="font-size:12px;font-weight:800;color:#003580;text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px;padding-bottom:6px;border-bottom:2px solid #f1f5f9;">
                🏨 Détails du séjour
            </div>
            @php $nuits = $reservation->date_debut->diffInDays($reservation->date_fin); @endphp
            @foreach([
                ['Hôtel', $reservation->chambre->typeChambre->hotel->nom],
                ['Type de chambre', $reservation->chambre->typeChambre->nom_type],
                ['Chambre', 'N° '.$reservation->chambre->numero],
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

        {{-- Paiement --}}
        <div style="background:#f0f7ff;border-radius:10px;padding:16px;margin-bottom:24px;">
            <div style="font-size:12px;font-weight:800;color:#003580;text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px;">
                💰 Récapitulatif paiement
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
                <span style="color:#64748b;">Acompte payé ({{ \App\Models\Reservation::DEPOSIT_PERCENT }}%)</span>
                <span style="font-weight:800;color:#16a34a;">✅ {{ number_format($acompte,0,',',' ') }} DJF</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:6px 0;font-size:13px;">
                <span style="color:#64748b;">Solde à payer à l'hôtel</span>
                <span style="font-weight:800;color:#f59e0b;">{{ number_format($solde,0,',',' ') }} DJF</span>
            </div>
        </div>

        {{-- Prochaines étapes --}}
        <div style="margin-bottom:24px;">
            <div style="font-size:12px;font-weight:800;color:#003580;text-transform:uppercase;letter-spacing:.5px;margin-bottom:12px;">
                📋 Prochaines étapes
            </div>
            @foreach([
                ['✅', 'Acompte reçu', 'Votre paiement a bien été enregistré.'],
                ['🏨', 'Arrivée à l\'hôtel', 'Présentez votre code et pièce d\'identité à la réception.'],
                ['💵', 'Solde restant', 'Réglez '.number_format($solde,0,',',' ').' DJF directement à l\'hôtel.'],
            ] as [$icon, $title, $desc])
            <div style="display:flex;gap:12px;margin-bottom:10px;padding:10px;background:#f8fafc;border-radius:8px;">
                <span style="font-size:18px;flex-shrink:0;">{{ $icon }}</span>
                <div>
                    <div style="font-size:13px;font-weight:700;color:#1e293b;">{{ $title }}</div>
                    <div style="font-size:12px;color:#64748b;">{{ $desc }}</div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- CTA --}}
        <div style="text-align:center;margin-bottom:20px;">
            <a href="{{ url(route('reservations.status').'?code='.urlencode($reservation->code_reservation)) }}"
               style="display:inline-block;background:linear-gradient(135deg,#003580,#0071c2);color:#fff;padding:13px 28px;border-radius:9px;text-decoration:none;font-weight:800;font-size:14px;">
                🔍 Suivre ma réservation
            </a>
        </div>

        <p style="font-size:12px;color:#94a3b8;text-align:center;margin:0;">
            Des questions ? Contactez-nous — nous sommes là pour vous aider.
        </p>
    </div>

    {{-- Footer --}}
    <div style="background:#0f1729;border-radius:0 0 14px 14px;padding:16px 32px;text-align:center;">
        <p style="font-size:12px;color:rgba(255,255,255,0.45);margin:0;">© {{ date('Y') }} {{ $appName }} — Tous droits réservés</p>
    </div>

</div>
</body>
</html>