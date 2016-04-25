CREATE TABLE IF NOT EXISTS `weather` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `temperature` float NOT NULL,
  `temperature_pressure` float NOT NULL,
  `humidity` float NOT NULL,
  `pressure` float NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
