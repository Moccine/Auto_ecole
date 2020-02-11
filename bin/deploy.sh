#!/bin/bash

pkill -f 'ssh-agent -s'
eval `ssh-agent -s`

ssh-add ~/.ssh/github-oise-booking
git pull origin $(git rev-parse --abbrev-ref HEAD)

cd app/symfony
composer install
php bin/console doctrine:migrations:migrate
php bin/console cache:clear
php bin/console assets:install public

cd ../integration/ && yarn run start:prod
