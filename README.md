# tlklan

This is the source code for https://lan.tlk.fi/

## Setting up a development environment

* Clone the repository
* Place a complete database dump in `provisioning/sql/database.sql`, ask someone if you don't have one
* Run `vagrant up`
* Run `vagrant ssh`, then `cd /vagrant`
* Run `composer install`
* Run `cp .env.example .env`
* (optional) copy dynamic image files from the production server (`files/images/originals/*`) to make profile images work
* (optional) copy CMS attachments from the production server (`files/cms/attachments/*`) to make uploaded document files work
* Browse to http://192.168.15.15/

## Configuration

The application reads configuration from the environment, or optionally from a `.env` file if one is found in the 
project root directory. See `.env.example` for the available variables.
