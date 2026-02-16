# Manuel d'Installation Locale - PixelVerse Studios

Ce guide vous aidera à configurer et à lancer le projet PixelVerse Studios sur votre machine locale.

## Pré-requis

Avant de commencer, assurez-vous d'avoir installé les outils suivants :
- [Docker & Docker Compose](https://docs.docker.com/get-docker/)
- [Git](https://git-scm.com/downloads)
- [Visual Studio Code](https://code.visualstudio.com/) (Recommandé)

## Installation Rapide

1. **Cloner le dépôt** :
   ```bash
   git clone https://github.com/quentinL/PixelVerse-Website.git
   cd PixelVerse-Website
   ```

2. **Démarrer les services** :
   ```bash
   docker compose up -d --build
   ```

3. **Accéder à l'application** :
   - **Site Web** : [http://localhost:8080](http://localhost:8080)
   - **PhpMyAdmin** (SQL) : [http://localhost:8081](http://localhost:8081)
   - **Mongo Express** (NoSQL) : [http://localhost:8082](http://localhost:8082)

## Développement avec VS Code

Nous avons inclus des tâches VS Code pour vous simplifier la vie. Appuyez sur `Ctrl+Shift+P` et tapez `Tasks: Run Task` pour accéder à :
- **PixelVerse: Build & Start** : Reconstruit les images et lance les conteneurs.
- **PixelVerse: Start (Fast)** : Lance les conteneurs existants (plus rapide).
- **PixelVerse: Stop** : Arrête tous les services.
- **PixelVerse: Full Reset** : Supprime les volumes et reconstruit tout (utile en cas de corruption de DB).
- **PixelVerse: Run Tests** : Lance les tests unitaires.

## Commandes Docker

Si vous préférez la ligne de commande :
- **Lancer** : `docker compose up -d`
- **Arrêter** : `docker compose down`
- **Logs** : `docker compose logs -f`
- **Terminal PHP** : `docker compose exec www bash`

   docker compose exec www ./vendor/bin/phpunit

## Structure du Projet
- `www/` : Point d'entrée public (`index.php`, CSS, JS).
- `src/` : Code source de l'application (Controllers, Models, Router).
- `docs/` : Documentation et diagrammes.
- `docker-compose.yml` : Orchestration des conteneurs.
