CREATE TABLE IF NOT EXISTS `#__albo_atti` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,                 -- nome atto
  `document_number` VARCHAR(50) NOT NULL,        -- numero documento
  `albo_number` VARCHAR(50) DEFAULT NULL,        -- numero albo pretorio
  `document_date` DATE NOT NULL,                 -- data documento
  `publish_start` DATE NOT NULL,                 -- inizio pubblicazione
  `publish_end` DATE NOT NULL,                   -- fine pubblicazione
  `category` VARCHAR(100) DEFAULT NULL,          -- categoria (per ora testo semplice)
  `state` TINYINT(1) NOT NULL DEFAULT 1,         -- 1=pubblicato, 0=non pubblicato
  `ordering` INT(11) NOT NULL DEFAULT 0,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
