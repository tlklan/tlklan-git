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

## Deploying

The instructions here may or may not be correct, always take a backup before touching the production code.

1. Run `git pull` in the deployment directory
2. Manually run any new SQL statements from `protected/data`. If you change the database structure, you must 
manually clear the cache (the schema is cached for some time) by deleting everything in `protected/runtime/cache`.
3. Run `php protected/yiic.php findmessages` if there have been any new `Yii::t()` calls added


## Alternative using docker

1. Add db dump as `provisioning/sql/startup.sql`
2. Run `docker-compose build`
3. Run `docker-compose up -d`
4. Open `http://localhost:8082/`
5. Ask skug for more details ;)
