#!/bin/sh
# ---------------------------------------------------------------------------
# Démarrage du conteneur Radar : prépare l'appli, lance le worker et le planificateur,
# puis le serveur web (CMD du Dockerfile).
# Une commande passée au conteneur (ex. « php artisan radar:mesurer ») est exécutée telle quelle.
# ---------------------------------------------------------------------------
set -e

if [ "$1" = 'frankenphp' ]; then
    # Le volume arrive vide au premier démarrage
    mkdir -p "$(dirname "$DB_DATABASE")"
    touch "$DB_DATABASE"

    php artisan migrate --force
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

    # Sans le worker, aucune mesure ne part ; sans le planificateur, pas de tournée de 3 h
    php artisan queue:work --tries=3 --timeout=120 &
    php artisan schedule:work &
fi

exec "$@"
