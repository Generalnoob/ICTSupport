CREATE DATABASE IF NOT EXISTS `ict-support` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ict-support`;

CREATE TABLE IF NOT EXISTS `accounts` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
  	`username` varchar(50) NOT NULL,
  	`password` varchar(255) NOT NULL,
  	`email` varchar(100) NOT NULL,
	`activation_code` varchar(50) NOT NULL DEFAULT '',
    `rememberme` varchar(255) NOT NULL DEFAULT '',
	`role` enum('Member','Admin', 'ICT Support') NOT NULL DEFAULT 'Member',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `accounts` (`id`, `username`, `password`, `email`, `role`) VALUES
(1, 'admin', '$2y$10$ZU7Jq5yZ1U/ifeJoJzvLbenjRyJVkSzmQKQc.X0KDPkfR3qs/iA7O', 'admin@example.com', 'Admin'),
(2, 'member', '$2y$10$7vKi0TjZimZyp/S5aCtK0eLsGagyIJVfpzGSFgRSqDGkJMxqoIYV.', 'member@example.com', 'Member');

CREATE TABLE IF NOT EXISTS `login_attempts` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`ip_address` varchar(255) NOT NULL,
	`attempts_left` tinyint(1) NOT NULL DEFAULT '5',
	`date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `login_attempts` ADD UNIQUE KEY `ip_address` (`ip_address`);

CREATE TABLE IF NOT EXISTS `languages` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
  	`language_name` varchar(255) NOT NULL,
  	`slug` varchar(255) NOT NULL,
	`active` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `languages` (`id`, `language_name`, `slug`, `active`) VALUES
(1, 'English', 'EN', '1');

CREATE TABLE IF NOT EXISTS `devices` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
  	`device_type` varchar(255) NOT NULL,
  	`department` varchar(255) NOT NULL,
  	`device_id` varchar(255) NOT NULL,
	`motherboard` varchar(255) NOT NULL,
	`ram` varchar(255) NOT NULL,
	`processor` varchar(255) NOT NULL,
	`gpu` varchar(255) NOT NULL,
	`sound_card` varchar(255) NOT NULL,
	`wifi` varchar(255) NOT NULL,
	`bluetooth` varchar(255) NOT NULL,
	`simcard` varchar(255) NOT NULL,
	`make` varchar(255) NOT NULL,
	`model` varchar(255) NOT NULL,
	`camera` varchar(255) NOT NULL,
	`os` varchar(255) NOT NULL,

    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `support` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
  	`device_id` varchar(255) NOT NULL,
	`user_id` varchar(255) NOT NULL,
  	`status` varchar(255) NOT NULL,
  	`problem` LONGTEXT NOT NULL,
	`solution` LONGTEXT NOT NULL,
	`date` varchar(255) NOT NULL,
	`priority` varchar(255) NOT NULL,

    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `support_chat` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`ticket_id` varchar(255) NOT NULL,
  	`response` LONGTEXT NOT NULL,
	`user_id` varchar(255) NOT NULL,
	`date` varchar(255) NOT NULL,

    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `config` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`url` varchar(255) NOT NULL,
  	`theme` varchar(255) NOT NULL,
	`logo` varchar(255) NOT NULL,

    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

