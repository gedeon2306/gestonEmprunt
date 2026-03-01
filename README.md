# Gestion d'Emprunt - Système de gestion des avances salariales

Application web Laravel permettant aux entreprises de gérer les demandes d'emprunt (avances sur salaire) de leurs salariés. Le système offre deux espaces distincts : un espace **Entreprise** pour l'administration et un espace **Salarié** pour les demandes d'emprunt.

## Table des matières

- [Prérequis](#prérequis)
- [Installation](#installation)
- [Fonctionnalités](#fonctionnalités)
- [Structure du projet](#structure-du-projet)
- [Technologies utilisées](#technologies-utilisées)

---

## Prérequis

- **PHP** >= 8.2
- **Composer**
- **Laravel** 12.x
- Base de données (MySQL, PostgreSQL, SQLite)

---

## Installation

```bash
# Cloner le projet
git clone <url-du-repo>
cd gestionEmprunt

# Installer les dépendances
composer install

# Configurer l'environnement
cp .env.example .env
php artisan key:generate

# Configurer la base de données dans .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_DATABASE=gestion_emprunt
# DB_USERNAME=root
# DB_PASSWORD=

# Exécuter les migrations
php artisan migrate

# Lancer le serveur
php artisan serve
```

L'application sera accessible à l'adresse : `http://localhost:8000`

---

## Fonctionnalités

### Page d'accueil

- **Choix du type d'utilisateur** : L'utilisateur peut accéder soit à l'espace **Entreprise** (gestion administrative), soit à l'espace **Salarié** (demandes d'emprunt).

---

### Espace Entreprise

#### Authentification

- **Inscription** : Création d'un compte entreprise avec nom, email et mot de passe (hashé).
- **Connexion** : Authentification par email et mot de passe.
- **Déconnexion** : Fermeture de session et redirection vers la page d'authentification.

#### Gestion du profil entreprise

- **Modification des informations** : Mise à jour du nom et de l'email (avec vérification du mot de passe actuel).
- **Changement de mot de passe** : Modification du mot de passe avec confirmation (déconnexion automatique après changement).
- **Suppression du compte** : Suppression définitive de l'entreprise et de toutes ses données associées.

#### Tableau de bord (Dashboard)

- **Statistiques** : Nombre total de départements et de salariés.
- **Graphiques dynamiques** :
  - Graphique en barres : Montant total emprunté par département.
  - Graphique en donut : Répartition des salariés par département.
- **Données en temps réel** : Les graphiques sont alimentés dynamiquement selon les données de l'entreprise.

---

### Gestion des départements

- **Liste des départements** : Affichage de tous les départements de l'entreprise.
- **Création** : Ajout d'un département avec nom et chef de département.
- **Modification** : Mise à jour du nom et du chef de département.
- **Suppression** : Suppression d'un département (avec cascade sur les salariés).
- **Détail** : Visualisation des salariés d'un département avec accès à la gestion des employés.

---

### Gestion des employés (Salariés)

- **Création** : Ajout d'un salarié avec :
  - Nom complet
  - Téléphone (unique)
  - Genre (M/F/A)
  - Seuil d'emprunt (montant maximum autorisé)
  - PIN généré automatiquement (4 chiffres)
- **Modification** : Mise à jour des informations du salarié (nom, téléphone, genre, seuil, PIN).
- **Suppression** : Suppression d'un salarié (mise à jour automatique du compteur de salariés du département).
- **Réinitialisation du solde** : Remise du solde disponible (`reste`) au seuil maximum (remboursement simulé).
- **Migration** : Transfert d'un salarié vers un autre département de l'entreprise.
- **Activation/Désactivation** : Blocage ou déblocage du compte d'un salarié (empêche les demandes d'emprunt).
- **Historique des emprunts** : Consultation de tous les emprunts effectués par un salarié avec le montant total emprunté.

---

### Espace Salarié - Demandes d'emprunt

#### Identification

- **Vérification par téléphone** : Le salarié saisit son numéro de téléphone pour accéder à son espace emprunt.
- **Validation** : Vérification que le numéro existe dans la base de données.

#### Processus d'emprunt

1. **Demande** : Saisie du montant souhaité.
2. **Vérification des limites** :
   - Le montant ne doit pas dépasser le solde disponible (`reste`).
   - Le compte ne doit pas être désactivé.
3. **Confirmation** : Saisie du PIN à 4 chiffres pour valider l'emprunt.
4. **Sécurité PIN** :
   - 2 tentatives maximum en cas de PIN incorrect.
   - Blocage automatique du compte après 2 échecs (nécessite l'intervention du superviseur).
5. **Succès** : Affichage d'une page de confirmation avec le montant emprunté.
6. **Mise à jour** : Le solde disponible (`reste`) est automatiquement déduit.

#### Déconnexion

- Le salarié peut se déconnecter de l'espace emprunt pour revenir à la page d'identification.

---

### Sécurité et Middlewares

- **CheckAuth** : Protège les routes de l'espace entreprise. Redirige vers la page d'authentification si l'utilisateur n'est pas connecté.
- **CheckTel** : Protège les routes d'emprunt. Vérifie que le salarié a validé son numéro de téléphone avant d'accéder aux formulaires d'emprunt.

---

### Modèle de données

| Entité | Champs principaux |
|--------|-------------------|
| **Entreprise** | nomEntreprise, email, password |
| **Département** | nomDepartement, chefDepartement, nbSalarier, entreprise_id |
| **Employé** | nomComplet, telephone, genre, seuil, reste, pin, counter, departement_id |
| **Emprunt** | motant, employer_id, created_at |

---

## Structure du projet

```
gestionEmprunt/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── EntrepriseController.php
│   │   │   ├── DepartementController.php
│   │   │   ├── EmployerController.php
│   │   │   └── EmpruntController.php
│   │   └── Middleware/
│   │       ├── CheckAuth.php
│   │       └── CheckTel.php
│   └── Models/
│       ├── Entreprise.php
│       ├── Departement.php
│       ├── Employer.php
│       └── Emprunt.php
├── resources/views/
│   ├── welcome.blade.php          # Page d'accueil
│   ├── authForm.blade.php         # Authentification entreprise
│   ├── dashboard.blade.php        # Tableau de bord
│   ├── gestionDepartement.blade.php
│   ├── gestionEmployer.blade.php
│   ├── historique.blade.php       # Historique des emprunts
│   ├── emprunt-login.blade.php    # Identification salarié
│   ├── loan-request.blade.php     # Formulaire de demande
│   ├── loan-confirmation.blade.php # Confirmation avec PIN
│   └── loan-success.blade.php     # Page de succès
└── routes/
    └── web.php
```

---

## Technologies utilisées

- **Framework** : Laravel 12
- **PHP** : 8.2+
- **Base de données** : MySQL / PostgreSQL / SQLite
- **Frontend** : Blade, CSS, JavaScript
- **Graphiques** : Chart.js
- **Notifications** : SweetAlert2

---

## Licence

Ce projet est sous licence MIT.
