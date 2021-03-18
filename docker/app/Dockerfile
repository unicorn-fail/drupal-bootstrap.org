FROM debian:buster-slim

RUN apt update && \
    apt install -y git wget apache2 composer php php-curl && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Create build dir
RUN mkdir -p /var/www/drupal-bootstrap.org

# Set workdir to build dir
WORKDIR /var/www/drupal-bootstrap.org

# Copy site code
COPY --chown=root:root docroot/ docroot/
COPY --chown=root:root composer* ./

# Fetch site dependencies
RUN /usr/bin/composer install

# Copy in basic settings.php.
COPY --chown=root:root docker/app/conf/drupal/* /var/www/drupal-bootstrap.org/docroot/sites/default/

# Set permissions on build and install Drush
RUN chown -R root:root docroot && \
    chmod -R 750 docroot && \
    wget https://github.com/drush-ops/drush/releases/download/8.1.18/drush.phar -O /usr/local/bin/drush && \
    chmod +x /usr/local/bin/drush

RUN ln -s /var/www/drupal-bootstrap.org-files /var/www/drupal-bootstrap.org/docroot/sites/default/files 

# Enable modules for FCGI and Disable php module
RUN a2enmod proxy_fcgi rewrite && a2dismod php7.3

COPY docker/app/conf/apache /etc/apache2/sites-enabled

RUN touch /var/log/apache2/bootstrap-access.log /var/log/apache2/bootstrap-error.log && \
    chmod g+wrx -R /var/log/apache2 /var/run/apache2 && \
    chown root:root -R /var/log/apache2 /var/log/apache2/bootstrap-access.log /var/log/apache2/bootstrap-error.log && \
    sed -i 's/80/8080/g' /etc/apache2/ports.conf && \
    sed -i 's/443/8443/g' /etc/apache2/ports.conf && \
    rm -f /etc/apache2/sites-enabled/000-default.conf

EXPOSE 8080

COPY docker/app/entrypoint.sh /usr/local/bin/entrypoint.sh
ENTRYPOINT entrypoint.sh
