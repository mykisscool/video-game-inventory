CREATE TABLE `video_game_inventory`.`game` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(128) NOT NULL,
  `image` VARCHAR(256) NULL,
  `system` VARCHAR(128) NOT NULL,
  `genre` VARCHAR(256) NULL,
  `developer` VARCHAR(256) NULL,
  `description` TEXT NULL,
  `released_on` DATE NULL,
  `notes` VARCHAR(256) NULL,  
  `completed` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `video_game_inventory_uqk_1` (`title`, `system`),
  KEY `video_game_inventory_idx_1` (`completed`),
  KEY `video_game_inventory_idx_2` (`system`),
  KEY `video_game_inventory_idx_3` (`created_at`),
  KEY `video_game_inventory_idx_4` (`released_on`))
  ENGINE = MyISAM ;
GRANT ALL PRIVILEGES ON `video_game_inventory`.* to 'video_gamer'@'localhost' IDENTIFIED BY 'mikeiscool!';
