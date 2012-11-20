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

/*
 *
 * tlk_users must be populated before running the next commands!
 *
 */

# Add user_id column to tlk_registrations
ALTER TABLE `tlk_registrations`
	ADD COLUMN `user_id` INT NOT NULL AFTER `lan_id`;

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

# Populate tlk_registrations.user_id
UPDATE tlk_registrations
INNER JOIN tlk_users ON tlk_users.name = tlk_registrations.name 
SET tlk_registrations.user_id = tlk_users.id;

# Add foreign key constraints between tlk_users and tlk_registration
ALTER TABLE `tlk_registrations`
	ADD CONSTRAINT `user_fk` FOREIGN KEY (`user_id`) REFERENCES `tlk_users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;

# Manually set user_id for non-matching rows
UPDATE `tlk_registrations` SET `user_id`=10 WHERE  `id`=12;
UPDATE `tlk_registrations` SET `user_id`=12 WHERE  `id`=71;
UPDATE `tlk_registrations` SET `user_id`=24 WHERE  `id`=93;
UPDATE `tlk_registrations` SET `user_id`=14 WHERE  `id`=319;
UPDATE `tlk_registrations` SET `user_id`=25 WHERE  `id`=327;

# Add user_id column to tlk_submissions
ALTER TABLE `tlk_submissions`
	ADD COLUMN `user_id` INT(11) NULL DEFAULT NULL AFTER `submitter_id`;

# Populate tlk_submissions.user_id
UPDATE tlk_submissions
LEFT OUTER JOIN tlk_registrations ON (tlk_registrations.id = tlk_submissions.submitter_id) 
SET tlk_submissions.user_id = tlk_registrations.user_id;

# Remove user_id and its old foreign key constraints
ALTER TABLE `tlk_submissions`
	DROP COLUMN `submitter_id`,
	DROP FOREIGN KEY `submitter_fk`;

# Remove foreign key constraint from tlk_users
ALTER TABLE `tlk_votes`
	DROP FOREIGN KEY `votes_voter_id_fk`;

# Change tlk_votes.voter_id to point to tlk_users.id 
UPDATE tlk_votes
LEFT OUTER JOIN tlk_registrations ON tlk_votes.voter_id = tlk_registrations.id 
SET voter_id = tlk_registrations.user_id;

# Drop foreign key constraint from tlk_competitors
ALTER TABLE `tlk_competitors`
	DROP FOREIGN KEY `reg_id_fk`;

# Fix foreign keys on tlk_votes
ALTER TABLE `tlk_votes`
	DROP FOREIGN KEY `votes_submission_id_fk`;
ALTER TABLE `tlk_votes`
	ADD CONSTRAINT `votes_submission_id_fk` FOREIGN KEY (`submission_id`) REFERENCES `tlk_submissions` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE `tlk_votes`
	ADD CONSTRAINT `votes_voter_id_fk` FOREIGN KEY (`voter_id`) REFERENCES `tlk_users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;

# Fix foreign keys on tlk_submissions
ALTER TABLE `tlk_submissions`
	DROP FOREIGN KEY `submissions_compo_fk`;

ALTER TABLE `tlk_submissions`
	CHANGE COLUMN `compo_id` `compo_id` INT(11) NULL DEFAULT NULL AFTER `id`;

ALTER TABLE `tlk_submissions`
	ADD CONSTRAINT `submissions_compo_id_fk` FOREIGN KEY (`compo_id`) REFERENCES `tlk_competitions` (`id`) ON UPDATE CASCADE ON DELETE SET NULL;

ALTER TABLE `tlk_submissions`
	ADD CONSTRAINT `submissions_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `tlk_users` (`id`) ON UPDATE CASCADE ON DELETE SET NULL;

# Fix foreign keys on tlk_registrations
ALTER TABLE `tlk_registrations`
	ADD CONSTRAINT `registration_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `tlk_users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE `tlk_registrations`
	DROP FOREIGN KEY `lan_fk`;
ALTER TABLE `tlk_registrations`
	ADD CONSTRAINT `lan_fk` FOREIGN KEY (`lan_id`) REFERENCES `tlk_lans` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;

# Fix foreign keys on tlk_competitors
ALTER TABLE `tlk_competitors`
	DROP FOREIGN KEY `compo_id_fk`;
ALTER TABLE `tlk_competitors`
	ADD CONSTRAINT `competitor_competition_id_fk` FOREIGN KEY (`competition_id`) REFERENCES `tlk_competitions` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	ADD CONSTRAINT `competitor_registration_id_fk` FOREIGN KEY (`registration_id`) REFERENCES `tlk_registrations` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;

# Fix foreign keys on tlk_competitions
ALTER TABLE `tlk_competitions`
	DROP FOREIGN KEY `compo_lan_fk`;
ALTER TABLE `tlk_competitions`
	ADD CONSTRAINT `compo_lan_fk` FOREIGN KEY (`lan_id`) REFERENCES `tlk_lans` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;

# Drop the alternate nicks table
DROP TABLE `tlk_alternate_nicks`;

# Fix foreign keys on tlk_actual_competitors
ALTER TABLE `tlk_actual_competitors`
	ADD CONSTRAINT `actual_competitor_competition_id_fk` FOREIGN KEY (`competition_id`) REFERENCES `tlk_competitions` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	ADD CONSTRAINT `actual_competitor_registration_id_fk` FOREIGN KEY (`registration_id`) REFERENCES `tlk_registrations` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;

# Add some indexes
ALTER TABLE `tlk_users`
	ADD INDEX `email_username` (`email`, `username`);
ALTER TABLE `tlk_competitions`
	ADD INDEX `lan_id_votable_signupable_deadline` (`lan_id`, `votable`, `signupable`, `deadline`);
ALTER TABLE `tlk_registrations`
	ADD INDEX `name_nick` (`nick`, `name`);
ALTER TABLE `tlk_lans`
	ADD INDEX `enabled` (`enabled`);
ALTER TABLE `tlk_votes`
	ADD INDEX `voter_id_compo_id` (`voter_id`, `compo_id`);

#
# 2012-11-20
#
# Add size column to tlk_submissions
ALTER TABLE `tlk_submissions`
	ADD COLUMN `size` BIGINT UNSIGNED NOT NULL DEFAULT '0' AFTER `physical_path`;