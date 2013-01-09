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

# Change collation on tlk_submissions
ALTER TABLE `tlk_submissions`
	COLLATE='utf8_general_ci';

# Add location column to tlk_lans
ALTER TABLE `tlk_lans`
	ADD COLUMN `location` VARCHAR(50) NOT NULL AFTER `end_date`;

# Add is_founder column to tlk_users
ALTER TABLE `tlk_users`
	ADD COLUMN `is_founder` TINYINT(1) NOT NULL DEFAULT '0' AFTER `has_werket_login`;

# Add committee table
CREATE TABLE `tlk_committee` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`user_id` INT(10) NOT NULL,
	`year` SMALLINT NOT NULL,
	`position` VARCHAR(50) NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `user_id` (`user_id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

# Add a foreign key constraints (needs proper data first)
ALTER TABLE `tlk_committee`
	ADD CONSTRAINT `committee_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `tlk_users` (`id`) ON UPDATE CASCADE ON DELETE NO ACTION;

#
# 2012-11-23
#
# Created seasons table and populate it
CREATE TABLE `tlk_seasons` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(20) NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

INSERT INTO `tlk_seasons` (`id`, `name`) VALUES (1, '2009-2010');
INSERT INTO `tlk_seasons` (`id`, `name`) VALUES (2, '2010-2011');
INSERT INTO `tlk_seasons` (`id`, `name`) VALUES (3, '2011-2012');
INSERT INTO `tlk_seasons` (`id`, `name`) VALUES (4, '2012-2013');

# Created payments table
CREATE TABLE `tlk_payments` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`user_id` INT(10) NOT NULL,
	`lan_id` INT(10) NOT NULL,
	`season_id` INT(10) NULL DEFAULT NULL,
	`payment_type` ENUM('single','season') NOT NULL,
	PRIMARY KEY (`id`),
	CONSTRAINT `payments_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `tlk_users` (`id`) ON UPDATE CASCADE ON DELETE NO ACTION,
	CONSTRAINT `payments_lan_id_fk` FOREIGN KEY (`lan_id`) REFERENCES `tlk_lans` (`id`) ON UPDATE CASCADE ON DELETE NO ACTION,
	CONSTRAINT `payments_season_id_fk` FOREIGN KEY (`season_id`) REFERENCES `tlk_seasons` (`id`) ON UPDATE CASCADE ON DELETE NO ACTION
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

# Added season_id column to the tlk_lans table
ALTER TABLE `tlk_lans`
	ADD COLUMN `season_id` INT NOT NULL AFTER `id`;

# Populated tlk_lans.season_id
UPDATE `tlk_lans` SET `season_id`=1 WHERE  `id`=1;
UPDATE `tlk_lans` SET `season_id`=1 WHERE  `id`=2;
UPDATE `tlk_lans` SET `season_id`=1 WHERE  `id`=3;
UPDATE `tlk_lans` SET `season_id`=2 WHERE  `id`=4;
UPDATE `tlk_lans` SET `season_id`=2 WHERE  `id`=5;
UPDATE `tlk_lans` SET `season_id`=2 WHERE  `id`=6;
UPDATE `tlk_lans` SET `season_id`=2 WHERE  `id`=7;
UPDATE `tlk_lans` SET `season_id`=3 WHERE  `id`=8;
UPDATE `tlk_lans` SET `season_id`=3 WHERE  `id`=9;
UPDATE `tlk_lans` SET `season_id`=3 WHERE  `id`=10;
UPDATE `tlk_lans` SET `season_id`=3 WHERE  `id`=11;
UPDATE `tlk_lans` SET `season_id`=4 WHERE  `id`=13;
UPDATE `tlk_lans` SET `season_id`=4 WHERE  `id`=14;

# Allow NULL values for season_id
ALTER TABLE `tlk_lans`
	CHANGE COLUMN `season_id` `season_id` INT NULL AFTER `id`;

# Remove season_id from the Assembly LAN
UPDATE `tlk_lans` SET `season_id`=NULL WHERE  `id`=12;

# Add foreign key constraint
ALTER TABLE `tlk_lans`
	ADD CONSTRAINT `lans_season_id_fk` FOREIGN KEY (`season_id`) REFERENCES `tlk_seasons` (`id`) ON UPDATE CASCADE ON DELETE SET NULL;

# Renamed payment_type to type
ALTER TABLE `tlk_payments`
	ALTER `payment_type` DROP DEFAULT;
ALTER TABLE `tlk_payments`
	CHANGE COLUMN `payment_type` `type` ENUM('single','season') NOT NULL AFTER `season_id`;

# Added index on tlk_users.name
ALTER TABLE `tlk_users`
	ADD INDEX `name` (`name`);

# Added nickname column to tlk_users and populate it
ALTER TABLE `tlk_users`
	ADD COLUMN `nick` VARCHAR(25) NOT NULL AFTER `username`;

UPDATE tlk_users SET nick = username;

# Drop name, nick and email from registrations (they're useless these days)
ALTER TABLE `tlk_registrations`
	DROP COLUMN `name`,
	DROP COLUMN `email`,
	DROP COLUMN `nick`;

# Drop "confirmed" and "deleted" columns as they're nto used
ALTER TABLE `tlk_registrations`
	DROP COLUMN `confirmed`,
	DROP COLUMN `deleted`;

#
# 2012-12-25
#
# Added image_id column to tlk_users
ALTER TABLE `tlk_users`
	ADD COLUMN `image_id` INT NOT NULL AFTER `password`;
ALTER TABLE `tlk_users`
	CHANGE COLUMN `image_id` `image_id` INT(11) NULL DEFAULT NULL AFTER `password`;
UPDATE tlk_users SET image_id = NULL

# Add foreign key constraint
ALTER TABLE `tlk_users`
	ADD CONSTRAINT `users_image_id_fk` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`) ON UPDATE CASCADE ON DELETE SET NULL;

#
# 2012-08-12
#
# Added "never showed" column
ALTER TABLE `tlk_registrations`
	ADD COLUMN `never_showed` TINYINT(1) NOT NULL DEFAULT '0' AFTER `date`;

#
# 2012-11-12
#
# Created a new view for easy access to voting results per submission, per user
# or per competition
CREATE ALGORITHM = UNDEFINED VIEW `tlk_submission_votes` AS 
SELECT tlk_competitions.id AS competition_id, tlk_submissions.user_id AS user_id, tlk_submissions.id 
AS submission_id, COUNT(tlk_votes.id) AS vote_count
FROM tlk_submissions
INNER JOIN tlk_competitions ON tlk_submissions.compo_id = tlk_competitions.id
LEFT OUTER
JOIN tlk_votes ON tlk_votes.submission_id = tlk_submissions.id
GROUP BY tlk_submissions.id ;

#
# 2012-12-12
#
# Remove the is_founder column - it is not needed anymore
ALTER TABLE `tlk_users`
	DROP COLUMN `is_founder`;

# Change column data type for consistency with other 0/1 columns
ALTER TABLE `tlk_submissions`
	CHANGE COLUMN `disqualified` `disqualified` TINYINT(1) NOT NULL DEFAULT '0' AFTER `comments`;

# Move the competition_id column so it matches the schema for tlk_competitors
ALTER TABLE `tlk_actual_competitors`
	ALTER `registration_id` DROP DEFAULT;
ALTER TABLE `tlk_actual_competitors`
	CHANGE COLUMN `registration_id` `registration_id` INT(10) NOT NULL AFTER `id`;

# Change to smaller data type
ALTER TABLE `tlk_competitions`
	ALTER `display_order` DROP DEFAULT;
ALTER TABLE `tlk_competitions`
	CHANGE COLUMN `display_order` `display_order` TINYINT NOT NULL AFTER `lan_id`;

# Rename column to competition_id for consistency
ALTER TABLE `tlk_votes`
	ALTER `compo_id` DROP DEFAULT;
ALTER TABLE `tlk_votes`
	CHANGE COLUMN `compo_id` `competition_id` INT(11) NOT NULL AFTER `submission_id`;

# Rename column to competition_id for consistency
ALTER TABLE `tlk_submissions`
	DROP FOREIGN KEY `submissions_compo_id_fk`;
ALTER TABLE `tlk_submissions`
	CHANGE COLUMN `compo_id` `competition_id` INT(11) NULL DEFAULT NULL AFTER `id`,
	ADD CONSTRAINT `submissions_compo_id_fk` FOREIGN KEY (`competition_id`) REFERENCES `tlk_competitions` (`id`) ON UPDATE CASCADE ON DELETE SET NULL;

# The tlk_submission_votes view must be updated due to the above change
ALTER DEFINER=`root`@`localhost` VIEW `tlk_submission_votes` AS SELECT tlk_competitions.id AS competition_id, tlk_submissions.user_id AS user_id, tlk_submissions.id 
AS submission_id, COUNT(tlk_votes.id) AS vote_count
FROM tlk_submissions
INNER JOIN tlk_competitions ON tlk_submissions.competition_id = tlk_competitions.id
LEFT OUTER
JOIN tlk_votes ON tlk_votes.submission_id = tlk_submissions.id
GROUP BY tlk_submissions.id  ;

# Set column width to 11 (for consistency and ability to add foreign keys)
ALTER TABLE `tlk_committee`
	ALTER `user_id` DROP DEFAULT;
ALTER TABLE `tlk_committee`
	CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT FIRST,
	CHANGE COLUMN `user_id` `user_id` INT(11) NOT NULL AFTER `id`;

# Remove the index on user_id
ALTER TABLE `tlk_committee`
	DROP INDEX `user_id`;

# Add a foreign key constraint
ALTER TABLE `tlk_committee`
	ADD CONSTRAINT `committee_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `tlk_users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;

# Added another foreign key constraint
ALTER TABLE `tlk_votes`
	ADD CONSTRAINT `votes_competition_id_fk` FOREIGN KEY (`competition_id`) REFERENCES `tlk_competitions` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;

# Remove table comments
ALTER TABLE `tlk_competitions`
	COMMENT='';
ALTER TABLE `tlk_competitors`
	COMMENT='';
ALTER TABLE `tlk_lans`
	COMMENT='';
ALTER TABLE `tlk_submissions`
	COMMENT='';

# Use utf8_general_ci everywhere
ALTER TABLE `tlk_competitions`
	COLLATE='utf8_general_ci',
	CONVERT TO CHARSET utf8;
ALTER TABLE `tlk_competitors`
	COLLATE='utf8_general_ci',
	CONVERT TO CHARSET utf8;
ALTER TABLE `tlk_lans`
	COLLATE='utf8_general_ci',
	CONVERT TO CHARSET utf8;
ALTER TABLE `tlk_votes`
	COLLATE='utf8_general_ci',
	CONVERT TO CHARSET utf8;
ALTER TABLE `tlk_submissions`
	ALTER `name` DROP DEFAULT;
ALTER TABLE `tlk_submissions`
	CHANGE COLUMN `name` `name` VARCHAR(30) NOT NULL AFTER `user_id`,
	CHANGE COLUMN `physical_path` `physical_path` TINYTEXT NULL AFTER `name`,
	CHANGE COLUMN `size` `size` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0' AFTER `physical_path`,
	CHANGE COLUMN `comments` `comments` TINYTEXT NOT NULL AFTER `size`;

#
# 2013-01-09
#
# Added a table for competition suggestions
CREATE TABLE `tlk_suggestions` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`name` VARCHAR(50) NOT NULL,
	`description` MEDIUMTEXT NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

# Create a suggestion votes table
CREATE TABLE `tlk_suggestion_votes` (
	`suggestion_id` INT(10) NOT NULL,
	`user_id` INT(10) NOT NULL,
	PRIMARY KEY (`suggestion_id`, `user_id`),
	CONSTRAINT `suggestion_votes_fk_suggestion_id` FOREIGN KEY (`suggestion_id`) REFERENCES `tlk_suggestions` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `suggestion_votes_fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `tlk_users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

# Add an index for searching only by suggestion_id
ALTER TABLE `tlk_suggestion_votes`
	ADD INDEX `suggestion_id` (`suggestion_id`);

# Add user_id column to the suggestions table
ALTER TABLE `tlk_suggestions`
	ADD COLUMN `user_id` INT NOT NULL AFTER `id`;

# Make it nullable so we can create the foreign key constraint
ALTER TABLE `tlk_suggestions`
	CHANGE COLUMN `user_id` `user_id` INT(11) NULL DEFAULT NULL AFTER `id`;

# Add foreign key constraint
ALTER TABLE `tlk_suggestions`
	ADD CONSTRAINT `suggestion_fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `tlk_users` (`id`) ON UPDATE CASCADE ON DELETE SET NULL;