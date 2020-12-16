#!/bin/bash

. /etc/apache2/envvars

# Run apache and discard output
apache2 -D FOREGROUND > /dev/null &

# Put access logs into container's stdout
tail -f /var/log/apache2/bootstrap-access.log &

# Put error logs into container's stderr
tail -f /var/log/apache2/bootstrap-error.log 1>&2 &

# Keep the container alive as long as apache hasn't died
while pgrep apache2; do
  sleep 10
done
