CREATE TABLE `always_leads` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mastermind_name` varchar(255) DEFAULT NULL,
  `set` varchar(255) DEFAULT NULL,
  `always_leads` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `handlers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `handler_name` varchar(255) DEFAULT NULL,
  `handler_code` longtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `handler_name` (`handler_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `manually_fixed` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `list` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `played_counts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `list` varchar(255) DEFAULT NULL,
  `_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `store` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `list` varchar(255) DEFAULT NULL,
  `_id` varchar(255) DEFAULT NULL,
  `rec` longtext DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `unveiled_schemes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `partner` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
