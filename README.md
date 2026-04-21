# Mini LinkedIn API - Plateforme de Recrutement

##  Description du Projet
Ce projet est une API backend construite avec Laravel pour une plateforme de recrutement. Elle permet la mise en relation entre candidats et recruteurs avec une gestion complète des profils, des offres d'emploi, des candidatures et un système de monitoring via des événements.

## Guide d'Installation et de Création

# 1. Initialisation du Projet
```bash
composer create-project laravel/laravel minilinkedin
cd minilinkedin
```
# 2. Configuration de l'Authentification JWT (Tymon)

```bash
composer require tymon/jwt-auth
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret
```
# 3. Modélisation de la Base de Données
Génération des modèles et des migrations pour les entités et la table pivot :
### Modèles et Migrations de base
```bash
php artisan make:model Profil -m
php artisan make:model Competence -m
php artisan make:model Offre -m
php artisan make:model Candidature -m
```
### Création de la table pivot competence_profil
```bash
php artisan make:migration create_competence_profil_table
```
# 4. Structure de la Table Pivot (competence_profil)
Dans le fichier de migration database/migrations competence_profil_table.php :
```bash
Schema::create('competence_profil', function (Blueprint $table) {
    $table->id();
    $table->foreignId('profil_id')->constrained()->onDelete('cascade');
    $table->foreignId('competence_id')->constrained()->onDelete('cascade');
    $table->enum('niveau', ['débutant', 'intermédiaire', 'expert']);
    $table->timestamps();
});
```
# 5. Développement des Contrôleurs
```bash
php artisan make:controller AuthController
php artisan make:controller ProfilController
php artisan make:controller OffreController
php artisan make:controller CandidatureController
php artisan make:controller AdminController
```
# 6. Système d'Events & Listeners
```bash
php artisan make:event CandidatureDeposee
php artisan make:event StatutCandidatureMis
php artisan make:listener LogCandidature --event=CandidatureDeposee
php artisan make:listener LogStatutChange--event=StatutCandidatureMis
```
# 7. Migration et Seeders
```bash
php artisan migrate:fresh --seed
```

# Récapitulatif des Routes (Endpoints)
| Méthode | Endpoint | Accès | Action |
| :--- | :--- | :--- | :--- |
| **POST** | `/api/login` | Public | Authentification et Token |
| **POST** | `/api/profil` | Candidat | Création du profil |
| **POST** | `/api/profil/competences` | Candidat | Ajout via `competence_profil` |
| **GET** | `/api/offres` | Tous | Liste des offres (Pagination 10) |
| **POST** | `/api/offres` | Recruteur | Publication d'offre |
| **POST** | `/api/offres/{id}/candidater` | Candidat | Postuler (Trigger Event) |
| **PATCH** | `/api/candidatures/{id}/statut` | Recruteur | Modifier statut (Trigger Event) |
| **GET** | `/api/admin/users` | Admin | Gestion des utilisateurs |
# Règles de Gestion
Sécurité : Un recruteur ne gère que ses offres. Un candidat ne voit que ses candidatures.

Autorisation : Utilisation de Middleware pour restreindre l'accès par rôle (candidat, recruteur, admin).

Monitoring : Chaque action sur une candidature est enregistrée dans storage/logs/candidatures.log.
# Tests avec Postman
L'API est accompagnée d'une collection Postman (MiniLinkedin.postman_collection.json) permettant de valider les scénarios fonctionnels complets.
## 1. Cycle d'Authentification et Création d'Utilisateur
Le processus débute par la gestion des accès :Inscription (Register) : Création d'un compte utilisateur en spécifiant le nom, l'email et surtout le rôle (Candidat, Recruteur ou Admin), ce qui détermine les permissions sur l'API.Connexion (Login) : Authentification via email et mot de passe pour obtenir un Bearer Token JWT. Ce token doit être inclus dans le Header de chaque requête protégée.Vérification (Me) : Validation de la session active pour récupérer les informations de l'utilisateur connecté.
## 2. Gestion des Profils et Compétences
Une fois connecté en tant que candidat, le flux suivant est testé :Initialisation du Profil : Création unique du profil avec bio et titre professionnel.Enrichissement technique : Ajout de compétences via la table pivot competence_profil. Les tests valident l'attribution d'un niveau (ex: "Débutant") à une compétence spécifique (ID).Maintenance : Modification des informations ou retrait d'une compétence pour tester l'intégrité des relations Eloquent.
## 3. Flux des Candidatures et Monitoring (Events)
Les scénarios de recrutement permettent de vérifier le découplage de la logique applicative :Dépôt de candidature : La soumission d'une candidature par un candidat déclenche l'événement CandidatureDeposee. Le test confirme que les informations (nom du candidat, titre de l'offre) sont enregistrées dans storage/logs/candidatures.log.Gestion du statut : Lorsqu'un recruteur modifie le statut d'une candidature reçue, l'événement StatutCandidatureMis est activé. Le système enregistre alors l'ancien et le nouveau statut dans les logs.
## 4. Contrôle des Autorisations et Sécurité
Des tests de restriction d'accès (règles d'ownership) garantissent la confidentialité des données :Validation 403 : Tentative d'accès ou de modification d'une ressource appartenant à un autre utilisateur (ex: un recruteur essayant de modifier une offre dont il n'est pas l'auteur).Validation 401 : Tentative d'appel aux routes sans token valide.