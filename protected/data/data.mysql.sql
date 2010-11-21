# --------------------------------------------------------
# Host:                         127.0.0.1
# Database:                     ogitorbugs
# Server version:               5.1.36-community-log
# Server OS:                    Win32
# HeidiSQL version:             5.0.0.3272
# Date/time:                    2010-11-21 23:22:11
# --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
# Dumping data for table ogitorbugs.bug_action_log: 0 rows
/*!40000 ALTER TABLE `bug_action_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `bug_action_log` ENABLE KEYS */;

# Dumping data for table ogitorbugs.bug_auth_assignment: 9 rows
/*!40000 ALTER TABLE `bug_auth_assignment` DISABLE KEYS */;
INSERT INTO `bug_auth_assignment` (`itemname`, `userid`, `bizrule`, `data`) VALUES ('Admin', '3', NULL, 'N;'), ('Developer', '1', NULL, 'N;'), ('Developer', '7', NULL, 'N;'), ('Developer', '8', NULL, 'N;'), ('Project Admin', '1', NULL, 'N;'), ('Project Admin', '3', NULL, 'N;'), ('Project.*', '2', NULL, 'N;'), ('User', '1', NULL, 'N;'), ('User', '8', NULL, 'N;');
/*!40000 ALTER TABLE `bug_auth_assignment` ENABLE KEYS */;

# Dumping data for table ogitorbugs.bug_auth_item: 147 rows
/*!40000 ALTER TABLE `bug_auth_item` DISABLE KEYS */;
INSERT INTO `bug_auth_item` (`name`, `type`, `description`, `bizrule`, `data`) VALUES ('ActionLog.*', 0, NULL, NULL, 'N;'), ('ActionLog.Admin', 0, NULL, NULL, 'N;'), ('ActionLog.Create', 0, NULL, NULL, 'N;'), ('ActionLog.Delete', 0, NULL, NULL, 'N;'), ('ActionLog.Index', 0, NULL, NULL, 'N;'), ('ActionLog.Update', 0, NULL, NULL, 'N;'), ('ActionLog.View', 0, NULL, NULL, 'N;'), ('Admin', 2, 'Global Administrator', NULL, 'N;'), ('Admin.Config.*', 0, NULL, NULL, 'N;'), ('Admin.Config.Admin', 0, NULL, NULL, 'N;'), ('Admin.Config.Create', 0, NULL, NULL, 'N;'), ('Admin.Config.Delete', 0, NULL, NULL, 'N;'), ('Admin.Config.Index', 0, NULL, NULL, 'N;'), ('Admin.Config.Update', 0, NULL, NULL, 'N;'), ('Admin.Config.View', 0, NULL, NULL, 'N;'), ('Admin.Default.*', 0, NULL, NULL, 'N;'), ('Admin.Default.Index', 0, NULL, NULL, 'N;'), ('Comment.*', 0, NULL, NULL, 'N;'), ('Comment.Admin', 0, NULL, NULL, 'N;'), ('Comment.Create', 0, NULL, NULL, 'N;'), ('Comment.Delete', 0, NULL, NULL, 'N;'), ('Comment.Feed', 0, NULL, NULL, 'N;'), ('Comment.Index', 0, NULL, NULL, 'N;'), ('Comment.Update', 0, NULL, NULL, 'N;'), ('Comment.View', 0, NULL, NULL, 'N;'), ('CommentUpdateOwn', 0, 'Update own comments', NULL, 'N;'), ('Developer', 2, 'Project Developer', NULL, 'N;'), ('Guest', 2, 'Anonymous', NULL, 'N;'), ('Issue.*', 0, NULL, NULL, 'N;'), ('Issue.Admin', 0, NULL, NULL, 'N;'), ('Issue.Create', 0, NULL, NULL, 'N;'), ('Issue.Delete', 0, NULL, NULL, 'N;'), ('Issue.Index', 0, NULL, NULL, 'N;'), ('Issue.MassEdit', 0, NULL, NULL, 'N;'), ('Issue.Update', 0, NULL, NULL, 'N;'), ('Issue.View', 0, NULL, NULL, 'N;'), ('Issue.Watch', 0, NULL, NULL, 'N;'), ('IssueCategory.*', 0, NULL, NULL, 'N;'), ('IssueCategory.Admin', 0, NULL, NULL, 'N;'), ('IssueCategory.Create', 0, NULL, NULL, 'N;'), ('IssueCategory.Delete', 0, NULL, NULL, 'N;'), ('IssueCategory.Index', 0, NULL, NULL, 'N;'), ('IssueCategory.Update', 0, NULL, NULL, 'N;'), ('IssueCategory.View', 0, NULL, NULL, 'N;'), ('IssuePriority.*', 0, NULL, NULL, 'N;'), ('IssuePriority.Admin', 0, NULL, NULL, 'N;'), ('IssuePriority.Create', 0, NULL, NULL, 'N;'), ('IssuePriority.Delete', 0, NULL, NULL, 'N;'), ('IssuePriority.Index', 0, NULL, NULL, 'N;'), ('IssuePriority.Update', 0, NULL, NULL, 'N;'), ('IssuePriority.View', 0, NULL, NULL, 'N;'), ('IssueStatus.*', 0, NULL, NULL, 'N;'), ('IssueStatus.Admin', 0, NULL, NULL, 'N;'), ('IssueStatus.Create', 0, NULL, NULL, 'N;'), ('IssueStatus.Delete', 0, NULL, NULL, 'N;'), ('IssueStatus.Index', 0, NULL, NULL, 'N;'), ('IssueStatus.Update', 0, NULL, NULL, 'N;'), ('IssueStatus.View', 0, NULL, NULL, 'N;'), ('IssueUpdateOwn', 0, 'Update own issue', 'return Issue::isOwnerOf();', 'N;'), ('Member.*', 0, NULL, NULL, 'N;'), ('Member.Admin', 0, NULL, NULL, 'N;'), ('Member.Create', 0, NULL, NULL, 'N;'), ('Member.Delete', 0, NULL, NULL, 'N;'), ('Member.Index', 0, NULL, NULL, 'N;'), ('Member.Update', 0, NULL, NULL, 'N;'), ('Member.View', 0, NULL, NULL, 'N;'), ('Project Admin', 2, 'Project Administrator', NULL, 'N;'), ('Project.*', 0, NULL, NULL, 'N;'), ('Project.Activity', 0, NULL, NULL, 'N;'), ('Project.Adduser', 0, NULL, NULL, 'N;'), ('Project.Admin', 0, NULL, NULL, 'N;'), ('Project.Code', 0, NULL, NULL, 'N;'), ('Project.Create', 0, NULL, NULL, 'N;'), ('Project.Delete', 0, NULL, NULL, 'N;'), ('Project.Index', 0, NULL, NULL, 'N;'), ('Project.Issues', 0, NULL, NULL, 'N;'), ('Project.NewIssue', 0, NULL, NULL, 'N;'), ('Project.Roadmap', 0, NULL, NULL, 'N;'), ('Project.Settings', 0, NULL, NULL, 'N;'), ('Project.Update', 0, NULL, NULL, 'N;'), ('Project.View', 0, NULL, NULL, 'N;'), ('RelationType.*', 0, NULL, NULL, 'N;'), ('RelationType.Admin', 0, NULL, NULL, 'N;'), ('RelationType.Create', 0, NULL, NULL, 'N;'), ('RelationType.Delete', 0, NULL, NULL, 'N;'), ('RelationType.Index', 0, NULL, NULL, 'N;'), ('RelationType.Update', 0, NULL, NULL, 'N;'), ('RelationType.View', 0, NULL, NULL, 'N;'), ('Repository.*', 0, NULL, NULL, 'N;'), ('Repository.Admin', 0, NULL, NULL, 'N;'), ('Repository.Create', 0, NULL, NULL, 'N;'), ('Repository.Delete', 0, NULL, NULL, 'N;'), ('Repository.Index', 0, NULL, NULL, 'N;'), ('Repository.Update', 0, NULL, NULL, 'N;'), ('Repository.View', 0, NULL, NULL, 'N;'), ('Site.*', 0, NULL, NULL, 'N;'), ('Site.Contact', 0, NULL, NULL, 'N;'), ('Site.Error', 0, NULL, NULL, 'N;'), ('Site.Index', 0, NULL, NULL, 'N;'), ('Site.Login', 0, NULL, NULL, 'N;'), ('Site.Logout', 0, NULL, NULL, 'N;'), ('Tracker.*', 0, NULL, NULL, 'N;'), ('Tracker.Admin', 0, NULL, NULL, 'N;'), ('Tracker.Create', 0, NULL, NULL, 'N;'), ('Tracker.Delete', 0, NULL, NULL, 'N;'), ('Tracker.Index', 0, NULL, NULL, 'N;'), ('Tracker.Update', 0, NULL, NULL, 'N;'), ('Tracker.View', 0, NULL, NULL, 'N;'), ('User', 2, 'Regular user', NULL, 'N;'), ('User.Activation.*', 0, NULL, NULL, 'N;'), ('User.Activation.Activation', 0, NULL, NULL, 'N;'), ('User.Admin.*', 0, NULL, NULL, 'N;'), ('User.Admin.Admin', 0, NULL, NULL, 'N;'), ('User.Admin.Create', 0, NULL, NULL, 'N;'), ('User.Admin.Delete', 0, NULL, NULL, 'N;'), ('User.Admin.Update', 0, NULL, NULL, 'N;'), ('User.Admin.View', 0, NULL, NULL, 'N;'), ('User.Default.*', 0, NULL, NULL, 'N;'), ('User.Default.Index', 0, NULL, NULL, 'N;'), ('User.Login.*', 0, NULL, NULL, 'N;'), ('User.Login.Login', 0, NULL, NULL, 'N;'), ('User.Logout.*', 0, NULL, NULL, 'N;'), ('User.Logout.Logout', 0, NULL, NULL, 'N;'), ('User.Profile.*', 0, NULL, NULL, 'N;'), ('User.Profile.Changepassword', 0, NULL, NULL, 'N;'), ('User.Profile.Edit', 0, NULL, NULL, 'N;'), ('User.Profile.Profile', 0, NULL, NULL, 'N;'), ('User.ProfileField.*', 0, NULL, NULL, 'N;'), ('User.ProfileField.Admin', 0, NULL, NULL, 'N;'), ('User.ProfileField.Create', 0, NULL, NULL, 'N;'), ('User.ProfileField.Delete', 0, NULL, NULL, 'N;'), ('User.ProfileField.Update', 0, NULL, NULL, 'N;'), ('User.ProfileField.View', 0, NULL, NULL, 'N;'), ('User.Recovery.*', 0, NULL, NULL, 'N;'), ('User.Recovery.Recovery', 0, NULL, NULL, 'N;'), ('User.Registration.*', 0, NULL, NULL, 'N;'), ('User.Registration.Registration', 0, NULL, NULL, 'N;'), ('User.User.*', 0, NULL, NULL, 'N;'), ('User.User.Index', 0, NULL, NULL, 'N;'), ('User.User.View', 0, NULL, NULL, 'N;'), ('Version.*', 0, NULL, NULL, 'N;'), ('Version.Admin', 0, NULL, NULL, 'N;'), ('Version.Create', 0, NULL, NULL, 'N;'), ('Version.Delete', 0, NULL, NULL, 'N;'), ('Version.Index', 0, NULL, NULL, 'N;'), ('Version.Update', 0, NULL, NULL, 'N;'), ('Version.View', 0, NULL, NULL, 'N;');
/*!40000 ALTER TABLE `bug_auth_item` ENABLE KEYS */;

# Dumping data for table ogitorbugs.bug_auth_item_child: 58 rows
/*!40000 ALTER TABLE `bug_auth_item_child` DISABLE KEYS */;
INSERT INTO `bug_auth_item_child` (`parent`, `child`) VALUES ('Project Admin', 'Comment.Admin'), ('User', 'Comment.Create'), ('Developer', 'Comment.Delete'), ('Guest', 'Comment.Index'), ('Developer', 'Comment.Update'), ('Guest', 'Comment.View'), ('User', 'CommentUpdateOwn'), ('Project Admin', 'Developer'), ('User', 'Guest'), ('User', 'Issue.Create'), ('Developer', 'Issue.Delete'), ('Guest', 'Issue.Index'), ('Developer', 'Issue.MassEdit'), ('Project Admin', 'Issue.MassEdit'), ('Developer', 'Issue.Update'), ('IssueUpdateOwn', 'Issue.Update'), ('Guest', 'Issue.View'), ('User', 'Issue.Watch'), ('Project Admin', 'IssueCategory.Admin'), ('Developer', 'IssueCategory.Create'), ('Developer', 'IssueCategory.Delete'), ('Guest', 'IssueCategory.Index'), ('Developer', 'IssueCategory.Update'), ('Guest', 'IssueCategory.View'), ('Project Admin', 'IssuePriority.Admin'), ('Developer', 'IssuePriority.Create'), ('Project Admin', 'IssuePriority.Delete'), ('Guest', 'IssuePriority.Index'), ('Project Admin', 'IssuePriority.Update'), ('Guest', 'IssuePriority.View'), ('Project Admin', 'IssueStatus.Admin'), ('Developer', 'IssueStatus.Create'), ('Project Admin', 'IssueStatus.Delete'), ('Guest', 'IssueStatus.Index'), ('Developer', 'IssueStatus.Update'), ('Guest', 'IssueStatus.View'), ('User', 'IssueUpdateOwn'), ('Developer', 'Member.Create'), ('Developer', 'Member.Delete'), ('Developer', 'Member.Update'), ('Project Admin', 'Project.*'), ('Guest', 'Project.Activity'), ('Project Admin', 'Project.Adduser'), ('Project Admin', 'Project.Admin'), ('Guest', 'Project.Code'), ('Project Admin', 'Project.Create'), ('Project Admin', 'Project.Delete'), ('Guest', 'Project.Index'), ('Guest', 'Project.Issues'), ('User', 'Project.NewIssue'), ('Guest', 'Project.Roadmap'), ('Developer', 'Project.Settings'), ('Developer', 'Project.Update'), ('Guest', 'Project.View'), ('Developer', 'User'), ('Developer', 'Version.Create'), ('Developer', 'Version.Delete'), ('Developer', 'Version.Update');
/*!40000 ALTER TABLE `bug_auth_item_child` ENABLE KEYS */;

# Dumping data for table ogitorbugs.bug_auth_item_weight: 5 rows
/*!40000 ALTER TABLE `bug_auth_item_weight` DISABLE KEYS */;
INSERT INTO `bug_auth_item_weight` (`itemname`, `type`, `weight`) VALUES ('Admin', 2, 0), ('Developer', 2, 1), ('Guest', 2, 2), ('Project Admin', 2, 4), ('User', 2, 3);
/*!40000 ALTER TABLE `bug_auth_item_weight` ENABLE KEYS */;

# Dumping data for table ogitorbugs.bug_comment: 6 rows
/*!40000 ALTER TABLE `bug_comment` DISABLE KEYS */;
INSERT INTO `bug_comment` (`id`, `content`, `issue_id`, `created`, `create_user_id`, `modified`, `update_user_id`) VALUES (24, 'asdfsad', 28, '2010-11-21 18:53:03', 3, '2010-11-21 18:53:03', 3), (25, '<div class="alt"><small>No comments for this change</small></div>', 29, '2010-11-21 18:54:10', 3, '2010-11-21 18:54:10', 3), (26, '<div class="alt"><small>No comments for this change</small></div>', 29, '2010-11-21 19:01:32', 3, '2010-11-21 19:01:32', 3), (27, '<div class="alt"><small>(Mass Edit) No comments for this change</small></div>', 28, '2010-11-21 20:09:01', 3, '2010-11-21 20:09:01', 3), (28, '<div class="alt"><small>(Mass Edit) No comments for this change</small></div>', 28, '2010-11-21 20:09:35', 3, '2010-11-21 20:09:35', 3), (29, '<div class="alt"><small>(Mass Edit) No comments for this change</small></div>', 29, '2010-11-21 20:09:36', 3, '2010-11-21 20:09:36', 3);
/*!40000 ALTER TABLE `bug_comment` ENABLE KEYS */;

# Dumping data for table ogitorbugs.bug_comment_detail: 18 rows
/*!40000 ALTER TABLE `bug_comment_detail` DISABLE KEYS */;
INSERT INTO `bug_comment_detail` (`id`, `comment_id`, `change`) VALUES (23, 24, 'Issue Category set to <i>Backend</i>'), (24, 24, 'Version set to <i>0.5.0</i>'), (25, 24, 'Owner set to <i>Admin</i>'), (26, 24, 'Done Ratio changed from <i>0</i> to <i>35</i>'), (27, 24, 'Status changed from <i>New</i> to <i>Assigned</i>'), (28, 24, 'Updated by set to <i>3</i>'), (29, 25, 'Issue Category set to <i>Backend</i>'), (30, 25, 'Version set to <i>0.5.0</i>'), (31, 25, 'Owner set to <i>Admin</i>'), (32, 25, 'Done Ratio changed from <i>0</i> to <i>25</i>'), (33, 25, 'Status changed from <i>New</i> to <i>Assigned</i>'), (34, 26, '<b>Issue Category</b> changed from <i>Backend</i> to <i>UI</i>'), (35, 26, '<b>Version</b> removed (<s>0.5.0</s>)'), (36, 26, '<b>Owner</b> changed from <i>Admin</i> to <i>Jacmoe</i>'), (37, 26, '<b>Done Ratio</b> changed from <i>25</i> to <i>40</i>'), (38, 27, '<b>Issue Priority</b> changed from <i>Normal</i> to <i>High</i>'), (39, 28, '<b>Issue Priority</b> changed from <i>High</i> to <i>Low</i>'), (40, 29, '<b>Issue Priority</b> changed from <i>Normal</i> to <i>Low</i>');
/*!40000 ALTER TABLE `bug_comment_detail` ENABLE KEYS */;

# Dumping data for table ogitorbugs.bug_config: 1 rows
/*!40000 ALTER TABLE `bug_config` DISABLE KEYS */;
INSERT INTO `bug_config` (`key`, `value`) VALUES ('defaultPagesize', 'i:20;');
/*!40000 ALTER TABLE `bug_config` ENABLE KEYS */;

# Dumping data for table ogitorbugs.bug_issue: 3 rows
/*!40000 ALTER TABLE `bug_issue` DISABLE KEYS */;
INSERT INTO `bug_issue` (`id`, `tracker_id`, `project_id`, `subject`, `description`, `issue_category_id`, `user_id`, `issue_priority_id`, `version_id`, `assigned_to`, `created`, `modified`, `done_ratio`, `status`, `closed`, `pre_done_ratio`, `updated_by`) VALUES (28, 1, 13, 'sdfsadf', 'asdfsadf', 11, 3, 1, 2, 1, '2010-11-21 18:52:51', '2010-11-21 20:09:36', 35, 'swIssue/assigned', 0, 0, 3), (29, 1, 13, 'asdfawe', 'asdfasdfasdf', 14, 3, 1, 2, 3, '2010-11-21 18:54:00', '2010-11-21 20:09:36', 40, 'swIssue/assigned', 0, 0, 3), (30, 1, 13, 'sdf', 'sdfs asdf asdf asdf asdf', NULL, 3, 2, NULL, NULL, '2010-11-21 20:35:54', '2010-11-21 20:35:54', 0, 'swIssue/new', 0, 0, NULL);
/*!40000 ALTER TABLE `bug_issue` ENABLE KEYS */;

# Dumping data for table ogitorbugs.bug_issue_category: 4 rows
/*!40000 ALTER TABLE `bug_issue_category` DISABLE KEYS */;
INSERT INTO `bug_issue_category` (`id`, `name`, `project_id`, `description`) VALUES (1, 'UI', 12, 'User Interface'), (11, 'Backend', 13, 'afwe'), (13, 'UI', 14, 'fa asfd'), (14, 'UI', 13, 'lkj lsadk sldkf');
/*!40000 ALTER TABLE `bug_issue_category` ENABLE KEYS */;

# Dumping data for table ogitorbugs.bug_issue_priority: 3 rows
/*!40000 ALTER TABLE `bug_issue_priority` DISABLE KEYS */;
INSERT INTO `bug_issue_priority` (`id`, `name`) VALUES (3, 'High'), (1, 'Low'), (2, 'Normal');
/*!40000 ALTER TABLE `bug_issue_priority` ENABLE KEYS */;

# Dumping data for table ogitorbugs.bug_member: 7 rows
/*!40000 ALTER TABLE `bug_member` DISABLE KEYS */;
INSERT INTO `bug_member` (`id`, `project_id`, `user_id`, `role`) VALUES (7, 13, 1, 'Project Admin'), (8, 13, 8, 'Developer'), (9, 12, 1, 'Developer'), (10, 12, 8, 'Developer'), (11, 14, 3, 'Project Admin'), (13, 14, 8, 'Developer'), (14, 12, 3, 'Project Admin');
/*!40000 ALTER TABLE `bug_member` ENABLE KEYS */;

# Dumping data for table ogitorbugs.bug_profiles: 3 rows
/*!40000 ALTER TABLE `bug_profiles` DISABLE KEYS */;
INSERT INTO `bug_profiles` (`user_id`, `lastname`, `firstname`, `birthday`, `timezone`, `locale`) VALUES (1, 'Administrator', 'Admin', '1968-05-06', 'Europe/Zagreb', 'en_gb'), (3, 'Moen', 'Jacob', '1968-05-06', 'Europe/Copenhagen', 'en_gb'), (8, 'Dy', 'How', '0000-00-00', '', 'en_gb');
/*!40000 ALTER TABLE `bug_profiles` ENABLE KEYS */;

# Dumping data for table ogitorbugs.bug_profiles_fields: 5 rows
/*!40000 ALTER TABLE `bug_profiles_fields` DISABLE KEYS */;
INSERT INTO `bug_profiles_fields` (`id`, `varname`, `title`, `field_type`, `field_size`, `field_size_min`, `required`, `match`, `range`, `error_message`, `other_validator`, `default`, `widget`, `widgetparams`, `position`, `visible`) VALUES (1, 'lastname', 'Last Name', 'VARCHAR', 50, 2, 1, '', '', 'Incorrect Last Name (length between 3 and 50 characters).', '', '', '', '', 1, 3), (2, 'firstname', 'First Name', 'VARCHAR', 50, 2, 1, '', '', 'Incorrect First Name (length between 3 and 50 characters).', '', '', '', '', 0, 3), (3, 'birthday', 'Birthday', 'DATE', 0, 0, 0, '', '', '', '', '0000-00-00', 'UWjuidate', '{"ui-theme":"redmond"}', 3, 2), (4, 'timezone', 'TimeZone', 'VARCHAR', 32, 0, 0, '', '', '', '', 'Europe/London', '', '', 2, 2), (5, 'locale', 'Locale', 'VARCHAR', 32, 0, 0, '', '', '', '', 'en_gb', '', '', 5, 2);
/*!40000 ALTER TABLE `bug_profiles_fields` ENABLE KEYS */;

# Dumping data for table ogitorbugs.bug_project: 3 rows
/*!40000 ALTER TABLE `bug_project` DISABLE KEYS */;
INSERT INTO `bug_project` (`id`, `name`, `description`, `homepage`, `public`, `created`, `modified`, `identifier`) VALUES (12, 'MyProject', 'A description **here**..  sdafsadf', 'http://www.jacmoe.dk/', 1, '2010-10-30 02:46:59', '2010-11-14 15:41:39', 'myproject'), (13, 'Bugitor', '**woo-hoo** heesdf', '', 1, '2010-10-31 02:46:59', '2010-11-07 11:19:18', 'bugitor'), (14, 'Testing Project', 'A real description', '', 1, '2010-11-02 16:31:40', '2010-11-02 16:32:26', 'testingproject');
/*!40000 ALTER TABLE `bug_project` ENABLE KEYS */;

# Dumping data for table ogitorbugs.bug_project_tracker: 0 rows
/*!40000 ALTER TABLE `bug_project_tracker` DISABLE KEYS */;
/*!40000 ALTER TABLE `bug_project_tracker` ENABLE KEYS */;

# Dumping data for table ogitorbugs.bug_related_issue: 0 rows
/*!40000 ALTER TABLE `bug_related_issue` DISABLE KEYS */;
/*!40000 ALTER TABLE `bug_related_issue` ENABLE KEYS */;

# Dumping data for table ogitorbugs.bug_relation_type: 3 rows
/*!40000 ALTER TABLE `bug_relation_type` DISABLE KEYS */;
INSERT INTO `bug_relation_type` (`id`, `name`, `description`) VALUES (1, 'related', 'is related to'), (2, 'duplicates', 'is a duplicate of'), (3, 'closes', 'is closed by');
/*!40000 ALTER TABLE `bug_relation_type` ENABLE KEYS */;

# Dumping data for table ogitorbugs.bug_repository: 0 rows
/*!40000 ALTER TABLE `bug_repository` DISABLE KEYS */;
/*!40000 ALTER TABLE `bug_repository` ENABLE KEYS */;

# Dumping data for table ogitorbugs.bug_tracker: 2 rows
/*!40000 ALTER TABLE `bug_tracker` DISABLE KEYS */;
INSERT INTO `bug_tracker` (`id`, `name`, `is_in_chlog`, `is_in_roadmap`, `position`) VALUES (1, 'Bug', 1, 1, 1), (2, 'Feature', 1, 1, 1);
/*!40000 ALTER TABLE `bug_tracker` ENABLE KEYS */;

# Dumping data for table ogitorbugs.bug_users: 3 rows
/*!40000 ALTER TABLE `bug_users` DISABLE KEYS */;
INSERT INTO `bug_users` (`id`, `username`, `password`, `email`, `activkey`, `createtime`, `lastvisit`, `superuser`, `status`) VALUES (1, 'admin', 'a7a786d05e54653f1abe6da9aff5a8ce', 'jacmoe@mail.dk', '4c815c995b2ed5e33e4690a72906d0f0', 1261146094, 1290110153, 1, 1), (3, 'jacmoe', 'a7a786d05e54653f1abe6da9aff5a8ce', 'mail@jacmoe.dk', 'dfe46d52b9755d58e83b265bb72b0aae', 1286721861, 1290355077, 1, 1), (8, 'howdy', 'a7a786d05e54653f1abe6da9aff5a8ce', 'admin@jacmoe.dk', '0d8522280d4744189fbf9fb31c700cab', 1289655197, 1290351527, 0, 1);
/*!40000 ALTER TABLE `bug_users` ENABLE KEYS */;

# Dumping data for table ogitorbugs.bug_version: 3 rows
/*!40000 ALTER TABLE `bug_version` DISABLE KEYS */;
INSERT INTO `bug_version` (`id`, `project_id`, `name`, `description`, `effective_date`, `created`, `modified`) VALUES (1, 12, '0.5.0', 'A good version', '2010-12-30', '2010-11-11 14:39:57', '2010-11-14 17:58:20'), (2, 13, '0.5.0', 'A completely new version', '2010-11-24', '2010-11-18 19:56:30', '2010-11-18 19:56:30'), (4, 14, '0.5.0', 'lasdkfj', '2010-11-30', '2010-11-19 22:23:02', '2010-11-19 22:23:02');
/*!40000 ALTER TABLE `bug_version` ENABLE KEYS */;

# Dumping data for table ogitorbugs.bug_watcher: 1 rows
/*!40000 ALTER TABLE `bug_watcher` DISABLE KEYS */;
INSERT INTO `bug_watcher` (`issue_id`, `user_id`) VALUES (29, 3);
/*!40000 ALTER TABLE `bug_watcher` ENABLE KEYS */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
