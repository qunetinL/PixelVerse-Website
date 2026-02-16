# Gestion de Projet - PixelVerse

## 1. Méthodologie
Le projet a été mené en suivant une approche hybride, principalement inspirée de la méthodologie **Agile (Scrum/Kanban)** pour permettre une flexibilité maximale lors du développement des fonctionnalités.

## 2. Outils de suivi
- **Trello** : Utilisé pour le backlog produit et le suivi des sprints.
- **Git/GitHub** : Utilisé pour le versioning, avec une stratégie de branches (feature branching).
- **Checklists (task.md / ROADMAP.txt)** : Utilisées pour le suivi quotidien et la validation des étapes clés (ECF).

## 3. Découpage du Projet
Le projet a été divisé en 6 phases majeures :
1. **Initialisation** : Architecture MVC, Router, Autoloader.
2. **Design System** : Charte graphique "Zen", Responsive Design, Accessibilité.
3. **Core Features** : Authentification, CRUD Personnages, Gestion des Accessoires.
4. **Modération** : Dashboards Employé/Admin, Système de validation d'avis.
5. **Advanced logic** : Logging NoSQL (MongoDB), Duplication de personnages.
6. **Finalisation** : Sécurité (CSRF/XSS), RGPD, Déploiement Fly.io et Documentation.

## 4. Gestion des Risques
- **Retard technique** : Pallié par l'utilisation de PHP Natif (maîtrise totale du code).
- **Sécurité** : Audit régulier des entrées/sorties et utilisation de standards reconnus (Argon2id).
- **Disponibilité BDD** : Utilisation de services Cloud (TiDB, Atlas) pour garantir le fonctionnement en production.
