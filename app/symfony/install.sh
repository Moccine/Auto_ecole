#!/bin/bash

# install dependencies
composer install

# Create database + update schema
echo ' ***************** Creation of the Database *********************'
php bin/console doctrine:database:create --if-not-exists
echo '***************** Creation of tables *********************'
php bin/console doctrine:migrations:migrate --no-interaction
echo ' ***************** Load fixture *********************'
php bin/console doctrine:fixtures:load --no-interaction
# import csv to database
echo ' ***************** Update var directory permission *********************'
# Update var directory permissions
chown www-data: ../* -R
