# tlklan

This is the source code for http://lan.tlk.fi/

## Setting up a development environment

* Clone the repository
* Place a complete database dump (preferably with DROP DATABASE IF EXISTS and DROP TABLE IF EXISTS statements) in 
`provisioning/sql/database.sql`
* Run `vagrant up`
* (optional) copy dynamic image files from the production server (`files/image/*`)
* Browse to http://192.168.15.15/
