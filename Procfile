web: chmod +x 777 /app/deploy.sh &&  /app/deploy.sh
queue: php artisan queue:work --tries=1 --no-ansi
cron: chmod a+x ./cron.sh && ./cron.sh
