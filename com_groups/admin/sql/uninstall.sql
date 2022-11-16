SET foreign_key_checks = 0;

DROP TABLE IF EXISTS
    `#__groups_attributes`,
    `#__groups_categories`,
    `#__groups_content`,
    `#__groups_fields`,
    `#__groups_profile_associations`,
    `#__groups_profile_attributes`,
    `#__groups_profiles`,
    `#__groups_role_associations`,
    `#__groups_roles`,
    `#__groups_template_attributes`,
    `#__groups_templates`,
    `#__groups_types`;

SET foreign_key_checks = 1;