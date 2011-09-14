CREATE TABLE shortcuts (
  `shortcutid` mediumint(8) unsigned NOT NULL auto_increment,
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `date` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `hits` int(8) unsigned NOT NULL default '0',
  `rating` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (shortcutid),
  KEY uid (uid),
  KEY title (title)
) ENGINE=MyISAM;