
CREATE TABLE IF NOT EXISTS `f2a_sessions` (
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `session` varchar(26) NOT NULL,
  `ip` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `f2a_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(128) NOT NULL UNIQUE,
  `regdate` datetime NOT NULL,
  `pass` varchar(64) NOT NULL,
  `2fa_imgname` varchar(32) NOT NULL,
  `2fa_hash` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `f2a_tmpUnAuth` (
  `hash` varchar(32) NOT NULL,
  `login` varchar(32) NOT NULL,
  `pas` varchar(64) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `time` datetime NOT NULL,
  `message` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
