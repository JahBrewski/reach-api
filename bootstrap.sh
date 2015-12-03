#!/usr/bin/env bash

# Use single quotes instead of double quotes to make it work with special-character passwords
PASSWORD='12345678'
PROJECTFOLDER='myproject'

# create project folder
#sudo mkdir "/var/www/html/${PROJECTFOLDER}"

# update / upgrade
sudo apt-get update
sudo apt-get -y upgrade

# install apache 2.5 and php 5.5
sudo apt-get install -y apache2
sudo apt-get install -y php5

# install mysql and give password to installer
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password $PASSWORD"
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $PASSWORD"
sudo apt-get -y install mysql-server
sudo apt-get install php5-mysql

# configure mysql and load dummy data
sudo mysql -uroot --password=$PASSWORD -e "create database reach_app2"
sudo mysql -uroot --password=$PASSWORD -e "create user reachdb identified by 'reachdb123'"
sudo mysql -uroot --password=$PASSWORD -e "grant all privileges on reach_app2.* to reachdb"
sudo mysql -uroot --password=$PASSWORD -e "flush privileges"
sudo mysql -uroot --password=$PASSWORD reach_app2 < /var/www/html/dummy_data-xxx.sql

# install phpmyadmin and give password(s) to installer
# for simplicity I'm using the same password for mysql and phpmyadmin
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/dbconfig-install boolean true"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/app-password-confirm password $PASSWORD"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/admin-pass password $PASSWORD"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/app-pass password $PASSWORD"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2"
sudo apt-get -y install phpmyadmin

# CREATE SSL CERT

#domain=192.168.33.22
#commonname=$domain
#
#country=US
#state=Colorado
#locality=""
#organization=BrewerDigital
#organizationalunit=""
#email=joel@brewerdigital.com
#
#password=secretsauce
#
#sudo mkdir /etc/apache2/ssl
#echo "Generating key request for $domain"
#
##Generate a key
#sudo openssl genrsa -des3 -passout pass:$password -out /etc/apache2/ssl/$domain.key 2048 -noout
#
##Remove passphrase from the key. Comment the line out to keep the passphrase
#echo "Removing passphrase from key"
#sudo openssl rsa -in /etc/apache2/ssl/$domain.key -passin pass:$password -out /etc/apache2/ssl/$domain.key
#
##Create the request
#echo "Creating CSR"
#sudo openssl req -new -key /etc/apache2/ssl/$domain.key -out /etc/apache2/ssl/$domain.csr -passin pass:$password \
#  -subj "/C=$country/ST=$state/L=$locality/O=$organization/OU=$organizationalunit/CN=$commonname/emailAddress=$email"
#
#echo "---------------------------"
#echo "-----Below is your CSR-----"
#echo "---------------------------"
#echo
#sudo cat /etc/apache2/ssl/$domain.csr
#
#echo
#echo "---------------------------"
#echo "-----Below is your Key-----"
#echo "---------------------------"
#echo
#sudo cat /etc/apache2/ssl/$domain.key
#
#sudo openssl x509 -req -days 365 -in /etc/apache2/ssl/$domain.csr -signkey /etc/apache2/ssl/$domain.key -out /etc/apache2/ssl/$domain.crt
#
## END CREATE CERT

# setup hosts file
VHOST=$(cat <<EOF
<VirtualHost *:80>
    DocumentRoot "/var/www/html"
    <Directory "/var/www/html">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>

<VirtualHost *:443>
    DocumentRoot "/var/www/html"
    <Directory "/var/www/html">
        AllowOverride All
        Require all granted
    </Directory>
    SSLEngine on
    SSLCertificateFile "/etc/apache2/ssl/192.168.33.22.crt"
    SSLCertificateKeyFile "/etc/apache2/ssl/192.168.33.22.key"
</VirtualHost>

EOF
)
echo "${VHOST}" > /etc/apache2/sites-available/000-default.conf

# enable mod_rewrite
sudo a2enmod rewrite

# enable ssl
sudo a2enmod ssl

# enable mod_headers
sudo a2enmod headers

# restart apache
service apache2 restart

# install git
sudo apt-get -y install git

# install php5-curl
sudo apt-get install php5-curl

# install Composer
curl -s https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
