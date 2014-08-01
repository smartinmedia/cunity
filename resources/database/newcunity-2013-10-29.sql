
CREATE TABLE IF NOT EXISTS `cunity_contact` (
  `contact_id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `firstname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`contact_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

CREATE TABLE IF NOT EXISTS `cunity_gallery_albums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `albumhash` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `cunity_gallery_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `albumid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `cunity_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pos` int(11) NOT NULL,
  `type` enum('module','link') COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `content` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `iconClass` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;


INSERT INTO `cunity_menu` (`id`, `pos`, `type`, `title`, `content`, `iconClass`) VALUES
(1, 0, 'module', 'Newsfeed', 'newsfeed', 'rss'),
(2, 1, 'module', 'Profile', 'profile', 'user'),
(3, 2, 'module', 'Gallery', 'gallery', 'picture-o');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cunity_modules`
--

CREATE TABLE IF NOT EXISTS `cunity_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `namespace` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `cunity_modules`
--

INSERT INTO `cunity_modules` (`id`, `namespace`, `name`) VALUES
(1, 'profile', 'Profile'),
(2, 'friends', 'Friends'),
(3, 'newsfeed', 'Newsfeed'),
(4, 'gallery', 'Gallery');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cunity_newsfeed`
--

CREATE TABLE IF NOT EXISTS `cunity_newsfeed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `wallid` int(11) NOT NULL,
  `tiemstamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'status',
  `content` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `cunity_pages` (
  `shortlink` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`shortlink`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cunity_pages`
--

INSERT INTO `cunity_pages` (`shortlink`, `title`, `content`) VALUES
('imprint', 'Imprint', 'Here You can specify the imprint for your Cunity. Currently there is no data to show! In Germany you have to enter this data!'),
('privacy', 'Privacy Policy', 'Put here your privacy-policy to tell your users more about their data!'),
('terms', 'Terms & Conditions', 'Put here your terms and Conditions!');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cunity_profile_pins`
--

CREATE TABLE IF NOT EXISTS `cunity_profile_pins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `column` int(11) NOT NULL,
  `row` int(11) NOT NULL,
  `title` varchar(53) COLLATE utf8_unicode_ci NOT NULL,
  `iconclass` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;


CREATE TABLE IF NOT EXISTS `cunity_relations` (
  `relation_id` int(11) NOT NULL AUTO_INCREMENT,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`relation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

CREATE TABLE IF NOT EXISTS `cunity_settings` (
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cunity_settings`
--

INSERT INTO `cunity_settings` (`name`, `value`) VALUES
('contact_mail', ''),
('design', 'default'),
('fullname', 'true'),
('site_url', 'http://localhost/newcunity/trunk/'),
('sitename', 'New Cunity');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cunity_users`
--

CREATE TABLE IF NOT EXISTS `cunity_users` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `userhash` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `lang` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `sex` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `profileImage` int(11) NOT NULL,
  `titleImage` int(11) NOT NULL,
  `groupid` int(1) NOT NULL,
  `salt` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastLogin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastIp` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `userhash` (`userhash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;
