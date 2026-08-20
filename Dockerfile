# =============================================================================
# Stage 1 — Composer: install PHP production dependencies
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

# Run discovery and autoload optimization
RUN composer run-script post-autoload-dump --no-interaction


# =============================================================================
# Stage 2 — Production: PHP 8.3-FPM + Nginx (Pure PHP, No Node required)
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

# ── Nginx config template ─────────────────────────────────────────────────────
COPY docker/nginx/default.conf.template /etc/nginx/http.d/default.conf.template

# ── Supervisor config (manages nginx + php-fpm in one container) ──────────────
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# ── Application files ─────────────────────────────────────────────────────────
WORKDIR /var/www/html

# Copy application and vendor from Composer stage
COPY --from=composer_builder /app /var/www/html

# ── Permissions ───────────────────────────────────────────────────────────────
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# ── Entrypoint ────────────────────────────────────────────────────────────────
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
