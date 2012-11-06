#
# 2012-09-07
#
# Add published property
ALTER TABLE `cms_node`
	ADD COLUMN `published` TINYINT(1) NOT NULL DEFAULT '1' AFTER `name`;

ALTER TABLE `cms_node`
	ADD COLUMN `level` VARCHAR(255) NOT NULL DEFAULT 'page' AFTER `published`;

ALTER TABLE `cms_node`
	CHANGE COLUMN `level` `level` VARCHAR(255) NOT NULL DEFAULT 'page' AFTER `name`;

ALTER TABLE `cms_attachment`
	ADD COLUMN `nodeId` INT(10) UNSIGNED NOT NULL AFTER `created`,
	DROP COLUMN `contentId`,
	DROP INDEX `contentId`;

#
# 2012-10-03
#
# Added a column indicating whether a competition can be voted on
ALTER TABLE `tlk_competitions`
	ADD COLUMN `votable` TINYINT(1) NOT NULL DEFAULT '0' AFTER `full_name`;

# Added a column indicitaing how long voting will be possible (and when 
# it's possible to display results)
ALTER TABLE `tlk_competitions`
	ADD COLUMN `deadline` TIMESTAMP NULL DEFAULT NULL AFTER `votable`;

#
# 2012-10-04
#
# Added a new table for registering to competitions while on the LAN
CREATE TABLE `tlk_actual_competitors` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`competition_id` INT(10) NOT NULL,
	`registration_id` INT(10) NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `compo_id` (`competition_id`),
	INDEX `fk_registration_id` (`registration_id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

# Added "signupable" column which indicates whether it's possible to register
# for the competition
ALTER TABLE `tlk_competitions`
	ADD COLUMN `signupable` TINYINT(1) NOT NULL DEFAULT '0' AFTER `votable`;

#
# 2012-11-06
#
# Added users table
CREATE TABLE `tlk_users` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(75) NOT NULL,
	`email` VARCHAR(50) NOT NULL,
	`username` VARCHAR(25) NOT NULL,
	`password` CHAR(32) NULL DEFAULT NULL,
	`has_werket_login` TINYINT(1) NOT NULL DEFAULT '0',
	`date_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `username` (`username`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=512;

# Change collation on tlk_registrations and its columns
ALTER TABLE `tlk_registrations`
	COLLATE='utf8_general_ci';
ALTER TABLE `tlk_registrations`
	ALTER `name` DROP DEFAULT,
	ALTER `email` DROP DEFAULT,
	ALTER `nick` DROP DEFAULT,
	ALTER `device` DROP DEFAULT;
ALTER TABLE `tlk_registrations`
	CHANGE COLUMN `name` `name` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci' AFTER `user_id`,
	CHANGE COLUMN `email` `email` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci' AFTER `name`,
	CHANGE COLUMN `nick` `nick` VARCHAR(30) NOT NULL COLLATE 'utf8_general_ci' AFTER `email`,
	CHANGE COLUMN `device` `device` ENUM('desktop','laptop') NOT NULL COLLATE 'utf8_general_ci' AFTER `nick`;

# Add foreign key constraints between tlk_users and tlk_registration
ALTER TABLE `tlk_registrations`
	ADD CONSTRAINT `user_fk` FOREIGN KEY (`user_id`) REFERENCES `tlk_users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;