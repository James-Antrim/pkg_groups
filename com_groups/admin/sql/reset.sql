SET foreign_key_checks = 0;

#region Reset

#IF EXISTS in case a reset was performed separately to make a clean backup
DROP TABLE IF EXISTS
    `v7ocf_groups_attributes`,
    `v7ocf_groups_groups`,
    `v7ocf_groups_profile_attributes`,
    `v7ocf_groups_role_associations`,
    `v7ocf_groups_roles`,
    `v7ocf_groups_template_attributes`,
    `v7ocf_groups_templates`;

ALTER TABLE `v7ocf_users`
    DROP COLUMN IF EXISTS `alias`,
    DROP COLUMN IF EXISTS `content`,
    DROP COLUMN IF EXISTS `converisID`,
    DROP COLUMN IF EXISTS `editing`,
    DROP COLUMN IF EXISTS `forenames`,
    DROP COLUMN IF EXISTS `published`,
    DROP COLUMN IF EXISTS `surnames`;

# even if the primary is in its original state this statement should not cause any issues
ALTER TABLE `v7ocf_user_usergroup_map`
    DROP CONSTRAINT `PRIMARY`,
    DROP COLUMN IF EXISTS `id`,
    DROP CONSTRAINT IF EXISTS `entry`,
    ADD PRIMARY KEY (`user_id`, `group_id`);
#endregion

SET foreign_key_checks = 1;