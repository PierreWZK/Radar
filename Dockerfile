# syntax=docker/dockerfile:1
# ---------------------------------------------------------------------------
# Radar en production : FrankenPHP (Caddy + PHP 8.4) derrière le proxy de Coolify.
#
# Au démarrage (docker/entrypoint.sh) : migrations, caches, worker de la file et planificateur,
# puis serveur web sur le port 80. Les réglages viennent des variables d'environnement de Coolify
# (voir docs/deploiement.md). Un seul dossier est à conserver dans un volume :
#   /data   base SQLite (comptes, sites, historique des mesures)
# ---------------------------------------------------------------------------

# 1. Fichiers front (Vite + Tailwind)
FROM node:22-alpine AS front
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund
COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY resources ./resources
RUN npm run build

# 2. Application
# Version exacte de PHP, comme Kairos : à monter à chaque correctif de sécurité de PHP 8.4.
FROM dunglas/frankenphp:1-php8.4.27

WORKDIR /app

RUN install-php-extensions pdo_sqlite intl opcache zip pcntl \
    && cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY docker/php.ini "$PHP_INI_DIR/conf.d/zz-radar.ini"
COPY docker/Caddyfile /etc/caddy/Caddyfile

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV APP_ENV=production \
    APP_DEBUG=false \
    COMPOSER_ALLOW_SUPERUSER=1 \
    DB_CONNECTION=sqlite \
    DB_DATABASE=/data/database.sqlite

# Dépendances d'abord : cette couche reste en cache tant que composer.lock ne change pas
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-progress --no-interaction

COPY . .
COPY --from=front /app/public/build ./public/build

RUN composer dump-autoload --no-dev --optimize \
    && php artisan package:discover --ansi \
    && mkdir -p /data storage/framework/cache storage/framework/sessions storage/framework/views storage/logs \
    && chmod +x docker/entrypoint.sh

EXPOSE 80
ENTRYPOINT ["/app/docker/entrypoint.sh"]
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
