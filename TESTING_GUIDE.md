# 🧪 Guide de Test - API Disponibilité

## Prérequis

- ✅ Laravel en cours d'exécution sur `http://localhost:8000`
- ✅ Base de données configurée avec des données
- ✅ Postman ou curl installé

---

## Test 1: Vérifier la disponibilité

### Avec Postman

1. **URL:** `POST` http://localhost:8000/api/availability/check
2. **Headers:**
   ```
   Content-Type: application/json
   ```
3. **Body (JSON):**
   ```json
   {
     "room_type_id": 1,
     "check_in": "2026-04-15",
     "check_out": "2026-04-18"
   }
   ```

### Avec cURL

```bash
curl -X POST http://localhost:8000/api/availability/check \
  -H "Content-Type: application/json" \
  -d '{
    "room_type_id": 1,
    "check_in": "2026-04-15",
    "check_out": "2026-04-18"
  }'
```

### Réponse attendue

```json
{
  "available": true,
  "available_rooms": 5,
  "unavailable_rooms": 0,
  "total_rooms": 5,
  "check_in": "2026-04-15",
  "check_out": "2026-04-18",
  "hotel_info": {
    "id": 1,
    "name": "Ayla Grand Hotel"
  }
}
```

---

## Test 2: Obtenir le calendrier mensuel

### Avec Postman

1. **URL:** `GET` http://localhost:8000/api/rooms/1/available-dates?start_date=2026-04-01&end_date=2026-04-30
2. **Params:**
   - `start_date`: 2026-04-01
   - `end_date`: 2026-04-30

### Avec cURL

```bash
curl -X GET "http://localhost:8000/api/rooms/1/available-dates?start_date=2026-04-01&end_date=2026-04-30"
```

### Réponse attendue (extrait)

```json
{
  "room_type": {
    "id": 1,
    "name": "Suite Prestige",
    "price_per_night": 250.0,
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
      "occupancy_rate": 0.0
    },
    "2026-04-02": {
      "date": "2026-04-02",
      "day_of_week": "Thursday",
      "available_rooms": 3,
      "total_rooms": 5,
      "is_available": true,
      "occupancy_rate": 40.0
    }
  }
}
```

---

## Test 3: Scénario complet de réservation

### Étape 1: Vérifier la disponibilité

```bash
curl -X POST http://localhost:8000/api/availability/check \
  -H "Content-Type: application/json" \
  -d '{
    "room_type_id": 1,
    "check_in": "2026-04-15",
    "check_out": "2026-04-18"
  }'
```

✅ Si `available: true` → continuer

### Étape 2: Se connecter (obtenir token)

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "password"
  }'
```

💾 Copier le token `access_token` de la réponse

### Étape 3: Créer la réservation

```bash
curl -X POST http://localhost:8000/api/bookings \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {ACCESS_TOKEN}" \
  -d '{
    "room_id": 1,
    "check_in": "2026-04-15",
    "check_out": "2026-04-18",
    "guests": 2,
    "special_requests": "Room avec vue"
  }'
```

---

## Test 4: Cas d'erreur

### Dates invalides

```bash
curl -X POST http://localhost:8000/api/availability/check \
  -H "Content-Type: application/json" \
  -d '{
    "room_type_id": 1,
    "check_in": "2026-04-18",
    "check_out": "2026-04-15"
  }'
```

✅ Erreur: `The check_out field must be after check_in.`

### Chambre inexistante

```bash
curl -X POST http://localhost:8000/api/availability/check \
  -H "Content-Type: application/json" \
  -d '{
    "room_type_id": 999,
    "check_in": "2026-04-15",
    "check_out": "2026-04-18"
  }'
```

✅ Erreur: `The room_type_id field must exist.`

### Plage de dates trop longue

```bash
curl -X GET "http://localhost:8000/api/rooms/1/available-dates?start_date=2026-01-01&end_date=2027-12-31"
```

✅ Toujours valide (la génération est relativement rapide)

---

## Collection Postman

### Importer dans Postman

Créer une nouvelle Collection avec ces requêtes:

```json
{
  "info": {
    "name": "Hotel Availability API",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "Check Availability",
      "request": {
        "method": "POST",
        "header": [
          {
            "key": "Content-Type",
            "value": "application/json"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "{\"room_type_id\": 1, \"check_in\": \"2026-04-15\", \"check_out\": \"2026-04-18\"}"
        },
        "url": {
          "raw": "http://localhost:8000/api/availability/check",
          "protocol": "http",
          "host": ["localhost"],
          "port": "8000",
          "path": ["api", "availability", "check"]
        }
      }
    },
    {
      "name": "Get Calendar",
      "request": {
        "method": "GET",
        "url": {
          "raw": "http://localhost:8000/api/rooms/1/available-dates?start_date=2026-04-01&end_date=2026-04-30",
          "protocol": "http",
          "host": ["localhost"],
          "port": "8000",
          "path": ["api", "rooms", "1", "available-dates"],
          "query": [
            {
              "key": "start_date",
              "value": "2026-04-01"
            },
            {
              "key": "end_date",
              "value": "2026-04-30"
            }
          ]
        }
      }
    }
  ]
}
```

---

## Tests CLI Laravel

### Exécuter les tests de disponibilité

```bash
php artisan test tests/Feature/Api/AvailabilityTest.php
```

### Exécuter un test spécifique

```bash
php artisan test tests/Feature/Api/AvailabilityTest.php \
  --filter test_check_availability_when_no_reservations
```

### Exécuter avec plus de verbosité

```bash
php artisan test tests/Feature/Api/AvailabilityTest.php -v
```

---

## Données de test

### TypeChambre à tester

```sql
-- Pour tester, il faut au minimum:
-- 1. Un Hotel
-- 2. Un TypeChambre
-- 3. Au moins 1 Chambre

INSERT INTO hotels (nom, user_id) VALUES ('Test Hotel', 1);
INSERT INTO types_chambre (nom_type, prix_par_nuit, hotel_id) VALUES ('Suite Test', 150, 1);
INSERT INTO chambres (numero, etat, type_id) VALUES ('101', 'DISPONIBLE', 1);
INSERT INTO chambres (numero, etat, type_id) VALUES ('102', 'DISPONIBLE', 1);
```

### Créer une réservation de test

```sql
INSERT INTO reservations (
  chambre_id, nom_client, prenom_client, email_client, 
  code_identite, date_reservation, date_debut, date_fin,
  quantite, prix_unitaire, montant_total, statut, code_reservation
) VALUES (
  1, 'Test', 'Client', 'test@example.com',
  'TEST123', NOW(), '2026-04-10', '2026-04-15',
  1, 150, 750, 'CONFIRMEE', 'RES-TEST-001'
);
```

---

## Tips & Tricks

### 1. Utiliser des variables Postman

```
{{baseUrl}}/api/availability/check

// Base URL: http://localhost:8000
```

### 2. Vérifier les erreurs de validation

```bash
curl -X POST http://localhost:8000/api/availability/check \
  -H "Content-Type: application/json" \
  -d '{}' -v
```

L'option `-v` montre les en-têtes et le statut HTTP

### 3. Formatter la réponse JSON

```bash
curl -X POST ... | jq .
```

### 4. Sauvegarder la réponse dans un fichier

```bash
curl -X POST ... > response.json
```

### 5. Tester avec authentification

```bash
TOKEN="eyJ0eXAiOiJKV1QiLCJhbGc..."
curl -X POST http://localhost:8000/api/bookings \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{...}'
```

---

## Résolution des problèmes

### Erreur: "Route not found"
```
✗ Les routes ne sont pas enregistrées
→ Vérifier que routes/api.php est correct
→ Exécuter: php artisan route:clear && php artisan route:cache
```

### Erreur: "SQLSTATE[42S22]"
```
✗ Colonne inexistante
→ Vérifier que les migrations sont executées
→ Exécuter: php artisan migrate
```

### Erreur: "Call to undefined method"
```
✗ Méthode n'existe pas
→ Vérifier que la méthode est ajoutée à la bonne classe
→ Vérifier la syntaxe PHP (paramètres wiretypes)
```

### Performance lente

```
→ Augmenter la plage de dates testées progressivement
→ Vérifier indexes sur les tables
→ Ajouter chargement avec select() / select('column1', 'column2')
```

---

**Bon test! 🚀**
