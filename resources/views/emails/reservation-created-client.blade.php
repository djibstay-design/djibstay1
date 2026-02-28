<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmation de réservation</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h1>Réservation confirmée</h1>
    <p>Bonjour {{ $reservation->prenom_client }},</p>
    <p>Votre réservation a bien été enregistrée.</p>

    <h2>Détails</h2>
    <ul>
        <li><strong>Code :</strong> {{ $reservation->code_reservation }}</li>
        <li><strong>Hôtel :</strong> {{ $reservation->chambre->typeChambre->hotel->nom }}</li>
        <li><strong>Chambre :</strong> {{ $reservation->chambre->numero }} ({{ $reservation->chambre->typeChambre->nom_type }})</li>
        <li><strong>Arrivée :</strong> {{ $reservation->date_debut->format('d/m/Y') }}</li>
        <li><strong>Départ :</strong> {{ $reservation->date_fin->format('d/m/Y') }}</li>
        <li><strong>Montant total :</strong> {{ number_format($reservation->montant_total, 0, ',', ' ') }} FCFA</li>
        <li><strong>Statut :</strong> En attente de confirmation</li>
    </ul>

    <p>
        <a href="{{ url(route('reservations.status').'?code='.urlencode($reservation->code_reservation)) }}" style="display: inline-block; padding: 10px 20px; background: #1b1b18; color: white; text-decoration: none; border-radius: 5px;">Suivre le statut de ma réservation</a>
    </p>

    <p style="margin-top: 30px; font-size: 12px; color: #666;">Conservez votre code de réservation pour suivre l'évolution.</p>
    <p style="font-size: 12px; color: #666;">{{ config('app.name') }}</p>
</body>
</html>
