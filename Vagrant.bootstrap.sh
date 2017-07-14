#!/usr/bin/env bash

apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv EA312927
echo "deb http://repo.mongodb.org/apt/ubuntu xenial/mongodb-org/3.2 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-3.2.list

apt-get -qq update
apt-get install -y --no-install-recommends \
    apache2 \
    libapache2-mod-php7.0 \
    php7.0 \
    git \
	libcurl4-openssl-dev \
	pkg-config \
	libssl-dev \
	libsslcommon2-dev \
	mongodb-org \
	php-mongodb
apt-get -qy autoremove
apt-get -qy clean 

# configure apache
a2enmod php7.0 rewrite
echo "ServerRoot /var/www/html" >> /etc/apache2/apache2.conf
echo "ServerName nosqlilab" >> /etc/apache2/httpd.conf
rm -f /var/www/html/index.html

# download and install nosqli-labs
cd && git clone https://github.com/digininja/nosqlilab.git && cd nosqlilab && cp -r * /var/www/html/

# start mongodb
# via https://www.digitalocean.com/community/tutorials/how-to-install-mongodb-on-ubuntu-16-04
cat > /etc/systemd/system/mongodb.service <<- EOF
[Unit]
Description=High-performance, schema-free document-oriented database
After=network.target

[Service]
User=mongodb
ExecStart=/usr/bin/mongod --quiet --config /etc/mongod.conf

[Install]
WantedBy=multi-user.target
EOF

systemctl start mongodb

# start apache
service apache2 reload
