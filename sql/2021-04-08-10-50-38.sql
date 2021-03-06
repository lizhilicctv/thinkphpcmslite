/*
MySQL Database Backup Tools
Server:127.0.0.1:3306
Database:_lizhili_database
Data:2021-04-08 10:50:38
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for lizhili_ad_img
-- ----------------------------

DROP TABLE IF EXISTS `lizhili_ad_img`;
CREATE TABLE `lizhili_ad_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `isopen` varchar(255) DEFAULT NULL,
  `ad_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lizhili_ad_img
-- ----------------------------



-- ----------------------------
-- Table structure for lizhili_admin
-- ----------------------------

DROP TABLE IF EXISTS `lizhili_admin`;
CREATE TABLE `lizhili_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `role` int(11) DEFAULT NULL,
  `isopen` int(11) NOT NULL DEFAULT '1',
  `mark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lizhili_admin
-- ----------------------------

INSERT INTO `lizhili_admin` (`id`,`username`,`password`,`create_time`,`update_time`,`role`,`isopen`,`mark`) VALUES ('1','admin','751aff6be33b5649fde05436dc4cb4f7','1529570040','1532252345','1','1','超级管理员不能删除和停用');
INSERT INTO `lizhili_admin` (`id`,`username`,`password`,`create_time`,`update_time`,`role`,`isopen`,`mark`) VALUES ('2','lizhili','25eee4665b3053ff200ea3c9b776bc35','1532685192',NULL,'2','1','');


-- ----------------------------
-- Table structure for lizhili_advertisement
-- ----------------------------

DROP TABLE IF EXISTS `lizhili_advertisement`;
CREATE TABLE `lizhili_advertisement` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `isopen` tinyint(1) DEFAULT '0' COMMENT '1代表启用，0代表不启用',
  `desc` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL COMMENT '关键字，根据关键字访问',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告';

-- ----------------------------
-- Records of lizhili_advertisement
-- ----------------------------



-- ----------------------------
-- Table structure for lizhili_article
-- ----------------------------

DROP TABLE IF EXISTS `lizhili_article`;
CREATE TABLE `lizhili_article` (
  `id` mediumint(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(60) DEFAULT NULL,
  `keyword` varchar(10) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `pic` varchar(160) DEFAULT NULL,
  `text` text,
  `state` smallint(6) unsigned DEFAULT '0',
  `click` mediumint(9) DEFAULT '0',
  `zan` mediumint(9) DEFAULT '0',
  `time` int(10) DEFAULT NULL,
  `cateid` mediumint(9) DEFAULT NULL,
  `faid` int(11) DEFAULT '0' COMMENT '发布者id',
  `laiyuan` varchar(255) DEFAULT NULL,
  `click_wai` mediumint(9) DEFAULT '0' COMMENT '展示数据',
  `isopen` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lizhili_article
-- ----------------------------



-- ----------------------------
-- Table structure for lizhili_article_img
-- ----------------------------

DROP TABLE IF EXISTS `lizhili_article_img`;
CREATE TABLE `lizhili_article_img` (
  `id` mediumint(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(60) DEFAULT NULL,
  `pic` varchar(160) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `aid` int(11) DEFAULT NULL COMMENT '对应ariticle id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lizhili_article_img
-- ----------------------------



-- ----------------------------
-- Table structure for lizhili_auth_group
-- ----------------------------

DROP TABLE IF EXISTS `lizhili_auth_group`;
CREATE TABLE `lizhili_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` char(80) NOT NULL DEFAULT '',
  `sort` tinyint(4) DEFAULT '0',
  `desc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lizhili_auth_group
-- ----------------------------

INSERT INTO `lizhili_auth_group` (`id`,`title`,`status`,`rules`,`sort`,`desc`) VALUES ('1','超级管理员','1','1,3,2,10,7,9,8,4,6,5,11','0','拥有至高无上的权利');
INSERT INTO `lizhili_auth_group` (`id`,`title`,`status`,`rules`,`sort`,`desc`) VALUES ('2','内容发布员','1','1,3,2,10,7,9,8,4,6,5,11','0','只能管理内容');


-- ----------------------------
-- Table structure for lizhili_auth_group_access
-- ----------------------------

DROP TABLE IF EXISTS `lizhili_auth_group_access`;
CREATE TABLE `lizhili_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lizhili_auth_group_access
-- ----------------------------

INSERT INTO `lizhili_auth_group_access` (`uid`,`group_id`) VALUES ('1','1');
INSERT INTO `lizhili_auth_group_access` (`uid`,`group_id`) VALUES ('2','2');


-- ----------------------------
-- Table structure for lizhili_auth_rule
-- ----------------------------

DROP TABLE IF EXISTS `lizhili_auth_rule`;
CREATE TABLE `lizhili_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  `fid` mediumint(9) DEFAULT '0',
  `level` tinyint(4) DEFAULT '0',
  `sort` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lizhili_auth_rule
-- ----------------------------

INSERT INTO `lizhili_auth_rule` (`id`,`name`,`title`,`type`,`status`,`condition`,`fid`,`level`,`sort`) VALUES ('1','article/all','资讯总权限','1','1','','0','0','0');
INSERT INTO `lizhili_auth_rule` (`id`,`name`,`title`,`type`,`status`,`condition`,`fid`,`level`,`sort`) VALUES ('2','article/add','添加资讯','1','1','','1','1','0');
INSERT INTO `lizhili_auth_rule` (`id`,`name`,`title`,`type`,`status`,`condition`,`fid`,`level`,`sort`) VALUES ('3','article/edit','资讯修改','1','1','','1','1','0');
INSERT INTO `lizhili_auth_rule` (`id`,`name`,`title`,`type`,`status`,`condition`,`fid`,`level`,`sort`) VALUES ('4','link/all','友情链接','1','1','','0','0','0');
INSERT INTO `lizhili_auth_rule` (`id`,`name`,`title`,`type`,`status`,`condition`,`fid`,`level`,`sort`) VALUES ('5','link/add','添加友情链接','1','1','','4','1','0');
INSERT INTO `lizhili_auth_rule` (`id`,`name`,`title`,`type`,`status`,`condition`,`fid`,`level`,`sort`) VALUES ('6','link/edit','修改友情链接','1','1','','4','1','0');
INSERT INTO `lizhili_auth_rule` (`id`,`name`,`title`,`type`,`status`,`condition`,`fid`,`level`,`sort`) VALUES ('7','slide/all','幻灯片总权限','1','1','','0','0','0');
INSERT INTO `lizhili_auth_rule` (`id`,`name`,`title`,`type`,`status`,`condition`,`fid`,`level`,`sort`) VALUES ('8','slide/add','添加幻灯片','1','1','','7','1','0');
INSERT INTO `lizhili_auth_rule` (`id`,`name`,`title`,`type`,`status`,`condition`,`fid`,`level`,`sort`) VALUES ('9','slide/edit','修改幻灯片','1','1','','7','1','0');
INSERT INTO `lizhili_auth_rule` (`id`,`name`,`title`,`type`,`status`,`condition`,`fid`,`level`,`sort`) VALUES ('11','message/all','留言总权限','1','1','','0','0','0');


-- ----------------------------
-- Table structure for lizhili_cate
-- ----------------------------

DROP TABLE IF EXISTS `lizhili_cate`;
CREATE TABLE `lizhili_cate` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `catename` varchar(30) DEFAULT NULL,
  `en_name` varchar(30) DEFAULT NULL,
  `fid` tinyint(4) DEFAULT NULL,
  `type` tinyint(3) DEFAULT NULL COMMENT '1代表是列表，2代表是单页，3代表图片列表，4代表打开链接',
  `keyword` varchar(255) DEFAULT NULL COMMENT '栏目关键字',
  `mark` varchar(255) DEFAULT NULL,
  `editorValue` text COMMENT '单页的数据',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `isopen` tinyint(1) DEFAULT '1',
  `url` varchar(255) DEFAULT NULL,
  `catehtml` varchar(255) DEFAULT NULL COMMENT '栏目模版',
  `showhtml` varchar(255) DEFAULT NULL COMMENT '详情模版',
  `tiao_type` tinyint(1) DEFAULT '0' COMMENT '1代表栏目，2代表文章',
  `tiao_id` int(11) DEFAULT '0' COMMENT '跳转的id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lizhili_cate
-- ----------------------------



-- ----------------------------
-- Table structure for lizhili_cms
-- ----------------------------

DROP TABLE IF EXISTS `lizhili_cms`;
CREATE TABLE `lizhili_cms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text,
  `iswo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lizhili_cms
-- ----------------------------

INSERT INTO `lizhili_cms` (`id`,`text`,`iswo`) VALUES ('1','<p style="overflow-wrap: break-word; margin-top: 0px; margin-bottom: 10px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Hiragino Sans GB&quot;, &quot;Helvetica Neue&quot;, Helvetica, tahoma, arial, &quot;WenQuanYi Micro Hei&quot;, Verdana, sans-serif, 宋体; font-size: 14px; white-space: normal; text-indent: 20px;">感谢您一年来对我们的支持和包容。为了更好的服务大家，在2018年6月份，我们全新发布了后台管理系统版本。我们的发布离不开广大用户给出的建议和意见。我们整合了更多优秀插件；优化了框架的体积。当然相比目前行业其他管理系统还有很多不足。但初心不改，实实在在把事做好，做用户最喜欢的框架。更好为客户服务。</p><p style="overflow-wrap: break-word; margin-top: 0px; margin-bottom: 10px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Hiragino Sans GB&quot;, &quot;Helvetica Neue&quot;, Helvetica, tahoma, arial, &quot;WenQuanYi Micro Hei&quot;, Verdana, sans-serif, 宋体; font-size: 14px; white-space: normal; text-indent: 20px;">我们在2018年版本上面，先进行了，大量的技术更新，包括了秒杀，团购，即时通讯，购物，等等功能的扩展。然后在2019年的9月和11月份，我们又进行了重构，大量的精简了原始代码，把原始的一些插件进行了替换，删除没有必要的程序增加水印缩略图等功能，速度是2018年第一版的3倍以上。从最早网站开发，到现在我们已经经历过了6个年头，我们经历过的项目数百个，每一次修改后台我们都抱着不忘初心的态度，努力的写好每一句代码，希望我们的努力，可以得到您的认可。你们的肯定就是对我们最大支持！</p><p style="overflow-wrap: break-word; margin-top: 0px; margin-bottom: 10px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Hiragino Sans GB&quot;, &quot;Helvetica Neue&quot;, Helvetica, tahoma, arial, &quot;WenQuanYi Micro Hei&quot;, Verdana, sans-serif, 宋体; font-size: 14px; white-space: normal; text-indent: 20px;">2020年，添加了广告和水印的判断，修复了bug。添加了数据备份还原功能。添加了关闭网站后的302重定向。5月份添加了后台动态修改菜单功能。6月修改了sql逻辑，添加了广告分类等等。添加了商品的操作。添加了大量的功能。7月修改分销的逻辑，已经发现的bug。8月份添加面包屑等前台功能。模仿dede添加多级循环等标签！</p><p style="overflow-wrap: break-word; margin-top: 0px; margin-bottom: 10px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Hiragino Sans GB&quot;, &quot;Helvetica Neue&quot;, Helvetica, tahoma, arial, &quot;WenQuanYi Micro Hei&quot;, Verdana, sans-serif, 宋体; font-size: 14px; white-space: normal; text-indent: 20px;">2020年12月，进行大量修改bug，更新了下载，搜索，SEO，等等功能，更新手册！2021年2月份进行了，修改已知bug，修改了操作手册！如果有使用上面问题可以联系我，lizhilimaster@163.com。</p><p><br/></p>','1');


-- ----------------------------
-- Table structure for lizhili_config
-- ----------------------------

DROP TABLE IF EXISTS `lizhili_config`;
CREATE TABLE `lizhili_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `shuo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lizhili_config
-- ----------------------------

INSERT INTO `lizhili_config` (`id`,`key`,`value`,`shuo`) VALUES ('1','watermark','0','水印');
INSERT INTO `lizhili_config` (`id`,`key`,`value`,`shuo`) VALUES ('2','shui_weizhi','9','水印位置具体看手册');
INSERT INTO `lizhili_config` (`id`,`key`,`value`,`shuo`) VALUES ('3','shui_neirong','李志立 lizhilimaster@163.com','水印内容');
INSERT INTO `lizhili_config` (`id`,`key`,`value`,`shuo`) VALUES ('4','thumbnail','0','缩率图');
INSERT INTO `lizhili_config` (`id`,`key`,`value`,`shuo`) VALUES ('5','t_w','10001','缩略图宽');
INSERT INTO `lizhili_config` (`id`,`key`,`value`,`shuo`) VALUES ('6','t_h','300','缩略图高');
INSERT INTO `lizhili_config` (`id`,`key`,`value`,`shuo`) VALUES ('7','shui_zihao','18','水印字号');
INSERT INTO `lizhili_config` (`id`,`key`,`value`,`shuo`) VALUES ('8','shui_yanse','#ffffff','水印颜色');
INSERT INTO `lizhili_config` (`id`,`key`,`value`,`shuo`) VALUES ('9','is_ya','1','是否开启压缩图片');
INSERT INTO `lizhili_config` (`id`,`key`,`value`,`shuo`) VALUES ('10','ya_w','1000','压缩后的图片大小');


-- ----------------------------
-- Table structure for lizhili_download
-- ----------------------------

DROP TABLE IF EXISTS `lizhili_download`;
CREATE TABLE `lizhili_download` (
  `id` mediumint(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(60) DEFAULT NULL,
  `keyword` varchar(10) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `pic` varchar(160) DEFAULT NULL,
  `text` text,
  `state` smallint(6) unsigned DEFAULT '0',
  `click` mediumint(9) DEFAULT '0',
  `zan` mediumint(9) DEFAULT '0',
  `time` int(10) DEFAULT NULL,
  `faid` int(11) DEFAULT '0' COMMENT '发布者id',
  `laiyuan` varchar(255) DEFAULT NULL,
  `click_wai` mediumint(9) DEFAULT '0' COMMENT '展示数据',
  `isopen` tinyint(1) DEFAULT '1',
  `file` varchar(255) DEFAULT NULL COMMENT '下载地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lizhili_download
-- ----------------------------



-- ----------------------------
-- Table structure for lizhili_link
-- ----------------------------

DROP TABLE IF EXISTS `lizhili_link`;
CREATE TABLE `lizhili_link` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `linkurl` varchar(255) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `isopen` tinyint(1) DEFAULT '1',
  `pic` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lizhili_link
-- ----------------------------



-- ----------------------------
-- Table structure for lizhili_log
-- ----------------------------

DROP TABLE IF EXISTS `lizhili_log`;
CREATE TABLE `lizhili_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `ip` char(15) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lizhili_log
-- ----------------------------



-- ----------------------------
-- Table structure for lizhili_member
-- ----------------------------

DROP TABLE IF EXISTS `lizhili_member`;
CREATE TABLE `lizhili_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  `openId` varchar(255) DEFAULT NULL,
  `tonken` varchar(255) DEFAULT NULL,
  `fid` int(11) DEFAULT '0',
  `phone` varchar(11) DEFAULT NULL,
  `avatarUrl` varchar(255) DEFAULT NULL COMMENT '头像',
  `province` varchar(255) DEFAULT NULL COMMENT '省',
  `city` varchar(255) DEFAULT NULL COMMENT '市',
  `country` varchar(255) DEFAULT NULL COMMENT '国家',
  `nickName` varchar(255) DEFAULT NULL COMMENT '姓名',
  `gender` varchar(255) DEFAULT NULL COMMENT '性别',
  `isopen` tinyint(1) DEFAULT '0',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '余额',
  `haibao` varchar(255) DEFAULT NULL,
  `tui_id` int(11) DEFAULT '0' COMMENT '推荐人id',
  `fen_num` int(11) NOT NULL DEFAULT '0' COMMENT '分享的次数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of lizhili_member
-- ----------------------------



-- ----------------------------
-- Table structure for lizhili_message
-- ----------------------------

DROP TABLE IF EXISTS `lizhili_message`;
CREATE TABLE `lizhili_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(60) DEFAULT NULL,
  `name` varchar(60) DEFAULT NULL,
  `phone` bigint(20) DEFAULT NULL,
  `neirong` text,
  `isopen` tinyint(4) DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lizhili_message
-- ----------------------------



-- ----------------------------
-- Table structure for lizhili_pilot_list
-- ----------------------------

DROP TABLE IF EXISTS `lizhili_pilot_list`;
CREATE TABLE `lizhili_pilot_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sort` smallint(6) DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `fid` int(11) DEFAULT '0',
  `icon` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `isopen` tinyint(1) DEFAULT '1',
  `pn_id` int(11) DEFAULT '1' COMMENT '头部导航',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8 COMMENT='后台侧面导航';

-- ----------------------------
-- Records of lizhili_pilot_list
-- ----------------------------

INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('1','1','栏目管理','0','&#xe681;','没有地址','1','2');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('2','2','会员管理','0','&#xe60d;','没有地址','1','2');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('3','3','管理员管理','0','&#xe653;',NULL,'1','1');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('4','4','系统管理','0','&#xe62e;',NULL,'1','1');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('5','6','登陆日志','0','&#xe645;','login/log','1','1');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('6','7','数据库管理','0','&#xe639;','sql/index','1','1');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('8','1','资讯管理','0','&#xe616;',NULL,'1','2');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('9','10','友情链接','0','&#xe6f1;',NULL,'1','2');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('10','11','图片管理','0','&#xe613;',NULL,'1','2');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('12','13','反馈留言','0','&#xe68a;',NULL,'1','2');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('13','0','栏目管理','1',NULL,'cate/index','1','1');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('14','0','会员列表','2',NULL,'Member/index','1','1');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('16','0','角色管理','3','','auth_group/index','1','1');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('17','0','权限管理','3','','auth_rule/index','1','1');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('18','0','管理员列表','3',NULL,'admin/index','1','1');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('19','0','设置管理','4',NULL,'system/index','1','1');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('20','0','系统设置','4',NULL,'system/show','1','1');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('21','0','常用配置','4',NULL,'system/config','1','1');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('22','0','屏蔽词','4',NULL,'system/shield','1','1');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('23','0','顶部导航','31',NULL,'pilot/nav','1','1');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('24','8','清除缓存','0','&#xe60b;','admin/cahe','1','1');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('25','0','资讯管理','8',NULL,'article/index','1','2');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('26','0','链接管理','9',NULL,'link/index','1','2');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('27','0','幻灯片管理','10',NULL,'slide/index','1','2');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('28','0','广告管理','10',NULL,'advertisement/index','1','2');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('30','0','反馈留言','12',NULL,'message/index','1','2');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('31','5','导航设置','0','&#xe6b6;',NULL,'1','1');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('32','0','侧面导航','31',NULL,'pilot/lit','1','1');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('44','0','说明管理','31','','pilot/cms','1','1');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('50','5','SEO优化','0','&#xe72b;','没有地址','1','1');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('51','0','生成地图','50','','map/index','1','1');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('52','2','下载管理','0','&#xe641;','','1','2');
INSERT INTO `lizhili_pilot_list` (`id`,`sort`,`name`,`fid`,`icon`,`url`,`isopen`,`pn_id`) VALUES ('53','0','下载管理','52','','download/index','1','2');


-- ----------------------------
-- Table structure for lizhili_pilot_nav
-- ----------------------------

DROP TABLE IF EXISTS `lizhili_pilot_nav`;
CREATE TABLE `lizhili_pilot_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sort` smallint(6) DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `isopen` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='后台头部导航';

-- ----------------------------
-- Records of lizhili_pilot_nav
-- ----------------------------

INSERT INTO `lizhili_pilot_nav` (`id`,`sort`,`name`,`isopen`) VALUES ('1','0','网站配置','1');
INSERT INTO `lizhili_pilot_nav` (`id`,`sort`,`name`,`isopen`) VALUES ('2','0','内容管理','1');


-- ----------------------------
-- Table structure for lizhili_shield
-- ----------------------------

DROP TABLE IF EXISTS `lizhili_shield`;
CREATE TABLE `lizhili_shield` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shield` text,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lizhili_shield
-- ----------------------------

INSERT INTO `lizhili_shield` (`id`,`shield`,`create_time`,`update_time`) VALUES ('1','她妈|它妈|他妈|你妈|去死|贱人|1090tv|10jil|21世纪中国基金会|2c8|3p|4kkasi|64惨案|64惨剧|64学生运动|64运动|64运动民國|89惨案|89惨剧|89学生运动|89运动|adult|asiangirl|avxiu|av女|awoodong|A片|bbagoori|bbagury|bdsm|binya|bitch|bozy|bunsec|bunsek|byuntae|B样|fa轮|fuck|ＦｕｃΚ|gay|hrichina|jiangzemin|j女|kgirls|kmovie|lihongzhi|MAKELOVE|NND|nude|petish|playbog|playboy|playbozi|pleybog|pleyboy|q奸|realxx|s2x|sex|shit|sorasex|tmb|TMD|tm的|tongxinglian|triangleboy|UltraSurf|unixbox|ustibet|voa|admin|lizhili|manage',NULL,NULL);


-- ----------------------------
-- Table structure for lizhili_slide
-- ----------------------------

DROP TABLE IF EXISTS `lizhili_slide`;
CREATE TABLE `lizhili_slide` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `img` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT '0',
  `isopen` tinyint(1) DEFAULT '0' COMMENT '1代表启用，0代表不启用',
  `desc` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lizhili_slide
-- ----------------------------



-- ----------------------------
-- Table structure for lizhili_system
-- ----------------------------

DROP TABLE IF EXISTS `lizhili_system`;
CREATE TABLE `lizhili_system` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cnname` varchar(100) DEFAULT NULL,
  `enname` varchar(100) DEFAULT NULL,
  `type` tinyint(4) DEFAULT '1',
  `value` varchar(255) DEFAULT NULL,
  `kxvalue` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT '0',
  `desc` varchar(255) DEFAULT NULL,
  `st` tinyint(3) DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lizhili_system
-- ----------------------------

INSERT INTO `lizhili_system` (`id`,`cnname`,`enname`,`type`,`value`,`kxvalue`,`sort`,`desc`,`st`,`create_time`,`update_time`) VALUES ('1','网站名称','webname','1','','','0','网站名称','1',NULL,'1567676416');
INSERT INTO `lizhili_system` (`id`,`cnname`,`enname`,`type`,`value`,`kxvalue`,`sort`,`desc`,`st`,`create_time`,`update_time`) VALUES ('2','关键词','keyword','1','','','0','网站关键字','1',NULL,'1567676416');
INSERT INTO `lizhili_system` (`id`,`cnname`,`enname`,`type`,`value`,`kxvalue`,`sort`,`desc`,`st`,`create_time`,`update_time`) VALUES ('3','描述','miaoshu','1','','','0','网站描述','1',NULL,'1567676416');
INSERT INTO `lizhili_system` (`id`,`cnname`,`enname`,`type`,`value`,`kxvalue`,`sort`,`desc`,`st`,`create_time`,`update_time`) VALUES ('4','底部版权信息','copyright','1','','','0','网站版权信息','1',NULL,'1567676416');
INSERT INTO `lizhili_system` (`id`,`cnname`,`enname`,`type`,`value`,`kxvalue`,`sort`,`desc`,`st`,`create_time`,`update_time`) VALUES ('5','备案号','No','1','','','0','网站备案号','1',NULL,'1567676416');
INSERT INTO `lizhili_system` (`id`,`cnname`,`enname`,`type`,`value`,`kxvalue`,`sort`,`desc`,`st`,`create_time`,`update_time`) VALUES ('6','统计代码','statistics','2','','','0','网站统计代码','1',NULL,'1567676416');
INSERT INTO `lizhili_system` (`id`,`cnname`,`enname`,`type`,`value`,`kxvalue`,`sort`,`desc`,`st`,`create_time`,`update_time`) VALUES ('7','网站状态','value','3','开启','开启,关闭','0','网站的状态','1',NULL,'1567676416');
INSERT INTO `lizhili_system` (`id`,`cnname`,`enname`,`type`,`value`,`kxvalue`,`sort`,`desc`,`st`,`create_time`,`update_time`) VALUES ('8','闭站重定向','redirect','1','http://down.linglukeji.com/','','0','','1','1585987185','1585987185');


