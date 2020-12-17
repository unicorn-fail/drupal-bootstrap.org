#!/bin/sh

# Create missing PIDfile dir \
mkdir -p /run/php/

# Configure logging
{ \
    echo 'catch_workers_output = yes'; \
    echo 'access.log = /proc/self/fd/2'; \
    echo 'php_admin_value[error_log] = /proc/self/fd/2'; \
    echo 'php_admin_flag[log_errors] = on'; \
    echo 'php_flag[display_errors] = off'; \
    echo 'php_admin_value[display_startup_errors] = on'; \
    echo 'php_admin_value[error_reporting] = E_ALL & ~E_NOTICE & ~E_WARNING & ~E_STRICT & ~E_DEPRECATED'; \
} >> /etc/php/$PHP_VERSION/fpm/pool.d/www.conf

# Force FPM to listen on localhost:9000
sed -i 's/\/run.*sock/127.0.0.1:9000/' /etc/php/$PHP_VERSION/fpm/pool.d/www.conf

# Start FPM
/usr/sbin/php-fpm${PHP_VERSION} -F --fpm-config /etc/php/$PHP_VERSION/fpm/php-fpm.conf
