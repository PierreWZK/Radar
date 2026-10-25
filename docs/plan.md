# Plan · Radar

Tests : `php artisan test` (PHPUnit). Chaque étape : tests d'abord, puis code, puis commit après validation. Reprendre une étape : relire ce fichier et `docs/spec.md`.

## Étape 1 · Setup

- Projet Laravel dans ce dossier, base SQLite, `.env.example` avec `PAGESPEED_API_KEY`.
- Un test de fumée qui passe.

**Terminé** : `php artisan test` passe ; `php artisan serve` affiche la page d'accueil.

## Étape 2 · Comptes

- Inscription, confirmation par lien signé, mot de passe oublié.
- Un compte non confirmé ne peut suivre aucun site.
- Champ `plan` (Gratuit par défaut) ; pages d'offres et de paiement, sans encaissement.

**Terminé** : tests de l'inscription, de la confirmation, du lien expiré et de la réinitialisation passent ; un compte non confirmé est refusé sur l'ajout de site.

## Étape 3 · Mesures PageSpeed

- Client PageSpeed côté serveur, clé lue depuis `.env`.
- File de tâches (driver base de données) : une à la fois, délai 60 s, 3 tentatives.
- Médiane de 3 mesures ; stockage des quatre notes et des Core Web Vitals.

**Terminé** : tests avec réponses PageSpeed en fixtures (succès, échec, retry, médiane) passent ; aucune requête réseau pendant les tests.

## Étape 4 · Sites, pages et limites

- Ajouter, modifier, supprimer un site et ses pages.
- Limites par offre (1×1, 3×5, 15×10), option « Site entier » (sitemap, jusqu'à 200 pages).
- Test sans compte : 3 par jour et par IP.

**Terminé** : tests de chaque limite (ajout refusé au-delà) passent ; le test sans compte refuse le 4ᵉ essai du jour.

## Étape 5 · Alerte

- Règle : baisse ≥ 5 points par rapport à la médiane des 7 derniers jours, ou Core Web Vital dégradé.
- Une alerte maximum par page et par jour ; e-mail avec les trois conseils les plus rentables.
- Conseils complets pour Solo et Agence ; trois conseils seulement pour le Gratuit sous 50.

**Terminé** : tests couvrent une baisse de 5 (alerte), une variation de 2 (pas d'alerte), la deuxième alerte du jour (bloquée) et le filtre Gratuit ; un e-mail est écrit dans le journal en local.

## Étape 6 · Tableau de bord et badge

- Vue d'ensemble, fiche de page avec courbes sur 90 jours et Core Web Vitals (Solo et Agence).
- Interface selon `design/tokens.css` et la direction A ; radar du héros avec les vraies notes.
- Badge SVG `/badge/{page}.svg`.

**Terminé** : tests du badge (couleurs aux seuils 90 et 50) ; capture mobile et ordinateur vérifiée à la main, sans débordement horizontal.

## Étape 6b · Refonte de l'accueil (maquette du 25/10)

- Héros : titre « La santé de vos sites, semaine après semaine. », tableau de bord en perspective, alerte magenta, décor dessiné en SVG (soleil, orbite, dunes, montagnes, phare).
- Animations : balayage qui révèle les quatre notes, note Performance qui baisse puis alerte et conseils, courbes qui se dessinent. État final affiché sans JavaScript ou avec `prefers-reduced-motion`.
- Bande de quatre fonctionnalités ; jeu du jour en démo (un défi par jour tiré d'une liste, rien en base) ; offres, FAQ et footer gardés.

**Terminé** : tests de l'accueil passent ; captures mobile et ordinateur sans débordement ; détecteur impeccable sans alerte.

## Étape 6c · Refonte du tableau de bord, page Alertes et exemple public

- Layout connecté : barre latérale noire (Tableau de bord, Mes sites, Alertes, Paramètres = profil).
- Tableau de bord et fiche de page au visuel de la maquette (anneaux de score, courbe 90 jours, Core Web Vitals).
- Page Alertes : historique des alertes du compte, la plus récente en premier.
- Page `/exemple` publique : tableau de bord en lecture seule avec des données d'exemple signalées.

**Terminé** : tests de la page Alertes (seulement les alertes du compte) et de `/exemple` passent ; captures mobile et ordinateur vérifiées.

## Étape 6d · Tableau de bord façon maquette

- Cadre noir, barre latérale avec icônes, panneau clair arrondi.
- Une page à la fois, choisie dans une liste : anneau Performance avec écart, quatre notes avec écart à la mesure précédente, courbes des quatre notes sur 90 jours, Core Web Vitals colorés selon les seuils (Solo et Agence).
- Colonne de droite : carte d'alerte magenta (alerte des 7 derniers jours, conseils dépliables), note ordinateur, autres pages.
- `/exemple` reprend la même vue avec des données fictives.

**Terminé** : tests (page choisie, page d'un autre compte ignorée, alerte affichée ou non, Core Web Vitals selon l'offre) ; captures mobile et ordinateur.

## Étape 7 · Planification et finitions

- Mesures quotidiennes et hebdomadaires lancées par le planificateur Laravel.
- Textes d'erreur et états vides, README à jour (lancement, tests).

**Terminé** : `php artisan schedule:list` affiche les deux tâches ; `php artisan test` passe ; le parcours complet (inscription, site, mesure, alerte, badge) fonctionne en local.
