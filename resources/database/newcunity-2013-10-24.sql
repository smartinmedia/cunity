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

CREATE TABLE IF NOT EXISTS `cunity_gallery_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
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
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cunity_relations`
--

CREATE TABLE IF NOT EXISTS `cunity_relations` (
  `relation_id` int(11) NOT NULL AUTO_INCREMENT,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`relation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `cunity_settings` (
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cunity_settings`
--

INSERT INTO `cunity_settings` (`name`, `value`) VALUES
('contact_mail', 'j.seibert@smartinmedia.com'),
('fullname', 'true'),
('site_url', 'http//localhost/newcunity/'),
('sitename', 'New Cunity');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cunity_users`
--

CREATE TABLE IF NOT EXISTS `cunity_users` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `userhash` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `groupid` int(1) NOT NULL,
  `salt` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastLogin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastIp` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `userhash` (`userhash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;


CREATE TABLE IF NOT EXISTS `cunity_user_details` (
  `userid` int(11) NOT NULL,
  `firstname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `sex` enum('m','f') COLLATE utf8_unicode_ci NOT NULL,
  `titleImage` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `profileImage` int(11) NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;