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
