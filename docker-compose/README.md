# Docker Compose (PHP, MySQL and phpMyAdmin)

In this repository, I explore how to develop a server with PHP, MySQL and phpMyAdmin using Docker Compose.   

**Here you can find**:
- [Docker Compose Installation](#docker-compose-installation)
- [PHP Server Dockerfile](#php-server-dockerfile)
    - Dockerfile
- [Docker Compose File](#docker-compose-file)
    - Server Definitions
    - Running the server
    - phpMyAdmin login

---
## Docker Compose Installation

Important links: [DockerHub](http://hub.docker.com/), [Documentation](https://docs.docker.com/compose/).   

Each Operating System (OS) have your own steps.   
Open the _Documentation_, search for `Install Compose`.   
For Linux users use [Command Line](https://github.com/docker/compose/releases).   

---
## PHP Server Dockerfile

**1.** Create a file called `Dockerfile`, and apply the code below:

```shell
# Container image from DockerHub
# DockerHub: https://hub.docker.com/_/php
FROM php:7.2-apache

# Install necessary libs
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \

# Install php-core extension: mysqli
#  php extensions: https://docs.docker.com/samples/library/php/
RUN docker-php-ext-install mysqli
```

---
## Docker Compose File

**2.** Create a file called `docker-compose.yml`, and apply the code below:

```shell
# Docker Compose version
version: '3'

# Storage data
#  to save the database locally
volumes:
  data:

# Services (3): db, workbench, web
services:
  # -----------------------------------------
  # db: database
  db:
    # Container image
    image: mysql:5.6
    # Container name
    container_name: db-mysql
    # Mapping ports
    ports:
      - "3306:3306"
    # Storage data
    #  volume of MySQL is save locally in 'volumes:data'
    volumes:
      - data:/var/lib/mysql
    # Variables of this container image
    #  MYSQL_ROOT_PASSWORD: define root password
    #  MYSQL_DATABASE: your database name
    environment:
      - MYSQL_ROOT_PASSWORD: password
      - MYSQL_DATABASE: app_development

  # -----------------------------------------
  # workbench: visual tool for the database
  workbench:
    # Container image
    image: phpmyadmin/phpmyadmin:latest
    # Container name
    container_name: workbench-phpmyadmin
    # Dependence
    #  if db crash, workbench crash too
    depends_on:
      - db
    # Links
    #  means which 'workbench' can
    #  link/access the 'db'
    links:
      - db
    # Mapping ports
    # access: http://localhost:8081
    ports:
      - 8081:80
##    (uncomment) to connect an outside server
#    environment:
#      - PMA_ARBITRARY=1

  # -----------------------------------------
  # web: php server
  web:
    # Build the image
    # .: ./Dockerfile
    build: .
    # Container name
    container_name: web-php
    # Dependence
    depends_on:
      - db
    # Links
    links:
      - db
    # Storage data
    #  mapping my local volume to container
    #   local: ~/www
    #   container: /var/www/html
    volumes:
      - ~/www:/var/www/html
    # Mapping ports
    # access: http://localhost:8080
    ports:
      - 8080:80
    # Enable access the container
    #  -t: Talk to you (TTY)
    #  -i: STDIN
    stdin_open: true
    tty: true
```

**3.** Commands of the Docker Compose:

```shell
# Run the local docker-compose.yml
#  I prefer this command, to see the log
#  of the Docker Compose execution
$ docker-compose up

# Run the docker-compose in background
$ docker-compose up -d

# Stop the docker-compose
$ docker-compose stop
```

**4.** phpMyAdmin:

- Open browser: http://localhost:8081/
- Set-up the database:
    - host: db
    - user: root
    - password: password
    - _Defined in docker-compose.yml_
- Enjoy!

**5.** PHP:
- Open browser: http://localhost:8080/
- Enjoy!

Enjoy the Docker Compose!

---
### Also look ~

- Create by Leonardo Mauro (leo.mauro.desenv@gmail.com)
- GitHub: [leomaurodesenv](https://github.com/leomaurodesenv/)
- Site: [Portfolio](http://leonardomauro.com/portfolio/)