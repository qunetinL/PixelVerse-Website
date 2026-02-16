# 🎮 PixelVerse Studios - Système de Gestion de Personnages

Bienvenue dans le dépôt officiel du projet **PixelVerse Studios**. Cette application est une plateforme complète de personnalisation de personnages pour le MMORPG *FantasyRealm Online*.

## 🌐 Liens Rapides
- **Site en Ligne** : [https://pixelverse-website.fly.dev/](https://pixelverse-website.fly.dev/)
- **Gestion de Projet** : [Tableau Trello / Kanban](file:///home/iamacat/Documents/GitHub/PixelVerse-Website/docs/Pdf/PROJECT_MGMT.pdf)
- **Documentation Technique** : [Technical Documentation (PDF)](file:///home/iamacat/Documents/GitHub/PixelVerse-Website/docs/Pdf/TECH_DOC.pdf)

## 📦 Livrables ECF (Dossier `docs/Pdf`)
Le jury trouvera l'ensemble des documents requis dans le dossier [docs/Pdf](docs/Pdf) :
1.  **[Manuel d'Utilisation](docs/Pdf/MANUEL_UTILISATEUR.pdf)** : Navigation et comptes de test.
2.  **[Documentation Technique](docs/Pdf/TECH_DOC.pdf)** : Architecture MVC, UML (Cas d'usage, Séquence), MCD et Sécurité.
3.  **[Charte Graphique](docs/Charte%20Graphique/Charte%20Graphique.pdf)** : Mockups (Bureau/Mobile), Couleurs et Typographie.
4.  **[Gestion de Projet](docs/Pdf/PROJECT_MGMT.pdf)** : Méthodologie Agile et suivi Trello.
5.  **[Installation Locale](docs/Pdf/INSTALLATION.pdf)** : Guide Docker.

## 🛠️ Stack Technique
- **Front-End** : HTML5, CSS3 (Design "Zen", Responsive, Accessible RGAA).
- **Back-End** : PHP 8.2 Natif (Architecture MVC).
- **Données SQL** : MySQL 8.0 (TiDB Cloud en production).
- **Données NoSQL** : MongoDB 6.0 (Atlas en production pour les Logs).
- **Tests** : Suite de tests PHPUnit intégrée.

## 🚀 Installation Locale Express
```bash
git clone https://github.com/quentinL/PixelVerse-Website.git
cd PixelVerse-Website
docker compose up -d --build
```
*Accès : http://localhost:8080*

---
*© 2026 PixelVerse Studios*
