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