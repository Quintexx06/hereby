# Hereby, production image for Coolify (docker-compose.yml).
# One image, four roles: web (nginx + php-fpm), Inertia SSR, queue, scheduler.

# --- Build: PHP dependencies, Wayfinder routes, client and SSR bundles ------
FROM serversideup/php:8.5-cli AS build

USER root
RUN install-php-extensions bcmath intl \
    && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
COPY --chown=www-data:www-data . .
USER www-data

# wayfinder:generate boots the app, so it gets a throwaway key and database.
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader \
    && PLAYWRIGHT_SKIP_BROWSER_DOWNLOAD=1 npm install --no-audit --no-fund \
    && APP_ENV=production \
       APP_KEY=base64:aGVyZWJ5LWJ1aWxkLW9ubHkta2V5LTAwMDAwMDAwMDA= \
       DB_CONNECTION=sqlite \
       DB_DATABASE=/tmp/build.sqlite \
       php artisan wayfinder:generate --with-form --no-interaction \
    && npm run build:ssr \
    && npm prune --omit=dev --no-audit --no-fund
# The SSR bundle imports vue and @inertiajs at runtime, so production node_modules stay.

# --- Runtime -----------------------------------------------------------------
FROM serversideup/php:8.5-fpm-nginx

USER root
# The base image has no gd or exif: gd makes the web copies of every photo
# (app/Support/WebImage), exif reads which way up a phone photo was taken.
RUN install-php-extensions bcmath exif gd intl
# Node runs the Inertia SSR server (ssr service); the binary is all it needs.
COPY --from=build /usr/bin/node /usr/local/bin/node

COPY --from=build --chown=www-data:www-data /var/www/html /var/www/html
USER www-data
