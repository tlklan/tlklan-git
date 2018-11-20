#!/bin/bash

{
	apt-get update

	# set database password to "root"
	debconf-set-selections <<< 'mysql-server mysql-server/root_password password root'
	debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'

	apt-get -y install php5-cli mysql-server php5-mysql apache2 libapache2-mod-php5 unzip
	
	wget https://getcomposer.org/download/1.7.3/composer.phar -O /usr/local/bin/composer
	chmod +x /usr/local/bin/composer
}

# > /dev/null 2>&1
