FROM quay.io/tag1consulting/modphp74-ubi8-base

ENV DOCUMENTROOT "/web"
ENV DB_NAME "bootstrap"
ENV DB_USERNAME "bootstrap"
ENV DB_PASSWORD "dev"
ENV GIT_CLONE_PATH "/var/www/drupal-bootstrap.org-repositories"

USER 0

# Copy site code
COPY . .

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

COPY conf/settings.local.php web/sites/default/settings.local.php

RUN { \
  echo "if (file_exists(__DIR__ . '/settings.local.php')) {"; \
  echo "  include_once __DIR__ . '/settings.local.php';"; \
  echo "}"; \
} >> web/sites/default/settings.php

USER 1001

EXPOSE 8080

CMD /usr/libexec/s2i/run
