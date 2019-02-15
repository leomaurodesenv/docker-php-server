# !/bin/bash

echo "Running PHP server FROM php-server container ..."
docker run --name php-server-container -itd -p 8080:80 -v ~/www:/var/www/app php-server