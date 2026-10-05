#!/usr/bin/env bash
set -euo pipefail

export APP_NAME="OTEWE Preview"
export APP_ENV=testing
export APP_KEY=base64:MTIzNDU2Nzg5MDEyMzQ1Njc4OTAxMjM0NTY3ODkwMTI=
export APP_URL=http://127.0.0.1:8020
export DB_CONNECTION=sqlite
export DB_DATABASE
DB_DATABASE="$(mktemp "${TMPDIR:-/tmp/}otewe-browser-XXXXXX")"
export SESSION_DRIVER=file
export SESSION_COOKIE=otewe_browser_session
export CACHE_DRIVER=array
export MAIL_MAILER=log
export QUEUE_CONNECTION=sync

php artisan migrate --force --quiet
php artisan db:seed --class='Tests\Fixtures\BrowserSeeder' --force --quiet
if [ ! -e public/storage ]; then
    php artisan storage:link --quiet
fi
exec php artisan serve --host=127.0.0.1 --port=8020
