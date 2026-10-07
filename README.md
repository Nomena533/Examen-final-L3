# BiblioTech

Application web de gestion d'une bibliothèque municipale : catalogue de livres, adhérents et suivi des emprunts. Développée avec **Laravel**, **Blade**, **Tailwind CSS** et **Font Awesome**.

> Projet d'examen final L3 — sujet « BiblioTech ».

---

## Sommaire

1. [Fonctionnalités](#fonctionnalités)
2. [Règles de gestion](#règles-de-gestion)
3. [Technologies](#technologies)
4. [Installation](#installation)
5. [Fonctionnement hors ligne](#fonctionnement-hors-ligne)
6. [Structure du projet](#structure-du-projet)
7. [Modèle de données](#modèle-de-données)
8. [Routes](#routes)
9. [Pistes d'amélioration](#pistes-damélioration)

---

## Fonctionnalités

### Authentification

- Inscription d'un bibliothécaire (nom, e-mail, mot de passe).
- Connexion / déconnexion, avec option « se souvenir de moi » et redirection vers la page demandée initialement.
- Pages d'authentification centrées dans un cadre, dans le même style que le reste de l'application.

### Tableau de bord

- Statistiques : nombre de livres, nombre d'adhérents, emprunts en cours, nombre de retards.
- Liste des emprunts en retard, triés du plus ancien au plus récent.

### Livres

- Catalogue paginé (10 par page), trié par titre.
- Recherche par **titre** ou **auteur**.
- Filtre par **catégorie** et filtre « **disponibles uniquement** ».
- Ajout, modification et suppression d'un livre.

### Adhérents

- Liste paginée (15 par page), triée par nom puis prénom, avec le nombre d'emprunts en cours de chacun.
- Inscription, modification et suppression d'un adhérent.
- Fiche adhérent avec l'historique complet de ses emprunts.

### Emprunts

- Liste des emprunts en cours, triés par date de retour prévue.
- Enregistrement d'un nouvel emprunt (seuls les livres disponibles sont proposés).
- Enregistrement du **retour** d'un livre.
- **Export CSV** des emprunts en cours (`emprunts-en-cours.csv`, séparateur `;`, encodage UTF-8 avec BOM pour une ouverture directe dans Excel).

---

## Règles de gestion

| Domaine     | Règle                                                                                                                                                                                      |
| ----------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| Emprunt     | Un livre ne peut être emprunté que s'il reste au moins un exemplaire disponible.                                                                                                           |
| Emprunt     | Un adhérent ne peut pas avoir plus de **3 emprunts en cours**.                                                                                                                             |
| Emprunt     | Un adhérent ayant un emprunt **en retard** ne peut pas emprunter tant que ce livre n'a pas été rendu.                                                                                      |
| Emprunt     | La date de retour prévue doit être postérieure ou égale à la date d'emprunt.                                                                                                               |
| Stock       | Un emprunt décrémente `quantite_disponible` ; un retour l'incrémente.                                                                                                                      |
| Stock       | À la création d'un livre, `quantite_disponible` = `quantite_totale`.                                                                                                                       |
| Stock       | Lors de la modification d'un livre, la quantité totale ne peut pas être inférieure au nombre d'exemplaires actuellement empruntés ; la quantité disponible est recalculée en conséquence.  |
| Retard      | Un emprunt est en retard si le livre n'est pas rendu et que la date de retour prévue est passée.                                                                                           |
| Suppression | Un livre ou un adhérent possédant un **historique d'emprunts** ne peut pas être supprimé.                                                                                                  |
| Unicité     | L'ISBN d'un livre et l'e-mail d'un adhérent sont uniques.                                                                                                                                  |
| Intégrité   | Les opérations d'emprunt et de retour s'exécutent dans une **transaction** avec verrouillage des lignes (`lockForUpdate`) pour éviter les incohérences de stock en cas d'accès simultanés. |

---

## Technologies

- **PHP** 8.3+ et **Laravel** (version récente)
- **MySQL** (ou tout autre SGBD supporté par Laravel, à configurer dans `.env`)
- **Blade** pour les vues
- **Tailwind CSS** via **Vite**
- **Font Awesome** (hébergé localement)
- Polices **Public Sans** et **Source Serif 4** (hébergées localement)

---

## Installation

### Prérequis

- PHP 8.3 ou supérieur, avec les extensions habituelles de Laravel
- Composer
- Node.js et npm
- Un serveur de base de données (MySQL par exemple)

### Étapes

```bash
# 1. Récupérer le projet puis installer les dépendances
composer install
npm install

# 2. Configurer l'environnement
cp .env.example .env
php artisan key:generate
```

Éditer ensuite le fichier `.env` pour renseigner la base de données :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bibliotech
DB_USERNAME=root
DB_PASSWORD=
```

Créer la base `bibliotech`, puis :

```bash
# 3. Créer les tables
php artisan migrate

# 4. Compiler les assets (Tailwind)
npm run build        # production
# ou
npm run dev          # développement, avec rechargement à chaud

# 5. Lancer l'application
php artisan serve
```

L'application est alors disponible sur <http://127.0.0.1:8000>. Créer un compte depuis la page **Inscription**, puis se connecter.

---

## Fonctionnement hors ligne

L'application ne dépend d'aucune ressource externe (CDN) :

| Ressource                        | Emplacement                                |
| -------------------------------- | ------------------------------------------ |
| Font Awesome                     | `public/assets/fontawesome/`               |
| Polices (`.woff2` + `fonts.css`) | `public/assets/fonts/`                     |
| Tailwind CSS                     | compilé par Vite (`resources/css/app.css`) |

Les polices sont déclarées dans `public/assets/fonts/fonts.css` et chargées dans les layouts avec :

```blade
<link rel="stylesheet" href="{{ asset('assets/fonts/fonts.css') }}">
```

---

## Structure du projet

```
app/
├── Http/Controllers/
│   ├── Auth/AuthenticatedSessionController.php   # connexion, inscription, déconnexion
│   ├── HomeController.php                        # tableau de bord
│   ├── LivreController.php                       # CRUD livres + recherche/filtres
│   ├── AdherentController.php                    # CRUD adhérents
│   └── EmpruntController.php                     # emprunts, retours, export CSV
└── Models/
    ├── User.php
    ├── Livre.php
    ├── Adherent.php
    └── Emprunt.php

resources/views/
├── layouts/
│   ├── app.blade.php        # layout principal (barre latérale + contenu)
│   └── auth.blade.php       # layout des pages de connexion / inscription
├── auth/                    # login, register
├── livres/                  # index, create, edit
├── adherents/               # index, create, show, edit
├── emprunts/                # index, create
└── dashboard.blade.php

public/assets/
├── fontawesome/             # icônes (local)
└── fonts/                   # polices (local)
```

---

## Modèle de données

```
livres                          adherents                       emprunts
──────                          ─────────                       ────────
id                              id                              id
titre                           nom                             livre_id            → livres.id
auteur                          prenom                          adherent_id         → adherents.id
isbn (unique)                   email (unique)                  date_emprunt
categorie                       telephone (nullable)            date_retour_prevue
annee                           date_inscription                date_retour_effective (nullable)
quantite_totale                 timestamps                      timestamps
quantite_disponible
timestamps
```

Relations Eloquent :

- `Livre` **hasMany** `Emprunt`
- `Adherent` **hasMany** `Emprunt`
- `Emprunt` **belongsTo** `Livre` et **belongsTo** `Adherent`

Un emprunt est « en cours » tant que `date_retour_effective` est `NULL`.

---

## Routes

Les routes de gestion (tableau de bord, livres, adhérents, emprunts) sont protégées par l'authentification. Les noms ci-dessous sont ceux utilisés dans les vues et les contrôleurs ; la liste exacte et à jour s'obtient avec :

```bash
php artisan route:list
```

| Méthode    | Nom de route        | Contrôleur                                    | Description                      |
| ---------- | ------------------- | --------------------------------------------- | -------------------------------- |
| GET        | `login`             | `AuthenticatedSessionController@create`       | Formulaire de connexion          |
| POST       | `login`             | `AuthenticatedSessionController@store`        | Connexion                        |
| GET        | `register`          | `AuthenticatedSessionController@register`     | Formulaire d'inscription         |
| POST       | `registerPost`      | `AuthenticatedSessionController@registerPost` | Création du compte               |
| POST       | `logout`            | `AuthenticatedSessionController@destroy`      | Déconnexion                      |
| GET        | `dashboard`         | `HomeController@index`                        | Tableau de bord                  |
| GET        | `livres.index`      | `LivreController@index`                       | Liste, recherche et filtres      |
| GET        | `livres.create`     | `LivreController@create`                      | Formulaire d'ajout               |
| POST       | `livres.store`      | `LivreController@store`                       | Enregistrer un livre             |
| GET        | `livres.edit`       | `LivreController@edit`                        | Formulaire de modification       |
| PUT/PATCH  | `livres.update`     | `LivreController@update`                      | Modifier un livre                |
| DELETE     | `livres.destroy`    | `LivreController@destroy`                     | Supprimer un livre               |
| GET        | `adherents.index`   | `AdherentController@index`                    | Liste des adhérents              |
| GET        | `adherents.create`  | `AdherentController@create`                   | Formulaire d'inscription         |
| POST       | `adherents.store`   | `AdherentController@store`                    | Enregistrer un adhérent          |
| GET        | `adherents.show`    | `AdherentController@show`                     | Fiche et historique              |
| GET        | `adherents.edit`    | `AdherentController@edit`                     | Formulaire de modification       |
| PUT/PATCH  | `adherents.update`  | `AdherentController@update`                   | Modifier un adhérent             |
| DELETE     | `adherents.destroy` | `AdherentController@destroy`                  | Supprimer un adhérent            |
| GET        | `emprunts.index`    | `EmpruntController@index`                     | Emprunts en cours                |
| GET        | `emprunts.create`   | `EmpruntController@create`                    | Formulaire d'emprunt             |
| POST       | `emprunts.store`    | `EmpruntController@store`                     | Enregistrer un emprunt           |
| POST/PATCH | `emprunts.retour`   | `EmpruntController@retour`                    | Enregistrer un retour            |
| GET        | `emprunts.export`   | `EmpruntController@export`                    | Export CSV des emprunts en cours |

---

## Pistes d'amélioration

- Restreindre l'inscription (elle est actuellement ouverte à tout visiteur) ou la réserver à un administrateur.
- Ajouter la confirmation du mot de passe et des règles de robustesse à l'inscription.
- Ajouter des tests automatisés (PHPUnit / Pest) sur les règles d'emprunt.
- Notifier par e-mail les adhérents en retard.
