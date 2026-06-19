CREATE TABLE IF NOT EXISTS `#__albo_categorie` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `state` tinyint NOT NULL DEFAULT 1,
  `ordering` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__albo_atti` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `document_number` varchar(50) NOT NULL,
  `albo_number` int NOT NULL DEFAULT 0,
  `albo_year` int NOT NULL DEFAULT 0,
  `document_date` date NOT NULL,
  `publish_start` date NOT NULL,
  `publish_end` date NOT NULL,
  `category` int NOT NULL DEFAULT 0,
  `state` tinyint NOT NULL DEFAULT 1,
  `ordering` int NOT NULL DEFAULT 0,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int NOT NULL DEFAULT 0,
  `file` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_albo_year_number` (`albo_year`, `albo_number`),
  KEY `idx_category` (`category`),
  KEY `idx_state` (`state`),
  KEY `idx_publish_dates` (`publish_start`, `publish_end`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `#__albo_categorie` (`id`, `title`, `state`, `ordering`) VALUES
(2, 'Determinazione Dirigente 2025', 1, 2),
(3, 'Nomine Personale ATA', 1, 1),
(5, 'Delibere Consiglio dell''Istituzione 2025', 1, 3),
(6, 'Graduatorie', 1, 0),
(7, 'Determinazioni Dirigente 2026', 1, 0),
(8, 'Delibere Consiglio dell''Istituzione 2026', 1, 0),
(9, 'Pubblicazioni PNRR', 1, 0);
