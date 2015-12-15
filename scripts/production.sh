#!/bin/sh

mkdir $(date +%F)

echo "Copying required files and folders"

cp -R ../app $(date +%F)
cp -R ../bower_components $(date +%F)
cp -R ../build $(date +%F)
cp -R ../cache $(date +%F)
cp -R ../log $(date +%F)
cp -R ../migrations $(date +%F)
cp -R ../node_modules $(date +%F)
cp -R ../public $(date +%F)
cp -R ../var $(date +%F)
cp  ../run.php $(date +%F)/.
cp  ../composer.json $(date +%F)/.
cp  ../composer.lock $(date +%F)/.
cp  ../bower.json $(date +%F)/.
cp  ../gulpfile.js $(date +%F)/.
cp  ../package.json $(date +%F)/.

# Copy production files
echo "Copying production files"

cp  /srv/phinx.yml $(date +%F)/.
cp /srv/app/config/settings.php $(date +%F)/app/config/settings.php


# Install vendors
echo "Running composer"
composer install -d $(date +%F)/ --no-dev --verbose --prefer-dist --optimize-autoloader --no-progress

# Run migration
echo 'Would run Migration at this point...'
#$(date +%F)/vendor/bin/phinx migrate -e production

# Move directory to /srv
echo "Moving directory"
mv $(date +%F) /srv

# Update permissions
echo "Updating permissions"
find storage -type d -exec chmod 0777 {} \;
find storage -type f -exec chmod 0666 {} \;

chmod -R 777 /srv/$(date +%F)/cache
chmod -R 777 /srv/$(date +%F)/log
chmod -R 777 /srv/$(date +%F)/var

# Symlink for web server
echo "Creating symlink for web server"


