#!/bin/bash
set -e

echo "==============================================="
echo "  🚀 Starting SLTES Production Deployment      "
echo "==============================================="

# Optional: Put into maintenance mode
(php artisan down --retry=60 || true)

# Pull latest code from main
echo "📥 Pulling latest release from origin/main..."
git fetch origin main
git reset --hard origin/main

# Install production dependencies
echo "📦 Installing production composer packages..."
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Rebuild high-performance caches
echo "⚡ Clearing and caching configuration, routes, and views..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Reload PHP-FPM if available
if command -v systemctl >/dev/null 2>&1; then
    echo "🔄 Reloading PHP-FPM service..."
    (sudo systemctl reload php8.2-fpm 2>/dev/null || sudo systemctl reload php8.3-fpm 2>/dev/null || sudo systemctl reload php-fpm 2>/dev/null || true)
fi

# Bring application back online
echo "✅ Restoring application state..."
php artisan up

echo "==============================================="
echo "  🎉 Deployment successfully finished!         "
echo "==============================================="
