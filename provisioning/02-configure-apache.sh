#!/bin/bash

set -e

{
	# disable default site
	a2dissite 000-default

	# install sites
	cp /vagrant/provisioning/etc/apache2/sites-available/tlklan.conf /etc/apache2/sites-available
	a2ensite tlklan

	# enable modules and restart
	a2enmod rewrite expires rewrite
	service apache2 restart
}

# > /dev/null 2>&1
