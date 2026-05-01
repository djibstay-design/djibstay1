# 📱 Documentation API Mobile - DjibStay

Cette documentation est destinée au développement de l'application mobile. Toutes les réponses sont au format **JSON**.

**Base URL :** `https://ton-domaine.com/api`

---

## 🔐 Authentification
L'API utilise **Laravel Sanctum**. Pour les routes protégées, vous devez envoyer le token dans le header :
`Authorization: Bearer {votre_token}`

### Inscription
- **POST** `/register`
- **Params** : `name`, `email`, `password`, `password_confirmation`, `phone` (opt)
- **Note** : Retourne le `token` et l'objet `user`. Bloqué si l'inscription est désactivée dans les réglages.

### Connexion
- **POST** `/login`
- **Params** : `email`, `password`
- **Retourne** : `token` et `user`.

### Mot de passe oublié
- **POST** `/password/forgot` : Envoie l'email de récupération. (Params: `email`)
- **POST** `/password/reset` : Réinitialise le mot de passe. (Params: `email`, `password`, `password_confirmation`, `token`)

---

## ⚙️ Configuration & Réglages

### Réglages globaux
- **GET** `/settings`
- **Description** : Récupère le nom de l'app, le logo, la devise et le **% d'acompte** configuré par l'admin.
- **Utilité** : À appeler au démarrage de l'app pour synchroniser les règles métier.

### Méthodes de paiement
- **GET** `/payment-methods`
- **Description** : Liste des portefeuilles (Waafi, D-Money) actifs et leurs instructions de paiement.

---

## 🏨 Hôtels & Disponibilités

### Liste des hôtels
- **GET** `/hotels`
- **Filtres (Query String)** : 
    - `search` : Nom de l'hôtel.
    - `city` : Ville.
    - `check_in` / `check_out` : Dates (Y-m-d). Filtre uniquement les hôtels ayant des chambres libres.
    - `sort` : `price_asc` (moins cher) ou `rating_desc` (mieux notés).

### Détails d'un hôtel
- **GET** `/hotels/{id}`
- **Retourne** : Descriptions, toutes les images, avis et types de chambres.

### Calendrier de disponibilité
- **GET** `/rooms/{room_type_id}/available-dates`
- **Params** : `start_date`, `end_date`
- **Description** : Retourne un calendrier jour par jour avec le nombre de chambres restantes.

---

## 📅 Réservations (Privé)

### Mes Réservations
- **GET** `/bookings` : Liste l'historique du client.

### Créer une réservation
- **POST** `/bookings`
- **Params (Multipart/Form-Data)** :
    - `room_id`, `check_in`, `check_out`, `guests`.
    - `photo_carte` (File) : Photo de la pièce d'identité.
    - `photo_visage` (File) : Photo selfie.
- **Retourne** : L'objet réservation avec le montant de l'acompte à payer (`deposit_amount`).

### Envoyer la preuve de paiement
- **POST** `/bookings/{reservation_id}/payment`
- **Params (Multipart/Form-Data)** :
    - `payment_method_id` : ID de la méthode choisie.
    - `screenshot` (File) : Capture d'écran du transfert.
    - `transaction_sms_code` : Code reçu par SMS.
    - `sender_name` : Nom utilisé pour le transfert.

---

## 👤 Profil & Notifications

### Mise à jour du profil (et Push)
- **PUT** `/user`
- **Params** : `name`, `phone`, `fcm_token`.
- **Note** : Envoyez le **fcm_token** de Firebase ici pour que l'utilisateur reçoive des notifications push lors du changement de statut de ses réservations.

### Laisser un avis
- **POST** `/reviews`
- **Params** : `hotel_id`, `note` (1-5), `commentaire`.

---

## 🖼️ Gestion des images
Toutes les images renvoyées par l'API sont des URLs absolues prêtes à être affichées :
`"thumbnail": "https://domaine.com/storage/hotels/photo.jpg"`
