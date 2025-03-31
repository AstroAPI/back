# AstroAPI - Système de gestion des données astronomiques et horoscopes

## 📝 À propos

AstroAPI est une interface de programmation d'application RESTful conçue pour fournir des données astronomiques, météorologiques et des horoscopes personnalisés. L'API offre un accès sécurisé aux données avec authentification JWT et intègre plusieurs fonctionnalités avancées comme la mise en cache Redis et la documentation OpenAPI.

## 🚀 Fonctionnalités

- 🔒 Authentification JWT sécurisée
- 🌍 Données météorologiques en temps réel avec OpenWeatherMap
- 🪐 Horoscopes personnalisés basés sur les conditions météorologiques
- 📊 Mise en cache optimisée avec Redis
- 📄 Documentation API complète avec Swagger/OpenAPI
- 🧪 Tests unitaires et fonctionnels
- 🖼️ Gestion de médias personnalisée

## 🛠️ Technologies utilisées

- Symfony 6.4
- PHP 8.3
- Docker & Docker Compose
- PostgreSQL
- Redis
- Swagger/OpenAPI

## 📋 Prérequis

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Git](https://git-scm.com/downloads)

## 🚀 Mise en route rapide

### Cloner le dépôt

```bash
git clone https://github.com/AstroAPI/back.git
cd back
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

## 🔧 Guide de configuration étape par étape

Suivez ces étapes pour configurer complètement votre environnement de développement:

### 1. Configuration initiale

```bash
# Copier le fichier d'environnement
cp .env .env.local

# Éditer le fichier .env.local avec vos variables
nano .env.local
# ou avec votre éditeur préféré
# code .env.local
# vim .env.local
```

### 2. Installation des dépendances

```bash
# Installer toutes les dépendances PHP via Composer
docker compose exec php composer install

# En cas d'erreur de permissions
docker compose exec php chown -R www-data:www-data var/
```

### 3. Configuration de la base de données

```bash
# Créer la base de données si elle n'existe pas
docker compose exec php bin/console doctrine:database:create --if-not-exists

# Exécuter les migrations pour créer le schéma
docker compose exec php bin/console doctrine:migrations:migrate --no-interaction

# Charger les données initiales (fixtures)
docker compose exec php bin/console doctrine:fixtures:load --no-interaction
```

### 4. Configuration de JWT (pour l'authentification)

```bash
# Générer les clés JWT
docker compose exec php bin/console lexik:jwt:generate-keypair

# Vérifier que les clés ont bien été générées
docker compose exec php ls -la config/jwt/
```

### 5. Configuration de Redis pour le cache

```bash
# Vérifier la connexion à Redis
docker compose exec php bin/console redis:flushall

# Tester le cache
docker compose exec php bin/console cache:pool:clear cache.app
```

### 6. Configuration de la documentation API

```bash
# Installer le bundle NelmioApiDoc si ce n'est pas déjà fait
docker compose exec php composer require nelmio/api-doc-bundle

# Vider le cache après installation
docker compose exec php bin/console cache:clear
```

### 7. Vérification de l'installation

```bash
# Vérifier que tous les services sont en bonne santé
docker compose ps

# Tester l'API avec une requête simple
curl http://localhost/api/doc
```

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

# Exécuter une requête SQL directement
docker compose exec database psql -U app -d app -c "SELECT * FROM users LIMIT 5;"
```

### Gestion du cache Redis

```bash
# Vider le cache Redis
docker compose exec php bin/console redis:flushall

# Vérifier les clés en cache
docker compose exec redis redis-cli keys "*"
```

## 🧪 Tests

Exécuter les tests unitaires :

```bash
./run-tests.sh --unit
```

Exécuter les tests fonctionnels :

```bash
./run-tests.sh --functional
```

Générer un rapport de couverture de code :

```bash
./run-tests.sh --unit --coverage
```

## 📡 Utilisation de l'API

### Authentification

```bash
# Obtenir un token JWT
curl -X POST -H "Content-Type: application/json" -d '{"email":"admin@example.com","password":"admin123"}' http://localhost/api/login_check

# Utiliser le token dans les requêtes
curl -X GET -H "Authorization: Bearer VOTRE_TOKEN" http://localhost/api/horoscope/Bélier?city=Paris
```

### Exemples d'endpoints

- `GET /api/horoscope/list` - Liste tous les signes du zodiaque disponibles
- `GET /api/horoscope/{sign}?city={city}` - Obtenir l'horoscope pour un signe spécifique et une ville
- `GET /api/me` - Obtenir les informations de l'utilisateur authentifié
- `GET /api/users` (admin) - Lister tous les utilisateurs
- `GET /api/meteo/{city}` - Obtenir les données météorologiques pour une ville

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

## ⚠️ Résolution des problèmes courants

### Problèmes de permissions

```bash
# Si vous rencontrez des problèmes de permissions avec le dossier var/
docker compose exec php chown -R www-data:www-data var/
```

### Problèmes de cache

```bash
# Si l'application se comporte de façon inattendue
docker compose exec php bin/console cache:clear
```

### Problèmes de connexion à la base de données

```bash
# Vérifier que le service de base de données fonctionne
docker compose ps database

# Vérifier les logs de la base de données
docker compose logs database
```

## 📋 Documentation supplémentaire

Une documentation supplémentaire est disponible dans le répertoire `docs/`:

- `docs/production.md`: Guide de déploiement en production
- `docs/mysql.md`: Utilisation de MySQL au lieu de PostgreSQL
- `docs/troubleshooting.md`: Problèmes courants et solutions
- `docs/xdebug.md`: Configuration de Xdebug
- `docs/makefile.md`: Utilisation de Makefile pour les tâches courantes

## 📝 Licence

Ce projet est sous licence MIT - voir le fichier LICENSE pour plus de détails.