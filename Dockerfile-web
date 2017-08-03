FROM php:7.0-apache
MAINTAINER jose nazario <jose@monkey.org>
LABEL version="1.0" description="nosqli-labs Docker image"

# modifying from https://hub.docker.com/r/spittet/php-mongodb/

RUN apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv EA312927 && \
    echo "deb http://repo.mongodb.org/apt/debian wheezy/mongodb-org/3.2 main" | tee /etc/apt/sources.list.d/mongodb-org-3.2.list  && \
	apt-get -qq update && \
    apt-get install -y mongodb-org --no-install-recommends && \
	apt-get install -y libssl-dev unzip && \
    pecl install mongodb && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \	
    docker-php-ext-enable mongodb && \
	apt-get -qy autoremove && \
	apt-get clean && \
	mkdir -p /data/db && \
	/usr/bin/mongod --fork --syslog

COPY . /var/www/html
RUN sed -i s/"localhost:27017"/"mongo:27017"/g /var/www/html/user_lookup.php && \
	sed -i s/"localhost:27017"/"mongo:27017"/g /var/www/html/populate_db.php && \
	sed -i s/"localhost:27017"/"mongo:27017"/g /var/www/html/guess_the_key.php
