# Guide de Déploiement - PixelVerse

Ce document détaille la procédure pour déployer l'application PixelVerse Studios sur **Fly.io**.

## 1. Prérequis
- Un compte [Fly.io](https://fly.io)
- L'outil CLI `flyctl` installé
- Les accès à **TiDB Cloud** (MySQL) et **MongoDB Atlas** (Logs)

## 2. Configuration des Secrets
Avant le premier déploiement, vous devez configurer les variables d'environnement sécurisées. Remplacez les valeurs par vos propres accès.

### Base de données MySQL (TiDB Cloud)
```bash
fly secrets set MYSQL_HOST="votre-hote.tidbcloud.com"
fly secrets set MYSQL_PORT="4000"
fly secrets set MYSQL_USER="votre-user.root"
fly secrets set MYSQL_PASSWORD="votre-password"
fly secrets set MYSQL_DATABASE="pixelverse"
fly secrets set MYSQL_SSL="true"
```

### Base de données NoSQL (MongoDB Atlas)
```bash
fly secrets set MONGO_URL="mongodb+srv://user:password@cluster.mongodb.net/pixelverse"
```

## 3. Déploiement
Lancez simplement la commande suivante à la racine du projet :
```bash
fly deploy
```

L'application sera construite via le `Dockerfile` et déployée sur les serveurs de Paris (`cdg`).

## 4. Initialisation de la Base de Données
Pour créer les tables en production (TiDB Cloud), exécutez le script d'initialisation via la console SSH de Fly.io :
```bash
fly ssh console -C "php /var/www/html/init_db.php"
```
*Cette commande va exécuter le contenu de `database.sql` sur la base de production.*

## 5. Base de données Locale vs Production
- **Local** : L'application utilise les conteneurs Docker `db` et `mongo` définis dans `docker-compose.yml`.
- **Production** : L'application utilise les secrets configurés ci-dessus pour se connecter aux services managés (TiDB et Atlas).
