# Restructure this so that structure, data and references are separated
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `#__groups_attributes` (
    `id`          INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `typeID`      INT(11) UNSIGNED    NOT NULL,
    `label`       VARCHAR(100)        NOT NULL,
    `showLabel`   TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
    `icon`        VARCHAR(255)        NOT NULL DEFAULT '',
    `showIcon`    TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
    `options`     TEXT,
    `ordering`    INT(3) UNSIGNED     NOT NULL DEFAULT 0,
    `published`   TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
    `required`    TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    `viewLevelID` INT(10) UNSIGNED             DEFAULT 1,
    PRIMARY KEY (`id`),
    UNIQUE (`label`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;

# profileID & categoryID remain signed because of the users and categories table dependencies
CREATE TABLE IF NOT EXISTS `#__groups_categories` (
    `id`        INT(11) NOT NULL,
    `profileID` INT(11) NOT NULL,
    PRIMARY KEY (`id`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;

# profileID remains signed because of the users table dependency
CREATE TABLE IF NOT EXISTS `#__groups_content` (
    `id`        INT(11) UNSIGNED    NOT NULL,
    `profileID` INT(11)             NOT NULL,
    `featured`  TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__groups_fields` (
    `id`      INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `field`   VARCHAR(20)      NOT NULL,
    `options` TEXT,
    PRIMARY KEY (`id`),
    UNIQUE (`field`)
)
    ENGINE = INNODB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;

# profileID remains signed because of the users table dependency
CREATE TABLE IF NOT EXISTS `#__groups_profile_associations` (
    `id`                 INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `profileID`          INT(11)          NOT NULL,
    `role_associationID` INT(11) UNSIGNED NOT NULL,
    PRIMARY KEY (`ID`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;

# profileID remains signed because of the users table dependency
CREATE TABLE IF NOT EXISTS `#__groups_profile_attributes` (
    `id`          INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `profileID`   INT(11)             NOT NULL,
    `attributeID` INT(11) UNSIGNED    NOT NULL,
    `value`       TEXT,
    `published`   TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (`ID`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;

# id remains signed because of the users table dependency
CREATE TABLE IF NOT EXISTS `#__groups_profiles` (
    `id`             INT(11)             NOT NULL,
    `published`      TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    `canEdit`        TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    `contentEnabled` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    `alias`          VARCHAR(190)        NOT NULL DEFAULT '',
    PRIMARY KEY (`id`),
    UNIQUE (`alias`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__groups_template_attributes` (
    `id`          INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `templateID`  INT(11) UNSIGNED    NOT NULL,
    `attributeID` INT(11) UNSIGNED    NOT NULL,
    `published`   TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
    `ordering`    INT(11) UNSIGNED    NOT NULL DEFAULT 0,
    `showLabel`   TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
    `showIcon`    TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__groups_templates` (
    `id`           INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `templateName` VARCHAR(100)     NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE (`templateName`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;

INSERT INTO `#__groups_attributes` (`id`, `typeID`, `label`, `showLabel`, `icon`, `showIcon`, `options`, `ordering`, `published`, `required`, `viewLevelID`)
VALUES (3, 4, 'Bild', 0, '', 0, '{"mode":1}', 1, 1, 0, 1),
       (5, 9, 'Namenszusatz (vor)', 0, '', 0, '{"hint":"Prof. Dr."}', 2, 1, 0, 1),
       (1, 8, 'Vorname', 0, '', 0, '{"hint":"Maxine"}', 3, 1, 0, 1),
       (2, 8, 'Nachname', 0, '', 0, '{"hint":"Mustermann"}', 4, 1, 1, 1),
       (7, 9, 'Namenszusatz (nach)', 0, '', 0, '{"hint":"M.Sc."}', 5, 1, 0, 1),
       (4, 6, 'Email', 1, 'icon-mail', 1, '{}', 6, 1, 1, 1),
       (6, 7, 'Telefon', 1, 'icon-phone', 1, '{"hint":"+49 (0) 641 309 1234"}', 7, 1, 0, 1),
       (8, 7, 'Fax', 1, 'icon-print', 1, '{"hint":"+49 (0) 641 309 1235"}', 8, 1, 0, 1),
       (9, 3, 'Homepage', 1, 'icon-new-tab', 1, '{"hint":"www.thm.de/fb/maxine-mustermann"}', 9, 1, 0, 1),
       (10, 1, 'Raum', 1, 'icon-home', 1, '{"hint":"A1.0.01"}', 10, 1, 0, 1);

INSERT INTO `#__groups_fields` (`id`, `field`, `options`)
VALUES (5, 'calendar', '{"calendarformat":"","showtime":"0","timeformat":"24","regex":""}');

INSERT INTO `#__groups_template_attributes` (`id`, `templateID`, `attributeID`, `published`, `ordering`, `showLabel`, `showIcon`)
VALUES (1, 1, 3, 1, 1, 0, 0),
       (2, 1, 5, 1, 2, 0, 0),
       (3, 1, 1, 1, 3, 0, 0),
       (4, 1, 2, 1, 4, 0, 0),
       (5, 1, 7, 1, 5, 0, 0),
       (6, 1, 4, 1, 6, 1, 1),
       (7, 1, 6, 1, 7, 1, 1),
       (8, 1, 8, 1, 8, 1, 1),
       (9, 1, 9, 1, 9, 1, 1),
       (10, 1, 10, 1, 10, 1, 1);

INSERT INTO `#__groups_templates` (`id`, `templateName`)
VALUES (1, 'Default');

ALTER TABLE `#__groups_attributes`
    ADD CONSTRAINT `attributes_typeid` FOREIGN KEY (`typeID`)
        REFERENCES `#__groups_attribute_types` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    ADD CONSTRAINT `attributes_viewlevelid` FOREIGN KEY (`viewLevelID`) REFERENCES `#__viewlevels` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE;

ALTER TABLE `#__groups_categories`
    ADD CONSTRAINT `categories_categoryid` FOREIGN KEY (`id`) REFERENCES `#__categories` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    ADD CONSTRAINT `categories_profileid` FOREIGN KEY (`profileID`) REFERENCES `#__groups_profiles` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE;

ALTER TABLE `#__groups_content`
    ADD CONSTRAINT `content_contentid` FOREIGN KEY (`id`) REFERENCES `#__content` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    ADD CONSTRAINT `content_profileid` FOREIGN KEY (`profileID`) REFERENCES `#__groups_profiles` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE;

ALTER TABLE `#__groups_profile_associations`
    ADD CONSTRAINT `profileassociations_profileid` FOREIGN KEY (`profileID`) REFERENCES `#__groups_profiles` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    ADD CONSTRAINT `profileassociations_roleassociationid` FOREIGN KEY (`role_associationID`)
        REFERENCES `#__groups_role_associations` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE;

ALTER TABLE `#__groups_profile_attributes`
    ADD CONSTRAINT `profileattributes_attributeid` FOREIGN KEY (`attributeID`) REFERENCES `#__groups_attributes` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    ADD CONSTRAINT `profileattributes_profileid` FOREIGN KEY (`profileID`) REFERENCES `#__groups_profiles` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE;

ALTER TABLE `#__groups_profiles`
    ADD CONSTRAINT `profiles_userid` FOREIGN KEY (`id`) REFERENCES `#__users` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE;

ALTER TABLE `#__groups_template_attributes`
    ADD CONSTRAINT `templateattributes_templateid` FOREIGN KEY (`templateID`) REFERENCES `#__groups_templates` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    ADD CONSTRAINT `templateattributes_attributeid` FOREIGN KEY (`attributeID`) REFERENCES `#__groups_attributes` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE;