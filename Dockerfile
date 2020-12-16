FROM debian:buster

RUN apt update && \
    apt install -y composer git wget apache2 php php-curl && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Enable modules for FCGI
RUN a2enmod proxy_fcgi 

# Disable php module
RUN a2dismod php7.3

# Create build dir
RUN mkdir /var/www/drupal-bootstrap.org-build

# Set workdir to build dir
WORKDIR /var/www/drupal-bootstrap.org-build

# Copy site code
ADD --chown=root:www-data docroot/ docroot/
COPY --chown=root:www-data composer* ./

RUN ls -l

RUN composer install

COPY conf/apache /etc/apache2/conf-enabled

# Copy in basic settings.php. This ought to be included in the source repo
COPY --chown=root:www-data conf/drupal/* /var/www/drupal-bootstrap.org-build/docroot/sites/default/

RUN mkdir docroot/sites/default/files

# Set permissions on build
RUN chown -R root:www-data docroot && \
    chmod -R 750 docroot && \
    chmod -R 770 docroot/sites/default/files

# Install Drush
RUN wget https://github.com/drush-ops/drush/releases/download/8.1.18/drush.phar -o /usr/local/bin/drush && \
    chmod +x /usr/local/bin/drush

EXPOSE 80

COPY ./entrypoint.sh /usr/local/bin/entrypoint.sh
ENTRYPOINT entrypoint.sh
