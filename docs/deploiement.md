# Mise en ligne sur Coolify · radar.projectp.fr

## 1. Clé PageSpeed dédiée à la production

- Google Cloud Console → Identifiants → créer une clé d'API **pour la production**.
- Restriction d'API : **PageSpeed Insights API** uniquement.
- Restriction d'IP : **aucune**, ou l'IP sortante du serveur Coolify. Une restriction d'IP mal réglée donne un 403 sur toutes les mesures.
- Supprimer l'ancienne clé exposée dans le chat.

## 2. Application dans Coolify

- **Source** : dépôt `PierreWZK/Radar`.
- **Build** : `Dockerfile` à la racine (FrankenPHP, PHP 8.4). Le build front (Vite) se fait dans l'image.
- **Port** : 80. **Domaine** : `https://radar.projectp.fr`.
- **Healthcheck** : `/up`.
- Au démarrage, le conteneur joue les migrations, prépare les caches, puis lance le worker (`queue:work`) et le planificateur (`schedule:work`, tournée de 3 h). Aucun processus à ajouter à côté.

## 3. Variables d'environnement

| Variable | Valeur |
|---|---|
| `APP_NAME` | `Radar` |
| `APP_URL` | `https://radar.projectp.fr` |
| `APP_LOCALE` | `fr` |
| `APP_KEY` | **à saisir** : `php artisan key:generate --show` en local, puis coller |
| `QUEUE_CONNECTION` | `database` |
| `CACHE_STORE` | `database` |
| `SESSION_DRIVER` | `database` |
| `LOG_CHANNEL` | `stderr` (journaux visibles dans Coolify) |
| `PAGESPEED_API_KEY` | **à saisir** : la clé de l'étape 1 |
| `MAIL_MAILER` | `smtp` |
| `MAIL_URL` | **à saisir** : même valeur que `MAILER_DSN` de Kairos si c'est un `smtp://` |
| `MAIL_FROM_ADDRESS` | l'adresse d'envoi retenue |
| `MAIL_FROM_NAME` | `Radar` |

`APP_ENV`, `APP_DEBUG`, `DB_CONNECTION` et `DB_DATABASE` sont fixés dans l'image.

## 4. Données qui survivent aux déploiements

- **Volume persistant** monté sur `/data` (contient `database.sqlite`).
- Sans ce volume, chaque déploiement efface les comptes et l'historique des mesures.

## 5. Vérifications après déploiement

1. `https://radar.projectp.fr/up` répond 200.
2. `https://radar.projectp.fr/` répond, le titre est « Radar · la santé de vos sites… ».
3. `robots.txt`, `sitemap.xml` et `images/og-radar.png` répondent.
4. Le test sans compte fonctionne sur `/tester` avec une vraie adresse.
5. Inscription : l'e-mail de confirmation arrive, et le lien signé ouvre le compte.
6. Mesure manuelle : `php artisan radar:mesurer` dans le terminal Coolify, puis la page apparaît dans le tableau de bord.

## 6. Après la mise en ligne

- Google Search Console : ajouter `https://radar.projectp.fr/` et soumettre `https://radar.projectp.fr/sitemap.xml`.
