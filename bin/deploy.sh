#!/bin/bash
echo -e 'Loading the github deployment key'
pkill -f 'ssh-agent -s'
eval `ssh-agent -s`

ssh-add ~/.ssh/github-Auto_ecole

echo -e 'Download updates'
git pull origin $(git rev-parse --abbrev-ref HEAD)

echo -e 'Installing Symfony dependencies'
cd app/symfony
export APP_ENV=prod
export APP_DEBUG=0
export MYSQL_HOST=mysql
export MYSQL_PORT=3306
export MYSQL_DATABASE=auto_ecole
export MYSQL_USER=root
export MYSQL_PASSWORD=TLJqyA2t
export DATABASE_URL=mysql://root:TLJqyA2t@127.0.0.1:3306/auto_ecole

composer install --no-dev --optimize-autoloader
php bin/console doctrine:migrations:migrate

php bin/console cache:clear

echo -e 'Database update'
php bin/console doctrine:migrations:migrate

#echo -e 'Fixture'
#php bin/console doctrine:fixtures:load

echo -e 'Installation of assets'
php bin/console assets:install public


echo -e 'Installation of Front dependencies'
cd ../integration/ && yarn run start:prod

echo -e 'Clear du cache'
php bin/console cache:clear
php bin/console cache:warmup

echo -e 'Updating permissions'
#chmod -R +w var
chown www-data: ../* -R