# 📋 API Plans d'Abonnement (Subscription Plans)

## 📌 Vue d'ensemble

Cette API gère la création, la lecture, la modification et la suppression des plans d'abonnement fitness.

---

## 🔗 Endpoints

### 1️⃣ **GET /api/plans** - Récupérer tous les plans
**Accès :** Public

```bash
curl -X GET http://localhost:8000/api/plans
```

**Réponse (200 OK):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Basic",
            "description": "Plan basique",
            "duration_months": 1,
            "price": 50000,
            "features": "Accès 24/7",
            "is_active": true,
            "created_at": "2026-01-16T10:00:00Z",
            "updated_at": "2026-01-16T10:00:00Z"
        }
    ],
    "message": "Plans récupérés avec succès"
}
```

---

### 2️⃣ **GET /api/plans/{id}** - Récupérer un plan spécifique
**Accès :** Public

```bash
curl -X GET http://localhost:8000/api/plans/1
```

**Réponse (200 OK):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Premium",
        "description": "Accès illimité à tous les équipements et cours",
        "duration_months": 6,
        "price": 150000,
        "features": "Accès illimité, 2 séances coaching/mois, Vestiaire privé, Parking gratuit",
        "is_active": true,
        "created_at": "2026-01-16T10:00:00Z",
        "updated_at": "2026-01-16T10:00:00Z"
    },
    "message": "Plan trouvé"
}
```

**Erreur (404 Not Found):**
```json
{
    "success": false,
    "message": "Plan non trouvé"
}
```

---

### 3️⃣ **POST /api/plans** - Créer un plan (Admin)
**Accès :** Administrateur uniquement

```bash
curl -X POST http://localhost:8000/api/plans \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Premium",
    "description": "Accès illimité à tous les équipements et cours",
    "duration_months": 6,
    "price": 150000,
    "features": "Accès illimité, 2 séances coaching/mois, Vestiaire privé, Parking gratuit",
    "is_active": true
}'
```

**Corps de la requête (Body):**
```json
{
    "name": "Premium",
    "description": "Accès illimité à tous les équipements et cours",
    "duration_months": 6,
    "price": 150000,
    "features": "Accès illimité, 2 séances coaching/mois, Vestiaire privé, Parking gratuit",
    "is_active": true
}
```

**Réponse (201 Created):**
```json
{
    "success": true,
    "data": {
        "id": 5,
        "name": "Premium",
        "description": "Accès illimité à tous les équipements et cours",
        "duration_months": 6,
        "price": 150000,
        "features": "Accès illimité, 2 séances coaching/mois, Vestiaire privé, Parking gratuit",
        "is_active": true,
        "created_at": "2026-01-16T12:30:00Z",
        "updated_at": "2026-01-16T12:30:00Z"
    },
    "message": "Plan créé avec succès"
}
```

**Erreur validation (422 Unprocessable Entity):**
```json
{
    "success": false,
    "message": "Erreurs de validation",
    "errors": {
        "name": ["Le champ name est requis"],
        "price": ["Le champ price doit être supérieur à 0"]
    }
}
```

---

### 4️⃣ **PUT /api/plans/{id}** - Modifier un plan (Admin)
**Accès :** Administrateur uniquement

```bash
curl -X PUT http://localhost:8000/api/plans/1 \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Premium Plus",
    "price": 200000
}'


*Corps de la requête (Body):
json
{
    "name": "Premium Plus",
    "price": 200000
}


Réponse (200 OK):*
json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Premium Plus",
        "description": "Accès illimité à tous les équipements et cours",
        "duration_months": 6,
        "price": 200000,
        "features": "Accès illimité, 2 séances coaching/mois, Vestiaire privé, Parking gratuit",
        "is_active": true,
        "created_at": "2026-01-16T10:00:00Z",
        "updated_at": "2026-01-16T12:35:00Z"
    },
    "message": "Plan modifié avec succès"
}


---

 **DELETE /api/plans/{id}* - Supprimer un plan (Admin)
*Accès :** Administrateur uniquement

```bash
curl -X DELETE http://localhost:8000/api/plans/1


*Réponse (200 OK):
json
{
    "success": true,
    "message": "Plan supprimé avec succès"
}
```

*Erreur (404 Not Found):**
```json
{
    "success": false,
    "message": "Plan non trouvé"
}
```

---

#Validation des champs

| Champ | Type | Règles |
|-------|------|--------|
| `name` | string | Requis, max 255 caractères, unique |
| `description` | string | Requis |
| `duration_months` | integer | Requis, minimum 1 mois |
| `price` | numeric | Requis, minimum 0 |
| `features` | string | Optionnel |
| `is_active` | boolean | Optionnel (défaut: false) |

---

#Contrôle d'accès

- *GET /api/plans** : ✅ Public
- *GET /api/plans/{id}** : ✅ Public
- *POST /api/plans** : 🔒 Admin
- *PUT /api/plans/{id}** : 🔒 Admin
- *DELETE /api/plans/{id}** : 🔒 Admin

---

#Exemple complet - Postman

Importez le fichier `Postman_Collection.json` dans Postman pour avoir tous les endpoints prêts à tester.

---

#Gestion des erreurs

| Code | Signification |
|------|---------------|
| `200` | OK - Requête réussie |
| `201` | Created - Ressource créée |
| `404` | Not Found - Plan non trouvé |
| `422` | Unprocessable Entity - Erreurs de validation |
| `500` | Server Error - Erreur serveur |

