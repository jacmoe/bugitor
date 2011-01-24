# --------------------------------------------------------
# Host:                         127.0.0.1
# Database:                     ogitorbugs
# Server version:               5.1.36-community-log
# Server OS:                    Win32
# HeidiSQL version:             5.0.0.3272
# Date/time:                    2010-11-21 23:21:45
# --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

# Dumping structure for table ogitorbugs.bug_action_log
CREATE TABLE IF NOT EXISTS `bug_action_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` varchar(32) NOT NULL,
  `author_id` int(10) NOT NULL,
  `when` datetime NOT NULL,
  `url` varchar(50) NOT NULL,
  `project_id` int(10) NOT NULL,
  `subject` varchar(155) NOT NULL,
  `description` tinytext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `fk_action_log_user_id` (`author_id`),
  KEY `fk_action_log_project_id` (`project_id`),
  CONSTRAINT `fk_action_log_project_id` FOREIGN KEY (`project_id`) REFERENCES `bug_project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_action_log_user_id` FOREIGN KEY (`author_id`) REFERENCES `bug_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Data exporting was unselected.


# Dumping structure for table ogitorbugs.bug_auth_assignment
CREATE TABLE IF NOT EXISTS `bug_auth_assignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`),
  CONSTRAINT `bug_auth_assignment_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `bug_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Data exporting was unselected.


# Dumping structure for table ogitorbugs.bug_auth_item
CREATE TABLE IF NOT EXISTS `bug_auth_item` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Data exporting was unselected.


# Dumping structure for table ogitorbugs.bug_auth_item_child
CREATE TABLE IF NOT EXISTS `bug_auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `bug_auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `bug_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bug_auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `bug_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Data exporting was unselected.


# Dumping structure for table ogitorbugs.bug_auth_item_weight
CREATE TABLE IF NOT EXISTS `bug_auth_item_weight` (
  `itemname` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `weight` int(11) DEFAULT NULL,
  PRIMARY KEY (`itemname`),
  CONSTRAINT `bug_auth_item_weight_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `bug_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Data exporting was unselected.


# Dumping structure for table ogitorbugs.bug_comment
CREATE TABLE IF NOT EXISTS `bug_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `issue_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `create_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_comment_issue` (`issue_id`),
  KEY `FK_comment_author` (`create_user_id`),
  KEY `FK_comment_updater` (`update_user_id`),
  CONSTRAINT `FK_comment_author` FOREIGN KEY (`create_user_id`) REFERENCES `bug_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_comment_issue` FOREIGN KEY (`issue_id`) REFERENCES `bug_issue` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_comment_updater` FOREIGN KEY (`update_user_id`) REFERENCES `bug_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Data exporting was unselected.


# Dumping structure for table ogitorbugs.bug_comment_detail
CREATE TABLE IF NOT EXISTS `bug_comment_detail` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `comment_id` int(10) NOT NULL,
  `change` tinytext,
  PRIMARY KEY (`id`),
  KEY `comment_id` (`comment_id`),
  CONSTRAINT `bug_comment_detail_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `bug_comment` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Data exporting was unselected.


# Dumping structure for table ogitorbugs.bug_config
CREATE TABLE IF NOT EXISTS `bug_config` (
  `key` varchar(100) COLLATE utf8_bin NOT NULL,
  `value` text COLLATE utf8_bin,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

# Data exporting was unselected.


# Dumping structure for table ogitorbugs.bug_issue
CREATE TABLE IF NOT EXISTS `bug_issue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tracker_id` int(11) NOT NULL DEFAULT '0',
  `project_id` int(11) NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `issue_category_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `issue_priority_id` int(11) NOT NULL DEFAULT '0',
  `version_id` int(11) DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  `done_ratio` int(11) NOT NULL DEFAULT '0',
  `status` varchar(50) NOT NULL,
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `pre_done_ratio` int(11) NOT NULL DEFAULT '0',
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_bug_issue_tracker_id` (`tracker_id`),
  KEY `fk_bug_issue_project_id` (`project_id`),
  KEY `fk_bug_issue_category_id` (`issue_category_id`),
  KEY `fk_bug_issue_prority_id` (`issue_priority_id`),
  KEY `fk_bug_issue_user_id` (`user_id`),
  KEY `fk_bug_issue_version_id` (`version_id`),
  KEY `fk_bug_issue_assigned_to_id` (`assigned_to`),
  KEY `fk_bug_issue_updated_by` (`updated_by`),
  KEY `closed` (`closed`),
  CONSTRAINT `fk_bug_issue_assigned_to` FOREIGN KEY (`assigned_to`) REFERENCES `bug_users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_bug_issue_category` FOREIGN KEY (`issue_category_id`) REFERENCES `bug_issue_category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_bug_issue_issue_priority` FOREIGN KEY (`issue_priority_id`) REFERENCES `bug_issue_priority` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_bug_issue_tracker` FOREIGN KEY (`tracker_id`) REFERENCES `bug_tracker` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_bug_issue_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `bug_users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_bug_issue_user` FOREIGN KEY (`user_id`) REFERENCES `bug_users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_bug_issue_version` FOREIGN KEY (`version_id`) REFERENCES `bug_version` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_bug_issue_project` FOREIGN KEY (`project_id`) REFERENCES `bug_project` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Data exporting was unselected.


# Dumping structure for table ogitorbugs.bug_issue_category
CREATE TABLE IF NOT EXISTS `bug_issue_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `project_id` int(10) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `name_UNIQUE` (`name`),
  CONSTRAINT `FK_bug_issue_category_bug_project` FOREIGN KEY (`project_id`) REFERENCES `bug_project` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Data exporting was unselected.


# Dumping structure for table ogitorbugs.bug_issue_priority
CREATE TABLE IF NOT EXISTS `bug_issue_priority` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Data exporting was unselected.


# Dumping structure for table ogitorbugs.bug_member
CREATE TABLE IF NOT EXISTS `bug_member` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `project_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `role` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_member_user` (`user_id`),
  KEY `FK_member_project` (`project_id`),
  CONSTRAINT `FK_member_project` FOREIGN KEY (`project_id`) REFERENCES `bug_project` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_member_user` FOREIGN KEY (`user_id`) REFERENCES `bug_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Data exporting was unselected.


# Dumping structure for table ogitorbugs.bug_profiles
CREATE TABLE IF NOT EXISTS `bug_profiles` (
  `user_id` int(11) NOT NULL,
  `lastname` varchar(50) NOT NULL DEFAULT '',
  `firstname` varchar(50) NOT NULL DEFAULT '',
  `birthday` date NOT NULL DEFAULT '0000-00-00',
  `timezone` varchar(65) NOT NULL DEFAULT '',
  `locale` varchar(32) NOT NULL DEFAULT 'en_gb',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Data exporting was unselected.


# Dumping structure for table ogitorbugs.bug_profiles_fields
CREATE TABLE IF NOT EXISTS `bug_profiles_fields` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `varname` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `field_type` varchar(50) NOT NULL,
  `field_size` int(3) NOT NULL DEFAULT '0',
  `field_size_min` int(3) NOT NULL DEFAULT '0',
  `required` int(1) NOT NULL DEFAULT '0',
  `match` varchar(255) NOT NULL DEFAULT '',
  `range` varchar(255) NOT NULL DEFAULT '',
  `error_message` varchar(255) NOT NULL DEFAULT '',
  `other_validator` varchar(255) NOT NULL DEFAULT '',
  `default` varchar(255) NOT NULL DEFAULT '',
  `widget` varchar(255) NOT NULL DEFAULT '',
  `widgetparams` varchar(5000) NOT NULL DEFAULT '',
  `position` int(3) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `varname` (`varname`,`widget`,`visible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Data exporting was unselected.


# Dumping structure for table ogitorbugs.bug_project
CREATE TABLE IF NOT EXISTS `bug_project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `description` text,
  `homepage` varchar(255) DEFAULT NULL,
  `public` int(1) NOT NULL DEFAULT '1',
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  `identifier` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identifier` (`identifier`),
  KEY `public` (`public`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Data exporting was unselected.


# Dumping structure for table ogitorbugs.bug_project_tracker
CREATE TABLE IF NOT EXISTS `bug_project_tracker` (
  `project_id` int(11) NOT NULL,
  `tracker_id` int(11) NOT NULL,
  PRIMARY KEY (`project_id`,`tracker_id`),
  KEY `fk_project_tracker_project_id` (`project_id`),
  KEY `fk_project_tracker_tracker_id` (`tracker_id`),
  CONSTRAINT `fk_project_tracker_project_id` FOREIGN KEY (`project_id`) REFERENCES `bug_project` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_project_tracker_tracker_id` FOREIGN KEY (`tracker_id`) REFERENCES `bug_tracker` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Data exporting was unselected.


# Dumping structure for table ogitorbugs.bug_related_issue
CREATE TABLE IF NOT EXISTS `bug_related_issue` (
  `issue_from` int(11) NOT NULL,
  `issue_to` int(11) NOT NULL,
  `relation_type_id` int(11) NOT NULL,
  PRIMARY KEY (`issue_from`,`issue_to`),
  KEY `fk_related_issue_issue_from_id` (`issue_from`),
  KEY `fk_related_issue_issue_to_id` (`issue_to`),
  KEY `fk_related_issue_relation_type_id` (`relation_type_id`),
  CONSTRAINT `fk_related_issue_issue_from_id` FOREIGN KEY (`issue_from`) REFERENCES `bug_issue` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_related_issue_issue_to_id` FOREIGN KEY (`issue_to`) REFERENCES `bug_issue` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_related_issue_relation_type_id` FOREIGN KEY (`relation_type_id`) REFERENCES `bug_relation_type` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Data exporting was unselected.


# Dumping structure for table ogitorbugs.bug_relation_type
CREATE TABLE IF NOT EXISTS `bug_relation_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` varchar(65) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Data exporting was unselected.


# Dumping structure for table ogitorbugs.bug_repository
CREATE TABLE IF NOT EXISTS `bug_repository` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL DEFAULT '0',
  `url` varchar(60) DEFAULT NULL,
  `login` varchar(60) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `repository_project_id` (`project_id`),
  CONSTRAINT `repository_project_id` FOREIGN KEY (`project_id`) REFERENCES `bug_project` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Data exporting was unselected.


# Dumping structure for table ogitorbugs.bug_tracker
CREATE TABLE IF NOT EXISTS `bug_tracker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `is_in_chlog` int(1) NOT NULL DEFAULT '1',
  `is_in_roadmap` int(1) NOT NULL DEFAULT '1',
  `position` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Data exporting was unselected.


# Dumping structure for table ogitorbugs.bug_users
CREATE TABLE IF NOT EXISTS `bug_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `activkey` varchar(128) NOT NULL DEFAULT '',
  `createtime` int(10) NOT NULL DEFAULT '0',
  `lastvisit` int(10) NOT NULL DEFAULT '0',
  `superuser` int(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`),
  KEY `superuser` (`superuser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Data exporting was unselected.


# Dumping structure for table ogitorbugs.bug_version
CREATE TABLE IF NOT EXISTS `bug_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `effective_date` date DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `versions_project_id` (`project_id`),
  KEY `name` (`name`),
  CONSTRAINT `bug_version_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `bug_project` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Data exporting was unselected.


# Dumping structure for table ogitorbugs.bug_watcher
CREATE TABLE IF NOT EXISTS `bug_watcher` (
  `issue_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`issue_id`,`user_id`),
  KEY `fk_watcher_user_id` (`user_id`),
  KEY `fk_watcher_issue_id` (`issue_id`),
  CONSTRAINT `fk_watcher_issue_id` FOREIGN KEY (`issue_id`) REFERENCES `bug_issue` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_watcher_user_id` FOREIGN KEY (`user_id`) REFERENCES `bug_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Data exporting was unselected.
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
