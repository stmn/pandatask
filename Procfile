web: heroku-php-nginx public
queue: php artisan queue:work --tries=1 --no-ansi
cron: chmod a+x ./cron.sh && ./cron.sh
release: php artisan optimize && php artisan migrate --force
