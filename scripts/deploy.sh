#!/usr/bin/env bash

# Expects first $1 argument with name of tag

if [ -z ${1} ]; then
    echo "No tag was passed. Exiting.";
    exit;
fi

cd /srv/phpminds-website
git checkout tags/$1
composer install --no-dev --optimize-autoloader
vendor/bin/phinx migrate -e production
sudo /etc/init.d/php7.1-fpm restart
sudo service nginx restart