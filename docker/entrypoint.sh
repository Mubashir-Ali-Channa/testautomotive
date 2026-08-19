#!/bin/bash
set -e

echo "=== TESTAUTOMOTIVE — Container Boot ==="

cd /var/www/html

# ── Storage & cache directories ───────────────────────────────────────────────
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# ── Laravel bootstrap caches ──────────────────────────────────────────────────
echo "[1/4] Caching configuration..."
php artisan config:cache

echo "[2/4] Caching routes..."
php artisan route:cache

echo "[3/4] Caching views..."
php artisan view:cache

echo "[4/4] Running database migrations..."
php artisan migrate --force --no-interaction

echo "=== Boot complete. Starting services ==="

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
