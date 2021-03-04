FROM debian:buster-slim

RUN apt update && \
    apt install -y git wget apache2 php php-curl && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

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
