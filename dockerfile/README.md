# Dockerfile (PHP + Nginx)

In this repository, I describe how to develop your own container with PHP + Nginx using the Dockerfile.   

**Here you can find**:
- [Creating a Dockerfile](#creating-a-dockerfile)
    - Nginx contigurations
    - Dockerfile
        - Define the startup image
        - Define the 'maintainer'
        - Installing PHP and Nginx
        - Setting Nginx configurations
        - Running the services
    - Build the Dockerfile
        - Construct the image from the Dockerfile
        - Running the PHP server
- [Build with URL](#build-with-url)
    - Build from GitHub

---
## Creating a Dockerfile

How to create a Dockerfile to automate the creation of your PHP server.   
**Note**: You can build the container image from a ulr.   

1. Create a file called `default` (with Nginx contigurations), and apply the code below:

```shell
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

2. Create a file called `Dockerfile`, and apply the code below:

```shell
# Define the startup image
#  FROM (container image)
#  container image: ubuntu
FROM ubuntu

# Define the 'maintainer'
#  MAINTAINER (author)
#  author: Leonardo Mauro
MAINTAINER Leonardo Mauro

# Installing PHP and Nginx
#  RUN (command): run command in container
#   the command run only ONCE in container
#   usually, used for configurations and installations
RUN apt-get update
RUN apt-get install -y nginx
RUN apt-get install -y php7.2-fpm

# Copying Nginx configurations
#  COPY (origin) (destination)
#  origin: ./default
#  destination: (container) /etc/nginx/sites-available/default
COPY default /etc/nginx/sites-available/default

# Running the services
#  CMD (command)
#  command: service nginx start && service php5-fpm start && /bin/bash
#  -note: only can run ONE cmd in the dockerfile
#   the container startup with this command
CMD service nginx start && service php7.2-fpm start && /bin/bash
```

3. Open Terminal in the same folder of the _Dockerfile_.

```shell
# Construct the image from the Dockerfile
#  docker build [options] [path]
#  options: -t php-server2
#   -t php-server2: create the image 
#   'php-server2' from the Dockerfile
#  path: . (current path)
$ docker build -t php-server2 .

# Running the PHP server
$ docker run --name server3 -itd -p 8080:80 -v ~/www:/var/www/app php-server2

# open: 'http://localhost:8080' in the web browser. Enjoy!
```

---
## Build with URL

```shell
# Build this example
#  docker build [options] [url]
#  options: none
#   the image without name <none>, only hash
#  url: https://github.com/leomaurodesenv/php-nginx-container.git
#   #:dockerfile: means git:master > dockerfile (folder)
$ docker build https://github.com/leomaurodesenv/docker-php-server.git#:dockerfile
```

Enjoy the Dockerfile!

---
### Also look ~

- Create by Leonardo Mauro (leo.mauro.desenv@gmail.com)
- GitHub: [leomaurodesenv](https://github.com/leomaurodesenv/)
- Site: [Portfolio](http://leonardomauro.com/portfolio/)
