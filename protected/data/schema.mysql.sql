# --------------------------------------------------------
# Host:                         127.0.0.1
# Database:                     ogitorbugs
# Server version:               5.1.36-community-log
# Server OS:                    Win32
# HeidiSQL version:             5.0.0.3272
# Date/time:                    2010-10-13 22:27:13
# --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
# Dumping database structure for ogitorbugs
CREATE DATABASE IF NOT EXISTS `ogitorbugs` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `ogitorbugs`;


# Dumping structure for table ogitorbugs.bug_action_log
DROP TABLE IF EXISTS `bug_action_log`;
CREATE TABLE IF NOT EXISTS `bug_action_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `action` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `model` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `idModel` int(10) unsigned DEFAULT NULL,
  `field` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `creationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userid` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

# Dumping data for table ogitorbugs.bug_action_log: 3 rows
DELETE FROM `bug_action_log`;
/*!40000 ALTER TABLE `bug_action_log` DISABLE KEYS */;
INSERT INTO `bug_action_log` (`id`, `description`, `action`, `model`, `idModel`, `field`, `creationdate`, `userid`) VALUES (1, 'User jacmoe changed name for Project[10].', 'CHANGE', 'Project', 10, 'name', '2010-10-13 08:54:58', '3'), (2, 'User jacmoe changed public for Project[10].', 'CHANGE', 'Project', 10, 'public', '2010-10-13 08:55:41', '3'), (3, 'User jacmoe created Project[12].', 'CREATE', 'Project', 12, '', '2010-10-13 08:56:29', '3');
/*!40000 ALTER TABLE `bug_action_log` ENABLE KEYS */;


# Dumping structure for table ogitorbugs.bug_auth_assignment
DROP TABLE IF EXISTS `bug_auth_assignment`;
CREATE TABLE IF NOT EXISTS `bug_auth_assignment` (
  `itemname` varchar(64) CHARACTER SET latin1 NOT NULL,
  `userid` varchar(64) CHARACTER SET latin1 NOT NULL,
  `bizrule` text CHARACTER SET latin1,
  `data` text CHARACTER SET latin1,
  PRIMARY KEY (`itemname`,`userid`),
  CONSTRAINT `bug_auth_assignment_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `bug_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ogitorbugs.bug_auth_assignment: 3 rows
DELETE FROM `bug_auth_assignment`;
/*!40000 ALTER TABLE `bug_auth_assignment` DISABLE KEYS */;
INSERT INTO `bug_auth_assignment` (`itemname`, `userid`, `bizrule`, `data`) VALUES ('Admin', '1', NULL, 'N;'), ('Admin', '3', NULL, 'N;'), ('Project.*', '2', NULL, 'N;');
/*!40000 ALTER TABLE `bug_auth_assignment` ENABLE KEYS */;


# Dumping structure for table ogitorbugs.bug_auth_item
DROP TABLE IF EXISTS `bug_auth_item`;
CREATE TABLE IF NOT EXISTS `bug_auth_item` (
  `name` varchar(64) CHARACTER SET latin1 NOT NULL,
  `type` int(11) NOT NULL,
  `description` text CHARACTER SET latin1,
  `bizrule` text CHARACTER SET latin1,
  `data` text CHARACTER SET latin1,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ogitorbugs.bug_auth_item: 110 rows
DELETE FROM `bug_auth_item`;
/*!40000 ALTER TABLE `bug_auth_item` DISABLE KEYS */;
INSERT INTO `bug_auth_item` (`name`, `type`, `description`, `bizrule`, `data`) VALUES ('ActionLog.*', 0, NULL, NULL, 'N;'), ('ActionLog.Admin', 0, NULL, NULL, 'N;'), ('ActionLog.Create', 0, NULL, NULL, 'N;'), ('ActionLog.Delete', 0, NULL, NULL, 'N;'), ('ActionLog.Index', 0, NULL, NULL, 'N;'), ('ActionLog.Update', 0, NULL, NULL, 'N;'), ('ActionLog.View', 0, NULL, NULL, 'N;'), ('Admin', 2, NULL, NULL, 'N;'), ('Authenticated', 2, NULL, NULL, 'N;'), ('Guest', 2, NULL, NULL, 'N;'), ('Issue.*', 0, NULL, NULL, 'N;'), ('Issue.Admin', 0, NULL, NULL, 'N;'), ('Issue.Create', 0, NULL, NULL, 'N;'), ('Issue.Delete', 0, NULL, NULL, 'N;'), ('Issue.Index', 0, NULL, NULL, 'N;'), ('Issue.Update', 0, NULL, NULL, 'N;'), ('Issue.View', 0, NULL, NULL, 'N;'), ('IssueCategory.*', 0, NULL, NULL, 'N;'), ('IssueCategory.Admin', 0, NULL, NULL, 'N;'), ('IssueCategory.Create', 0, NULL, NULL, 'N;'), ('IssueCategory.Delete', 0, NULL, NULL, 'N;'), ('IssueCategory.Index', 0, NULL, NULL, 'N;'), ('IssueCategory.Update', 0, NULL, NULL, 'N;'), ('IssueCategory.View', 0, NULL, NULL, 'N;'), ('IssuePriority.*', 0, NULL, NULL, 'N;'), ('IssuePriority.Admin', 0, NULL, NULL, 'N;'), ('IssuePriority.Create', 0, NULL, NULL, 'N;'), ('IssuePriority.Delete', 0, NULL, NULL, 'N;'), ('IssuePriority.Index', 0, NULL, NULL, 'N;'), ('IssuePriority.Update', 0, NULL, NULL, 'N;'), ('IssuePriority.View', 0, NULL, NULL, 'N;'), ('IssueStatus.*', 0, NULL, NULL, 'N;'), ('IssueStatus.Admin', 0, NULL, NULL, 'N;'), ('IssueStatus.Create', 0, NULL, NULL, 'N;'), ('IssueStatus.Delete', 0, NULL, NULL, 'N;'), ('IssueStatus.Index', 0, NULL, NULL, 'N;'), ('IssueStatus.Update', 0, NULL, NULL, 'N;'), ('IssueStatus.View', 0, NULL, NULL, 'N;'), ('Project.*', 0, NULL, NULL, 'N;'), ('Project.Admin', 0, NULL, NULL, 'N;'), ('Project.Create', 0, NULL, NULL, 'N;'), ('Project.Delete', 0, NULL, NULL, 'N;'), ('Project.Index', 0, NULL, NULL, 'N;'), ('Project.Update', 0, NULL, NULL, 'N;'), ('Project.View', 0, NULL, NULL, 'N;'), ('RelationType.*', 0, NULL, NULL, 'N;'), ('RelationType.Admin', 0, NULL, NULL, 'N;'), ('RelationType.Create', 0, NULL, NULL, 'N;'), ('RelationType.Delete', 0, NULL, NULL, 'N;'), ('RelationType.Index', 0, NULL, NULL, 'N;'), ('RelationType.Update', 0, NULL, NULL, 'N;'), ('RelationType.View', 0, NULL, NULL, 'N;'), ('Repository.*', 0, NULL, NULL, 'N;'), ('Repository.Admin', 0, NULL, NULL, 'N;'), ('Repository.Create', 0, NULL, NULL, 'N;'), ('Repository.Delete', 0, NULL, NULL, 'N;'), ('Repository.Index', 0, NULL, NULL, 'N;'), ('Repository.Update', 0, NULL, NULL, 'N;'), ('Repository.View', 0, NULL, NULL, 'N;'), ('Site.*', 0, NULL, NULL, 'N;'), ('Site.Contact', 0, NULL, NULL, 'N;'), ('Site.Error', 0, NULL, NULL, 'N;'), ('Site.Index', 0, NULL, NULL, 'N;'), ('Site.Login', 0, NULL, NULL, 'N;'), ('Site.Logout', 0, NULL, NULL, 'N;'), ('Tracker.*', 0, NULL, NULL, 'N;'), ('Tracker.Admin', 0, NULL, NULL, 'N;'), ('Tracker.Create', 0, NULL, NULL, 'N;'), ('Tracker.Delete', 0, NULL, NULL, 'N;'), ('Tracker.Index', 0, NULL, NULL, 'N;'), ('Tracker.Update', 0, NULL, NULL, 'N;'), ('Tracker.View', 0, NULL, NULL, 'N;'), ('User.Activation.*', 0, NULL, NULL, 'N;'), ('User.Activation.Activation', 0, NULL, NULL, 'N;'), ('User.Admin.*', 0, NULL, NULL, 'N;'), ('User.Admin.Admin', 0, NULL, NULL, 'N;'), ('User.Admin.Create', 0, NULL, NULL, 'N;'), ('User.Admin.Delete', 0, NULL, NULL, 'N;'), ('User.Admin.Update', 0, NULL, NULL, 'N;'), ('User.Admin.View', 0, NULL, NULL, 'N;'), ('User.Default.*', 0, NULL, NULL, 'N;'), ('User.Default.Index', 0, NULL, NULL, 'N;'), ('User.Login.*', 0, NULL, NULL, 'N;'), ('User.Login.Login', 0, NULL, NULL, 'N;'), ('User.Logout.*', 0, NULL, NULL, 'N;'), ('User.Logout.Logout', 0, NULL, NULL, 'N;'), ('User.Profile.*', 0, NULL, NULL, 'N;'), ('User.Profile.Changepassword', 0, NULL, NULL, 'N;'), ('User.Profile.Edit', 0, NULL, NULL, 'N;'), ('User.Profile.Profile', 0, NULL, NULL, 'N;'), ('User.ProfileField.*', 0, NULL, NULL, 'N;'), ('User.ProfileField.Admin', 0, NULL, NULL, 'N;'), ('User.ProfileField.Create', 0, NULL, NULL, 'N;'), ('User.ProfileField.Delete', 0, NULL, NULL, 'N;'), ('User.ProfileField.Update', 0, NULL, NULL, 'N;'), ('User.ProfileField.View', 0, NULL, NULL, 'N;'), ('User.Recovery.*', 0, NULL, NULL, 'N;'), ('User.Recovery.Recovery', 0, NULL, NULL, 'N;'), ('User.Registration.*', 0, NULL, NULL, 'N;'), ('User.Registration.Registration', 0, NULL, NULL, 'N;'), ('User.User.*', 0, NULL, NULL, 'N;'), ('User.User.Index', 0, NULL, NULL, 'N;'), ('User.User.View', 0, NULL, NULL, 'N;'), ('Version.*', 0, NULL, NULL, 'N;'), ('Version.Admin', 0, NULL, NULL, 'N;'), ('Version.Create', 0, NULL, NULL, 'N;'), ('Version.Delete', 0, NULL, NULL, 'N;'), ('Version.Index', 0, NULL, NULL, 'N;'), ('Version.Update', 0, NULL, NULL, 'N;'), ('Version.View', 0, NULL, NULL, 'N;');
/*!40000 ALTER TABLE `bug_auth_item` ENABLE KEYS */;


# Dumping structure for table ogitorbugs.bug_auth_item_child
DROP TABLE IF EXISTS `bug_auth_item_child`;
CREATE TABLE IF NOT EXISTS `bug_auth_item_child` (
  `parent` varchar(64) CHARACTER SET latin1 NOT NULL,
  `child` varchar(64) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `bug_auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `bug_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bug_auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `bug_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ogitorbugs.bug_auth_item_child: 0 rows
DELETE FROM `bug_auth_item_child`;
/*!40000 ALTER TABLE `bug_auth_item_child` DISABLE KEYS */;
/*!40000 ALTER TABLE `bug_auth_item_child` ENABLE KEYS */;


# Dumping structure for table ogitorbugs.bug_auth_item_weight
DROP TABLE IF EXISTS `bug_auth_item_weight`;
CREATE TABLE IF NOT EXISTS `bug_auth_item_weight` (
  `itemname` varchar(64) CHARACTER SET latin1 NOT NULL,
  `type` int(11) NOT NULL,
  `weight` int(11) DEFAULT NULL,
  PRIMARY KEY (`itemname`),
  CONSTRAINT `bug_auth_item_weight_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `bug_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ogitorbugs.bug_auth_item_weight: 0 rows
DELETE FROM `bug_auth_item_weight`;
/*!40000 ALTER TABLE `bug_auth_item_weight` DISABLE KEYS */;
/*!40000 ALTER TABLE `bug_auth_item_weight` ENABLE KEYS */;


# Dumping structure for table ogitorbugs.bug_config
DROP TABLE IF EXISTS `bug_config`;
CREATE TABLE IF NOT EXISTS `bug_config` (
  `key` varchar(100) COLLATE utf8_bin NOT NULL,
  `value` text COLLATE utf8_bin,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

# Dumping data for table ogitorbugs.bug_config: 2 rows
DELETE FROM `bug_config`;
/*!40000 ALTER TABLE `bug_config` DISABLE KEYS */;
INSERT INTO `bug_config` (`key`, `value`) VALUES ('HostName', 's:9:"127.0.0.1";'), ('SiteName', 's:7:"Bugitor";');
/*!40000 ALTER TABLE `bug_config` ENABLE KEYS */;


# Dumping structure for table ogitorbugs.bug_issue
DROP TABLE IF EXISTS `bug_issue`;
CREATE TABLE IF NOT EXISTS `bug_issue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tracker_id` int(11) NOT NULL DEFAULT '0',
  `project_id` int(11) NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `due_date` date DEFAULT NULL,
  `issue_category_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `issue_priority_id` int(11) NOT NULL DEFAULT '0',
  `version_id` int(11) DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `done_ratio` int(11) NOT NULL DEFAULT '0',
  `status` varchar(50) NOT NULL,
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_bug_issue_tracker_id` (`tracker_id`),
  KEY `fk_bug_issue_project_id` (`project_id`),
  KEY `fk_bug_issue_category_id` (`issue_category_id`),
  KEY `fk_bug_issue_prority_id` (`issue_priority_id`),
  KEY `fk_bug_issue_user_id` (`user_id`),
  KEY `fk_bug_issue_version_id` (`version_id`),
  KEY `fk_bug_issue_assigned_to_id` (`assigned_to`),
  CONSTRAINT `fk_bug_issue_assigned_to_id` FOREIGN KEY (`assigned_to`) REFERENCES `bug_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_bug_issue_category_id` FOREIGN KEY (`issue_category_id`) REFERENCES `bug_issue_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_bug_issue_project_id` FOREIGN KEY (`project_id`) REFERENCES `bug_project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_bug_issue_prority_id` FOREIGN KEY (`issue_priority_id`) REFERENCES `bug_issue_priority` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_bug_issue_tracker_id` FOREIGN KEY (`tracker_id`) REFERENCES `bug_tracker` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_bug_issue_user_id` FOREIGN KEY (`user_id`) REFERENCES `bug_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_bug_issue_version_id` FOREIGN KEY (`version_id`) REFERENCES `bug_version` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ogitorbugs.bug_issue: 0 rows
DELETE FROM `bug_issue`;
/*!40000 ALTER TABLE `bug_issue` DISABLE KEYS */;
/*!40000 ALTER TABLE `bug_issue` ENABLE KEYS */;


# Dumping structure for table ogitorbugs.bug_issue_category
DROP TABLE IF EXISTS `bug_issue_category`;
CREATE TABLE IF NOT EXISTS `bug_issue_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ogitorbugs.bug_issue_category: 0 rows
DELETE FROM `bug_issue_category`;
/*!40000 ALTER TABLE `bug_issue_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `bug_issue_category` ENABLE KEYS */;


# Dumping structure for table ogitorbugs.bug_issue_priority
DROP TABLE IF EXISTS `bug_issue_priority`;
CREATE TABLE IF NOT EXISTS `bug_issue_priority` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ogitorbugs.bug_issue_priority: 0 rows
DELETE FROM `bug_issue_priority`;
/*!40000 ALTER TABLE `bug_issue_priority` DISABLE KEYS */;
/*!40000 ALTER TABLE `bug_issue_priority` ENABLE KEYS */;


# Dumping structure for table ogitorbugs.bug_profiles
DROP TABLE IF EXISTS `bug_profiles`;
CREATE TABLE IF NOT EXISTS `bug_profiles` (
  `user_id` int(11) NOT NULL,
  `lastname` varchar(50) NOT NULL DEFAULT '',
  `firstname` varchar(50) NOT NULL DEFAULT '',
  `birthday` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ogitorbugs.bug_profiles: 2 rows
DELETE FROM `bug_profiles`;
/*!40000 ALTER TABLE `bug_profiles` DISABLE KEYS */;
INSERT INTO `bug_profiles` (`user_id`, `lastname`, `firstname`, `birthday`) VALUES (1, 'Administrator', 'Admin', '1968-05-06'), (3, 'Moen', 'Jacob', '1968-05-06');
/*!40000 ALTER TABLE `bug_profiles` ENABLE KEYS */;


# Dumping structure for table ogitorbugs.bug_profiles_fields
DROP TABLE IF EXISTS `bug_profiles_fields`;
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

# Dumping data for table ogitorbugs.bug_profiles_fields: 3 rows
DELETE FROM `bug_profiles_fields`;
/*!40000 ALTER TABLE `bug_profiles_fields` DISABLE KEYS */;
INSERT INTO `bug_profiles_fields` (`id`, `varname`, `title`, `field_type`, `field_size`, `field_size_min`, `required`, `match`, `range`, `error_message`, `other_validator`, `default`, `widget`, `widgetparams`, `position`, `visible`) VALUES (1, 'lastname', 'Last Name', 'VARCHAR', 50, 3, 1, '', '', 'Incorrect Last Name (length between 3 and 50 characters).', '', '', '', '', 1, 3), (2, 'firstname', 'First Name', 'VARCHAR', 50, 3, 1, '', '', 'Incorrect First Name (length between 3 and 50 characters).', '', '', '', '', 0, 3), (3, 'birthday', 'Birthday', 'DATE', 0, 0, 2, '', '', '', '', '0000-00-00', 'UWjuidate', '{"ui-theme":"redmond"}', 3, 2);
/*!40000 ALTER TABLE `bug_profiles_fields` ENABLE KEYS */;


# Dumping structure for table ogitorbugs.bug_project
DROP TABLE IF EXISTS `bug_project`;
CREATE TABLE IF NOT EXISTS `bug_project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `description` text,
  `homepage` varchar(255) DEFAULT NULL,
  `public` int(1) NOT NULL DEFAULT '1',
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  `identifier` varchar(20) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `identifier` (`identifier`),
  KEY `public` (`public`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

# Dumping data for table ogitorbugs.bug_project: 2 rows
DELETE FROM `bug_project`;
/*!40000 ALTER TABLE `bug_project` DISABLE KEYS */;
INSERT INTO `bug_project` (`id`, `name`, `description`, `homepage`, `public`, `created`, `modified`, `identifier`, `status`) VALUES (10, 'tert', '', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'ouio', 1), (12, 'awerawe', 'sdf', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'tyuty', 1);
/*!40000 ALTER TABLE `bug_project` ENABLE KEYS */;


# Dumping structure for table ogitorbugs.bug_project_tracker
DROP TABLE IF EXISTS `bug_project_tracker`;
CREATE TABLE IF NOT EXISTS `bug_project_tracker` (
  `project_id` int(11) NOT NULL,
  `tracker_id` int(11) NOT NULL,
  PRIMARY KEY (`project_id`,`tracker_id`),
  KEY `fk_project_tracker_project_id` (`project_id`),
  KEY `fk_project_tracker_tracker_id` (`tracker_id`),
  CONSTRAINT `fk_project_tracker_project_id` FOREIGN KEY (`project_id`) REFERENCES `bug_project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_project_tracker_tracker_id` FOREIGN KEY (`tracker_id`) REFERENCES `bug_tracker` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ogitorbugs.bug_project_tracker: 0 rows
DELETE FROM `bug_project_tracker`;
/*!40000 ALTER TABLE `bug_project_tracker` DISABLE KEYS */;
/*!40000 ALTER TABLE `bug_project_tracker` ENABLE KEYS */;


# Dumping structure for table ogitorbugs.bug_related_issue
DROP TABLE IF EXISTS `bug_related_issue`;
CREATE TABLE IF NOT EXISTS `bug_related_issue` (
  `issue_from` int(11) NOT NULL,
  `issue_to` int(11) NOT NULL,
  `relation_type_id` int(11) NOT NULL,
  PRIMARY KEY (`issue_from`,`issue_to`),
  KEY `fk_related_issue_issue_from_id` (`issue_from`),
  KEY `fk_related_issue_issue_to_id` (`issue_to`),
  KEY `fk_related_issue_relation_type_id` (`relation_type_id`),
  CONSTRAINT `fk_related_issue_issue_from_id` FOREIGN KEY (`issue_from`) REFERENCES `bug_issue` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_related_issue_issue_to_id` FOREIGN KEY (`issue_to`) REFERENCES `bug_issue` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_related_issue_relation_type_id` FOREIGN KEY (`relation_type_id`) REFERENCES `bug_relation_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ogitorbugs.bug_related_issue: 0 rows
DELETE FROM `bug_related_issue`;
/*!40000 ALTER TABLE `bug_related_issue` DISABLE KEYS */;
/*!40000 ALTER TABLE `bug_related_issue` ENABLE KEYS */;


# Dumping structure for table ogitorbugs.bug_relation_type
DROP TABLE IF EXISTS `bug_relation_type`;
CREATE TABLE IF NOT EXISTS `bug_relation_type` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ogitorbugs.bug_relation_type: 0 rows
DELETE FROM `bug_relation_type`;
/*!40000 ALTER TABLE `bug_relation_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `bug_relation_type` ENABLE KEYS */;


# Dumping structure for table ogitorbugs.bug_repository
DROP TABLE IF EXISTS `bug_repository`;
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
  CONSTRAINT `repository_project_id` FOREIGN KEY (`project_id`) REFERENCES `bug_project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ogitorbugs.bug_repository: 0 rows
DELETE FROM `bug_repository`;
/*!40000 ALTER TABLE `bug_repository` DISABLE KEYS */;
/*!40000 ALTER TABLE `bug_repository` ENABLE KEYS */;


# Dumping structure for table ogitorbugs.bug_tracker
DROP TABLE IF EXISTS `bug_tracker`;
CREATE TABLE IF NOT EXISTS `bug_tracker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `is_in_chlog` int(1) NOT NULL DEFAULT '1',
  `is_in_roadmap` int(1) NOT NULL DEFAULT '1',
  `position` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ogitorbugs.bug_tracker: 0 rows
DELETE FROM `bug_tracker`;
/*!40000 ALTER TABLE `bug_tracker` DISABLE KEYS */;
/*!40000 ALTER TABLE `bug_tracker` ENABLE KEYS */;


# Dumping structure for table ogitorbugs.bug_users
DROP TABLE IF EXISTS `bug_users`;
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

# Dumping data for table ogitorbugs.bug_users: 2 rows
DELETE FROM `bug_users`;
/*!40000 ALTER TABLE `bug_users` DISABLE KEYS */;
INSERT INTO `bug_users` (`id`, `username`, `password`, `email`, `activkey`, `createtime`, `lastvisit`, `superuser`, `status`) VALUES (1, 'admin', 'a7a786d05e54653f1abe6da9aff5a8ce', 'jacmoe@mail.dk', '4c815c995b2ed5e33e4690a72906d0f0', 1261146094, 1286733617, 1, 1), (3, 'jacmoe', 'a7a786d05e54653f1abe6da9aff5a8ce', 'mail@jacmoe.dk', 'dfe46d52b9755d58e83b265bb72b0aae', 1286721861, 1286950644, 1, 1);
/*!40000 ALTER TABLE `bug_users` ENABLE KEYS */;


# Dumping structure for table ogitorbugs.bug_version
DROP TABLE IF EXISTS `bug_version`;
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
  CONSTRAINT `bug_version_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `bug_project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ogitorbugs.bug_version: 0 rows
DELETE FROM `bug_version`;
/*!40000 ALTER TABLE `bug_version` DISABLE KEYS */;
/*!40000 ALTER TABLE `bug_version` ENABLE KEYS */;


# Dumping structure for table ogitorbugs.bug_watcher
DROP TABLE IF EXISTS `bug_watcher`;
CREATE TABLE IF NOT EXISTS `bug_watcher` (
  `issue_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`issue_id`,`user_id`),
  KEY `fk_watcher_user_id` (`user_id`),
  KEY `fk_watcher_issue_id` (`issue_id`),
  CONSTRAINT `fk_watcher_issue_id` FOREIGN KEY (`issue_id`) REFERENCES `bug_issue` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_watcher_user_id` FOREIGN KEY (`user_id`) REFERENCES `bug_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table ogitorbugs.bug_watcher: 0 rows
DELETE FROM `bug_watcher`;
/*!40000 ALTER TABLE `bug_watcher` DISABLE KEYS */;
/*!40000 ALTER TABLE `bug_watcher` ENABLE KEYS */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
