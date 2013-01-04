<?php

class m110507_101301_initial extends CDbMigration
{
    public function safeUp()
    {
        //CREATE TABLE IF NOT EXISTS `{{action_log}}` (
        //  `id` int(10) NOT NULL AUTO_INCREMENT,
        //  `type` varchar(32) NOT NULL,
        //  `author_id` int(10) NOT NULL,
        //  `theDate` datetime NOT NULL,
        //  `url` varchar(100) NOT NULL,
        //  `project_id` int(10) NOT NULL,
        //  `subject` varchar(155) NOT NULL,
        //  `description` tinytext NOT NULL,
        //  PRIMARY KEY (`id`),
        //  KEY `type` (`type`),
        //  KEY `fk_action_log_user_id` (`author_id`),
        //  KEY `fk_action_log_project_id` (`project_id`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3255 ;
        $this->createTable('{{action_log}}',
                array(
                    'id' => 'pk',
                    'type' => 'string NOT NULL',
                    'author_id' => 'integer NOT NULL',
                    'theDate' => 'datetime NOT NULL',
                    'url' => 'string NOT NULL',
                    'project_id' => 'integer NOT NULL',
                    'subject' => 'string NOT NULL',
                    'description' => 'text NOT NULL',
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->createIndex('type', '{{action_log}}', 'type');
        $this->createIndex('fk_action_log_user_id', '{{action_log}}', 'author_id');
        $this->createIndex('fk_action_log_project_id', '{{action_log}}', 'project_id');

        //CREATE TABLE IF NOT EXISTS `{{attachment}}` (
        //  `id` int(11) NOT NULL AUTO_INCREMENT,
        //  `issue_id` int(11) NOT NULL,
        //  `user_id` int(11) NOT NULL,
        //  `name` varchar(255) NOT NULL,
        //  `size` int(11) NOT NULL,
        //  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        //  PRIMARY KEY (`id`),
        //  KEY `issue_id` (`issue_id`),
        //  KEY `user_id` (`user_id`),
        //  KEY `name` (`name`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;
        $this->createTable('{{attachment}}',
                array(
                    'id' => 'pk',
                    'issue_id' => 'integer NOT NULL',
                    'user_id' => 'integer NOT NULL',
                    'name' => 'string NOT NULL',
                    'size' => 'integer NOT NULL',
                    'created' => 'timestamp NOT NULL',
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->createIndex('issue_id', '{{attachment}}', 'issue_id');
        $this->createIndex('user_id', '{{attachment}}', 'user_id');
        $this->createIndex('name', '{{attachment}}', 'name');

        //CREATE TABLE IF NOT EXISTS `{{author_user}}` (
        //  `id` int(11) NOT NULL AUTO_INCREMENT,
        //  `user_id` int(11) DEFAULT NULL,
        //  `author` varchar(60) NOT NULL,
        //  PRIMARY KEY (`id`),
        //  UNIQUE KEY `author` (`author`),
        //  KEY `user_id` (`user_id`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;
        $this->createTable('{{author_user}}',
                array(
                    'id' => 'pk',
                    'user_id' => 'integer',
                    'author' => 'string NOT NULL',
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->createIndex('author', '{{author_user}}', 'author', true);
        $this->createIndex('user_id', '{{author_user}}', 'user_id');

        //CREATE TABLE IF NOT EXISTS `{{auth_assignment}}` (
        //  `itemname` varchar(64) NOT NULL,
        //  `userid` varchar(64) NOT NULL,
        //  `bizrule` text,
        //  `data` text,
        //  PRIMARY KEY (`itemname`,`userid`)
        //) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        $this->createTable('{{auth_assignment}}',
                array(
                    'itemname' => 'string NOT NULL',
                    'userid' => 'string NOT NULL',
                    'bizrule' => 'text',
                    'data' => 'text',
                    'PRIMARY KEY (`itemname`,`userid`)'
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");

        //CREATE TABLE IF NOT EXISTS `{{auth_item}}` (
        //  `name` varchar(64) NOT NULL,
        //  `type` int(11) NOT NULL,
        //  `description` text,
        //  `bizrule` text,
        //  `data` text,
        //  PRIMARY KEY (`name`)
        //) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        $this->createTable('{{auth_item}}',
                array(
                    'name' => 'string NOT NULL',
                    'type' => 'integer NOT NULL',
                    'description' => 'text',
                    'bizrule' => 'text',
                    'data' => 'text',
                    'PRIMARY KEY (`name`)'
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");

        //CREATE TABLE IF NOT EXISTS `{{auth_item_child}}` (
        //  `parent` varchar(64) NOT NULL,
        //  `child` varchar(64) NOT NULL,
        //  PRIMARY KEY (`parent`,`child`),
        //  KEY `child` (`child`)
        //) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        $this->createTable('{{auth_item_child}}',
                array(
                    'parent' => 'string NOT NULL',
                    'child' => 'string NOT NULL',
                    'PRIMARY KEY (`parent`,`child`)'
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->createIndex('child', '{{auth_item_child}}', 'child');

        //CREATE TABLE IF NOT EXISTS `{{auth_item_weight}}` (
        //  `itemname` varchar(64) NOT NULL,
        //  `type` int(11) NOT NULL,
        //  `weight` int(11) DEFAULT NULL,
        //  PRIMARY KEY (`itemname`)
        //) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        $this->createTable('{{auth_item_weight}}',
                array(
                    'itemname' => 'string NOT NULL',
                    'type' => 'integer NOT NULL',
                    'weight' => 'integer DEFAULT NULL',
                    'PRIMARY KEY (`itemname`)'
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");

        //CREATE TABLE IF NOT EXISTS `{{change}}` (
        //  `id` int(11) NOT NULL AUTO_INCREMENT,
        //  `changeset_id` int(11) NOT NULL,
        //  `action` varchar(50) NOT NULL,
        //  `path` varchar(255) NOT NULL,
        //  PRIMARY KEY (`id`),
        //  KEY `fk_change_changeset_id` (`changeset_id`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26716 ;
        $this->createTable('{{change}}',
            array(
                'id' => 'pk',
                'changeset_id' => 'integer NOT NULL',
                'action' => 'string NOT NULL',
                'path' => 'string NOT NULL',
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->createIndex('fk_change_changeset_id', '{{change}}', 'changeset_id');

        //CREATE TABLE IF NOT EXISTS `{{changeset}}` (
        //  `id` int(10) NOT NULL AUTO_INCREMENT,
        //  `unique_ident` varchar(60) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
        //  `revision` varchar(20) NOT NULL,
        //  `author` varchar(60) DEFAULT NULL,
        //  `user_id` int(11) DEFAULT NULL,
        //  `scm_id` int(11) NOT NULL DEFAULT '0',
        //  `commit_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        //  `message` tinytext NOT NULL,
        //  `short_rev` int(11) NOT NULL DEFAULT '0',
        //  `parent` varchar(50) DEFAULT NULL,
        //  `branch` varchar(50) DEFAULT NULL,
        //  `tags` varchar(50) DEFAULT NULL,
        //  `add` int(11) DEFAULT NULL,
        //  `edit` int(11) DEFAULT NULL,
        //  `del` int(11) DEFAULT NULL,
        //  PRIMARY KEY (`id`),
        //  UNIQUE KEY `unique_ident` (`unique_ident`),
        //  KEY `fk_changeset_user_id` (`user_id`),
        //  KEY `fk_changeset_repository` (`scm_id`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2431 ;
        $this->createTable('{{changeset}}',
            array(
                'id' => 'pk',
                'unique_ident' => 'string NOT NULL',
                'revision' => 'string NOT NULL',
                'author' => 'string DEFAULT NULL',
                'user_id' => 'integer DEFAULT NULL',
                'scm_id' => 'integer NOT NULL DEFAULT 0',
                'commit_date' => 'timestamp NOT NULL',
                'message' => 'text NOT NULL',
                'short_rev' => 'integer NOT NULL DEFAULT 0',
                'parent' => 'string DEFAULT NULL',
                'branch' => 'string DEFAULT NULL',
                'tags' => 'string DEFAULT NULL',
                'add' => 'integer DEFAULT NULL',
                'edit' => 'integer DEFAULT NULL',
                'del' => 'integer DEFAULT NULL',
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->createIndex('unique_ident', '{{changeset}}', 'unique_ident', true);
        $this->createIndex('fk_changeset_user_id', '{{changeset}}', 'user_id');
        $this->createIndex('fk_changeset_repository', '{{changeset}}', 'scm_id');

        //CREATE TABLE IF NOT EXISTS `{{changeset_issue}}` (
        //  `id` int(11) NOT NULL AUTO_INCREMENT,
        //  `changeset_id` int(11) NOT NULL,
        //  `issue_id` int(11) NOT NULL,
        //  PRIMARY KEY (`id`),
        //  KEY `issue_id` (`issue_id`),
        //  KEY `changeset_id` (`changeset_id`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=198 ;
        $this->createTable("{{changeset_issue}}",
            array(
                "id"=>"pk",
                "changeset_id"=>"integer NOT NULL",
                "issue_id"=>"integer NOT NULL",
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->createIndex('issue_id', '{{changeset_issue}}', 'issue_id');
        $this->createIndex('changeset_id', '{{changeset_issue}}', 'changeset_id');

        //CREATE TABLE IF NOT EXISTS `{{comment}}` (
        //  `id` int(11) NOT NULL AUTO_INCREMENT,
        //  `content` text NOT NULL,
        //  `issue_id` int(11) NOT NULL,
        //  `created` datetime DEFAULT NULL,
        //  `create_user_id` int(11) DEFAULT NULL,
        //  `modified` datetime DEFAULT NULL,
        //  `update_user_id` int(11) DEFAULT NULL,
        //  PRIMARY KEY (`id`),
        //  KEY `FK_comment_issue` (`issue_id`),
        //  KEY `FK_comment_author` (`create_user_id`),
        //  KEY `FK_comment_updater` (`update_user_id`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2213 ;
        $this->createTable("{{comment}}",
            array(
                "id"=>"pk",
                "content"=>"text NOT NULL",
                "issue_id"=>"integer NOT NULL",
                "created"=>"datetime DEFAULT NULL",
                "create_user_id"=>"integer DEFAULT NULL",
                "modified"=>"datetime DEFAULT NULL",
                "update_user_id"=>"integer DEFAULT NULL",
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->createIndex('FK_comment_issue', '{{comment}}', 'issue_id');
        $this->createIndex('FK_comment_author', '{{comment}}', 'create_user_id');
        $this->createIndex('FK_comment_updater', '{{comment}}', 'update_user_id');

        //CREATE TABLE IF NOT EXISTS `{{comment_detail}}` (
        //  `id` int(10) NOT NULL AUTO_INCREMENT,
        //  `comment_id` int(10) NOT NULL,
        //  `change` varchar(255) DEFAULT NULL,
        //  PRIMARY KEY (`id`),
        //  KEY `bug_comment_detail_ibfk_1` (`comment_id`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1703 ;
        $this->createTable("{{comment_detail}}",
            array(
                "id"=>"pk",
                "comment_id"=>"integer NOT NULL",
                "change"=>"string DEFAULT NULL",
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->createIndex('comment_detail_ibfk_1', '{{comment_detail}}', 'comment_id');

        //CREATE TABLE IF NOT EXISTS `{{config}}` (
        //  `key` varchar(100) COLLATE utf8_bin NOT NULL,
        //  `value` text COLLATE utf8_bin,
        //  PRIMARY KEY (`key`)
        //) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
        $this->createTable("{{config}}",
            array(
                "key"=>"string COLLATE utf8_bin",
                "value"=>"text COLLATE utf8_bin",
                'PRIMARY KEY (`key`)'
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");

        //CREATE TABLE IF NOT EXISTS `{{issue}}` (
        //  `id` int(11) NOT NULL AUTO_INCREMENT,
        //  `tracker_id` int(11) NOT NULL DEFAULT '0',
        //  `project_id` int(11) NOT NULL DEFAULT '0',
        //  `subject` varchar(255) NOT NULL,
        //  `description` text NOT NULL,
        //  `issue_category_id` int(11) DEFAULT NULL,
        //  `user_id` int(11) NOT NULL,
        //  `issue_priority_id` int(11) NOT NULL DEFAULT '0',
        //  `version_id` int(11) DEFAULT NULL,
        //  `assigned_to` int(11) DEFAULT NULL,
        //  `created` timestamp NULL DEFAULT NULL,
        //  `modified` timestamp NULL DEFAULT NULL,
        //  `done_ratio` int(11) NOT NULL DEFAULT '0',
        //  `status` varchar(50) NOT NULL,
        //  `closed` tinyint(1) NOT NULL DEFAULT '0',
        //  `pre_done_ratio` int(11) NOT NULL DEFAULT '0',
        //  `updated_by` int(11) DEFAULT NULL,
        //  `last_comment` int(10) DEFAULT NULL,
        //  PRIMARY KEY (`id`),
        //  KEY `fk_issue_tracker_id` (`tracker_id`),
        //  KEY `fk_issue_project_id` (`project_id`),
        //  KEY `fk_issue_category_id` (`issue_category_id`),
        //  KEY `fk_issue_prority_id` (`issue_priority_id`),
        //  KEY `fk_issue_user_id` (`user_id`),
        //  KEY `fk_issue_version_id` (`version_id`),
        //  KEY `fk_issue_assigned_to_id` (`assigned_to`),
        //  KEY `fk_issue_updated_by` (`updated_by`),
        //  KEY `closed` (`closed`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=701 ;
        $this->createTable("{{issue}}",
            array(
                "id"=>"pk",
                "tracker_id"=>"integer NOT NULL",
                "project_id"=>"integer NOT NULL",
                "subject"=>"string NOT NULL",
                "description"=>"text NOT NULL",
                "issue_category_id"=>"integer",
                "user_id"=>"integer NOT NULL",
                "issue_priority_id"=>"integer NOT NULL",
                "version_id"=>"integer",
                "assigned_to"=>"integer",
                "created"=>"timestamp",
                "modified"=>"timestamp",
                "done_ratio"=>"integer NOT NULL",
                "status"=>"string NOT NULL",
                "closed"=>"boolean NOT NULL",
                "pre_done_ratio"=>"integer NOT NULL",
                "updated_by"=>"integer",
                "last_comment"=>"integer",
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->createIndex('fk_issue_tracker_id', '{{issue}}', 'tracker_id');
        $this->createIndex('fk_issue_project_id', '{{issue}}', 'project_id');
        $this->createIndex('fk_issue_category_id', '{{issue}}', 'issue_category_id');
        $this->createIndex('fk_issue_prority_id', '{{issue}}', 'issue_priority_id');
        $this->createIndex('fk_issue_user_id', '{{issue}}', 'user_id');
        $this->createIndex('fk_issue_version_id', '{{issue}}', 'version_id');
        $this->createIndex('fk_issue_assigned_to_id', '{{issue}}', 'assigned_to');
        $this->createIndex('fk_issue_updated_by', '{{issue}}', 'updated_by');
        $this->createIndex('closed', '{{issue}}', 'closed');

        //CREATE TABLE IF NOT EXISTS `{{issue_category}}` (
        //  `id` int(11) NOT NULL AUTO_INCREMENT,
        //  `name` varchar(45) NOT NULL,
        //  `project_id` int(10) NOT NULL,
        //  `description` varchar(255) DEFAULT NULL,
        //  PRIMARY KEY (`id`),
        //  KEY `project_id` (`project_id`),
        //  KEY `name_UNIQUE` (`name`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;
        $this->createTable("{{issue_category}}",
            array(
                "id"=>"pk",
                "name"=>"string NOT NULL",
                "project_id"=>"integer NOT NULL",
                "description"=>"string DEFAULT NULL",
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->createIndex('project_id', '{{issue_category}}', 'project_id');
        $this->createIndex('name_UNIQUE', '{{issue_category}}', 'name');

        //CREATE TABLE IF NOT EXISTS `{{issue_priority}}` (
        //  `id` int(11) NOT NULL AUTO_INCREMENT,
        //  `name` varchar(45) NOT NULL,
        //  PRIMARY KEY (`id`),
        //  UNIQUE KEY `name_UNIQUE` (`name`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;
        $this->createTable("{{issue_priority}}",
            array(
                "id"=>"pk",
                "name"=>"string NOT NULL",
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->createIndex('name_UNIQUE', '{{issue_priority}}', 'name', true);

        //CREATE TABLE IF NOT EXISTS `{{member}}` (
        //  `id` int(10) NOT NULL AUTO_INCREMENT,
        //  `project_id` int(10) NOT NULL,
        //  `user_id` int(10) NOT NULL,
        //  `role` varchar(32) NOT NULL,
        //  PRIMARY KEY (`id`),
        //  KEY `FK_member_user` (`user_id`),
        //  KEY `FK_member_project` (`project_id`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;
        $this->createTable("{{member}}",
            array(
                "id"=>"pk",
                "project_id"=>"integer NOT NULL",
                "user_id"=>"integer NOT NULL",
                "role"=>"string NOT NULL",
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->createIndex('FK_member_user', '{{member}}', 'user_id');
        $this->createIndex('FK_member_project', '{{member}}', 'project_id');

        //CREATE TABLE IF NOT EXISTS `{{profiles}}` (
        //  `user_id` int(11) NOT NULL,
        //  `lastname` varchar(50) NOT NULL DEFAULT '',
        //  `firstname` varchar(50) NOT NULL DEFAULT '',
        //  `birthday` date NOT NULL DEFAULT '0000-00-00',
        //  `timezone` varchar(65) NOT NULL DEFAULT '',
        //  `locale` varchar(32) NOT NULL DEFAULT 'en_gb',
        //  PRIMARY KEY (`user_id`)
        //) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        $this->createTable("{{profiles}}",
            array(
                "user_id"=>"pk",
                "lastname"=>"varchar(50) NOT NULL",
                "firstname"=>"varchar(50) NOT NULL",
                "birthday"=>"date NOT NULL DEFAULT '0000-00-00'",
                "timezone"=>"varchar(65) NOT NULL",
                "locale"=>"varchar(32) NOT NULL DEFAULT 'en_gb'",
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");

        //CREATE TABLE IF NOT EXISTS `{{profiles_fields}}` (
        //  `id` int(10) NOT NULL AUTO_INCREMENT,
        //  `varname` varchar(50) NOT NULL,
        //  `title` varchar(255) NOT NULL,
        //  `field_type` varchar(50) NOT NULL,
        //  `field_size` int(3) NOT NULL DEFAULT '0',
        //  `field_size_min` int(3) NOT NULL DEFAULT '0',
        //  `required` int(1) NOT NULL DEFAULT '0',
        //  `match` varchar(255) NOT NULL DEFAULT '',
        //  `range` varchar(255) NOT NULL DEFAULT '',
        //  `error_message` varchar(255) NOT NULL DEFAULT '',
        //  `other_validator` varchar(255) NOT NULL DEFAULT '',
        //  `default` varchar(255) NOT NULL DEFAULT '',
        //  `widget` varchar(255) NOT NULL DEFAULT '',
        //  `widgetparams` varchar(5000) NOT NULL DEFAULT '',
        //  `position` int(3) NOT NULL DEFAULT '0',
        //  `visible` int(1) NOT NULL DEFAULT '0',
        //  PRIMARY KEY (`id`),
        //  KEY `varname` (`varname`,`widget`,`visible`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;
        $this->createTable("{{profiles_fields}}",
            array(
                "id"=>"pk",
                "varname"=>"varchar(50) NOT NULL",
                "title"=>"varchar(255) NOT NULL",
                "field_type"=>"varchar(50) NOT NULL",
                "field_size"=>"int(3) NOT NULL",
                "field_size_min"=>"int(3) NOT NULL",
                "required"=>"int(1) NOT NULL",
                "match"=>"varchar(255) NOT NULL",
                "range"=>"varchar(255) NOT NULL",
                "error_message"=>"varchar(255) NOT NULL",
                "other_validator"=>"varchar(255) NOT NULL",
                "default"=>"varchar(255) NOT NULL",
                "widget"=>"varchar(255) NOT NULL",
                "widgetparams"=>"varchar(5000) NOT NULL",
                "position"=>"int(3) NOT NULL",
                "visible"=>"int(1) NOT NULL",
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");

        //CREATE TABLE IF NOT EXISTS `{{project}}` (
        //  `id` int(11) NOT NULL AUTO_INCREMENT,
        //  `name` varchar(30) NOT NULL,
        //  `description` text,
        //  `homepage` varchar(255) DEFAULT NULL,
        //  `public` int(1) NOT NULL DEFAULT '1',
        //  `created` timestamp NULL DEFAULT NULL,
        //  `modified` timestamp NULL DEFAULT NULL,
        //  `identifier` varchar(20) DEFAULT NULL,
        //  PRIMARY KEY (`id`),
        //  UNIQUE KEY `identifier` (`identifier`),
        //  KEY `public` (`public`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;
        $this->createTable("{{project}}",
            array(
                "id"=>"pk",
                "name"=>"varchar(30) NOT NULL",
                "description"=>"text",
                "homepage"=>"string DEFAULT NULL",
                "public"=>"boolean NOT NULL DEFAULT '1'",
                "created"=>"timestamp NULL DEFAULT NULL",
                "modified"=>"timestamp NULL DEFAULT NULL",
                "identifier"=>"varchar(20) DEFAULT NULL",
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->createIndex('identifier', '{{project}}', 'identifier', true);
        $this->createIndex('public', '{{project}}', 'public');

        //CREATE TABLE IF NOT EXISTS `{{project_link}}` (
        //  `id` int(11) NOT NULL AUTO_INCREMENT,
        //  `url` varchar(255) NOT NULL,
        //  `title` varchar(255) NOT NULL,
        //  `description` varchar(255) NOT NULL,
        //  `position` int(11) NOT NULL,
        //  `project_id` int(11) NOT NULL,
        //  PRIMARY KEY (`id`),
        //  KEY `project_id` (`project_id`)
        //) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
        $this->createTable("{{project_link}}",
            array(
                "id"=>"pk",
                "url"=>"string NOT NULL",
                "title"=>"string NOT NULL",
                "description"=>"string NOT NULL",
                "position"=>"integer NOT NULL",
                "project_id"=>"integer NOT NULL",
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->createIndex('project_id', '{{project_link}}', 'project_id');

        //CREATE TABLE IF NOT EXISTS `{{project_tracker}}` (
        //  `project_id` int(11) NOT NULL,
        //  `tracker_id` int(11) NOT NULL,
        //  PRIMARY KEY (`project_id`,`tracker_id`),
        //  KEY `fk_project_tracker_project_id` (`project_id`),
        //  KEY `fk_project_tracker_tracker_id` (`tracker_id`)
        //) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        $this->createTable("{{project_tracker}}",
            array(
                "project_id"=>"integer NOT NULL",
                "tracker_id"=>"integer NOT NULL",
                "PRIMARY KEY (`project_id`,`tracker_id`)",
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->createIndex('fk_project_tracker_project_id', '{{project_tracker}}', 'project_id');
        $this->createIndex('fk_project_tracker_tracker_id', '{{project_tracker}}', 'tracker_id');

        //CREATE TABLE IF NOT EXISTS `{{related_issue}}` (
        //  `issue_from` int(11) NOT NULL,
        //  `issue_to` int(11) NOT NULL,
        //  `relation_type_id` int(11) NOT NULL,
        //  PRIMARY KEY (`issue_from`,`issue_to`),
        //  KEY `fk_related_issue_issue_from_id` (`issue_from`),
        //  KEY `fk_related_issue_issue_to_id` (`issue_to`),
        //  KEY `fk_related_issue_relation_type_id` (`relation_type_id`)
        //) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        $this->createTable("{{related_issue}}",
            array(
                "issue_from" => "integer NOT NULL",
                "issue_to" => "integer NOT NULL",
                "relation_type_id" => "integer NOT NULL",
                "PRIMARY KEY (`issue_from`,`issue_to`)",
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->createIndex('fk_related_issue_issue_from_id', '{{related_issue}}', 'issue_from');
        $this->createIndex('fk_related_issue_issue_to_id', '{{related_issue}}', 'issue_to');
        $this->createIndex('fk_related_issue_relation_type_id', '{{related_issue}}', 'relation_type_id');

        //CREATE TABLE IF NOT EXISTS `{{relation_type}}` (
        //  `id` int(11) NOT NULL AUTO_INCREMENT,
        //  `name` varchar(45) NOT NULL,
        //  `description` varchar(65) NOT NULL,
        //  PRIMARY KEY (`id`),
        //  UNIQUE KEY `name_UNIQUE` (`name`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;
        $this->createTable("{{relation_type}}",
            array(
                "id"=>"pk",
                "name"=>"varchar(45) NOT NULL",
                "description"=>"varchar(65) NOT NULL",
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->createIndex('name_UNIQUE', '{{relation_type}}', 'name', true);

        //CREATE TABLE IF NOT EXISTS `{{repository}}` (
        //  `id` int(11) NOT NULL AUTO_INCREMENT,
        //  `project_id` int(11) NOT NULL DEFAULT '0',
        //  `url` varchar(60) DEFAULT NULL,
        //  `local_path` varchar(255) DEFAULT NULL,
        //  `name` varchar(255) NOT NULL,
        //  `identifier` varchar(255) NOT NULL,
        //  `status` int(11) NOT NULL DEFAULT '0',
        //  PRIMARY KEY (`id`),
        //  UNIQUE KEY `name` (`name`),
        //  KEY `repository_project_id` (`project_id`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;
        $this->createTable("{{repository}}",
            array(
                "id"=>"pk",
                "project_id"=>"int(11) NOT NULL",
                "url"=>"varchar(60) DEFAULT NULL",
                "local_path"=>"varchar(255) DEFAULT NULL",
                "name"=>"varchar(255) NOT NULL",
                "identifier"=>"varchar(255) NOT NULL",
                "status"=>"int(11) NOT NULL DEFAULT '0'",
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->createIndex('name', '{{repository}}', 'name', true);
        $this->createIndex('repository_project_id', '{{repository}}', 'project_id');

        //CREATE TABLE IF NOT EXISTS `{{tracker}}` (
        //  `id` int(11) NOT NULL AUTO_INCREMENT,
        //  `name` varchar(45) NOT NULL,
        //  `is_in_chlog` int(1) NOT NULL DEFAULT '1',
        //  `is_in_roadmap` int(1) NOT NULL DEFAULT '1',
        //  `position` int(11) NOT NULL DEFAULT '1',
        //  PRIMARY KEY (`id`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;
        $this->createTable("{{tracker}}",
            array(
                "id"=>"pk",
                "name"=>"varchar(45) NOT NULL",
                "is_in_chlog"=>"int(1) NOT NULL DEFAULT '1'",
                "is_in_roadmap"=>"int(1) NOT NULL DEFAULT '1'",
                "position"=>"int(11) NOT NULL DEFAULT '1'",
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");

        //CREATE TABLE IF NOT EXISTS `{{users}}` (
        //  `id` int(11) NOT NULL AUTO_INCREMENT,
        //  `username` varchar(20) NOT NULL,
        //  `password` varchar(128) NOT NULL,
        //  `email` varchar(128) NOT NULL,
        //  `activkey` varchar(128) NOT NULL DEFAULT '',
        //  `createtime` int(10) NOT NULL DEFAULT '0',
        //  `lastvisit` int(10) NOT NULL DEFAULT '0',
        //  `superuser` int(1) NOT NULL DEFAULT '0',
        //  `status` int(1) NOT NULL DEFAULT '0',
        //  PRIMARY KEY (`id`),
        //  UNIQUE KEY `username` (`username`),
        //  UNIQUE KEY `email` (`email`),
        //  KEY `status` (`status`),
        //  KEY `superuser` (`superuser`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=45 ;
        $this->createTable("{{users}}",
            array(
                "id"=>"pk",
                "username"=>"varchar(20) NOT NULL",
                "password"=>"varchar(128) NOT NULL",
                "email"=>"varchar(128) NOT NULL",
                "activkey"=>"varchar(128) NOT NULL DEFAULT ''",
                "createtime"=>"int(10) NOT NULL DEFAULT '0'",
                "lastvisit"=>"int(10) NOT NULL DEFAULT '0'",
                "superuser"=>"int(1) NOT NULL DEFAULT '0'",
                "status"=>"int(1) NOT NULL DEFAULT '0'",
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->createIndex('username', '{{users}}', 'username', true);
        $this->createIndex('email', '{{users}}', 'email', true);
        $this->createIndex('status', '{{users}}', 'status');
        $this->createIndex('superuser', '{{users}}', 'superuser');

        //CREATE TABLE IF NOT EXISTS `{{version}}` (
        //  `id` int(11) NOT NULL AUTO_INCREMENT,
        //  `project_id` int(11) NOT NULL DEFAULT '0',
        //  `name` varchar(255) NOT NULL,
        //  `description` varchar(255) DEFAULT NULL,
        //  `effective_date` date DEFAULT NULL,
        //  `created` timestamp NULL DEFAULT NULL,
        //  `modified` timestamp NULL DEFAULT NULL,
        //  `version_order` int(11) DEFAULT NULL,
        //  PRIMARY KEY (`id`),
        //  KEY `versions_project_id` (`project_id`),
        //  KEY `name` (`name`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48 ;
        $this->createTable("{{version}}",
            array(
                "id"=>"pk",
                "project_id"=>"int(11) NOT NULL DEFAULT '0'",
                "name"=>"varchar(255) NOT NULL",
                "description"=>"varchar(255) DEFAULT NULL",
                "effective_date"=>"date DEFAULT NULL",
                "created"=>"timestamp NULL DEFAULT NULL",
                "modified"=>"timestamp NULL DEFAULT NULL",
                "version_order"=>"int(11) DEFAULT NULL",
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->createIndex('versions_project_id', '{{version}}', 'project_id');
        $this->createIndex('name', '{{version}}', 'name');

        //CREATE TABLE IF NOT EXISTS `{{watcher}}` (
        //  `issue_id` int(11) NOT NULL,
        //  `user_id` int(11) NOT NULL,
        //  PRIMARY KEY (`issue_id`,`user_id`),
        //  KEY `fk_watcher_user_id` (`user_id`),
        //  KEY `fk_watcher_issue_id` (`issue_id`)
        //) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        $this->createTable("{{watcher}}",
            array(
                "issue_id"=>"integer NOT NULL",
                "user_id"=>"integer NOT NULL",
                "PRIMARY KEY (`issue_id`,`user_id`)",
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->createIndex('fk_watcher_user_id', '{{watcher}}', 'user_id');
        $this->createIndex('fk_watcher_issue_id', '{{watcher}}', 'issue_id');
    }

    public function safeDown()
    {
        $this->dropTable("{{action_log}}");
        $this->dropTable("{{attachment}}");
        $this->dropTable("{{auth_assignment}}");
        $this->dropTable("{{auth_item}}");
        $this->dropTable("{{auth_item_child}}");
        $this->dropTable("{{auth_item_weight}}");
        $this->dropTable("{{author_user}}");
        $this->dropTable("{{change}}");
        $this->dropTable("{{changeset}}");
        $this->dropTable("{{changeset_issue}}");
        $this->dropTable("{{comment}}");
        $this->dropTable("{{comment_detail}}");
        $this->dropTable("{{config}}");
        $this->dropTable("{{issue}}");
        $this->dropTable("{{issue_category}}");
        $this->dropTable("{{issue_priority}}");
        $this->dropTable("{{member}}");
        $this->dropTable("{{profiles}}");
        $this->dropTable("{{profiles_fields}}");
        $this->dropTable("{{project}}");
        $this->dropTable("{{project_link}}");
        $this->dropTable("{{project_tracker}}");
        $this->dropTable("{{related_issue}}");
        $this->dropTable("{{relation_type}}");
        $this->dropTable("{{repository}}");
        $this->dropTable("{{tracker}}");
        $this->dropTable("{{users}}");
        $this->dropTable("{{version}}");
        $this->dropTable("{{watcher}}");
    }

    /*
    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
