SET foreign_key_checks = 0;

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
    DROP COLUMN `forenames`,
    DROP COLUMN `published`,
    DROP COLUMN `surnames`;

ALTER TABLE `v7ocf_user_usergroup_map`
    DROP CONSTRAINT `PRIMARY`,
    DROP COLUMN `id`,
    DROP CONSTRAINT `entry`,
    ADD PRIMARY KEY (`user_id`, `group_id`);

SET foreign_key_checks = 1;