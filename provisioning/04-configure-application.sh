#!/bin/bash

set -e

{
	# create the main configuration file
	cp -f /vagrant/provisioning/config/main.php /vagrant/protected/config

    # change ownership of /media
    chown vagrant:vagrant /media

	# create a directory and a symlink for submissions (path is hardcoded in the database)
	if [ ! -d /media/Storage ]
	then
		mkdir /media/Storage
	fi
	
	if [ ! -L /media/Storage/submissions ]
	then
	    ln -s /vagrant/files/submissions /media/Storage/submissions
	fi
}

# > /dev/null 2>&1
