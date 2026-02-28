<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nouvelle réservation</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h1>Nouvelle réservation</h1>
    <p>Une nouvelle réservation a été effectuée pour votre hôtel <strong>{{ $reservation->chambre->typeChambre->hotel->nom }}</strong>.</p>

    <h2>Détails de la réservation</h2>
    <ul>
        <li><strong>Code :</strong> {{ $reservation->code_reservation }}</li>
        <li><strong>Client :</strong> {{ $reservation->prenom_client }} {{ $reservation->nom_client }}</li>
        <li><strong>Email :</strong> {{ $reservation->email_client }}</li>
        <li><strong>Téléphone :</strong> {{ $reservation->telephone_client ?? 'Non renseigné' }}</li>
        <li><strong>Chambre :</strong> {{ $reservation->chambre->numero }} ({{ $reservation->chambre->typeChambre->nom_type }})</li>
        <li><strong>Arrivée :</strong> {{ $reservation->date_debut->format('d/m/Y') }}</li>
        <li><strong>Départ :</strong> {{ $reservation->date_fin->format('d/m/Y') }}</li>
        <li><strong>Montant total :</strong> {{ number_format($reservation->montant_total, 0, ',', ' ') }} FCFA</li>
        <li><strong>Statut :</strong> {{ $reservation->statut }}</li>
    </ul>

    <p>
        <a href="{{ url(route('admin.reservations.show', $reservation)) }}" style="display: inline-block; padding: 10px 20px; background: #1b1b18; color: white; text-decoration: none; border-radius: 5px;">Voir la réservation</a>
    </p>

    <p style="margin-top: 30px; font-size: 12px; color: #666;">{{ config('app.name') }}</p>
</body>
</html>
