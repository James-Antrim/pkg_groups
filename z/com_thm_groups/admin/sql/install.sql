# Restructure this so that structure, data and references are separated
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

# profileID & categoryID remain signed because of the users and categories table dependencies
CREATE TABLE IF NOT EXISTS `#__thm_groups_categories` (
  `id`        INT(11) NOT NULL,
  `profileID` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

# profileID remains signed because of the users table dependency
CREATE TABLE IF NOT EXISTS `#__thm_groups_content` (
  `id`        INT(11)    UNSIGNED NOT NULL,
  `profileID` INT(11)             NOT NULL,
  `featured`  TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

ALTER TABLE `#__thm_groups_categories`
  ADD CONSTRAINT `categories_categoryid` FOREIGN KEY (`id`) REFERENCES `#__categories` (`id`)
  ON UPDATE CASCADE
  ON DELETE CASCADE,
  ADD CONSTRAINT `categories_profileid` FOREIGN KEY (`profileID`) REFERENCES `#__thm_groups_profiles` (`id`)
  ON UPDATE CASCADE
  ON DELETE CASCADE;

ALTER TABLE `#__thm_groups_content`
  ADD CONSTRAINT `content_contentid` FOREIGN KEY (`id`) REFERENCES `#__content` (`id`)
  ON UPDATE CASCADE
  ON DELETE CASCADE,
  ADD CONSTRAINT `content_profileid` FOREIGN KEY (`profileID`) REFERENCES `#__thm_groups_profiles` (`id`)
  ON UPDATE CASCADE
  ON DELETE CASCADE;