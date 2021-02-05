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
COPY --chown=root:root docroot/ docroot/
COPY --chown=root:root composer* ./

RUN composer install

COPY docker/app/conf/apache /etc/apache2/sites-enabled

# Copy in basic settings.php.
COPY --chown=root:root docker/app/conf/drupal/* /var/www/drupal-bootstrap.org-build/docroot/sites/default/

RUN mkdir docroot/sites/default/files

# Set permissions on build and install Drush
RUN chown -R root:root docroot && \
    chmod -R 750 docroot && \
    chmod -R 770 docroot/sites/default/files && \
    wget https://github.com/drush-ops/drush/releases/download/8.1.18/drush.phar -O /usr/local/bin/drush && \
    chmod +x /usr/local/bin/drush

# Configure permissions so apache can open log files when running as a non-root user
RUN touch /var/log/apache2/bootstrap-access.log /var/log/apache2/bootstrap-error.log && \
    chmod g+rw /var/log/apache2/bootstrap-access.log /var/log/apache2/bootstrap-error.log && \
    chmod g+wrx -R /var/log/apache2 && \
    chown root:root -R /var/log/apache2 && \
    chmod g+w /var/run/apache2 && \
    chown root:root /var/log/apache2/bootstrap-access.log /var/log/apache2/bootstrap-error.log && \
    sed -i 's/80/8080/g' /etc/apache2/ports.conf && \
    sed -i 's/443/8443/g' /etc/apache2/ports.conf && \
    rm -f /etc/apache2/sites-enabled/000-default.conf

EXPOSE 8080

COPY docker/app/entrypoint.sh /usr/local/bin/entrypoint.sh
ENTRYPOINT entrypoint.sh
