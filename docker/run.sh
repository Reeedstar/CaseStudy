#!/bin/sh
set -e
php /app/bin/console cache:clear
bin/console assets:install

export $(grep -v '^#' /app/.env | xargs)
if [ -f /app/.env.local ]; then
    export $(grep -v '^#' /app/.env.local | xargs)
fi

frankenphp run --config /app/docker/Caddyfile
