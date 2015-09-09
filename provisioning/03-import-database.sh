#!/bin/bash

{
	# create the database
	mysql -uroot -proot -e "CREATE DATABASE IF NOT EXISTS tlk_lan CHARACTER SET utf8 COLLATE utf8_general_ci;"

	# import the dump
	mysql -uroot -proot tlk_lan < /vagrant/provisioning/sql/database.sql
}

# > /dev/null 2>&1
