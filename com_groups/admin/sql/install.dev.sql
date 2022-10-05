CREATE TABLE IF NOT EXISTS `v7ocf_groups_groups` (
    `id`      INT(10) UNSIGNED    NOT NULL,
    `name_de` VARCHAR(100)        NOT NULL,
    `name_en` VARCHAR(100)        NOT NULL,
    `ignore`  TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
# no unique keys for groups which may have the same name in different contexts.
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `v7ocf_groups_role_associations` (
    `id`      INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `groupID` INT(11) UNSIGNED NOT NULL,
    `roleID`  INT(11) UNSIGNED NOT NULL,
    PRIMARY KEY (`id`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `v7ocf_groups_roles` (
    `id`        INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `name_de`   VARCHAR(100)        NOT NULL,
    `name_en`   VARCHAR(100)        NOT NULL,
    `names_de`  VARCHAR(100)        NOT NULL,
    `names_en`  VARCHAR(100)        NOT NULL,
    `ordering`  INT(3) UNSIGNED     NOT NULL,
    `protected` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `entry` (`name_de`, `name_en`),
    UNIQUE KEY `entries` (`names_de`, `names_en`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;

INSERT INTO `v7ocf_groups_roles` (`id`, `name_de`, `name_en`, `names_de`, `names_en`, `ordering`, `protected`)
VALUES (1, 'Mitglied', 'Member', 'Mitglieder', 'Members', 17, 1),
       (2, 'Dekan', 'Dean', 'Dekane', 'Deans', 1, 0),
       (3, 'Dekanin', 'Dean', 'Dekaninnen', 'Deans', 2, 0),
       (4, 'Prodekan', 'Vice Dean', 'Prodekane', 'Vice Deans', 3, 0),
       (5, 'Prodekanin', 'Vice Dean', 'Prodekaninnen', 'Vice Deans', 4, 0),
       (6, 'Studiendekan', 'Dean of Studies', 'Studiendekane', 'Deans of Studies', 5, 0),
       (7, 'Studiendekanin', 'Dean of Studies', 'Studiendekaninnen', 'Deans of Studies', 6, 0),
       (8, 'Leitung', 'Management', 'Leitung', 'Management', 7, 0),
       (9, 'Koordinator:in', 'Coordinator', 'Koordinator:innen', 'Coordinators', 8, 0),
       (10, 'Professor:in', 'Professor', 'Professor:innen', 'Professors', 9, 0),
       (11, 'Sekretariat', 'Secretariat', 'Sekretariat', 'Secretariat', 10, 0),
       (12, 'Mitarbeiter:in', 'Staff', 'Mitarbeiter:innen', 'Staff', 11, 0),
       (13, 'Lehrbeauftragte', 'Lecturer', 'Lehrbeauftragten', 'Lecturers', 12, 0),
       (14, 'Studentische Mitarbeiter:in', 'Student Staff', 'Studentische Mitarbeiter:innen', 'Student Staff', 13, 0),
       (15, 'Praktikant:in', 'Intern', 'Praktikant:innen', 'Interns', 14, 0),
       (16, 'Schülerpraktikant:in', 'Student Intern', 'Schülerpraktikant:innen', 'Student Interns', 15, 0),
       (17, 'Student:in', 'Student', 'Studenten:innen', 'Student', 16, 0),
       (18, 'Ehemalige', 'Alumnus', 'Alumni', 'Alumni', 18, 0);

ALTER TABLE `v7ocf_groups_groups`
    ADD CONSTRAINT `groups_groupID` FOREIGN KEY (`id`) REFERENCES `v7ocf_usergroups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `v7ocf_groups_role_associations`
    ADD CONSTRAINT `ra_roleID` FOREIGN KEY (`roleID`) REFERENCES `v7ocf_groups_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `ra_groupID` FOREIGN KEY (`groupID`) REFERENCES `v7ocf_groups_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;