#!/bin/sh
set -eu

PORT="${PORT:-10000}"
MIGRATE_ON_STARTUP="${MIGRATE_ON_STARTUP:-true}"
SEED_ON_STARTUP="${SEED_ON_STARTUP:-true}"
DB_WAIT_RETRIES="${DB_WAIT_RETRIES:-30}"
DB_WAIT_SECONDS="${DB_WAIT_SECONDS:-2}"

echo "[startup] Clearing cached config..."
php artisan config:clear || true

if [ "$MIGRATE_ON_STARTUP" = "true" ]; then
  echo "[startup] Waiting for database connectivity..."
  i=1
  while [ "$i" -le "$DB_WAIT_RETRIES" ]; do
    if php artisan migrate:status --no-interaction >/dev/null 2>&1; then
      echo "[startup] Database is reachable."
      break
    fi

    if [ "$i" -eq "$DB_WAIT_RETRIES" ]; then
      echo "[startup] Database did not become reachable in time."
      exit 1
    fi

    echo "[startup] DB not ready yet ($i/$DB_WAIT_RETRIES). Retrying in ${DB_WAIT_SECONDS}s..."
    sleep "$DB_WAIT_SECONDS"
    i=$((i + 1))
  done

  echo "[startup] Running migrations..."
  php artisan migrate --force --no-interaction

  if [ "$SEED_ON_STARTUP" = "true" ]; then
    echo "[startup] Running seeders..."
    php artisan db:seed --force --no-interaction
  fi
fi

echo "[startup] Starting Laravel server on port ${PORT}..."
exec php artisan serve --host=0.0.0.0 --port="$PORT"
