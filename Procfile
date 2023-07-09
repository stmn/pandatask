web: heroku-php-apache2 public/
queue: php artisan queue:work --tries=1 --no-ansi
cron: chmod a+x ./cron.sh && ./cron.sh
