FROM node:22-bookworm-slim AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
ARG VITE_REVERB_APP_KEY
ARG VITE_REVERB_HOST=ws.tiemnhaduy.com
ARG VITE_REVERB_PORT=443
ARG VITE_REVERB_SCHEME=https
ENV VITE_REVERB_APP_KEY=$VITE_REVERB_APP_KEY
ENV VITE_REVERB_HOST=$VITE_REVERB_HOST
ENV VITE_REVERB_PORT=$VITE_REVERB_PORT
ENV VITE_REVERB_SCHEME=$VITE_REVERB_SCHEME
COPY . .
RUN npm run build

FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --ignore-platform-req=ext-intl
COPY . .
RUN composer install --no-dev --no-scripts --optimize-autoloader --prefer-dist --ignore-platform-req=ext-intl \
    && rm -rf /root/.composer/cache

FROM php:8.3-fpm-bookworm

RUN apt-get update && apt-get install -y --no-install-recommends \
        git nginx supervisor \
        libicu-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
        libzip-dev libonig-dev $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" bcmath gd intl opcache pcntl pdo_mysql sockets zip \
    && apt-get purge -y --auto-remove $PHPIZE_DEPS \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
COPY --from=vendor /app /var/www/html
COPY --from=assets /app/public/build /var/www/html/public/build
COPY deploy/nginx.conf /etc/nginx/sites-available/default
COPY deploy/php.ini /usr/local/etc/php/conf.d/tiemnhaduy.ini
COPY deploy/php-fpm-www.conf /usr/local/etc/php-fpm.d/zz-app.conf
COPY deploy/supervisord.conf /etc/supervisor/conf.d/tiemnhaduy.conf
COPY deploy/entrypoint.sh /entrypoint.sh

RUN rm -f /etc/nginx/sites-enabled/default \
    && ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default \
    && chmod +x /entrypoint.sh \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80
ENTRYPOINT ["/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/tiemnhaduy.conf"]
