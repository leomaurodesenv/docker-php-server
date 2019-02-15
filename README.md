# Docker Container (PHP + Nginx)

In this repository, I explore some simple docker codes to develop your own container with PHP + Nginx.   

**Here you can find**:
- [Docker Installation](#docker-installation)
- [PHP Container](#php-container)
  - Download the PHP container
  - Running the PHP Built-In
  - Executing a PHP file
  - Executing a simple server
- [Creating your Server Container](#creating-your-server-container)
  - Creating a Ubuntu Server
  - Installing Nginx
  - Installing PHP
  - Configuring the server
  - Creating a working directory
  - Saving the container image
- [Saving your Container Image to File](#saving-your-container-image-to-file)
  - Saving a image in a TAR file
  - Split the file in small pieces
  - Loading a container image from a TAR file
- [Creating a Dockerfile](#creating-a-dockerfile)
- [Creating a Server with Docker Compose](#creating-a-server-with-docker-compose)

---
## Docker Installation

Important links: [DockerHub](http://hub.docker.com/), [Documentation](https://docs.docker.com/).   

Each Operating System (OS) have your own steps.   
Open the _Documentation_, search for `Get Docker > Docker CE > (your OS)`.   
**Note**: Docker CE (Community Edition), Docker EE (Enterprise Edition).   

**Post-installation steps for Linux**:   

```shell
# Create the 'docker group'
$ sudo groupadd docker
# Add your user to the 'docker group'
$ sudo usermod -aG docker $USER
```

**Hello World**:   

```shell
# Cheking if docker is working fine.
#  download and execute the container 'hello-world'
$ docker run hello-world
```

---
## PHP Container

This section introduces how to build PHP Built-In by the container.   

```shell
# Download the PHP container
#  download the container 'php' from DockerHuv
$ docker pull php

# Checking the PHP version
#  php: first 'php' means the container image name
#  php --version: command line
$ docker run php php --version

# Running the PHP container
#  run 'php' with -ti configuration
#  -t: Talk to you (TTY)
#  -i: STDIN
#  therefore, you can use the PHP terminal
$ docker run -ti php
$ echo 'Hellow World';
# Ctrl + C or 'exit' command to exit the container

# Executing a file 
#  -v: mapping your working directory to container directory
#  -w: define the virtual working directory of the container
#   note: the path in -v and -w is the same
#   because of this only links your real directory 
#   '~/www/' to a virtual one
#  php: first 'php' means the container name
#  php: run the PHP command on the file
#  file.php: PHP file inside of your '~/www/' directory
$ docker run -v ~/www:/usr/src/wd -w /usr/src/wd php php php.php

# Executing a simple server
#  -p: mapping your port '8080' to container port '8080'
#  php -S 0.0.0.0:8080: run a local host (0.0.0.0) on port 8080
$ docker run -p 8080:8080 -v ~/www:/usr/src/wd -w /usr/src/wd php php -S 0.0.0.0:8080
# open: 'http://localhost:8080' in the web browser. Enjoy!
```

---
## Creating your Server Container

How to create your own PHP server with Nginx.   
**Note**: these steps, I used the PHP 7.2, but works with any version.   

```shell
# Creating a Ubuntu Server
#  --name server: define 'server' as the container name
#  -d: run in background and print container ID
#  ubuntu: container image of Ubuntu
$ docker run --name server -itd -p 80:80 ubuntu

# Checking if the 'server' is running
#  ps: list all the active containers
$ docker ps

# Access the 'server' container
#  attach: connect to a container
#  server: container name or ID
$ docker attach server

# Installing Nginx
$ apt-get update
$ apt-get install nginx
#  checking Nginx version
$ nginx -v
#  out example: nginx version: nginx/1.14.0 (Ubuntu)
#  run Nginx
$ service nginx start

# Installing PHP
#  checking for PHP repositories
$ apt-cache search php | grep fpm
#  install your preferred PHP
$ apt-get install php-fpm
#  checking PHP version
$ php --version
# list the services to find the 'php'
$ service --status-all
#  run the php service, in my case:
$ service php7.2-fpm start

# Configuring your server
#  install your preferred terminal editor
#  like: nano, vim, ..., in my case:
$ apt-get install nano
#  open the Nginx configurations
$ nano /etc/nginx/sites-available/default
#  ~ the file editions are described below.
#  restart the Nginx
$ service nginx restart

# Creating your app working directory
$ mkdir /var/www/app
#  access the folder
$ cd /var/www/app
#  creating a PHP info page
$ echo "<?php phpinfo(); ?>" > index.php
#  restart the services
$ service nginx restart
$ service php7.2-fpm restart
#  open: 'http://localhost:80' in the web browser. Enjoy!
#  get out of the container
#  Ctrl + P + Q

# Saving the container image
#  list all running containers
#  copy the container ID
$ docker ps
#  commit the current container
#   container-ID: copied container ID
#   php-server: image name
#   v1.0.0: tag of current version
$ docker commit container-ID php-server:v1.0.0
#  list all local images
$ docker images
#  kill all docker containers
$ docker rm $(docker ps -a -q)
#  running the image
#   server2: container name
#   8080:80: localhost port
#   caution: stop the 'server' container, before run this image
#   or change the ports, e.g. 7070:80
#   php-server:v1.0.0: 'php-server' image of tag 'v1.0.0'
$ docker run --name server2 -itd -p 8080:80 -v ~/www:/var/www/app php-server:v1.0.0
#  run the services
$ docker exec server2 service nginx start
$ docker exec server2 service php7.2-fpm start
```

**File**: `/etc/nginx/sites-available/default`: (for PHP 7.2, see `fastcgi_pass`)
```txt
server {
    listen         80 default_server;
    listen         [::]:80 default_server;

    root /var/www/app;
    index index.php index.html;

    location ~* \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/run/php/php7.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}

```
---
## Saving your Container Image to File

How to save your container image in a TAR file.   

```shell
# Saving the image in a file.tar
#  php-server: image name
#  server-image.tar: file with the container image
$ docker save php-server > server-image.tar

# Split the file in small pieces
$ mkdir image
$ split --bytes=20M server-image.tar image/piece_
#  concat the pieces again
$ cat image/piece_* > server-image2.tar

# Loading the container image
docker load < server-image.tar 

```

---
## Creating a Dockerfile

Dockerfile is a file that defines the steps to construct your container.   
See how to create a [Dockerfile](dockerfile), I create a Dockerfile of this tutorial with few steps.   

---
## Creating a Server with Docker Compose

Docker Compose enables create multiple container with links each other.   
See how to use the [Docker Compose](docker-compose), I create a server with PHP, MySQL and phpMyAdmin.   

---
### Also look ~

- [MIT License](LICENSE)
- Create by Leonardo Mauro (leo.mauro.desenv@gmail.com)
- GitHub: [leomaurodesenv](https://github.com/leomaurodesenv/)
- Site: [Portfolio](http://leonardomauro.com/portfolio/)