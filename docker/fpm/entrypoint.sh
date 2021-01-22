#!/bin/sh

# Create missing PIDfile dir \
mkdir -p /run/php/

# Force FPM to listen on localhost:9000
sed -i 's/\/run.*sock/127.0.0.1:9000/' /etc/php/$PHP_VERSION/fpm/pool.d/www.conf

# Output logs to proper streams
tail -f /var/log/php_access.log &
tail -f /var/log/php_error.log 1>&2 &

# Start FPM
exec /usr/sbin/php-fpm${PHP_VERSION} -F --fpm-config /etc/php/$PHP_VERSION/fpm/php-fpm.conf
