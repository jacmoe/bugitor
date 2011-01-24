<?php

$this->createTable("bug_action_log", array(
    "id"=>"pk",
    "type"=>"varchar(32) NOT NULL",
    "author_id"=>"int(10) NOT NULL",
    "when"=>"datetime NOT NULL",
    "url"=>"varchar(100) NOT NULL",
    "project_id"=>"int(10) NOT NULL",
    "subject"=>"varchar(155) NOT NULL",
    "description"=>"tinytext NOT NULL",
), "");

$this->createTable("bug_auth_assignment", array(
    "itemname"=>"pk",
    "userid"=>"pk",
    "bizrule"=>"text",
    "data"=>"text",
), "");

$this->createTable("bug_auth_item", array(
    "name"=>"pk",
    "type"=>"int(11) NOT NULL",
    "description"=>"text",
    "bizrule"=>"text",
    "data"=>"text",
), "");

$this->createTable("bug_auth_item_child", array(
    "parent"=>"pk",
    "child"=>"pk",
), "");

$this->createTable("bug_auth_item_weight", array(
    "itemname"=>"pk",
    "type"=>"int(11) NOT NULL",
    "weight"=>"int(11)",
), "");

$this->createTable("bug_change", array(
    "id"=>"pk",
    "changeset_id"=>"int(11) NOT NULL",
    "action"=>"varchar(50) NOT NULL",
    "path"=>"varchar(255) NOT NULL",
), "");

$this->createTable("bug_changeset", array(
    "id"=>"pk",
    "revision"=>"varchar(50) NOT NULL",
    "user_id"=>"int(11) NOT NULL",
    "scm_id"=>"int(11) NOT NULL",
    "commit_date"=>"timestamp",
    "message"=>"tinytext NOT NULL",
    "short_rev"=>"int(11) NOT NULL",
    "parent"=>"varchar(50)",
    "branch"=>"varchar(50)",
    "tags"=>"varchar(50)",
    "add"=>"int(11)",
    "edit"=>"int(11)",
    "del"=>"int(11)",
), "");

$this->createTable("bug_comment", array(
    "id"=>"pk",
    "content"=>"text NOT NULL",
    "issue_id"=>"int(11) NOT NULL",
    "created"=>"datetime",
    "create_user_id"=>"int(11)",
    "modified"=>"datetime",
    "update_user_id"=>"int(11)",
), "");

$this->createTable("bug_comment_detail", array(
    "id"=>"pk",
    "comment_id"=>"int(10) NOT NULL",
    "change"=>"varchar(255)",
), "");

$this->createTable("bug_config", array(
    "key"=>"pk",
    "value"=>"text",
), "");

$this->createTable("bug_issue", array(
    "id"=>"pk",
    "tracker_id"=>"int(11) NOT NULL",
    "project_id"=>"int(11) NOT NULL",
    "subject"=>"varchar(255) NOT NULL",
    "description"=>"text NOT NULL",
    "issue_category_id"=>"int(11)",
    "user_id"=>"int(11) NOT NULL",
    "issue_priority_id"=>"int(11) NOT NULL",
    "version_id"=>"int(11)",
    "assigned_to"=>"int(11)",
    "created"=>"timestamp",
    "modified"=>"timestamp",
    "done_ratio"=>"int(11) NOT NULL",
    "status"=>"varchar(50) NOT NULL",
    "closed"=>"tinyint(1) NOT NULL",
    "pre_done_ratio"=>"int(11) NOT NULL",
    "updated_by"=>"int(11)",
    "last_comment"=>"int(10)",
), "");

$this->createTable("bug_issue_category", array(
    "id"=>"pk",
    "name"=>"varchar(45) NOT NULL",
    "project_id"=>"int(10) NOT NULL",
    "description"=>"varchar(255)",
), "");

$this->createTable("bug_issue_priority", array(
    "id"=>"pk",
    "name"=>"varchar(45) NOT NULL",
), "");

$this->createTable("bug_member", array(
    "id"=>"pk",
    "project_id"=>"int(10) NOT NULL",
    "user_id"=>"int(10) NOT NULL",
    "role"=>"varchar(32) NOT NULL",
), "");

$this->createTable("bug_profiles", array(
    "user_id"=>"pk",
    "lastname"=>"varchar(50) NOT NULL",
    "firstname"=>"varchar(50) NOT NULL",
    "birthday"=>"date NOT NULL DEFAULT '0000-00-00'",
    "timezone"=>"varchar(65) NOT NULL",
    "locale"=>"varchar(32) NOT NULL DEFAULT 'en_gb'",
), "");

$this->createTable("bug_profiles_fields", array(
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
), "");

$this->createTable("bug_project", array(
    "id"=>"pk",
    "name"=>"varchar(30) NOT NULL",
    "description"=>"text",
    "homepage"=>"varchar(255)",
    "public"=>"int(1) NOT NULL DEFAULT '1'",
    "created"=>"timestamp",
    "modified"=>"timestamp",
    "identifier"=>"varchar(20)",
), "");

$this->createTable("bug_project_link", array(
    "id"=>"pk",
    "url"=>"varchar(255) NOT NULL",
    "title"=>"varchar(255) NOT NULL",
    "description"=>"varchar(255) NOT NULL",
    "position"=>"int(11) NOT NULL",
    "project_id"=>"int(11) NOT NULL",
), "");

$this->createTable("bug_project_tracker", array(
    "project_id"=>"pk",
    "tracker_id"=>"pk",
), "");

$this->createTable("bug_related_issue", array(
    "issue_from"=>"pk",
    "issue_to"=>"pk",
    "relation_type_id"=>"int(11) NOT NULL",
), "");

$this->createTable("bug_relation_type", array(
    "id"=>"pk",
    "name"=>"varchar(45) NOT NULL",
    "description"=>"varchar(65) NOT NULL",
), "");

$this->createTable("bug_repository", array(
    "id"=>"pk",
    "project_id"=>"int(11) NOT NULL",
    "url"=>"varchar(60)",
    "local_path"=>"varchar(255)",
    "name"=>"varchar(255) NOT NULL",
    "identifier"=>"varchar(255) NOT NULL",
), "");

$this->createTable("bug_tracker", array(
    "id"=>"pk",
    "name"=>"varchar(45) NOT NULL",
    "is_in_chlog"=>"int(1) NOT NULL DEFAULT '1'",
    "is_in_roadmap"=>"int(1) NOT NULL DEFAULT '1'",
    "position"=>"int(11) NOT NULL DEFAULT '1'",
), "");

$this->createTable("bug_users", array(
    "id"=>"pk",
    "username"=>"varchar(20) NOT NULL",
    "password"=>"varchar(128) NOT NULL",
    "email"=>"varchar(128) NOT NULL",
    "activkey"=>"varchar(128) NOT NULL",
    "createtime"=>"int(10) NOT NULL",
    "lastvisit"=>"int(10) NOT NULL",
    "superuser"=>"int(1) NOT NULL",
    "status"=>"int(1) NOT NULL",
), "");

$this->createTable("bug_version", array(
    "id"=>"pk",
    "project_id"=>"int(11) NOT NULL",
    "name"=>"varchar(255) NOT NULL",
    "description"=>"varchar(255)",
    "effective_date"=>"date",
    "created"=>"timestamp",
    "modified"=>"timestamp",
    "version_order"=>"int(11)",
), "");

$this->createTable("bug_watcher", array(
    "issue_id"=>"pk",
    "user_id"=>"pk",
), "");

