# 🏗️ Architecture du Système de Disponibilité

## Flux utilisateur - Vérification de disponibilité

```
┌─────────────────────────────────────────────────────────────────┐
│ 1️⃣ UTILISATEUR ARRIVE SUR SITE                                   │
│    ↓                                                               │
│    Affiche liste des hôtels                                       │
│    Sélectionne type de chambre (Suite, Deluxe, Standard)          │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│ 2️⃣ CALENDRIER MENSUEL CHARGÉ                                     │
│    ↓                                                               │
│    Frontend: GET /api/rooms/{id}/available-dates                  │
│    📅 Affiche calendrier avec disponibilités par jour             │
│    🎨 Code vert = disponible, rouge = complet                     │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│ 3️⃣ UTILISATEUR SÉLECTIONNE DATES                                 │
│    ↓                                                               │
│    Click sur date d'arrivée → check_in                            │
│    Click sur date de départ → check_out                           │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│ 4️⃣ VÉRIFICATION DE DISPONIBILITÉ                                 │
│    ↓                                                               │
│    Frontend: POST /api/availability/check                         │
│    ├─ room_type_id: 1                                             │
│    ├─ check_in: 2026-04-15                                        │
│    └─ check_out: 2026-04-18                                       │
│                                                                    │
│    Backend vérifie:                                               │
│    ├─ TypeChambre existe? ✅                                      │
│    ├─ Cherche toutes les Chambres du type                         │
│    ├─ Pour chaque Chambre:                                        │
│    │  ├─ État = DISPONIBLE? ✅                                    │
│    │  └─ Aucune réservation CONFIRMEE/EN_ATTENTE? ✅              │
│    └─ Comte les disponibles: 3/5 ✅                               │
│                                                                    │
│    Response: {available: true, available_rooms: 3}                │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│ 5️⃣ AFFICHAGE DU RÉSULTAT                                         │
│    ├─ ✅ Si disponible: "3 chambres disponibles"                  │
│    │  → Bouton "Réserver" activé                                 │
│    │                                                               │
│    └─ ❌ Si pas disponible: "Aucune disponibilité"                │
│       → Bouton "Réserver" désactivé                              │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│ 6️⃣ RÉSERVATION (si disponible)                                   │
│    ↓                                                               │
│    Frontend: POST /api/bookings (avec token)                      │
│    ├─ room_id: 1                                                  │
│    ├─ check_in: 2026-04-15                                        │
│    ├─ check_out: 2026-04-18                                       │
│    └─ guests: 2                                                   │
│                                                                    │
│    Backend réserve:                                               │
│    ├─ Cherche Chambre disponible du type                          │
│    ├─ Vérifie ENCORE la disponibilité ⚠️                          │
│    ├─ Crée la réservation                                         │
│    ├─ Envoie emails                                               │
│    └─ Retourne confirmation                                       │
│                                                                    │
│    ✅ Réservation créée!                                          │
└─────────────────────────────────────────────────────────────────┘
```

---

## Architecture de base de données

```
┌──────────────────┐
│  hotels          │
├──────────────────┤
│ id               │──┐
│ nom              │  │ 1
│ user_id          │  │
└──────────────────┘  │
                      │
                   1:N├──────┐
                      │      │
                      ▼      │ N
        ┌──────────────────────────┐
        │  types_chambre           │
        ├──────────────────────────┤
        │ id                       │
        │ hotel_id (FK)            │◄──┘
        │ nom_type                 │
        │ prix_par_nuit            │
        └──────────────────────────┘
                    │ 1
                 1:N│
                    │
                    ▼ N
        ┌──────────────────────────┐
        │  chambres                │
        ├──────────────────────────┤
        │ id                       │
        │ type_id (FK)             │────┐
        │ numero                   │    │
        │ etat (DISPONIBLE,        │    │
        │       OCCUPEE,           │    │
        │       MAINTENANCE)       │    │
        └──────────────────────────┘    │
                    │ 1                 │
                 1:N│                   │
                    │                   │
                    ▼ N                 │
        ┌──────────────────────────┐   │
        │  reservations            │◄──┘
        ├──────────────────────────┤
        │ id                       │ 🔑
        │ chambre_id (FK)          │
        │ user_id (FK)             │
        │ date_debut               │ ⬅️ Cles pour vérifif
        │ date_fin                 │    chevauchement
        │ statut (EN_ATTENTE,      │
        │         CONFIRMEE,       │
        │         ANNULEE)         │
        └──────────────────────────┘
```

---

## Flux de vérification de disponibilité (détaillé)

### 1️⃣ REQUEST: POST /api/availability/check

```javascript
{
  "room_type_id": 1,
  "check_in": "2026-04-15",
  "check_out": "2026-04-18"
}
```

### 2️⃣ PROCESSING

```php
// ReservationController::checkAvailability()

// Valider les paramètres
$validated = $request->validate([
    'room_type_id' => 'required|exists:types_chambre,id',
    'check_in' => 'required|date|after_or_equal:today',
    'check_out' => 'required|date|after:check_in'
]);

// Charger le TypeChambre avec toutes ses chambres
$typeChambre = TypeChambre::with(['chambres'])->find(1);
// SELECT * FROM types_chambre WHERE id = 1
// SELECT * FROM chambres WHERE type_id = 1

// Pour chaque chambre du type
foreach ($typeChambre->chambres as $chambre) {
    
    // Vérifier conditions
    $isAvailable = (
        $chambre->etat === 'DISPONIBLE' &&  // État bon
        !$chambre->reservations()
            ->whereIn('statut', ['EN_ATTENTE', 'CONFIRMEE'])
            ->where('date_debut', '<', '2026-04-18')        // Début avant fin
            ->where('date_fin', '>', '2026-04-15')          // Fin après début
            ->exists()
    );
    // SELECT COUNT(*) FROM reservations
    // WHERE chambre_id = 101
    //   AND statut IN ('EN_ATTENTE', 'CONFIRMEE')
    //   AND date_debut < '2026-04-18'
    //   AND date_fin > '2026-04-15'
    
    if ($isAvailable) {
        $availableCount++;
    }
}

// Retourner résultat
return {
    "available": true,
    "available_rooms": 3,
    "unavailable_rooms": 2,
    "total_rooms": 5
};
```

### 3️⃣ RESPONSE

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

---

## Interaction TypeChambre - Chambre - Réservation

```
TypeChambre (Suite Prestige, 150€/nuit)
    │ 1:N
    ├── Chambre 101 ──────┐
    │       ↓ 1:N         │ Réservation{
    │   ┌─────────────────┤   chambre_id: 101,
    │   │ RES-001         │   date_debut: 2026-04-10,
    │   │ (CONFIRMEE)     │   date_fin: 2026-04-15
    │   │ 2026-04-10 até  │ }
    │   │ 2026-04-15      │
    │   └─────────────────┘
    │
    ├── Chambre 102 ──────┐
    │       ↓ 1:N         │ Réservation{
    │   ┌─────────────────┤   chambre_id: 102,
    │   │ RES-002         │   date_debut: 2026-04-05,
    │   │ (CONFIRMEE)     │   date_fin: 2026-04-12
    │   │ 2026-04-05 até  │ }
    │   │ 2026-04-12      │
    │   └─────────────────┘
    │
    ├── Chambre 103 ──────┘ Nenhuma reserva
    │
    ├── Chambre 104 ──────┐
    │       ↓ 1:N         │ Réservation{
    │   ┌─────────────────┤   chambre_id: 104,
    │   │ RES-003         │   date_debut: 2026-04-15,
    │   │ (EN_ATTENTE)    │   date_fin: 2026-04-18
    │   │ 2026-04-15 até  │ }
    │   │ 2026-04-18      │
    │   └─────────────────┘
    │
    └── Chambre 105 ──────┘ Nenhuma reserva

Recherche: 2026-04-15 até 2026-04-18
├── Chambre 101: État OK ✅ + Pas de chevauchement ✅ = DISPONIBLE ✅
├── Chambre 102: État OK ✅ + Pas de chevauchement ✅ = DISPONIBLE ✅
├── Chambre 103: État OK ✅ + Aucune réservation ✅ = DISPONIBLE ✅
├── Chambre 104: État OK ✅ + CHEVAUCHEMENT ❌ (2026-04-15 à 2026-04-18) = INDISPONIBLE ❌
└── Chambre 105: État OK ✅ + Aucune réservation ✅ = DISPONIBLE ✅

Résultat: 4 disponibles / 5 total
```

---

## Logique de chevauchement

```
Cas A: Pas de chevauchement (OK)
───────────────────────────────
Réservation existante:    [10──────15]
Dates recherchées:                      [15────20]
Résultat: PAS disponible (date_fin = date_debut)

⚠️ Note: date_fin=15, date_debut=15 → Pas de chevauchement
         (Check-out et check-in le même jour = OK)


Cas B: Chevauchement partiel (NON-OK)
──────────────────────────
Réservation existante:    [10──────15]
Dates recherchées:               [12────18]
Résultat: INDISPONIBLE


Cas C: Chevauchement complet (NON-OK)
───────────────────────────
Réservation existante:    [10──────────────20]
Dates recherchées:             [12────18]
Résultat: INDISPONIBLE


Cas D: Pas de chevauchement (OK)
─────────────────────
Réservation existante:    [10──────15]
Dates recherchées:    [05────10]
Résultat: DISPONIBLE
```

---

## Modèles et Méthodes

### Chambre.php

```php
class Chambre extends Model {
    
    // Relations existantes
    public function typeChambre(): BelongsTo
    public function reservations(): HasMany
    
    // ⭐ NOUVELLES MÉTHODES
    
    // Vérifier dispo pour plage
    public function isAvailableForDates($checkIn, $checkOut): bool {
        // État doit être DISPONIBLE
        // ET pas de réservation EN_ATTENTE/CONFIRMEE qui chevauche
    }
    
    // Dates indisponibles du mois
    public function getUnavailableDates(\DateTime $month): array {
        // Retourne array de dates indisponibles
    }
}
```

### ReservationController.php

```php
class ReservationController extends Controller {
    
    // Existante
    public function store(Request $request) {
        // Crée réservation avec double-check
    }
    
    // ⭐ NOUVELLES MÉTHODES
    
    // Vérifier dispo (public)
    public function checkAvailability(Request $request): JsonResponse {
        // Retourne: available, available_rooms, unavailable_rooms, total
    }
    
    // Calendrier (public)
    public function getAvailabilityCalendar(Request $request): JsonResponse {
        // Retourne: calendar par jour avec dispo et taux
    }
}
```

---

## Routes API

```php
// ⭐ ROUTES PUBLIQUES (pas besoin de token)
Route::post('/availability/check', [ReservationController::class, 'checkAvailability']);
Route::get('/rooms/{room_type_id}/available-dates', [ReservationController::class, 'getAvailabilityCalendar']);

// Routes existantes (protégées)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/bookings', [ReservationController::class, 'store']);
    // ...
});
```

---

## Optimisations possibles

### 1️⃣ Caching du calendrier
```php
// Premier appel: calcul + cache 30 min
// Appels suivants: depuis cache
Cache::remember("room_calendar_{$roomId}_{$month}", 30*60, fn => $this->generateCalendar());
```

### 2️⃣ Index de base de données
```sql
CREATE INDEX idx_reservations_check ON reservations(
    chambre_id, 
    statut, 
    date_debut, 
    date_fin
);

CREATE INDEX idx_chambres_state_type ON chambres(
    etat, 
    type_id
);
```

### 3️⃣ Pagination du calendrier
```
Au lieu de: Calendrier complet d'un an
Préférer: Calendrier par mois + rechargement
```

---

**Architecture simple, scalable et testée! ✅**
