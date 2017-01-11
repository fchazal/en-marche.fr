#!/bin/bash
set -e

# Change www-data's uid & guid to be the same as directory in host
usermod -u `stat -c %u /www` www-data || true
groupmod -g `stat -c %g /www` www-data || true

if [ "$1" = 'apache2-foreground' ]; then
    # Let's start apache
    apache2-foreground
else
    # Change to user www-data
    su www-data -s /bin/bash -c "$*"
fi

chown -R www-data . var/cache var/logs var/sessions
