web: vendor/bin/heroku-php-apache2 -i deploy/php.ini public/
#web: vendor/bin/heroku-php-nginx -C deploy/nginx.conf -i deploy/php.ini public/
queue: php artisan queue:work --tries=1 --no-ansi
cron: chmod a+x ./cron.sh && ./cron.sh
release: php artisan optimize && php artisan migrate --force
