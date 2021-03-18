FROM debian:buster-slim

# Need to set this as an ENV var to be used by the CMD directive at runtime
ENV PHP_VERSION=7.2

# Install sury repo and basic PHP-FPM
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y apt-transport-https lsb-release ca-certificates && \
    apt-get install -y wget curl && \
    apt-get install -y git mariadb-client composer && \
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
    rm -rf /var/lib/apt/lists/* && apt-get clean

# Create build dir
RUN mkdir -p /var/www/drupal-bootstrap.org

# Set workdir to build dir
WORKDIR /var/www/drupal-bootstrap.org

# Copy site code
COPY --chown=root:root docroot/ docroot/
COPY --chown=root:root composer* ./

RUN php${PHP_VERSION} /usr/bin/composer install

# Copy in basic settings.php.
COPY --chown=root:root docker/app/conf/drupal/* docroot/sites/default/

# Set permissions on build and install Drush
RUN chown -R root:root docroot && \
    chmod -R 750 docroot && \
    wget https://github.com/drush-ops/drush/releases/download/8.1.18/drush.phar -O /usr/local/bin/drush && \
    chmod +x /usr/local/bin/drush

RUN ln -s /var/www/drupal-bootstrap.org-files /var/www/drupal-bootstrap.org/docroot/sites/default/files 

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
    echo 'catch_workers_output = yes'; \
    echo 'access.log = /var/log/php_access.log'; \
    echo 'php_admin_value[error_log] = /var/log/php_error.log'; \
    echo 'php_admin_flag[log_errors] = on'; \
    echo 'php_flag[display_errors] = off'; \
    echo 'php_admin_value[display_startup_errors] = on'; \
    echo 'php_admin_value[error_reporting] = E_ALL & ~E_NOTICE & ~E_WARNING & ~E_STRICT & ~E_DEPRECATED'; \
  } >> /etc/php/$PHP_VERSION/fpm/pool.d/www.conf

RUN touch /var/log/php_access.log /var/log/php_error.log && \
    chmod g+rw /var/log/php_access.log /var/log/php_error.log && \
    chown root:root /var/log/php_access.log /var/log/php_error.log

RUN wget https://github.com/drush-ops/drush/releases/download/8.1.18/drush.phar -O /usr/local/bin/drush && \
    chmod +x /usr/local/bin/drush

RUN mkdir /run/php/ && \
    chown root:root /run/php && \
    chmod g+wrx /run/php && \
    chmod -R g+rw /var/log /etc/php && \
    mkdir -p /var/www/drupal-bootstrap.org-repositories && \
    chown -R root:www-data /var/www/drupal-bootstrap.org-repositories && \
    chmod -R 770 /var/www/drupal-bootstrap.org-repositories

COPY docker/fpm/entrypoint.sh /usr/local/bin/entrypoint.sh

EXPOSE 9000/TCP

ENTRYPOINT /usr/local/bin/entrypoint.sh
