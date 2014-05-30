CREATE TABLE IF NOT EXISTS `cache` (
	`key` VARCHAR(64) NOT NULL,
	`value` TEXT NOT NULL,
	PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT '[temp] datové úložiště';
