FROM debian:buster-slim

RUN apt update && \
    apt install -y composer git wget apache2 php php-curl && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Enable modules for FCGI and Disable php module
RUN a2enmod proxy_fcgi rewrite && a2dismod php7.3

# Create build dir
RUN mkdir /var/www/drupal-bootstrap.org-build

# Set workdir to build dir
WORKDIR /var/www/drupal-bootstrap.org-build

# Copy site code
COPY --chown=root:www-data docroot/ docroot/
COPY --chown=root:www-data composer* ./

RUN composer install

COPY docker/app/conf/apache /etc/apache2/sites-enabled

# Copy in basic settings.php.
COPY --chown=root:www-data docker/app/conf/drupal/* /var/www/drupal-bootstrap.org-build/docroot/sites/default/

RUN mkdir docroot/sites/default/files

# Set permissions on build and install Drush
RUN chown -R root:www-data docroot && \
    chmod -R 750 docroot && \
    chmod -R 770 docroot/sites/default/files && \
    wget https://github.com/drush-ops/drush/releases/download/8.1.18/drush.phar -O /usr/local/bin/drush && \
    chmod +x /usr/local/bin/drush

EXPOSE 80

COPY docker/app/entrypoint.sh /usr/local/bin/entrypoint.sh
ENTRYPOINT entrypoint.sh
