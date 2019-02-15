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
RUN apt-get update
RUN apt-get install -y nginx
RUN apt-get install -y php7.2-fpm
RUN mkdir /var/www/app

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
