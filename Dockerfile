FROM registry.access.redhat.com/ubi8/php-72

ENV DOCUMENTROOT "/docroot"
ENV DB_NAME "bootstrap"
ENV DB_USERNAME "bootstrap"
ENV DB_PASSWORD "dev"
ENV GIT_CLONE_PATH "/var/www/drupal-bootstrap.org-repositories"

USER 0

# Copy site code
COPY . .
COPY conf/settings.local.php docroot/sites/default/settings.local.php

RUN curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
  && curl -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
  && php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }" \
  && php /tmp/composer-setup.php --filename composer --install-dir /usr/local/bin

RUN composer install && \
    wget https://github.com/drush-ops/drush/releases/download/8.1.18/drush.phar -O /usr/local/bin/drush && \
    chmod +x /usr/local/bin/drush && \
    mkdir -p /var/www/drupal-bootstrap.org-repositories && \
    chown -R 1001:0 /var/www/drupal-bootstrap.org-repositories && \
    chmod -R 770 /var/www/drupal-bootstrap.org-repositories

RUN mkdir docroot/sites/default/files

USER 1001

EXPOSE 8080

CMD /usr/libexec/s2i/run
