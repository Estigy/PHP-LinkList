SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sort` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `subtitle` text NOT NULL,
  `description` text NOT NULL,
  `keywords` text NOT NULL,
  `status` enum('online','offline') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `slug` (`slug`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `categories` (`id`, `parent_id`, `sort`, `title`, `slug`, `subtitle`, `description`, `keywords`, `status`) VALUES
(1, 0, 0, 'Test-Kategorie', 'test-kategorie', 'Dies ist Ihre erste Kategorie', 'Hier kommt die Beschreibung hin', 'Kategorie', 'online');

CREATE TABLE `linkitems` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `keywords` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `screenshot` varchar(255) NOT NULL,
  `contact_name` varchar(255) NOT NULL,
  `contact_address` varchar(255) NOT NULL,
  `contact_zip` varchar(12) NOT NULL,
  `contact_city` varchar(255) NOT NULL,
  `contact_country` varchar(64) NOT NULL,
  `contact_phone` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `date_entry` datetime NOT NULL,
  `date_update` datetime DEFAULT NULL,
  `date_screenshot` datetime DEFAULT NULL,
  `date_activationmail` datetime DEFAULT NULL,
  `token` char(32) DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  FULLTEXT KEY `searchIndex` (`title`,`description`,`keywords`,`url`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `linkitems` (`id`, `title`, `slug`, `description`, `keywords`, `url`, `category_id`, `screenshot`, `contact_name`, `contact_address`, `contact_zip`, `contact_city`, `contact_country`, `contact_phone`, `contact_person`, `contact_email`, `date_entry`, `date_update`, `date_screenshot`, `date_activationmail`, `token`, `status`) VALUES
(1, 'First Link', 'first-link', 'Beschreibung', 'Keyw', 'http://www.example.com', 1, '', 'NAME', 'Beispielstr. 1', '9999', 'Ciry', 'Country', '123456789', 'Max Mustermann', 'office@example.com', '2014-01-01 08:00:00', NULL, NULL, '2014-01-01 08:00:00', NULL, 2);

CREATE TABLE `linkstats` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `linkitem_id` int(10) unsigned NOT NULL,
  `date` date NOT NULL,
  `views` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Tagesstatistik` (`linkitem_id`,`date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `slug` varchar(255) CHARACTER SET utf8 NOT NULL,
  `status` enum('online','offline') CHARACTER SET utf8 NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `text` text CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `keywords` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `slug` (`slug`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `pages` (`id`, `parent_id`, `slug`, `status`, `title`, `text`, `description`, `keywords`) VALUES
(1, 0, 'impressum', 'online', 'Impressum', '<p>Impressums-Text</p>', '', '');

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 NOT NULL,
  `password` char(32) CHARACTER SET utf8 NOT NULL,
  `firstname` varchar(255) CHARACTER SET utf8 NOT NULL,
  `lastname` varchar(255) CHARACTER SET utf8 NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `role` varchar(24) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `username`, `password`, `firstname`, `lastname`, `email`, `role`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'Vorname', 'Nachname', 'office@example.com', 'admin');
