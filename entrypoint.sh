#!/bin/bash
cd /var/www/weather-app || echo "project dir doesnt exist"
FILE='./vendor/autoload.php'

if [ -f $FILE ]; then
  echo "composer already installed"
else
  composer install
  php artisan storage:link
  echo "composer installed"
fi
