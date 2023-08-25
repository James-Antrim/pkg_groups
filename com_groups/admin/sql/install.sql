#region Creation
CREATE TABLE IF NOT EXISTS `#__groups_attributes`
(
    `id`          INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `label_de`    VARCHAR(100)        NOT NULL,
    `label_en`    VARCHAR(100)        NOT NULL,
    `showLabel`   TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '0 => No, 1 => Yes',
    `icon`        VARCHAR(255)        NOT NULL DEFAULT '',
    `showIcon`    TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0 => No, 1 => Yes',
    `typeID`      INT(11) UNSIGNED    NOT NULL,
    `options`     TEXT COMMENT 'A JSON string containing the optional parameters specific to the of the attribute.',
    `context`     TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0 => Both, 1 => Profile, 2 => Group',
    `viewLevelID` INT(10) UNSIGNED             DEFAULT 1,
    PRIMARY KEY (`id`),
    UNIQUE (`label_de`),
    UNIQUE (`label_en`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;

#todo integrate with usergroups?
# no unique keys for groups which may have the same name in different contexts.
CREATE TABLE IF NOT EXISTS `#__groups_groups`
(
    `id`      INT(10) UNSIGNED NOT NULL,
    `name_de` VARCHAR(100)     NOT NULL,
    `name_en` VARCHAR(100)     NOT NULL,
    PRIMARY KEY (`id`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__groups_person_attributes`
(
    `id`          INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `attributeID` INT(11) UNSIGNED    NOT NULL,
    `userID`      INT(11)             NOT NULL COMMENT 'Signed because of users table \'id\' fk.',
    `value`       TEXT,
    `published`   TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (`ID`),
    UNIQUE KEY `entry` (`attributeID`, `userID`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__groups_role_associations`
(
    `id`     INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `mapID`  INT(11) UNSIGNED NOT NULL,
    `roleID` INT(11) UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `entry` (`mapID`, `roleID`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__groups_roles`
(
    `id`       INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name_de`  VARCHAR(100)     NOT NULL,
    `name_en`  VARCHAR(100)     NOT NULL,
    `names_de` VARCHAR(100)     NOT NULL,
    `names_en` VARCHAR(100)     NOT NULL,
    `ordering` INT(3) UNSIGNED  NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `entry` (`name_de`, `name_en`),
    UNIQUE KEY `entries` (`names_de`, `names_en`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;

# modernize the table for ease of reference from the role associations table
ALTER TABLE `#__user_usergroup_map`
    DROP CONSTRAINT `PRIMARY`,
    ADD COLUMN `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    ADD PRIMARY KEY (`id`),
    MODIFY `group_id` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '~Foreign Key to #__usergroups.id' AFTER `id`,
    MODIFY `user_id` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '~Foreign Key to #__users.id' AFTER `group_id`,
    ADD UNIQUE KEY `entry` (`group_id`, `user_id`);

# add necessary columns to users
ALTER TABLE `#__users`
    ADD COLUMN `surnames`   VARCHAR(255)     DEFAULT NULL AFTER `email`,
    ADD COLUMN `forenames`  VARCHAR(255)     DEFAULT NULL AFTER `surnames`,
    ADD COLUMN `alias`      VARCHAR(255)     DEFAULT NULL AFTER `forenames`,
    ADD COLUMN `content`    TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 AFTER `block`,
    ADD COLUMN `editing`    TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 AFTER `content`,
    ADD COLUMN `published`  TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 AFTER `editing`,
    ADD COLUMN `converisID` INT(11) UNSIGNED DEFAULT NULL,
    ADD UNIQUE KEY (`alias`),
    ADD UNIQUE KEY (`converisID`);
#endregion

#region Fill

SET @address = 1;
SET @email = 4;
SET @hours = 5;
SET @html = 6;
SET @image = 7;
SET @link = 8;
SET @linkList = 9;
SET @phone = 10;
SET @supplement = 11;

# Forenames and surnames are now a part of the users table
INSERT INTO `#__groups_attributes` (`id`, `label_de`, `label_en`, `showLabel`, `icon`, `showIcon`, `typeID`, `options`, `context`, `viewLevelID`)
VALUES (1, 'Namenszusatz (nach)', 'Suffix', 0, '', 0, @supplement, '{"hint":"M.Sc."', 1, 1),
       (2, 'Namenszusatz (vor)', 'Prefix', 0, '', 0, @supplement, '{"hint":"Prof. Dr."}', 1, 1),
       (3, 'Bild', 'Picture', 0, '', 0, @image, '{}', 1, 1),
       (4, 'Anschrift', 'Address', 0, 'icon-location', 1, @address, '{}', 0, 1),
       (5, 'Büro', 'Office', 0, 'fa fa-home', 1, @linkList, '{}', 0, 1),
       (6, 'E-Mail', 'E-Mail', 0, 'fa fa-envelope', 1, @email, '{}', 0, 1),
       (7, 'weitere E-Mail', 'Additional E-Mail', 1, 'fa fa-envelope', 0, @email, '{}', 0, 1),
       (8, 'Fax', 'Fax', 0, 'fa fa-print', 1, @phone, '{}', 0, 1),
       (9, 'weiteres Fax', 'Additional Fax', 0, 'fa fa-print', 1, @phone, '{}', 0, 1),
       (10, 'Telefon', 'Telephone', 0, 'fa fa-phone', 1, @phone, '{}', 0, 1),
       (11, 'weiteres Telefon', 'Additional Telephone', 0, 'fa fa-phone', 1, @phone, '{}', 0, 1),
       (12, 'Homepage', 'Homepage', 0, 'fa fa-external-link-alt', 1, @link, '{}', 0, 1),
       (13, 'Aktuelles', 'Current Information', 1, 'fa fa-info', 0, @html, '{}', 0, 1),
       (14, 'Arbeitsgebiete', 'Areas of Activity', 1, '', 0, @linkList, '{}', 0, 1),
       (15, 'Fachgebiete', 'Fields', 1, 'fa fa-th-large', 0, @linkList, '{}', 0, 1),
       (16, 'Forschungsgebiete', 'Areas of Research', 1, '', 0, @linkList, '{}', 0, 1),
       (17, 'Funktionen', 'Duties', 1, 'fa fa-cog', 0, @linkList, '{}', 0, 1),
       (18, 'Labore', 'Laboratories', 1, '', 0, @linkList, '{}', 0, 1),
       (19, 'Sprechstunden', 'Consultation Hours', 0, 'fa fa-comment', 1, @hours, '{}', 0, 1),
       (20, 'Veranstaltungen', 'Classes', 1, '', 0, @linkList, '{}', 0, 1),
       (21, 'weitere Informationen', 'Additional Information', 1, 'fa fa-info', 0, @html, '{}', 0, 1),
       (22, 'Weiterführende Links', 'Additional Links', 1, 'fa fa-external-link-alt', 0, @linkList, '{}', 0, 1),
       (23, 'zur Person', 'Personal Information', 1, 'fa fa-user', 0, @html, '{}', 0, 1);

INSERT INTO `#__groups_roles` (`id`, `name_de`, `name_en`, `names_de`, `names_en`, `ordering`)
VALUES (1, 'Dekan', 'Dean', 'Dekane', 'Deans', 1),
       (2, 'Dekanin', 'Dean', 'Dekaninnen', 'Deans', 2),
       (3, 'Prodekan', 'Vice Dean', 'Prodekane', 'Vice Deans', 3),
       (4, 'Prodekanin', 'Vice Dean', 'Prodekaninnen', 'Vice Deans', 4),
       (5, 'Studiendekan', 'Dean of Studies', 'Studiendekane', 'Deans of Studies', 5),
       (6, 'Studiendekanin', 'Dean of Studies', 'Studiendekaninnen', 'Deans of Studies', 6),
       (7, 'Leitung', 'Management', 'Leitung', 'Management', 7),
       (8, 'Koordinator:in', 'Coordinator', 'Koordinator:innen', 'Coordinators', 8),
       (9, 'Professor:in', 'Professor', 'Professor:innen', 'Professors', 9),
       (10, 'Sekretariat', 'Secretariat', 'Sekretariat', 'Secretariat', 10),
       (11, 'Mitarbeiter:in', 'Staff', 'Mitarbeiter:innen', 'Staff', 11),
       (12, 'Lehrbeauftragte', 'Lecturer', 'Lehrbeauftragten', 'Lecturers', 12),
       (13, 'Studentische Mitarbeiter:in', 'Student Staff', 'Studentische Mitarbeiter:innen', 'Student Staff', 13),
       (14, 'Praktikant:in', 'Intern', 'Praktikant:innen', 'Interns', 14),
       (15, 'Schülerpraktikant:in', 'Student Intern', 'Schülerpraktikant:innen', 'Student Interns', 15),
       (16, 'Student:in', 'Student', 'Studenten:innen', 'Student', 16),
       (17, 'Ehemalige', 'Alumnus', 'Alumni', 'Alumni', 18);
#endregion

#region Reference
ALTER TABLE `#__groups_attributes`
    ADD CONSTRAINT `fk_attributes_viewLevelID` FOREIGN KEY (`viewLevelID`) REFERENCES `#__viewlevels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `#__groups_groups`
    ADD CONSTRAINT `fk_groups_groupID` FOREIGN KEY (`id`) REFERENCES `#__usergroups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `#__groups_profile_attributes`
    ADD CONSTRAINT `fk_pAttribs_attributeID` FOREIGN KEY (`attributeID`) REFERENCES `#__groups_attributes` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    ADD CONSTRAINT `fk_pAttribs_userID` FOREIGN KEY (`userID`) REFERENCES `#__users` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE;

ALTER TABLE `#__groups_role_associations`
    ADD CONSTRAINT `fk_rAssocs_roleID` FOREIGN KEY (`roleID`) REFERENCES `#__groups_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_rAssocs_groupID` FOREIGN KEY (`groupID`) REFERENCES `#__groups_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
#endregion