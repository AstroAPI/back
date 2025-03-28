# Projet API Symfony

Ce dépôt contient une application API REST basée sur Symfony avec support Docker pour un développement et un déploiement faciles.

## 📋 Prérequis

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)

## 🚀 Mise en route

### Cloner le dépôt

```bash
git clone <url-du-dépôt>
cd api
```

### Démarrer l'environnement Docker

```bash
# Construire et démarrer les conteneurs en mode détaché
docker compose up -d --build

# Consulter les logs si nécessaire
docker compose logs -f
```

L'application sera disponible à:
- API: https://localhost

## 🛠️ Commandes de développement

### Commandes Composer

```bash
# Installer les dépendances
docker compose exec php composer install

# Ajouter un package
docker compose exec php composer require <nom-du-package>

# Supprimer un package
docker compose exec php composer remove <nom-du-package>
```

### Commandes Symfony Console

```bash
# Lister toutes les commandes disponibles
docker compose exec php bin/console

# Vider le cache
docker compose exec php bin/console cache:clear

# Créer la base de données
docker compose exec php bin/console doctrine:database:create

# Créer une migration
docker compose exec php bin/console make:migration

# Exécuter les migrations
docker compose exec php bin/console doctrine:migrations:migrate

# Créer une nouvelle entité
docker compose exec php bin/console make:entity
```

### Accès à la base de données

```bash
# Se connecter à la base de données PostgreSQL (par défaut)
docker compose exec database psql -U app -d app
```

## 🔒 Configuration de l'environnement

- La configuration par défaut est stockée dans `.env`
- Créez un fichier `.env.local` pour des variables d'environnement personnalisées

## 📦 Déploiement

Pour le déploiement en production, utilisez la configuration de production fournie:

```bash
SERVER_NAME=votre-domaine.com \
APP_SECRET=votre-secret \
CADDY_MERCURE_JWT_SECRET=votre-clé-jwt \
docker compose -f compose.yaml -f compose.prod.yaml up -d
```

## 📋 Documentation supplémentaire

Une documentation supplémentaire est disponible dans le répertoire `docs/`:

- `docs/production.md`: Guide de déploiement en production
- `docs/mysql.md`: Utilisation de MySQL au lieu de PostgreSQL
- `docs/troubleshooting.md`: Problèmes courants et solutions
- `docs/xdebug.md`: Configuration de Xdebug
- `docs/makefile.md`: Utilisation de Makefile pour les tâches courantes

## 📝 Licence

Ce projet est sous licence MIT - voir le fichier [LICENSE](LICENSE) pour plus de détails. Symfony API Project

This repository contains a Symfony-based REST API application with Docker support for easy development and deployment.
