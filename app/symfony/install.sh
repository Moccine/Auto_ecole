#!/bin/bash

# install dependencies
composer install

# Create database + update schema
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
# import csv to database

# Update var directory permissions
chown -R www-data: .
