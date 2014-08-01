CREATE TABLE IF NOT EXISTS `cunity_versions` (
  `timestamp` bigint(20) NOT NULL,
  PRIMARY KEY (`timestamp`),
  UNIQUE KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
