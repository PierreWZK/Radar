# Mise en ligne sur Coolify · radar.projectp.fr

À suivre dans l'ordre. Rien ici ne demande de toucher au code.

## 1. Clé PageSpeed dédiée à la production

- Google Cloud Console → Identifiants → créer une clé d'API **pour la production**.
- Restriction d'API : **PageSpeed Insights API** uniquement.
- Restriction d'IP : **aucune**, ou les IP sortantes du serveur Coolify si vous les connaissez. Une restriction d'IP mal réglée donne un 403 sur toutes les mesures.
- Supprimer l'ancienne clé exposée dans le chat.

## 2. Application dans Coolify

- **Source** : le dépôt Git de Radar, branche `master` une fois la PR fusionnée.
- **Build** : PHP 8.4 (Nixpacks ou image PHP).
- **Commande de build** : `composer install --no-dev --optimize-autoloader && npm ci && npm run build`
- **Commande de démarrage** : `php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && php-fpm` (ou le serveur web fourni par Coolify ; la racine web est `public/`).
- **Domaine** : `radar.projectp.fr`, HTTPS activé.

## 3. Variables d'environnement

| Variable | Valeur |
|---|---|
| `APP_NAME` | `Radar` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_URL` | `https://radar.projectp.fr` |
| `APP_KEY` | généré avec `php artisan key:generate --show` en local, puis collé |
| `DB_CONNECTION` | `sqlite` |
| `DB_DATABASE` | `/app/database/database.sqlite` (chemin dans le conteneur, voir étape 4) |
| `QUEUE_CONNECTION` | `database` |
| `CACHE_STORE` | `database` |
| `SESSION_DRIVER` | `database` |
| `PAGESPEED_API_KEY` | la clé de l'étape 1 |
| `MAIL_MAILER` | `smtp` (ou le service d'e-mail choisi) ; `log` seulement pour un test |
| `MAIL_FROM_ADDRESS` | l'adresse d'envoi retenue |

## 4. Données qui survivent aux déploiements

- Ajouter un **volume persistant** sur le dossier `database/` (contient la base SQLite).
- Sans ce volume, chaque déploiement efface les comptes et l'historique des mesures.

## 5. Processus en arrière-plan

Dans Coolify, ajouter deux processus :

- **Worker** : `php artisan queue:work --tries=3 --timeout=120` (sans lui, aucune mesure ne part).
- **Planificateur** : tâche planifiée toutes les minutes `php artisan schedule:run`. Elle déclenche `radar:mesurer` chaque nuit à 3 h.

## 6. Vérifications après déploiement

1. `https://radar.projectp.fr/` répond, le titre est « Radar · la santé de vos sites… ».
2. `https://radar.projectp.fr/robots.txt` et `https://radar.projectp.fr/sitemap.xml` répondent.
3. `https://radar.projectp.fr/images/og-radar.png` répond en image 1200 × 630.
4. Le test sans compte fonctionne sur `/tester` avec une vraie adresse.
5. Inscription : l'e-mail de confirmation arrive, et le lien signé ouvre le compte.
6. Une mesure manuelle passe : `php artisan radar:mesurer` dans le terminal Coolify, puis la page apparaît dans le tableau de bord après le passage du worker.
7. Partage d'un lien dans un outil d'aperçu (Open Graph) : la carte affiche l'image et le titre.

## 7. Après la mise en ligne

- Google Search Console : ajouter `https://radar.projectp.fr/` et soumettre `https://radar.projectp.fr/sitemap.xml`.
- Vitrine : `30days30projects` (README et `projects.js`), seulement après validation.

> Accès à Coolify non vérifié à ce jour : les étapes ci-dessus suivent la documentation générale de Coolify et doivent être confirmées sur l'instance réelle.
