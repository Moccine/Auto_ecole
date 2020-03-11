#!/bin/bash

pkill -f 'ssh-agent -s'
eval `ssh-agent -s`

ssh-add ~/.ssh/github-Auto_ecole
git pull origin $(git rev-parse --abbrev-ref HEAD)

cd app/symfony
composer install
php bin/console doctrine:migrations:migrate
php bin/console cache:clear
php bin/console assets:install public

cd ../integration/ && yarn run start:prod

# -----------------------------------
#!/bin/bash
echo -e 'Charchement de la clef de déploiement github'
pkill -f 'ssh-agent -s'
eval "$(ssh-agent -s)"
ssh-add ~/.ssh/github-muzeo

echo -e 'Téléchargement des mises à jour'
git pull origin $(git rev-parse --abbrev-ref HEAD)

echo -e 'Installation des dépendances du Front'
cd app/integration
yarn install
yarn run encore production

echo -e 'Installation des dépendances Symfony'
cd ../symfony
composer install

echo -e 'Mise a jour de base de donées'
php bin/console doctrine:migrations:migrate --no-interaction

echo -e 'Installation des assets'
php bin/console assets:install

echo -e 'Clear du cache'
php bin/console cache:clear
php bin/console cache:warmup

echo -e 'Mise à jour des permissions'
chown -R apache: var/cache
chmod -R +w var
