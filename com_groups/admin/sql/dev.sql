SET foreign_key_checks = 0;

#region Reset
DROP TABLE IF EXISTS
    `v7ocf_groups_attributes`,
    `v7ocf_groups_groups`,
    `v7ocf_groups_profile_attributes`,
    `v7ocf_groups_role_associations`,
    `v7ocf_groups_roles`,
    `v7ocf_groups_types`;

ALTER TABLE `v7ocf_users`
    DROP COLUMN `alias`,
    DROP COLUMN `content`,
    DROP COLUMN `editing`,
    DROP COLUMN `functional`,
    DROP COLUMN `forenames`,
    DROP COLUMN `published`,
    DROP COLUMN `surnames`;

ALTER TABLE `v7ocf_user_usergroup_map`
    DROP CONSTRAINT `PRIMARY`,
    DROP COLUMN `id`,
    DROP CONSTRAINT `entry`,
    ADD PRIMARY KEY (`user_id`, `group_id`);
#endregion

#region Creation
CREATE TABLE IF NOT EXISTS `v7ocf_groups_attributes`
(
    `id`            INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `label_de`      VARCHAR(100)        NOT NULL,
    `label_en`      VARCHAR(100)        NOT NULL,
    `icon`          VARCHAR(255)        NOT NULL DEFAULT '',
    `typeID`        INT(11) UNSIGNED    NOT NULL,
    `configuration` TEXT COMMENT 'A JSON string containing the configuration of the attribute.',
    `context`       TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0 => Both, 1 => Profile, 2 => Group',
    `viewLevelID`   INT(10) UNSIGNED             DEFAULT 1,
    PRIMARY KEY (`id`),
    UNIQUE (`label_de`),
    UNIQUE (`label_en`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;

#todo integrate with usergroups?
# no unique keys for groups which may have the same name in different contexts.
CREATE TABLE IF NOT EXISTS `v7ocf_groups_groups`
(
    `id`      INT(10) UNSIGNED NOT NULL,
    `name_de` VARCHAR(100)     NOT NULL,
    `name_en` VARCHAR(100)     NOT NULL,
    PRIMARY KEY (`id`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `v7ocf_groups_profile_attributes`
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

CREATE TABLE IF NOT EXISTS `v7ocf_groups_role_associations`
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

CREATE TABLE IF NOT EXISTS `v7ocf_groups_roles`
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

CREATE TABLE IF NOT EXISTS `v7ocf_groups_types`
(
    `id`            INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `name_de`       VARCHAR(100)        NOT NULL,
    `name_en`       VARCHAR(100)        NOT NULL,
    `inputID`       TINYINT(2) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Resolves via Helpers\\Inputs.',
    `configuration` TEXT COMMENT 'A JSON string containing the configuration of the attribute type.',
    PRIMARY KEY (`id`),
    UNIQUE (`name_de`),
    UNIQUE (`name_en`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;

# modernize the table for ease of reference from the role associations table
ALTER TABLE `v7ocf_user_usergroup_map`
    DROP CONSTRAINT `PRIMARY`,
    ADD COLUMN `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    ADD PRIMARY KEY (`id`),
    MODIFY `group_id` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '~Foreign Key to #__usergroups.id' AFTER `id`,
    MODIFY `user_id` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '~Foreign Key to #__users.id' AFTER `group_id`,
    ADD UNIQUE KEY `entry` (`group_id`, `user_id`);

# add necessary columns to users
ALTER TABLE `v7ocf_users`
    ADD COLUMN `surnames`   VARCHAR(255) DEFAULT NULL AFTER `email`,
    ADD COLUMN `forenames`  VARCHAR(255) DEFAULT NULL AFTER `surnames`,
    ADD COLUMN `alias`      VARCHAR(255) DEFAULT NULL AFTER `forenames`,
    ADD COLUMN `content`    TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 AFTER `block`,
    ADD COLUMN `editing`    TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 AFTER `content`,
    ADD COLUMN `functional` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 AFTER `editing`,
    ADD COLUMN `published`  TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 AFTER `functional`,
    ADD UNIQUE KEY (`alias`);
#endregion

#region Fill
# Forenames and surnames are now a part of the users table
INSERT INTO `v7ocf_groups_attributes` (`id`, `label_de`, `label_en`, `icon`, `typeID`, `configuration`, `context`,
                                       `viewLevelID`)
VALUES (1, 'E-Mail', 'E-Mail', 'mail', 3, '{}', 0, 1),
       (2, 'Namenszusatz (nach)', 'Supplement (Post)', '', 4, '{"hint":"M.Sc."}', 1, 1),
       (3, 'Namenszusatz (vor)', 'Supplement (Pre)', '', 4, '{"hint":"Prof. Dr."}', 1, 1),
       (4, 'Profilbild', 'Profile Picture', '', 5, '{}', 1, 1),
       (5, 'Telefon', 'Telephone', 'phone', 6, '{}', 0, 1),
       (6, 'weiteres Telefon', 'Additional Telephone', 'phone', 6, '{}', 0, 1),
       (7, 'Fax', 'Fax', 'print', 6, '{}', 0, 1),
       (8, 'weiteres Fax', 'Additional Fax', 'print', 6, '{}', 0, 1),
       (9, 'weitere  E-Mail', 'Additional E-Mail', 'mail', 3, '{"hint":"maxine.mustermann@fb.thm.de"}', 0, 1),
       (10, 'Aktuelles', 'Current Information', 'info', 7, '{"buttons": 0}', 0, 1),
       (11, 'weitere  Informationen', 'Additional Information', 'info', 7, '{"buttons": 0}', 0, 1),
       (12, 'zur Person', 'Personal Information', 'user', 7, '{"buttons": 0}', 0, 1);

INSERT INTO `v7ocf_groups_roles` (`id`, `name_de`, `name_en`, `names_de`, `names_en`, `ordering`)
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

# Default messages and patterns derive from input classes
INSERT INTO `v7ocf_groups_types` (`id`, `name_de`, `name_en`, `inputID`, `configuration`)
VALUES (1, 'Einfaches Text', 'Simple Text', 1, '{}'),
       (2, 'Name', 'Name', 1,
        '{"message_de":"Namen dürfen nur aus Buchstaben und einzelne Apostrophen, Leer- und Minuszeichen und Punkten bestehen.","message_en":"Names may only consist of letters and singular apostrophes, hyphens, periods, and spaces.","pattern":"^([a-zß-ÿ]+ )*([a-zß-ÿ]+\'\')?[A-ZÀ-ÖØ-Þ](\\\\.|[a-zß-ÿ]+)([ |-]([a-zß-ÿ]+ )?([a-zß-ÿ]+\'\')?[A-ZÀ-ÖØ-Þ](\\\\.|[a-zß-ÿ]+))*$"}'),
       (3, 'E-Mail Adresse', 'E-Mail Address', 6, '{}'),
       (4, 'Namenszusatz', 'Name Supplement', 1,
        '{"message_de":"Der Namenszusatz/akademische Grad ist ungültig. Namenszusätze dürfen nur aus Buchstaben, Leerzeichen, Kommata, Punkte, Runde Klammer, Minus Zeichen und &dagger; bestehen.","message_en":"The name supplement / title is invalid. Name supplements may only consist of letters, spaces, commas, periods, round braces, minus signs and &dagger;.","pattern":"^[A-ZÀ-ÖØ-Þa-zß-ÿ ,.\\\\-()†]+$"}'),
       (5, 'Bild', 'Picture', 4, '{"accept":".bmp,.BMP,.gif,.GIF,.jpg,.JPG,.jpeg,.JPEG,.png,.PNG"}'),
       (6, 'Telefonnummer', 'Telephone Number', 7,
        '{"pattern":"^(\\\\+[\\\\d]+ ?)?( ?((\\\\(0?[\\\\d]*\\\\))|(0?[\\\\d]+(\\/| \\\\/)?)))?(([ \\\\-]|[\\\\d]+)+)$"}'),
       (7, 'Ausführlicher Text / HTML', 'Descriptive Text / HTML', 2, '{}'),
       (8, 'Datum', 'Date', 5, '{}');
#endregion

#region Reference
ALTER TABLE `v7ocf_groups_attributes`
    ADD CONSTRAINT `fk_attributes_typeID` FOREIGN KEY (`typeID`) REFERENCES `v7ocf_groups_types` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
    ADD CONSTRAINT `fk_attributes_viewLevelID` FOREIGN KEY (`viewLevelID`) REFERENCES `v7ocf_viewlevels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `v7ocf_groups_groups`
    ADD CONSTRAINT `fk_groups_groupID` FOREIGN KEY (`id`) REFERENCES `v7ocf_usergroups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `v7ocf_groups_profile_attributes`
    ADD CONSTRAINT `fk_pAttribs_attributeID` FOREIGN KEY (`attributeID`) REFERENCES `v7ocf_groups_attributes` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    ADD CONSTRAINT `fk_pAttribs_userID` FOREIGN KEY (`userID`) REFERENCES `v7ocf_users` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE;

ALTER TABLE `v7ocf_groups_role_associations`
    ADD CONSTRAINT `fk_rAssocs_mapID` FOREIGN KEY (`mapID`) REFERENCES `v7ocf_user_usergroup_map` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_rAssocs_roleID` FOREIGN KEY (`roleID`) REFERENCES `v7ocf_groups_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
#endregion

SET foreign_key_checks = 1;