# =============================================================================
# Stage 1 — Node: install JS deps and compile Vite/Tailwind assets
# =============================================================================
FROM node:22-alpine AS node_builder

WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm ci --ignore-scripts

COPY vite.config.* ./
COPY resources/ ./resources/
COPY public/ ./public/

RUN npm run build


# =============================================================================
# Stage 2 — Composer: install PHP deps (no dev dependencies)
# =============================================================================
FROM composer:2 AS composer_builder

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-scripts \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

COPY . .

# Re-run scripts now that the full app is in place (triggers package:discover, etc.)
RUN composer run-script post-autoload-dump --no-interaction


# =============================================================================
# Stage 3 — Production: PHP 8.3-FPM + Nginx
# =============================================================================
FROM php:8.3-fpm-alpine AS production

# ── System packages + Nginx ───────────────────────────────────────────────────
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    freetype-dev \
    mysql-client \
    bash \
    shadow

# ── PHP extensions ────────────────────────────────────────────────────────────
RUN docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache

# ── PHP config tweaks ─────────────────────────────────────────────────────────
COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

# ── Nginx config ──────────────────────────────────────────────────────────────
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

# ── Supervisor config (manages nginx + php-fpm in one container) ──────────────
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# ── Application files ─────────────────────────────────────────────────────────
WORKDIR /var/www/html

# Copy vendor from Composer stage
COPY --from=composer_builder /app /var/www/html

# Overwrite public/build with compiled Vite assets from Node stage
COPY --from=node_builder /app/public/build ./public/build

# ── Permissions ───────────────────────────────────────────────────────────────
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# ── Entrypoint ────────────────────────────────────────────────────────────────
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
