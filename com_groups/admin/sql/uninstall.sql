SET foreign_key_checks = 0;

DROP TABLE IF EXISTS
    `#__groups_attributes`,
    `#__groups_categories`,
    `#__groups_content`,
    `#__groups_fields`,
    `#__groups_profile_attributes`,
    `#__groups_role_associations`,
    `#__groups_roles`,
    `#__groups_template_attributes`,
    `#__groups_templates`;

ALTER TABLE `#__user_usergroup_map`
    DROP CONSTRAINT `PRIMARY`,
    DROP COLUMN `id`,
    DROP CONSTRAINT `entry`,
    ADD PRIMARY KEY (`user_id`, `group_id`);

ALTER TABLE `#__users`
    DROP COLUMN `alias`,
    DROP COLUMN `content`,
    DROP COLUMN `editing`,
    DROP COLUMN `forenames`,
    DROP COLUMN `published`,
    DROP COLUMN `surnames`;

SET foreign_key_checks = 1;