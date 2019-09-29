# tlklan

This is the source code for https://lan.tlk.fi/

## Setting up a development environment

* Clone the repository
* Place a complete database dump (preferably with DROP DATABASE IF EXISTS and DROP TABLE IF EXISTS statements) in 
`provisioning/sql/database.sql`
* Run `vagrant up`
* Run `vagrant ssh`, then `cd /vagrant`
* Run `composer install`
* Run `cp .env.example .env`
* (optional) copy dynamic image files from the production server (`files/images/originals/*`) to make profile images work
* Browse to http://192.168.15.15/

## Configuration

The application reads configuration from the environment, or optionally from a `.env` file if one is found in the 
project root directory. See `.env.example` for the available variables.
