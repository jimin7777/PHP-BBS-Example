CREATE TABLE `board` (
  `bid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(45) DEFAULT NULL,
  `subject` varchar(245) DEFAULT NULL,
  `content` text ,
  `regdate` datetime DEFAULT CURRENT_TIMESTAMP,
  `modifydate` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`bid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `members` (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(145) DEFAULT NULL,
  `email` varchar(245) DEFAULT NULL,
  `username` varchar(145) DEFAULT NULL, 

  `passwd` varchar(200) DEFAULT NULL,
  `regdate` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `memo` (
  `memoid` int(11) NOT NULL AUTO_INCREMENT,
  `bid` int(11) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `userid` varchar(100) DEFAULT NULL,
  `memo` varchar(300) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `regdate` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`memoid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE `recommend` (
  `reid` int(11) NOT NULL AUTO_INCREMENT,
  `bid` int(11) DEFAULT NULL,
  `userid` varchar(100) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `regdate` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`reid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;