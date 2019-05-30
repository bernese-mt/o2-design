DROP TABLE IF EXISTS `{dbpre}about`;

CREATE TABLE `{dbpre}about` (
  `abid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `modalias` varchar(50) DEFAULT NULL,
  `treeid` mediumint(8) unsigned DEFAULT '0',
  `catid` mediumint(8) unsigned DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `intro` text,
  `content` text,
  `thumbfiles` varchar(255) DEFAULT NULL,
  `uploadfiles` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `metakeyword` varchar(500) DEFAULT NULL,
  `metadescription` varchar(500) DEFAULT NULL,
  `tplname` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`abid`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `{dbpre}admin`;

CREATE TABLE `{dbpre}admin` (
  `adminid` mediumint(8) unsigned NOT NULL,
  `adminname` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `groupid` mediumint(8) unsigned DEFAULT '0',
  `super` tinyint(1) unsigned DEFAULT '0',
  `timeline` int(10) unsigned DEFAULT '0',
  `flag` tinyint(1) unsigned DEFAULT '0',
  `logintimeline` int(10) unsigned DEFAULT '0',
  `logintimes` int(10) unsigned DEFAULT '0',
  `loginip` varchar(50) DEFAULT NULL,
  `memo` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`adminid`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `{dbpre}article`;

CREATE TABLE `{dbpre}article` (
  `articleid` int(10) unsigned NOT NULL DEFAULT '0',
  `modalias` varchar(50) DEFAULT NULL,
  `treeid` mediumint(8) unsigned DEFAULT '0',
  `catid` mediumint(8) unsigned DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `author` varchar(50) DEFAULT NULL,
  `source` varchar(50) DEFAULT NULL,
  `thumbfiles` varchar(255) DEFAULT NULL,
  `uploadfiles` varchar(255) DEFAULT NULL,
  `summary` varchar(500) DEFAULT NULL,
  `content` text,
  `istop` tinyint(1) unsigned DEFAULT '0',
  `elite` tinyint(1) unsigned DEFAULT '0',
  `flag` tinyint(1) unsigned DEFAULT '1',
  `adduser` varchar(50) DEFAULT NULL,
  `addtime` int(10) unsigned DEFAULT '0',
  `updatetime` int(10) unsigned DEFAULT '0',
  `hits` int(10) unsigned DEFAULT '0',
  `linktype` tinyint(1) unsigned DEFAULT '0',
  `linkurl` varchar(255) DEFAULT NULL,
  `purview` smallint(2) unsigned DEFAULT '0',
  `tags` varchar(255) DEFAULT NULL,
  `metakeyword` varchar(255) DEFAULT NULL,
  `metadescription` varchar(500) DEFAULT NULL,
  `tplname` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`articleid`),
  KEY `modalias` (`modalias`),
  KEY `treeid` (`treeid`),
  KEY `catid` (`catid`),
  KEY `flag` (`flag`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `{dbpre}article_attr`;

CREATE TABLE `{dbpre}article_attr` (
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `modalias` varchar(50) DEFAULT NULL,
  `aid` mediumint(8) unsigned DEFAULT '0',
  `extvalue` text,
  `relid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `aid` (`aid`),
  KEY `relid` (`relid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `{dbpre}authgroup`;

CREATE TABLE `{dbpre}authgroup` (
  `groupid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `groupname` varchar(255) DEFAULT NULL,
  `auths` text,
  `rootid` mediumint(8) unsigned DEFAULT '0',
  `depth` mediumint(8) unsigned DEFAULT '0',
  `flag` tinyint(1) unsigned DEFAULT '0',
  `addtime` int(10) unsigned DEFAULT '0',
  `orders` smallint(2) unsigned DEFAULT '0',
  `intro` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `{dbpre}category`;

CREATE TABLE `{dbpre}category` (
  `catid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `modalias` varchar(50) DEFAULT NULL,
  `catname` varchar(100) DEFAULT NULL,
  `asname` varchar(100) DEFAULT NULL,
  `treeid` mediumint(8) unsigned DEFAULT '0',
  `rootid` mediumint(8) unsigned DEFAULT '0',
  `depth` mediumint(8) unsigned DEFAULT '0',
  `childs` varchar(255) DEFAULT NULL,
  `flag` tinyint(1) unsigned DEFAULT '0',
  `orders` mediumint(8) unsigned DEFAULT '0',
  `dirname` varchar(100) DEFAULT NULL,
  `dirpath` varchar(200) DEFAULT NULL,
  `catpic` varchar(255) DEFAULT NULL,
  `intro` varchar(500) DEFAULT NULL,
  `metatitle` varchar(255) DEFAULT NULL,
  `metakeyword` varchar(255) DEFAULT NULL,
  `metadescription` varchar(255) DEFAULT NULL,
  `tplindex` varchar(255) DEFAULT NULL,
  `tpllist` varchar(255) DEFAULT NULL,
  `tpldetail` varchar(255) DEFAULT NULL,
  `ismenu` tinyint(1) unsigned DEFAULT '0',
  `isaccessory` tinyint(1) unsigned DEFAULT '0',
  `showpart` tinyint(1) unsigned DEFAULT '0',
  `orderby` tinyint(1) unsigned DEFAULT '0',
  `pagemax` smallint(1) unsigned DEFAULT '0',
  `linktype` tinyint(1) unsigned DEFAULT '1',
  `outurl` varchar(255) DEFAULT NULL,
  `purview` mediumint(8) unsigned DEFAULT '0',
  `issystem` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`catid`),
  KEY `rootid` (`rootid`),
  KEY `dirname` (`dirname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `{dbpre}download`;

CREATE TABLE `{dbpre}download` (
  `downid` int(10) unsigned NOT NULL DEFAULT '0',
  `modalias` varchar(50) DEFAULT NULL,
  `treeid` mediumint(8) unsigned DEFAULT '0',
  `catid` mediumint(8) unsigned DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `thumbfiles` varchar(255) DEFAULT NULL,
  `uploadfiles` varchar(255) DEFAULT NULL,
  `filesize` int(10) unsigned DEFAULT '0',
  `fileurl` varchar(255) DEFAULT NULL,
  `summary` varchar(500) DEFAULT NULL,
  `content` text,
  `istop` tinyint(1) unsigned DEFAULT '0',
  `elite` tinyint(1) unsigned DEFAULT '0',
  `flag` tinyint(1) unsigned DEFAULT '0',
  `adduser` varchar(50) DEFAULT NULL,
  `addtime` int(10) unsigned DEFAULT '0',
  `updatetime` int(10) unsigned DEFAULT '0',
  `hits` int(10) unsigned DEFAULT '0',
  `downs` int(10) unsigned DEFAULT '0',
  `linktype` tinyint(1) unsigned DEFAULT '0',
  `linkurl` varchar(255) DEFAULT NULL,
  `purview` smallint(2) unsigned DEFAULT '0',
  `tags` varchar(255) DEFAULT NULL,
  `metakeyword` varchar(255) DEFAULT NULL,
  `metadescription` varchar(500) DEFAULT NULL,
  `tplname` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`downid`),
  KEY `modalias` (`modalias`),
  KEY `treeid` (`treeid`),
  KEY `catid` (`catid`),
  KEY `flag` (`flag`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `{dbpre}download_attr`;

CREATE TABLE `{dbpre}download_attr` (
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `modalias` varchar(50) DEFAULT NULL,
  `aid` mediumint(8) unsigned DEFAULT '0',
  `extvalue` text,
  `relid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `aid` (`aid`),
  KEY `relid` (`relid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `{dbpre}guestbook`;

CREATE TABLE `{dbpre}guestbook` (
  `gid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `content` text,
  `addtime` int(10) unsigned DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `flag` tinyint(1) unsigned DEFAULT '0',
  `qq` varchar(50) DEFAULT NULL,
  `msn` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `telephone` varchar(100) DEFAULT NULL,
  `mobile` varchar(100) DEFAULT NULL,
  `replyflag` tinyint(1) unsigned DEFAULT '0',
  `replycontent` text,
  `replyuser` varchar(50) DEFAULT NULL,
  `replytime` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`gid`),
  KEY `userid` (`userid`),
  KEY `flag` (`flag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `{dbpre}hr`;

CREATE TABLE `{dbpre}hr` (
  `hrid` int(10) unsigned NOT NULL DEFAULT '0',
  `modalias` varchar(50) DEFAULT NULL,
  `treeid` mediumint(8) unsigned DEFAULT '0',
  `catid` mediumint(8) unsigned DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `number` smallint(2) unsigned DEFAULT '0',
  `workarea` varchar(50) DEFAULT NULL,
  `thumbfiles` varchar(255) DEFAULT NULL,
  `uploadfiles` varchar(255) DEFAULT NULL,
  `summary` varchar(500) DEFAULT NULL,
  `content` text,
  `hrcontact` varchar(255) DEFAULT NULL,
  `istop` tinyint(1) unsigned DEFAULT '0',
  `elite` tinyint(1) unsigned DEFAULT '0',
  `flag` tinyint(1) unsigned DEFAULT '0',
  `adduser` varchar(50) DEFAULT NULL,
  `addtime` int(10) unsigned DEFAULT '0',
  `updatetime` int(10) unsigned DEFAULT '0',
  `hits` int(10) unsigned DEFAULT '0',
  `linktype` tinyint(1) unsigned DEFAULT '0',
  `linkurl` varchar(255) DEFAULT NULL,
  `purview` smallint(2) unsigned DEFAULT '0',
  `metakeyword` varchar(255) DEFAULT NULL,
  `metadescription` varchar(500) DEFAULT NULL,
  `tplname` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`hrid`),
  KEY `modalias` (`modalias`),
  KEY `treeid` (`treeid`),
  KEY `catid` (`catid`),
  KEY `flag` (`flag`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `{dbpre}hr_attr`;

CREATE TABLE `{dbpre}hr_attr` (
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `modalias` varchar(50) DEFAULT NULL,
  `aid` mediumint(8) unsigned DEFAULT '0',
  `extvalue` text,
  `relid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `aid` (`aid`),
  KEY `relid` (`relid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `{dbpre}htmllabel`;

CREATE TABLE `{dbpre}htmllabel` (
  `labelid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `labelname` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `content` text,
  `flag` tinyint(1) unsigned DEFAULT '0',
  `timeline` int(10) unsigned DEFAULT '0',
  `issystem` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`labelid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `{dbpre}log`;

CREATE TABLE `{dbpre}log` (
  `logid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `username` varchar(50) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `logtype` tinyint(1) unsigned DEFAULT '1',
  `timeline` int(10) unsigned DEFAULT '0',
  `success` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`logid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `{dbpre}module`;

CREATE TABLE `{dbpre}module` (
  `modid` smallint(2) unsigned NOT NULL,
  `modname` varchar(50) DEFAULT NULL,
  `alias` varchar(50) NOT NULL DEFAULT '',
  `color` varchar(20) DEFAULT NULL,
  `tplindex` varchar(100) DEFAULT NULL,
  `tpllist` varchar(100) DEFAULT NULL,
  `tpldetail` varchar(100) DEFAULT NULL,
  `posts` mediumint(8) unsigned DEFAULT '0',
  `comments` mediumint(8) unsigned DEFAULT '0',
  `pv` int(10) unsigned DEFAULT '0',
  `sort` tinyint(2) unsigned DEFAULT '0',
  `enabled` tinyint(1) unsigned DEFAULT '1',
  `intro` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`modid`),
  UNIQUE KEY `modelid` (`modid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `{dbpre}module_attr`;

CREATE TABLE `{dbpre}module_attr` (
  `aid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `modalias` varchar(50) DEFAULT NULL,
  `treeid` mediumint(8) unsigned DEFAULT '0',
  `typename` varchar(50) DEFAULT NULL,
  `typeremark` varchar(255) DEFAULT NULL,
  `attrname` varchar(50) DEFAULT NULL,
  `inputtype` varchar(20) DEFAULT NULL,
  `attrvalue` text,
  `attrwidth` smallint(2) unsigned DEFAULT '0',
  `attrheight` smallint(2) unsigned DEFAULT '0',
  `isvalid` tinyint(1) unsigned DEFAULT '0',
  `validtext` varchar(200) DEFAULT NULL,
  `orders` mediumint(8) unsigned DEFAULT '0',
  `flag` tinyint(1) unsigned DEFAULT '0',
  `issystem` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`aid`),
  KEY `modalias` (`modalias`),
  KEY `flag` (`flag`),
  KEY `treeid` (`treeid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `{dbpre}myads`;

CREATE TABLE `{dbpre}myads` (
  `aid` mediumint(8) unsigned NOT NULL,
  `zoneid` mediumint(8) unsigned DEFAULT '0',
  `tagname` varchar(100) DEFAULT NULL,
  `adname` varchar(100) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `target` tinyint(1) unsigned DEFAULT '1',
  `orders` mediumint(8) unsigned DEFAULT '0',
  `timeset` tinyint(2) unsigned DEFAULT '0',
  `starttime` int(10) unsigned DEFAULT '0',
  `endtime` int(10) unsigned DEFAULT '0',
  `normbody` varchar(1000) DEFAULT NULL,
  `flag` tinyint(1) unsigned DEFAULT '0',
  `issystem` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`aid`),
  KEY `zoneid` (`zoneid`),
  KEY `flag` (`flag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `{dbpre}options`;

CREATE TABLE `{dbpre}options` (
  `id` mediumint(8) unsigned NOT NULL,
  `optionname` varchar(255) DEFAULT NULL,
  `optionvalue` text,
  `optiondesc` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `{dbpre}photo`;

CREATE TABLE `{dbpre}photo` (
  `photoid` int(10) unsigned NOT NULL DEFAULT '0',
  `modalias` varchar(50) DEFAULT NULL,
  `treeid` mediumint(8) unsigned DEFAULT '0',
  `catid` mediumint(8) unsigned DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `thumbfiles` varchar(255) DEFAULT NULL,
  `uploadfiles` varchar(255) DEFAULT NULL,
  `albums` text,
  `summary` varchar(500) DEFAULT NULL,
  `content` text,
  `istop` tinyint(1) unsigned DEFAULT '0',
  `elite` tinyint(1) unsigned DEFAULT '0',
  `flag` tinyint(1) unsigned DEFAULT '0',
  `adduser` varchar(50) DEFAULT NULL,
  `addtime` int(10) unsigned DEFAULT '0',
  `updatetime` int(10) unsigned DEFAULT '0',
  `hits` int(10) unsigned DEFAULT '0',
  `linktype` tinyint(1) unsigned DEFAULT '0',
  `linkurl` varchar(255) DEFAULT NULL,
  `purview` smallint(2) unsigned DEFAULT '0',
  `tags` varchar(255) DEFAULT NULL,
  `metakeyword` varchar(255) DEFAULT NULL,
  `metadescription` varchar(500) DEFAULT NULL,
  `tplname` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`photoid`),
  KEY `modalias` (`modalias`),
  KEY `treeid` (`treeid`),
  KEY `catid` (`catid`),
  KEY `flag` (`flag`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `{dbpre}photo_attr`;

CREATE TABLE `{dbpre}photo_attr` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `modalias` varchar(50) DEFAULT NULL,
  `aid` mediumint(8) unsigned DEFAULT '0',
  `extvalue` text,
  `relid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `aid` (`aid`),
  KEY `relid` (`relid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `{dbpre}product`;

CREATE TABLE `{dbpre}product` (
  `productid` int(10) unsigned NOT NULL DEFAULT '0',
  `modalias` varchar(50) DEFAULT NULL,
  `treeid` mediumint(8) unsigned DEFAULT '0',
  `catid` mediumint(8) unsigned DEFAULT '0',
  `productsn` varchar(50) DEFAULT NULL,
  `productname` varchar(255) DEFAULT NULL,
  `oprice` decimal(18,2) unsigned DEFAULT '0.00',
  `bprice` decimal(18,2) unsigned DEFAULT '0.00',
  `thumbfiles` varchar(255) DEFAULT NULL,
  `uploadfiles` varchar(255) DEFAULT NULL,
  `albums` text,
  `summary` varchar(500) DEFAULT NULL,
  `content` text,
  `istop` tinyint(1) unsigned DEFAULT '0',
  `elite` tinyint(1) unsigned DEFAULT '0',
  `flag` tinyint(1) unsigned DEFAULT '0',
  `adduser` varchar(50) DEFAULT NULL,
  `addtime` int(10) unsigned DEFAULT '0',
  `updatetime` int(10) unsigned DEFAULT '0',
  `hits` int(10) unsigned DEFAULT '0',
  `linktype` tinyint(1) unsigned DEFAULT '1',
  `linkurl` varchar(255) DEFAULT NULL,
  `purview` smallint(2) unsigned DEFAULT '0',
  `tags` varchar(255) DEFAULT NULL,
  `metakeyword` varchar(255) DEFAULT NULL,
  `metadescription` varchar(500) DEFAULT NULL,
  `tplname` varchar(255) DEFAULT NULL,
  `isorder` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`productid`),
  KEY `treeid` (`treeid`),
  KEY `modalias` (`modalias`),
  KEY `catid` (`catid`),
  KEY `flag` (`flag`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `{dbpre}product_attr`;

CREATE TABLE `{dbpre}product_attr` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `modalias` varchar(50) DEFAULT NULL,
  `aid` mediumint(8) unsigned DEFAULT '0',
  `extvalue` text,
  `relid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `aid` (`aid`),
  KEY `relid` (`relid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `{dbpre}relatedlink`;

CREATE TABLE `{dbpre}relatedlink` (
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `linktag` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  `target` tinyint(1) unsigned DEFAULT '1',
  `nofollow` tinyint(1) unsigned DEFAULT '0',
  `flag` tinyint(1) unsigned DEFAULT '1',
  `timeline` int(10) unsigned DEFAULT '0',
  `article` tinyint(1) unsigned DEFAULT '1',
  `product` tinyint(1) unsigned DEFAULT '1',
  `photo` tinyint(1) unsigned DEFAULT '1',
  `download` tinyint(1) unsigned DEFAULT '1',
  `about` tinyint(1) unsigned DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `{dbpre}seo`;

CREATE TABLE `{dbpre}seo` (
  `id` smallint(2) unsigned NOT NULL DEFAULT '0',
  `idmark` varchar(100) DEFAULT NULL,
  `chname` varchar(500) DEFAULT NULL,
  `title` varchar(500) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `keyword` varchar(500) DEFAULT NULL,
  `intro` varchar(500) DEFAULT NULL,
  `orders` smallint(2) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `{dbpre}zone`;

CREATE TABLE `{dbpre}zone` (
  `zoneid` mediumint(8) unsigned NOT NULL,
  `zonename` varchar(100) DEFAULT NULL,
  `idmark` varchar(100) DEFAULT NULL,
  `sort` varchar(10) DEFAULT NULL,
  `zonewidth` smallint(2) unsigned DEFAULT '0',
  `zoneheight` smallint(2) unsigned DEFAULT '0',
  `flag` tinyint(1) unsigned DEFAULT '0',
  `issystem` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`zoneid`),
  KEY `flag` (`flag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

