#!/bin/sh
if [ ! -f /var/www/.env ]; then
  cp /var/www/.env.example /var/www/.env
fi

exec php-fpm
