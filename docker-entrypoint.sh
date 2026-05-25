#!/usr/bin/env bash
set -e
# Ensure storage and cache directories are writable
mkdir -p bootstrap/cache
chmod -R 0777 storage bootstrap/cache || true

# If an APP_KEY isn't set, try to generate one (only for convenience; in production prefer setting APP_KEY env).
if [ -z "$APP_KEY" ]; then
  php artisan key:generate --force || true
fi

# Cache config at container start so it picks up runtime environment variables
php artisan config:clear || true
php artisan config:cache || true

# Continue with the main container command
exec "$@"
