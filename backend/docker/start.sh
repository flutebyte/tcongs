#!/bin/sh
set -e

export PORT="${PORT:-8080}"

# Render the nginx config with Railway's assigned $PORT — nginx.conf itself
# can't read env vars directly, so this templates it once at container start.
envsubst '${PORT}' < /etc/nginx/templates/nginx.conf.template > /etc/nginx/conf.d/default.conf

php artisan storage:link || true
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
