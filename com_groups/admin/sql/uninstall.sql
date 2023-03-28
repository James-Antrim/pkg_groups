SET
foreign_key_checks = 0;

DROP TABLE IF EXISTS
    `#__groups_attributes`,
    `#__groups_categories`,
    `#__groups_content`,
    `#__groups_fields`,
    `#__groups_person_associations`,
    `#__groups_person_attributes`,
    `#__groups_persons`,
    `#__groups_role_associations`,
    `#__groups_roles`,
    `#__groups_template_attributes`,
    `#__groups_templates`,
    `#__groups_types`;

SET
foreign_key_checks = 1;