<?php

class m110507_101301_initial extends CDbMigration
{
    public function safeUp()
    {
        //CREATE TABLE IF NOT EXISTS `bug_action_log` (
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
        $this->createTable('bug_action_log',
                array(
                    'id' => 'pk',
                    'type' => 'string NOT NULL',
                    'author_id' => 'integer NOT NULL',
                    'theDate' => 'datetime NOT NULL',
                    'url' => 'string NOT NULL',
                    'project_id' => 'integer NOT NULL',
                    'subject' => 'string NOT NULL',
                    'description' => 'text NOT NULL',
                ));
        $this->createIndex('type', 'bug_action_log', 'type');
        $this->createIndex('fk_action_log_user_id', 'bug_action_log', 'author_id');
        $this->createIndex('fk_action_log_project_id', 'bug_action_log', 'project_id');

        //CREATE TABLE IF NOT EXISTS `bug_attachment` (
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
        $this->createTable('bug_attachment',
                array(
                    'id' => 'pk',
                    'issue_id' => 'integer NOT NULL',
                    'user_id' => 'integer NOT NULL',
                    'name' => 'string NOT NULL',
                    'size' => 'integer NOT NULL',
                    'created' => 'timestamp NOT NULL',
                )
                );
        $this->createIndex('issue_id', 'bug_attachment', 'issue_id');
        $this->createIndex('user_id', 'bug_attachment', 'user_id');
        $this->createIndex('name', 'bug_attachment', 'name');

        //CREATE TABLE IF NOT EXISTS `bug_author_user` (
        //  `id` int(11) NOT NULL AUTO_INCREMENT,
        //  `user_id` int(11) DEFAULT NULL,
        //  `author` varchar(60) NOT NULL,
        //  PRIMARY KEY (`id`),
        //  UNIQUE KEY `author` (`author`),
        //  KEY `user_id` (`user_id`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;
        $this->createTable('bug_author_user',
                array(
                    'id' => 'pk',
                    'user_id' => 'integer',
                    'author' => 'string NOT NULL',
                )
                );
        $this->createIndex('author', 'bug_author_user', 'author', true);
        $this->createIndex('user_id', 'bug_author_user', 'user_id');
        
        //CREATE TABLE IF NOT EXISTS `bug_auth_assignment` (
        //  `itemname` varchar(64) NOT NULL,
        //  `userid` varchar(64) NOT NULL,
        //  `bizrule` text,
        //  `data` text,
        //  PRIMARY KEY (`itemname`,`userid`)
        //) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        $this->createTable('bug_auth_assignment',
                array(
                    'itemname' => 'string NOT NULL',
                    'userid' => 'string NOT NULL',
                    'bizrule' => 'text',
                    'data' => 'text',
                    'PRIMARY KEY (`itemname`,`userid`)'
                )
                );
        
        //CREATE TABLE IF NOT EXISTS `bug_auth_item` (
        //  `name` varchar(64) NOT NULL,
        //  `type` int(11) NOT NULL,
        //  `description` text,
        //  `bizrule` text,
        //  `data` text,
        //  PRIMARY KEY (`name`)
        //) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        $this->createTable('bug_auth_item',
                array(
                    'name' => 'string NOT NULL',
                    'type' => 'integer NOT NULL',
                    'description' => 'text',
                    'bizrule' => 'text',
                    'data' => 'text',
                    'PRIMARY KEY (`name`)'
                )
                );
        
        //CREATE TABLE IF NOT EXISTS `bug_auth_item_child` (
        //  `parent` varchar(64) NOT NULL,
        //  `child` varchar(64) NOT NULL,
        //  PRIMARY KEY (`parent`,`child`),
        //  KEY `child` (`child`)
        //) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        $this->createTable('bug_auth_item_child',
                array(
                    'parent' => 'string NOT NULL',
                    'child' => 'string NOT NULL',
                    'PRIMARY KEY (`parent`,`child`)'
                )
                );
        $this->createIndex('child', 'bug_auth_item_child', 'child');

        //CREATE TABLE IF NOT EXISTS `bug_auth_item_weight` (
        //  `itemname` varchar(64) NOT NULL,
        //  `type` int(11) NOT NULL,
        //  `weight` int(11) DEFAULT NULL,
        //  PRIMARY KEY (`itemname`)
        //) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        $this->createTable('bug_auth_item_weight',
                array(
                    'itemname' => 'string NOT NULL',
                    'type' => 'integer NOT NULL',
                    'weight' => 'integer DEFAULT NULL',
                    'PRIMARY KEY (`itemname`)'
                )
                );
        
        //CREATE TABLE IF NOT EXISTS `bug_change` (
        //  `id` int(11) NOT NULL AUTO_INCREMENT,
        //  `changeset_id` int(11) NOT NULL,
        //  `action` varchar(50) NOT NULL,
        //  `path` varchar(255) NOT NULL,
        //  PRIMARY KEY (`id`),
        //  KEY `fk_change_changeset_id` (`changeset_id`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26716 ;
        $this->createTable('bug_change',
            array(
                'id' => 'pk',
                'changeset_id' => 'integer NOT NULL',
                'action' => 'string NOT NULL',
                'path' => 'string NOT NULL',
            )
        );
        $this->createIndex('fk_change_changeset_id', 'bug_change', 'changeset_id');

        //CREATE TABLE IF NOT EXISTS `bug_changeset` (
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
        $this->createTable('bug_changeset',
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
            )
        );
        $this->createIndex('unique_ident', 'bug_changeset', 'unique_ident', true);
        $this->createIndex('fk_changeset_user_id', 'bug_changeset', 'user_id');
        $this->createIndex('fk_changeset_repository', 'bug_changeset', 'scm_id');
        
        //CREATE TABLE IF NOT EXISTS `bug_changeset_issue` (
        //  `id` int(11) NOT NULL AUTO_INCREMENT,
        //  `changeset_id` int(11) NOT NULL,
        //  `issue_id` int(11) NOT NULL,
        //  PRIMARY KEY (`id`),
        //  KEY `issue_id` (`issue_id`),
        //  KEY `changeset_id` (`changeset_id`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=198 ;
        $this->createTable("bug_changeset_issue",
            array(
                "id"=>"pk",
                "changeset_id"=>"integer NOT NULL",
                "issue_id"=>"integer NOT NULL",
            )
        );
        $this->createIndex('issue_id', 'bug_changeset_issue', 'issue_id');
        $this->createIndex('changeset_id', 'bug_changeset_issue', 'changeset_id');
        
        //CREATE TABLE IF NOT EXISTS `bug_comment` (
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
        $this->createTable("bug_comment", 
            array(
                "id"=>"pk",
                "content"=>"text NOT NULL",
                "issue_id"=>"integer NOT NULL",
                "created"=>"datetime DEFAULT NULL",
                "create_user_id"=>"integer DEFAULT NULL",
                "modified"=>"datetime DEFAULT NULL",
                "update_user_id"=>"integer DEFAULT NULL",
            )
        );
        $this->createIndex('FK_comment_issue', 'bug_comment', 'issue_id');
        $this->createIndex('FK_comment_author', 'bug_comment', 'create_user_id');
        $this->createIndex('FK_comment_updater', 'bug_comment', 'update_user_id');
        
        //CREATE TABLE IF NOT EXISTS `bug_comment_detail` (
        //  `id` int(10) NOT NULL AUTO_INCREMENT,
        //  `comment_id` int(10) NOT NULL,
        //  `change` varchar(255) DEFAULT NULL,
        //  PRIMARY KEY (`id`),
        //  KEY `bug_comment_detail_ibfk_1` (`comment_id`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1703 ;
        $this->createTable("bug_comment_detail",
            array(
                "id"=>"pk",
                "comment_id"=>"integer NOT NULL",
                "change"=>"string DEFAULT NULL",
            )
        );
        $this->createIndex('bug_comment_detail_ibfk_1', 'bug_comment_detail', 'comment_id');

        //CREATE TABLE IF NOT EXISTS `bug_config` (
        //  `key` varchar(100) COLLATE utf8_bin NOT NULL,
        //  `value` text COLLATE utf8_bin,
        //  PRIMARY KEY (`key`)
        //) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
        $this->createTable("bug_config",
            array(
                "key"=>"string COLLATE utf8_bin",
                "value"=>"text COLLATE utf8_bin",
                'PRIMARY KEY (`key`)'
            )
        );
        
        //CREATE TABLE IF NOT EXISTS `bug_issue` (
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
        //  KEY `fk_bug_issue_tracker_id` (`tracker_id`),
        //  KEY `fk_bug_issue_project_id` (`project_id`),
        //  KEY `fk_bug_issue_category_id` (`issue_category_id`),
        //  KEY `fk_bug_issue_prority_id` (`issue_priority_id`),
        //  KEY `fk_bug_issue_user_id` (`user_id`),
        //  KEY `fk_bug_issue_version_id` (`version_id`),
        //  KEY `fk_bug_issue_assigned_to_id` (`assigned_to`),
        //  KEY `fk_bug_issue_updated_by` (`updated_by`),
        //  KEY `closed` (`closed`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=701 ;
        $this->createTable("bug_issue", 
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
            )
        );
        $this->createIndex('fk_bug_issue_tracker_id', 'bug_issue', 'tracker_id');
        $this->createIndex('fk_bug_issue_project_id', 'bug_issue', 'project_id');
        $this->createIndex('fk_bug_issue_category_id', 'bug_issue', 'issue_category_id');
        $this->createIndex('fk_bug_issue_prority_id', 'bug_issue', 'issue_priority_id');
        $this->createIndex('fk_bug_issue_user_id', 'bug_issue', 'user_id');
        $this->createIndex('fk_bug_issue_version_id', 'bug_issue', 'version_id');
        $this->createIndex('fk_bug_issue_assigned_to_id', 'bug_issue', 'assigned_to');
        $this->createIndex('fk_bug_issue_updated_by', 'bug_issue', 'updated_by');
        $this->createIndex('closed', 'bug_issue', 'closed');
        
        //CREATE TABLE IF NOT EXISTS `bug_issue_category` (
        //  `id` int(11) NOT NULL AUTO_INCREMENT,
        //  `name` varchar(45) NOT NULL,
        //  `project_id` int(10) NOT NULL,
        //  `description` varchar(255) DEFAULT NULL,
        //  PRIMARY KEY (`id`),
        //  KEY `project_id` (`project_id`),
        //  KEY `name_UNIQUE` (`name`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;
        $this->createTable("bug_issue_category",
            array(
                "id"=>"pk",
                "name"=>"string NOT NULL",
                "project_id"=>"integer NOT NULL",
                "description"=>"string DEFAULT NULL",
            )
        );
        $this->createIndex('project_id', 'bug_issue_category', 'project_id');
        $this->createIndex('name_UNIQUE', 'bug_issue_category', 'name');
        
        //CREATE TABLE IF NOT EXISTS `bug_issue_priority` (
        //  `id` int(11) NOT NULL AUTO_INCREMENT,
        //  `name` varchar(45) NOT NULL,
        //  PRIMARY KEY (`id`),
        //  UNIQUE KEY `name_UNIQUE` (`name`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;
        $this->createTable("bug_issue_priority",
            array(
                "id"=>"pk",
                "name"=>"string NOT NULL",
            )
        );
        $this->createIndex('name_UNIQUE', 'bug_issue_priority', 'name', true);
        
        //CREATE TABLE IF NOT EXISTS `bug_member` (
        //  `id` int(10) NOT NULL AUTO_INCREMENT,
        //  `project_id` int(10) NOT NULL,
        //  `user_id` int(10) NOT NULL,
        //  `role` varchar(32) NOT NULL,
        //  PRIMARY KEY (`id`),
        //  KEY `FK_member_user` (`user_id`),
        //  KEY `FK_member_project` (`project_id`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;
        $this->createTable("bug_member",
            array(
                "id"=>"pk",
                "project_id"=>"integer NOT NULL",
                "user_id"=>"integer NOT NULL",
                "role"=>"string NOT NULL",
            )
        );
        $this->createIndex('FK_member_user', 'bug_member', 'user_id');
        $this->createIndex('FK_member_project', 'bug_member', 'project_id');

        //CREATE TABLE IF NOT EXISTS `bug_profiles` (
        //  `user_id` int(11) NOT NULL,
        //  `lastname` varchar(50) NOT NULL DEFAULT '',
        //  `firstname` varchar(50) NOT NULL DEFAULT '',
        //  `birthday` date NOT NULL DEFAULT '0000-00-00',
        //  `timezone` varchar(65) NOT NULL DEFAULT '',
        //  `locale` varchar(32) NOT NULL DEFAULT 'en_gb',
        //  PRIMARY KEY (`user_id`)
        //) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        $this->createTable("bug_profiles",
            array(
                "user_id"=>"pk",
                "lastname"=>"varchar(50) NOT NULL",
                "firstname"=>"varchar(50) NOT NULL",
                "birthday"=>"date NOT NULL DEFAULT '0000-00-00'",
                "timezone"=>"varchar(65) NOT NULL",
                "locale"=>"varchar(32) NOT NULL DEFAULT 'en_gb'",
            )
        );
        
        //CREATE TABLE IF NOT EXISTS `bug_profiles_fields` (
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
        $this->createTable("bug_profiles_fields",
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
            )
        );
        
        //CREATE TABLE IF NOT EXISTS `bug_project` (
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
        $this->createTable("bug_project",
            array(
                "id"=>"pk",
                "name"=>"varchar(30) NOT NULL",
                "description"=>"text",
                "homepage"=>"string DEFAULT NULL",
                "public"=>"boolean NOT NULL DEFAULT '1'",
                "created"=>"timestamp NULL DEFAULT NULL",
                "modified"=>"timestamp NULL DEFAULT NULL",
                "identifier"=>"varchar(20) DEFAULT NULL",
            )
        );
        $this->createIndex('identifier', 'bug_project', 'identifier', true);
        $this->createIndex('public', 'bug_project', 'public');
        
        //CREATE TABLE IF NOT EXISTS `bug_project_link` (
        //  `id` int(11) NOT NULL AUTO_INCREMENT,
        //  `url` varchar(255) NOT NULL,
        //  `title` varchar(255) NOT NULL,
        //  `description` varchar(255) NOT NULL,
        //  `position` int(11) NOT NULL,
        //  `project_id` int(11) NOT NULL,
        //  PRIMARY KEY (`id`),
        //  KEY `project_id` (`project_id`)
        //) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
        $this->createTable("bug_project_link",
            array(
                "id"=>"pk",
                "url"=>"string NOT NULL",
                "title"=>"string NOT NULL",
                "description"=>"string NOT NULL",
                "position"=>"integer NOT NULL",
                "project_id"=>"integer NOT NULL",
            )
        );
        $this->createIndex('project_id', 'bug_project_link', 'project_id');
        
        //CREATE TABLE IF NOT EXISTS `bug_project_tracker` (
        //  `project_id` int(11) NOT NULL,
        //  `tracker_id` int(11) NOT NULL,
        //  PRIMARY KEY (`project_id`,`tracker_id`),
        //  KEY `fk_project_tracker_project_id` (`project_id`),
        //  KEY `fk_project_tracker_tracker_id` (`tracker_id`)
        //) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        $this->createTable("bug_project_tracker",
            array(
                "project_id"=>"integer NOT NULL",
                "tracker_id"=>"integer NOT NULL",
                "PRIMARY KEY (`project_id`,`tracker_id`)",
            )
        );
        $this->createIndex('fk_project_tracker_project_id', 'bug_project_tracker', 'project_id');
        $this->createIndex('fk_project_tracker_tracker_id', 'bug_project_tracker', 'tracker_id');
        
        //CREATE TABLE IF NOT EXISTS `bug_related_issue` (
        //  `issue_from` int(11) NOT NULL,
        //  `issue_to` int(11) NOT NULL,
        //  `relation_type_id` int(11) NOT NULL,
        //  PRIMARY KEY (`issue_from`,`issue_to`),
        //  KEY `fk_related_issue_issue_from_id` (`issue_from`),
        //  KEY `fk_related_issue_issue_to_id` (`issue_to`),
        //  KEY `fk_related_issue_relation_type_id` (`relation_type_id`)
        //) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        $this->createTable("bug_related_issue",
            array(
                "issue_from" => "integer NOT NULL",
                "issue_to" => "integer NOT NULL",
                "relation_type_id" => "integer NOT NULL",
                "PRIMARY KEY (`issue_from`,`issue_to`)",
            )
        );
        $this->createIndex('fk_related_issue_issue_from_id', 'bug_related_issue', 'issue_from');
        $this->createIndex('fk_related_issue_issue_to_id', 'bug_related_issue', 'issue_to');
        $this->createIndex('fk_related_issue_relation_type_id', 'bug_related_issue', 'relation_type_id');
        
        //CREATE TABLE IF NOT EXISTS `bug_relation_type` (
        //  `id` int(11) NOT NULL AUTO_INCREMENT,
        //  `name` varchar(45) NOT NULL,
        //  `description` varchar(65) NOT NULL,
        //  PRIMARY KEY (`id`),
        //  UNIQUE KEY `name_UNIQUE` (`name`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;
        $this->createTable("bug_relation_type",
            array(
                "id"=>"pk",
                "name"=>"varchar(45) NOT NULL",
                "description"=>"varchar(65) NOT NULL",
            )
        );
        $this->createIndex('name_UNIQUE', 'bug_relation_type', 'name', true);

        //CREATE TABLE IF NOT EXISTS `bug_repository` (
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
        $this->createTable("bug_repository",
            array(
                "id"=>"pk",
                "project_id"=>"int(11) NOT NULL",
                "url"=>"varchar(60) DEFAULT NULL",
                "local_path"=>"varchar(255) DEFAULT NULL",
                "name"=>"varchar(255) NOT NULL",
                "identifier"=>"varchar(255) NOT NULL",
                "status"=>"int(11) NOT NULL DEFAULT '0'",
            )
        );
        $this->createIndex('name', 'bug_repository', 'name', true);
        $this->createIndex('repository_project_id', 'bug_repository', 'project_id');

        //CREATE TABLE IF NOT EXISTS `bug_tracker` (
        //  `id` int(11) NOT NULL AUTO_INCREMENT,
        //  `name` varchar(45) NOT NULL,
        //  `is_in_chlog` int(1) NOT NULL DEFAULT '1',
        //  `is_in_roadmap` int(1) NOT NULL DEFAULT '1',
        //  `position` int(11) NOT NULL DEFAULT '1',
        //  PRIMARY KEY (`id`)
        //) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;
        $this->createTable("bug_tracker",
            array(
                "id"=>"pk",
                "name"=>"varchar(45) NOT NULL",
                "is_in_chlog"=>"int(1) NOT NULL DEFAULT '1'",
                "is_in_roadmap"=>"int(1) NOT NULL DEFAULT '1'",
                "position"=>"int(11) NOT NULL DEFAULT '1'",
            )
        );
        
        //CREATE TABLE IF NOT EXISTS `bug_users` (
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
        $this->createTable("bug_users",
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
            )
        );
        $this->createIndex('username', 'bug_users', 'username', true);
        $this->createIndex('email', 'bug_users', 'email', true);
        $this->createIndex('status', 'bug_users', 'status');
        $this->createIndex('superuser', 'bug_users', 'superuser');
        
        //CREATE TABLE IF NOT EXISTS `bug_version` (
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
        $this->createTable("bug_version",
            array(
                "id"=>"pk",
                "project_id"=>"int(11) NOT NULL DEFAULT '0'",
                "name"=>"varchar(255) NOT NULL",
                "description"=>"varchar(255) DEFAULT NULL",
                "effective_date"=>"date DEFAULT NULL",
                "created"=>"timestamp NULL DEFAULT NULL",
                "modified"=>"timestamp NULL DEFAULT NULL",
                "version_order"=>"int(11) DEFAULT NULL",
            )
        );
        $this->createIndex('versions_project_id', 'bug_version', 'project_id');
        $this->createIndex('name', 'bug_version', 'name');
        
        //CREATE TABLE IF NOT EXISTS `bug_watcher` (
        //  `issue_id` int(11) NOT NULL,
        //  `user_id` int(11) NOT NULL,
        //  PRIMARY KEY (`issue_id`,`user_id`),
        //  KEY `fk_watcher_user_id` (`user_id`),
        //  KEY `fk_watcher_issue_id` (`issue_id`)
        //) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        $this->createTable("bug_watcher",
            array(
                "issue_id"=>"integer NOT NULL",
                "user_id"=>"integer NOT NULL",
                "PRIMARY KEY (`issue_id`,`user_id`)",
            )
        );
        $this->createIndex('fk_watcher_user_id', 'bug_watcher', 'user_id');
        $this->createIndex('fk_watcher_issue_id', 'bug_watcher', 'issue_id');
    }

    public function safeDown()
    {
        $this->dropTable("bug_action_log");
        $this->dropTable("bug_attachment");
        $this->dropTable("bug_auth_assignment");
        $this->dropTable("bug_auth_item");
        $this->dropTable("bug_auth_item_child");
        $this->dropTable("bug_auth_item_weight");
        $this->dropTable("bug_author_user");
        $this->dropTable("bug_change");
        $this->dropTable("bug_changeset");
        $this->dropTable("bug_changeset_issue");
        $this->dropTable("bug_comment");
        $this->dropTable("bug_comment_detail");
        $this->dropTable("bug_config");
        $this->dropTable("bug_issue");
        $this->dropTable("bug_issue_category");
        $this->dropTable("bug_issue_priority");
        $this->dropTable("bug_member");
        $this->dropTable("bug_profiles");
        $this->dropTable("bug_profiles_fields");
        $this->dropTable("bug_project");
        $this->dropTable("bug_project_link");
        $this->dropTable("bug_project_tracker");
        $this->dropTable("bug_related_issue");
        $this->dropTable("bug_relation_type");
        $this->dropTable("bug_repository");
        $this->dropTable("bug_tracker");
        $this->dropTable("bug_users");
        $this->dropTable("bug_version");
        $this->dropTable("bug_watcher");
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