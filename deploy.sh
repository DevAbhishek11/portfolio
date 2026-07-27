#!/usr/bin/env bash
# ─────────────────────────────────────────────────────────────────
# Portfolio Deployment Script
# Usage: bash deploy.sh
# ─────────────────────────────────────────────────────────────────
set -e

echo "🚀 Starting deployment..."

# 1. Pull latest code
git pull origin main

# 2. Install / update PHP dependencies (no dev)
composer install --no-dev --optimize-autoloader --no-interaction

# 3. Run migrations
php artisan migrate --force

# 4. Clear & rebuild caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 5. Storage link
php artisan storage:link || true

# 6. Set correct permissions
chmod -R 755 storage bootstrap/cache
find storage -type f -exec chmod 644 {} \;
find storage -type d -exec chmod 755 {} \;

# 7. Optimise
php artisan optimize

echo "✅ Deployment complete."
echo ""
echo "Post-deploy checklist:"
echo "  □ APP_DEBUG=false in .env"
echo "  □ APP_ENV=production in .env"
echo "  □ SESSION_SECURE_COOKIE=true in .env"
echo "  □ Mail credentials configured"
echo "  □ Admin password changed from default"
echo "  □ Cron job added for schedule:run"