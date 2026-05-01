# 🏨 Système de Gestion de Disponibilité des Chambres

## 📋 Résumé des modifications

Ce projet a été amélioré avec un **système complet de vérification et affichage de disponibilité des chambres**. Les clients peuvent maintenant voir les disponibilités **AVANT de faire une réservation**.

## ✨ Fonctionnalités ajoutées

### 1. **Routes API publiques** (sans authentification)
- ✅ `POST /api/availability/check` - Vérifier la disponibilité pour des dates
- ✅ `GET /api/rooms/{room_type_id}/available-dates?start_date=...&end_date=...` - Obtenir le calendrier mensuel

### 2. **Modèle Chambre amélioré**
- ✅ `isAvailableForDates()` - Vérifier disponibilité pour une plage
- ✅ `getUnavailableDates()` - Obtenir les dates indisponibles du mois

### 3. **Interface utilisateur**
- 📅 Calendrier interactif avec visualisation de l'occupation
- 🎨 Styles CSS modernes et responsifs
- ⚙️ Service JavaScript pour l'intégration facile

## 🚀 Comment utiliser

### Option 1: Vérifier la disponibilité (avant de réserver)

```bash
curl -X POST http://localhost:8000/api/availability/check \
  -H "Content-Type: application/json" \
  -d '{
    "room_type_id": 1,
    "check_in": "2026-04-15",
    "check_out": "2026-04-18"
  }'
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

### Option 2: Afficher le calendrier mensuel

```bash
curl "http://localhost:8000/api/rooms/1/available-dates?start_date=2026-04-01&end_date=2026-04-30"
```

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

## 📂 Fichiers modifiés

### Controllers
- **[app/Http/Controllers/Api/ReservationController.php](app/Http/Controllers/Api/ReservationController.php)**
  - ✨ Ajout de `checkAvailability()` 
  - ✨ Ajout de `getAvailabilityCalendar()`

### Models
- **[app/Models/Chambre.php](app/Models/Chambre.php)**
  - ✨ Ajout de `isAvailableForDates()`
  - ✨ Ajout de `getUnavailableDates()`

### Routes
- **[routes/api.php](routes/api.php)**
  - ✨ Ajout de 2 routes publiques pour la disponibilité

### Documentation et Exemples
- **[AVAILABILITY_API.md](AVAILABILITY_API.md)** - Documentation complète des APIs
- **[AVAILABILITY_EXAMPLE.js](AVAILABILITY_EXAMPLE.js)** - Classe JavaScript pour l'intégration
- **[AVAILABILITY_STYLES.css](AVAILABILITY_STYLES.css)** - Styles CSS du calendrier
- **[tests/Feature/Api/AvailabilityTest.php](tests/Feature/Api/AvailabilityTest.php)** - Tests unitaires

## 🔍 Logique de disponibilité

### Une chambre est DISPONIBLE si:
- ✅ État = `DISPONIBLE` (pas en maintenance)
- ✅ Pas de réservation `EN_ATTENTE` ou `CONFIRMEE` qui chevauche les dates

### Une chambre est INDISPONIBLE si:
- ❌ État = `OCCUPEE` ou `MAINTENANCE`
- ❌ Réservation `EN_ATTENTE` ou `CONFIRMEE` qui chevauche les dates

### Réservations ignorées:
- Les réservations `ANNULEE` ou `TERMINEE` ne bloquent pas

## 💻 Intégration dans votre app

### 1. Charger le calendrier du mois en cours
```javascript
const service = new AvailabilityService('https://api.example.com');
const calendar = await service.getCurrentMonthCalendar(1); // room_type_id = 1
```

### 2. Vérifier avant de réserver
```javascript
const availability = await service.checkAvailability(1, '2026-04-15', '2026-04-18');
if (availability.available) {
  // Afficher le formulaire de réservation
}
```

### 3. Afficher le UI du calendrier
```javascript
const calendar = new ReservationCalendarUI(1, '#calendar-container', 'https://api.example.com');
await calendar.render();
```

## 🧪 Tests

Exécuter les tests:
```bash
php artisan test tests/Feature/Api/AvailabilityTest.php
```

Les tests couvrent:
- ✅ Vérification de disponibilité sans réservation
- ✅ Vérification avec réservations confirmées
- ✅ Calendrier mensuel
- ✅ Dates indisponibles
- ✅ Chevauchement de dates

## 📊 Taux d'occupation

Le calendrier affiche un **taux d'occupation** pour chaque jour:
- 🟢 **0-25%** - Peu d'occupants (vert)
- 🟢 **25-50%** - Occupation modérée (vert clair)
- 🟠 **50-75%** - Occupation élevée (orange)
- 🔴 **75-100%** - Presque complet (rouge)

## 🎯 Flux utilisateur recommandé

1. **L'utilisateur arrive sur la page de réservation**
   → Afficher le calendrier du mois en cours

2. **L'utilisateur sélectionne les dates**
   → Vérifier la disponibilité
   → Afficher le nombre de chambres disponibles

3. **L'utilisateur confirme**
   → Créer la réservation
   → Un dernier contrôle côté serveur valide la réservation

## 🔐 Sécurité

- ✅ Les routes de vérification sont **publiques** (pas de token requis)
- ✅ Les réservations sont **protégées** (authentification requise)
- ✅ Validation stricte des dates
- ✅ Double vérification côté serveur avant création

## ⚠️ Notes importantes

1. **Les réservations en attente bloquent les dates**
   - Les réservations `EN_ATTENTE` sont considérées comme confirmées pour la vérification

2. **Pas de demi-jours**
   - Un jour est soit disponible soit occupé (check-out et check-in le même jour = possible)

3. **Prix par nuit**
   - Les prix viennent du TypeChambre
   - Le calcul du montant = prix × nombre de nuits × nombre de clients

## 📞 Support

Pour toute question ou problème, consultez:
- [AVAILABILITY_API.md](AVAILABILITY_API.md) - Documentation technique
- [AVAILABILITY_EXAMPLE.js](AVAILABILITY_EXAMPLE.js) - Exemples d'intégration
- [tests/Feature/Api/AvailabilityTest.php](tests/Feature/Api/AvailabilityTest.php) - Tests

---

**Version:** 1.0  
**Date:** 2026-04-12  
**Statut:** ✅ Production ready
