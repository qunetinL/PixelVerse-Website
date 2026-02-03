# Schéma NoSQL (MongoDB) - Logs Administrateur

La collection `admin_logs` est utilisée pour tracer toutes les actions critiques effectuées par les administrateurs et employés dans le back-office.

## Collection: `admin_logs`

### Structure du Document
```json
{
  "_id": "ObjectId",
  "admin_id": "integer", // ID de l'utilisateur (SQL) ayant effectué l'action
  "action": "string",    // Type d'action (ex: "APPROVE_CHARACTER", "DELETE_USER", "UPDATE_ACCESSORY")
  "target_type": "string", // Type d'entité affectée (ex: "personnage", "utilisateur", "accessoire")
  "target_id": "integer",   // ID de l'entité affectée
  "date": "ISODate",     // Horodatage de l'action
  "details": {           // Détails variables selon l'action
    "old_value": "mixed",
    "new_value": "mixed",
    "ip_address": "string",
    "user_agent": "string"
  }
}
```

### Pourquoi MongoDB pour les Logs ?
- **Flexibilité** : Les détails des logs peuvent varier considérablement d'une action à l'autre.
- **Performance** : Les opérations d'écriture massives n'impactent pas la base relationnelle principale.
- **Scalabilité** : Facilité de stockage d'un gros volume de données historiques.
