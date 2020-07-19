# From: https://medium.com/code-kings/docker-building-a-lamp-stack-9503c62d9214

FROM ubuntu:latest
ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update -y
RUN apt-get upgrade -y
RUN apt-get install -y apache2 
RUN apt-get install -y php 
RUN apt-get install -y php-dev 
RUN apt-get install -y php-mysql 
RUN apt-get install -y libapache2-mod-php 
RUN apt-get install -y php-curl 
RUN apt-get install -y php-json 
RUN apt-get install -y php-common 
RUN apt-get install -y php-mbstring 
#RUN apt-get install -y composer
#RUN curl -s "https://packagecloud.io/install/repositories/phalcon/stable/script.deb.sh" | /bin/bash
#RUN apt-get install -y software-properties-common
#RUN apt-get install -y php 7.2-phalcon
#COPY ./php.ini /etc/php/7.2/apache2/php.ini
COPY ./ws.local.conf /etc/apache2/sites-available/ws.local.conf
#COPY ./ports.conf /etc/apache2/ports.conf
RUN rm -rfv /etc/apache2/sites-enabled/*.conf
RUN ln -s /etc/apache2/sites-available/ws.local.conf /etc/apache2/sites-enabled/ws.local.conf

# Install MySQL
RUN apt-get install -y mysql-server
# RUN mysqladmin -u root password $MYSQL_ROOT_PASSWORD

# Setup Wolverine Search
# CMD /bin/bash /var/www/wolverine-search/ws/build/dockerinit

# CMD ["apachectl","-D","FOREGROUND"]
RUN a2enmod rewrite
EXPOSE 80
EXPOSE 443
EXPOSE 3306
