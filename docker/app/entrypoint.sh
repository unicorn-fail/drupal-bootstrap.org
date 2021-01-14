#!/bin/bash


touch /var/log/apache2/bootstrap-access.log /var/log/apache2/bootstrap-error.log 

# Put access logs into container's stdout
tail -f /var/log/apache2/bootstrap-access.log &

# Put error logs into container's stderr
tail -f /var/log/apache2/bootstrap-error.log 1>&2 &

. /etc/apache2/envvars

# Run apache via exec syscall
exec apache2 -D FOREGROUND 
