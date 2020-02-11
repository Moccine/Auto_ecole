#!/bin/bash

# Copy config files
cp docker-compose.override.yml.dist docker-compose.override.yml
cp app/symfony/.env.dist app/symfony/.env
cp .env.dist .env

# build symfony app
docker-compose run apache ./install.sh

cd app/integration/ && yarn run start:dev
