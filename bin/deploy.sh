#!/bin/bash
echo -e 'Charchement de la clef de déploiement github'
pkill -f 'ssh-agent -s'
eval `ssh-agent -s`

ssh-add ~/.ssh/github-Auto_ecole

echo -e 'Téléchargement des mises à jour'
git pull origin $(git rev-parse --abbrev-ref HEAD)

echo -e 'Installation des dépendances Symfony'
cd app/symfony
composer install
php bin/console doctrine:migrations:migrate
php bin/console cache:clear

echo -e 'Mise a jour de base de donées'
php bin/console doctrine:migrations:migrate --no-interaction

echo -e 'Installation des assets'
php bin/console assets:install public


echo -e 'Installation des dépendances du Front'
cd ../integration/ && yarn run start:prod

echo -e 'Clear du cache'
php bin/console cache:clear
php bin/console cache:warmup

echo -e 'Mise à jour des permissions'
chown -R apache: var/cache
chmod -R +w var
