FROM quay.io/kindlehl/php-fpm:7.2

RUN apt-get update && \
    apt-get install -y git mariadb-client && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN mkdir -p /var/www/drupal-bootstrap.org-repositories
RUN chown -R root:www-data /var/www/drupal-bootstrap.org-repositories
RUN chmod -R 770 /var/www/drupal-bootstrap.org-repositories
