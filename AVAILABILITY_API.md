# API Gestion de Disponibilité des Chambres

## Vue d'ensemble
Ce système permet aux clients de vérifier la disponibilité des chambres avant de passer une réservation.

## Endpoints API

### 1. Vérifier la disponibilité (Check Availability)
**Endpoint:** `POST /api/availability/check`

**Sans authentification requise** ✅

**Body:**
```json
{
  "room_type_id": 1,
  "check_in": "2026-04-15",
  "check_out": "2026-04-18"
}
```

**Réponse:**
```json
{
  "available": true,
  "available_rooms": 3,
  "unavailable_rooms": 2,
  "total_rooms": 5,
  "check_in": "2026-04-15",
  "check_out": "2026-04-18",
  "hotel_info": {
    "id": 1,
    "name": "Ayla Grand Hotel"
  }
}
```

### 2. Obtenir le calendrier de disponibilité
**Endpoint:** `GET /api/rooms/{room_type_id}/available-dates?start_date=2026-04-01&end_date=2026-04-30`

**Sans authentification requise** ✅

**Paramètres query:**
- `start_date` (requis): Date de début au format YYYY-MM-DD
- `end_date` (requis): Date de fin au format YYYY-MM-DD

**Réponse:**
```json
{
  "room_type": {
    "id": 1,
    "name": "Suite Prestige",
    "price_per_night": 250.00,
    "total_rooms": 5
  },
  "start_date": "2026-04-01",
  "end_date": "2026-04-30",
  "calendar": {
    "2026-04-01": {
      "date": "2026-04-01",
      "day_of_week": "Wednesday",
      "available_rooms": 5,
      "total_rooms": 5,
      "is_available": true,
      "occupancy_rate": 0.00
    },
    "2026-04-02": {
      "date": "2026-04-02",
      "day_of_week": "Thursday",
      "available_rooms": 3,
      "total_rooms": 5,
      "is_available": true,
      "occupancy_rate": 40.00
    }
  }
}
```

## Utilisation dans l'application mobile

### Exemple 1: Avant de faire la réservation
```javascript
// Vérifier si des chambres sont disponibles
const checkAvailability = async () => {
  const response = await fetch('https://api.example.com/api/availability/check', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      room_type_id: 1,
      check_in: '2026-04-15',
      check_out: '2026-04-18'
    })
  });
  
  const data = await response.json();
  
  if (data.available) {
    console.log(`${data.available_rooms} chambre(s) disponible(s)`);
    // Afficher le formulaire de réservation
  } else {
    console.log('Aucune chambre disponible pour ces dates');
  }
};
```

### Exemple 2: Afficher un calendrier interactif
```javascript
// Charger le calendrier de disponibilité du mois
const getMonthlyCalendar = async (roomTypeId) => {
  const today = new Date();
  const startDate = today.toISOString().split('T')[0];
  const endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0)
    .toISOString().split('T')[0];
  
  const response = await fetch(
    `https://api.example.com/api/rooms/${roomTypeId}/available-dates?start_date=${startDate}&end_date=${endDate}`
  );
  
  const data = await response.json();
  
  // Afficher le calendrier avec les dates disponibles en couleur
  Object.entries(data.calendar).forEach(([date, info]) => {
    const element = document.getElementById(`date-${date}`);
    if (info.is_available) {
      element.classList.add('available');
    } else {
      element.classList.add('unavailable');
    }
    element.textContent = `${info.available_rooms}/${info.total_rooms}`;
  });
};
```

## Flux de réservation recommandé

1. **Afficher le calendrier de disponibilité**
   - Charger les dates disponibles du mois en cours
   - Permettre l'utilisateur de sélectionner check-in et check-out

2. **Vérifier la disponibilité**
   - Avant de valider la réservation, appeler l'endpoint de vérification
   - Afficher le nombre de chambres disponibles

3. **Créer la réservation**
   - Un dernier contrôle est effectué côté serveur avant la création
   - Si aucune chambre n'est disponible, l'API rejette la réservation avec un message approprié

## Logique de disponibilité

Une chambre est considérée comme **disponible** si:
- ✅ Son état est `DISPONIBLE` (pas en maintenance)
- ✅ Il n'existe pas de réservation `EN_ATTENTE` ou `CONFIRMEE` qui chevauche les dates

Une chambre est **indisponible** si:
- ❌ Son état est `OCCUPEE` ou `MAINTENANCE`
- ❌ Il existe une réservation confirmée ou en attente pour les dates demandées

## Modèle de données

### Réservation
- `date_debut`: Date de check-in
- `date_fin`: Date de check-out
- `statut`: EN_ATTENTE | CONFIRMEE | ANNULEE | TERMINEE

Les réservations annulées ou terminées ne bloquent pas les dates.
