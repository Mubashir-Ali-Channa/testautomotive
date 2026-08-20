#!/bin/bash
set -e

echo "=== TESTAUTOMOTIVE — Container Boot ==="

cd /var/www/html

# ── Dynamic Port Substitution for Nginx ───────────────────────────────────────
PORT="${PORT:-80}"
echo "Configuring Nginx to listen on port: $PORT"
sed "s/\${PORT}/$PORT/g" /etc/nginx/http.d/default.conf.template > /etc/nginx/http.d/default.conf

# ── Storage & cache directories ───────────────────────────────────────────────
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# ── Laravel bootstrap caches ──────────────────────────────────────────────────
echo "[1/5] Caching configuration..."
php artisan config:cache

echo "[2/5] Caching routes..."
php artisan route:cache

echo "[3/5] Caching views..."
php artisan view:cache

echo "[4/5] Creating storage symlink..."
php artisan storage:link --force || true

echo "[5/5] Running database migrations..."
php artisan migrate --force --no-interaction

echo "=== Boot complete. Starting services on port $PORT ==="

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
