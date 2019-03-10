# Container image from DockerHub
# DockerHub: https://hub.docker.com/_/php
FROM php:7.2-apache

# Install necessary libs
RUN apt-get update
RUN apt-get install -y libfreetype6-dev
RUN apt-get install -y libjpeg62-turbo-dev
RUN apt-get install -y libpng-dev

# Install php-core extension: mysqli
#  php extensions: https://docs.docker.com/samples/library/php/
RUN docker-php-ext-install mysqli