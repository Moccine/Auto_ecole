#!/usr/bin/env bash

if [ -f ./docker/data/database.sql ]; then
    echo "------- Database/Update import -------"
    sleep 10
    docker-compose exec mysql sh -c 'mysql -uroot -p$MYSQL_ROOT_PASSWORD $MYSQL_DATABASE < data/database.sql'
    mv ./docker/data/database.sql ./docker/data/database-imported.sql
    echo "------- Database imported/updated with success -------"
else
   echo "No sql update to perform, if you want to import one put your dump in /docker/data/database.sql"
fi

docker-compose exec apache sh -c 'npm install'
docker-compose exec apache sh -c 'yarn encore dev'

echo "------- Build finished -------"