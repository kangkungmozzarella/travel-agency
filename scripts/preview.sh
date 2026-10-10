#!/usr/bin/env bash
set -euo pipefail

export APP_NAME=OTEWE
export APP_ENV=local
export APP_KEY=base64:MTIzNDU2Nzg5MDEyMzQ1Njc4OTAxMjM0NTY3ODkwMTI=
export APP_URL=http://127.0.0.1:8020
export DB_CONNECTION=sqlite
export DB_DATABASE="$PWD/database/preview.sqlite"
export ADMIN_NAME="Admin OTEWE"
export ADMIN_EMAIL=admin@otewe.local
export ADMIN_PASSWORD=OtewePreview123!
export SESSION_DRIVER=file
export SESSION_COOKIE=otewe_content_preview
export CACHE_DRIVER=array
export MAIL_MAILER=log
export QUEUE_CONNECTION=sync

touch "$DB_DATABASE"
php artisan migrate --force --quiet
php artisan db:seed --class=AdminSeeder --force --quiet
php artisan db:seed --class=TravelContentSeeder --force --quiet
if [ ! -e public/storage ]; then
    php artisan storage:link --quiet
fi
exec php artisan serve --host=127.0.0.1 --port=8020
