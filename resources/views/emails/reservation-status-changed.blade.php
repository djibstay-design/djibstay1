<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mise à jour de votre réservation</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h1>Mise à jour de votre réservation</h1>
    <p>Bonjour {{ $reservation->prenom_client }},</p>
    <p>Le statut de votre réservation {{ $reservation->code_reservation }} a été mis à jour.</p>

    <p style="font-size: 18px; padding: 15px; background: #f0f0f0; border-radius: 5px;">
        <strong>Nouveau statut :</strong>
        @if($reservation->statut === 'CONFIRMEE')
            <span style="color: green;">Confirmée</span>
        @elseif($reservation->statut === 'ANNULEE')
            <span style="color: red;">Annulée</span>
        @else
            <span style="color: orange;">En attente</span>
        @endif
    </p>

    <p><strong>Hôtel :</strong> {{ $reservation->chambre->typeChambre->hotel->nom }}</p>
    <p><strong>Dates :</strong> {{ $reservation->date_debut->format('d/m/Y') }} - {{ $reservation->date_fin->format('d/m/Y') }}</p>

    <p>
        <a href="{{ url(route('reservations.status').'?code='.urlencode($reservation->code_reservation)) }}" style="display: inline-block; padding: 10px 20px; background: #1b1b18; color: white; text-decoration: none; border-radius: 5px;">Voir ma réservation</a>
    </p>

    <p style="margin-top: 30px; font-size: 12px; color: #666;">{{ config('app.name') }}</p>
</body>
</html>
