# PixelVerse Studios

## Stack Technique

- **Back-End** : PHP 8.2
- **Base de Données Relationnelle** : MySQL 8.0
- **Base de Données NoSQL** : MongoDB 6.0 (Logs & Audit)
- **Front-End** : HTML5, CSS3, Bootstrap 5
- **Environnement** : Docker & Docker Compose

## Installation

### Pré-requis
- Docker
- Git

### Lancement Rapide

1. Cloner le dépôt :
   ```bash
   git clone https://github.com/quentinL/PixelVerse-Website.git
   cd PixelVerse-Website
   ```

2. Démarrer l'environnement :
   ```bash
   docker-compose up -d --build
   ```

3. Accéder à l'application :
   - **Site Web** : [http://localhost:8080](http://localhost:8080)
   - **PhpMyAdmin** (SQL) : [http://localhost:8081](http://localhost:8081)
   - **Mongo Express** (NoSQL) : [http://localhost:8082](http://localhost:8082)

### Commandes

- **Lancer le projet** :
  ```bash
  docker-compose up -d --build
  ```

- **Arrêter les conteneurs** :
  ```bash
  docker-compose down
  ```

- **Voir les logs en temps réel** :
  ```bash
  docker-compose logs -f
  ```

- **Accéder au terminal du conteneur PHP** :
  ```bash
  docker-compose exec www bash
  ```

## Structure du Projet

- `www/` : Code source PHP/HTML
- `docker-compose.yml` : Configuration des conteneurs
- `Dockerfile` : Configuration personnalisée de l'image PHP
