#!/bin/bash

{
	# create the main configuration file
	cp -f /vagrant/provisioning/config/main.php /vagrant/protected/config

	# create a folder for submissions
	if [ ! -d /vagrant/files/submissions ]
	then
		mkdir /vagrant/files/submissions
	fi
}

# > /dev/null 2>&1
