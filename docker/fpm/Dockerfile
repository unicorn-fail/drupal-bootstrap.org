FROM debian:buster-slim

# Need to set this as an ENV var to be used by the CMD directive at runtime
ENV PHP_VERSION=7.2

# Install sury repo and basic PHP-FPM
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y curl && \
    curl -sSL https://packages.sury.org/php/README.txt | bash -x && \
    apt-get update && \
    apt install -y php$PHP_VERSION php${PHP_VERSION}-fpm && \
    apt-get install -y php${PHP_VERSION}-curl && \
    apt-get install -y php${PHP_VERSION}-dom && \
    apt-get install -y php${PHP_VERSION}-gd && \
    apt-get install -y php${PHP_VERSION}-json && \
    apt-get install -y php${PHP_VERSION}-mbstring && \
    apt-get install -y php${PHP_VERSION}-memcached && \
    apt-get install -y php${PHP_VERSION}-mysql && \
    apt-get install -y php${PHP_VERSION}-redis && \
    apt-get install -y php${PHP_VERSION}-xml && \
    apt-get install -y php${PHP_VERSION}-zip && \
    apt-get install -y wget git mariadb-client && \
    rm -rf /var/lib/apt/lists/* && apt-get clean

EXPOSE 9000/TCP

# Configure Opcache
RUN { \ 
    echo 'zend_extension=opcache.so'; \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=16'; \
    echo 'opcache.max_accelerated_files=4000'; \
    echo 'opcache.revalidate_freq=60'; \
    echo 'opcache.fast_shutdown=1'; \
    echo 'opcache.enable_cli=1'; \
  } > /etc/php/$PHP_VERSION/fpm/conf.d/10-opcache.ini

# Configure PHP in general
RUN { \
    echo 'access.log = /dev/stdout'; \
    echo 'php_admin_value[error_log] = /dev/stderr'; \
    echo 'php_admin_flag[log_errors] = on'; \
    echo 'php_flag[display_errors] = off'; \
  } >> /etc/php/$PHP_VERSION/fpm/pool.d/www.conf

RUN wget https://github.com/drush-ops/drush/releases/download/8.1.18/drush.phar -O /usr/local/bin/drush && \
    chmod +x /usr/local/bin/drush

RUN mkdir -p /var/www/drupal-bootstrap.org-repositories  && mkdir /run/php/ && \
    chown root:root /run/php && chmod g+wrx /run/php && \
    chown -R root:www-data /var/www/drupal-bootstrap.org-repositories && \
    chmod -R 770 /var/www/drupal-bootstrap.org-repositories

COPY docker/fpm/entrypoint.sh /usr/local/bin/entrypoint.sh

ENTRYPOINT /usr/local/bin/entrypoint.sh
