<?php

use yii\db\Schema;
use yii\db\Migration;

class m150225_020142_initial_tables extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%action_log}}', [
            'id' => Schema::TYPE_PK,
            'type' => Schema::TYPE_STRING . ' NOT NULL',
            'author_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'theDate' => Schema::TYPE_DATETIME . ' NOT NULL',
            'url' => Schema::TYPE_STRING . ' NOT NULL',
            'project_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'subject' => Schema::TYPE_STRING . ' NOT NULL',
            'description' => Schema::TYPE_TEXT . ' NOT NULL'
        ], $tableOptions);

        $this->createIndex('type', '{{%action_log}}', 'type');
        $this->createIndex('fk_action_log_user_id', '{{%action_log}}', 'author_id');
        $this->createIndex('fk_action_log_project_id', '{{%action_log}}', 'project_id');

        $this->createTable('{{%attachment}}', [
            'id' => Schema::TYPE_PK,
            'issue_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'size' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created' => Schema::TYPE_TIMESTAMP . ' NOT NULL'
        ], $tableOptions);
        $this->createIndex('issue_id', '{{%attachment}}', 'issue_id');
        $this->createIndex('user_id', '{{%attachment}}', 'user_id');
        $this->createIndex('name', '{{%attachment}}', 'name');

        $this->createTable('{{%author_user}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER,
            'author' => Schema::TYPE_STRING . ' NOT NULL'
        ], $tableOptions);
        $this->createIndex('author', '{{%author_user}}', 'author', true);
        $this->createIndex('user_id', '{{%author_user}}', 'user_id');

        $this->createTable('{{%change}}', [
            'id' => Schema::TYPE_PK,
            'changeset_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'action' => Schema::TYPE_STRING . ' NOT NULL',
            'path' => Schema::TYPE_STRING . ' NOT NULL'
        ], $tableOptions);
        $this->createIndex('fk_change_changeset_id', '{{%change}}', 'changeset_id');

        $this->createTable('{{%changeset}}', [
            'id' => Schema::TYPE_PK,
            'unique_ident' => Schema::TYPE_STRING . ' NOT NULL',
            'revision' => Schema::TYPE_STRING . ' NOT NULL',
            'author' => Schema::TYPE_STRING . ' DEFAULT NULL',
            'user_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'scm_id' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'commit_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL',
            'message' => Schema::TYPE_TEXT . ' NOT NULL',
            'short_rev' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'parent' => Schema::TYPE_STRING . ' DEFAULT NULL',
            'branch' => Schema::TYPE_STRING . ' DEFAULT NULL',
            'tags' => Schema::TYPE_STRING . ' DEFAULT NULL',
            'add' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'edit' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'del' => Schema::TYPE_INTEGER . ' DEFAULT NULL'
        ], $tableOptions);
        $this->createIndex('unique_ident', '{{%changeset}}', 'unique_ident', true);
        $this->createIndex('fk_changeset_user_id', '{{%changeset}}', 'user_id');
        $this->createIndex('fk_changeset_repository', '{{%changeset}}', 'scm_id');

        $this->createTable("{{%changeset_issue}}", [
            'id' => Schema::TYPE_PK,
            'changeset_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'issue_id' => Schema::TYPE_INTEGER . ' NOT NULL'
        ], $tableOptions);
        $this->createIndex('issue_id', '{{%changeset_issue}}', 'issue_id');
        $this->createIndex('changeset_id', '{{%changeset_issue}}', 'changeset_id');

        $this->createTable("{{%comment}}", [
            'id' => Schema::TYPE_PK,
            'content' => Schema::TYPE_TEXT . ' NOT NULL',
            'issue_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created' => Schema::TYPE_DATETIME . ' DEFAULT NULL',
            'create_user_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'modified' => Schema::TYPE_DATETIME . ' DEFAULT NULL',
            'update_user_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL'
        ], $tableOptions);
        $this->createIndex('FK_comment_issue', '{{%comment}}', 'issue_id');
        $this->createIndex('FK_comment_author', '{{%comment}}', 'create_user_id');
        $this->createIndex('FK_comment_updater', '{{%comment}}', 'update_user_id');

        $this->createTable("{{%comment_detail}}", [
            'id' => Schema::TYPE_PK,
            'comment_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'change' => Schema::TYPE_STRING . ' DEFAULT NULL'
        ], $tableOptions);
        $this->createIndex('comment_detail_ibfk_1', '{{%comment_detail}}', 'comment_id');

        $this->createTable("{{%issue}}", [
            'id' => Schema::TYPE_PK,
            'tracker_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'project_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'subject' => Schema::TYPE_STRING . ' NOT NULL',
            'description' => Schema::TYPE_TEXT . ' NOT NULL',
            'issue_category_id' => Schema::TYPE_INTEGER,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'issue_priority_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'version_id' => Schema::TYPE_INTEGER,
            'assigned_to' => Schema::TYPE_INTEGER,
            'created' => Schema::TYPE_TIMESTAMP,
            'modified' => Schema::TYPE_TIMESTAMP,
            'done_ratio' => Schema::TYPE_INTEGER . ' NOT NULL',
            'status' => Schema::TYPE_STRING . ' NOT NULL',
            'closed' => Schema::TYPE_BOOLEAN . ' NOT NULL',
            'pre_done_ratio' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_by' => Schema::TYPE_INTEGER,
            'last_comment' => Schema::TYPE_INTEGER
        ], $tableOptions);
        $this->createIndex('fk_issue_tracker_id', '{{%issue}}', 'tracker_id');
        $this->createIndex('fk_issue_project_id', '{{%issue}}', 'project_id');
        $this->createIndex('fk_issue_category_id', '{{%issue}}', 'issue_category_id');
        $this->createIndex('fk_issue_prority_id', '{{%issue}}', 'issue_priority_id');
        $this->createIndex('fk_issue_user_id', '{{%issue}}', 'user_id');
        $this->createIndex('fk_issue_version_id', '{{%issue}}', 'version_id');
        $this->createIndex('fk_issue_assigned_to_id', '{{%issue}}', 'assigned_to');
        $this->createIndex('fk_issue_updated_by', '{{%issue}}', 'updated_by');
        $this->createIndex('closed', '{{%issue}}', 'closed');

        $this->createTable("{{%issue_category}}", [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'project_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'description' => Schema::TYPE_STRING . ' DEFAULT NULL'
        ], $tableOptions);
        $this->createIndex('project_id', '{{%issue_category}}', 'project_id');
        $this->createIndex('name_UNIQUE', '{{%issue_category}}', 'name');

        $this->createTable("{{%issue_priority}}", [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL'
        ], $tableOptions);
        $this->createIndex('name_UNIQUE', '{{%issue_priority}}', 'name', true);

        $this->createTable("{{%member}}", [
            'id' => Schema::TYPE_PK,
            'project_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'role' => Schema::TYPE_STRING . ' NOT NULL'
        ], $tableOptions);
        $this->createIndex('FK_member_user', '{{%member}}', 'user_id');
        $this->createIndex('FK_member_project', '{{%member}}', 'project_id');

        $this->createTable("{{%project}}", [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'description' => Schema::TYPE_TEXT,
            'homepage' => Schema::TYPE_STRING . ' DEFAULT NULL',
            'public' => Schema::TYPE_BOOLEAN . " NOT NULL DEFAULT '1'",
            'created' => Schema::TYPE_TIMESTAMP . ' NULL DEFAULT NULL',
            'modified' => Schema::TYPE_TIMESTAMP . ' NULL DEFAULT NULL',
            'identifier' => Schema::TYPE_STRING . ' DEFAULT NULL'
        ], $tableOptions);
        $this->createIndex('identifier', '{{%project}}', 'identifier', true);
        $this->createIndex('public', '{{%project}}', 'public');

        $this->createTable("{{%project_link}}", [
            'id' => Schema::TYPE_PK,
            'url' => Schema::TYPE_STRING . ' NOT NULL',
            'title' => Schema::TYPE_STRING . ' NOT NULL',
            'description' => Schema::TYPE_STRING . ' NOT NULL',
            'position' => Schema::TYPE_INTEGER . ' NOT NULL',
            'project_id' => Schema::TYPE_INTEGER . ' NOT NULL'
        ], $tableOptions);
        $this->createIndex('project_id', '{{%project_link}}', 'project_id');

        $this->createTable("{{%project_tracker}}", [
            'project_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'tracker_id' => Schema::TYPE_INTEGER . ' NOT NULL'
        ], $tableOptions);
        $this->createIndex('fk_project_tracker_project_id', '{{%project_tracker}}', 'project_id');
        $this->createIndex('fk_project_tracker_tracker_id', '{{%project_tracker}}', 'tracker_id');
        $this->addPrimaryKey('pk_project_tracker', '{{%project_tracker}}', 'project_id, tracker_id');

        $this->createTable("{{%related_issue}}", [
            'issue_from' => Schema::TYPE_INTEGER . ' NOT NULL',
            'issue_to' => Schema::TYPE_INTEGER . ' NOT NULL',
            'relation_type_id' => Schema::TYPE_INTEGER . ' NOT NULL'
        ], $tableOptions);
        $this->createIndex('fk_related_issue_issue_from_id', '{{%related_issue}}', 'issue_from');
        $this->createIndex('fk_related_issue_issue_to_id', '{{%related_issue}}', 'issue_to');
        $this->createIndex('fk_related_issue_relation_type_id', '{{%related_issue}}', 'relation_type_id');
        $this->addPrimaryKey('pk_related_issue', '{{%related_issue}}', 'issue_from, issue_to');

        $this->createTable("{{%relation_type}}", [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'description' => Schema::TYPE_STRING . ' NOT NULL'
        ], $tableOptions);
        $this->createIndex('name_UNIQUE', '{{%relation_type}}', 'name', true);

        $this->createTable("{{%repository}}", [
            'id' => Schema::TYPE_PK,
            'project_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'url' => Schema::TYPE_STRING . ' DEFAULT NULL',
            'local_path' => Schema::TYPE_STRING . ' DEFAULT NULL',
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'identifier' => Schema::TYPE_STRING . ' NOT NULL',
            'status' => Schema::TYPE_INTEGER . " NOT NULL DEFAULT '0'"
        ], $tableOptions);
        $this->createIndex('name', '{{%repository}}', 'name', true);
        $this->createIndex('repository_project_id', '{{%repository}}', 'project_id');

        $this->createTable("{{%version}}", [
            'id' => Schema::TYPE_PK,
            'project_id' => Schema::TYPE_INTEGER . " NOT NULL DEFAULT '0'",
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'description' => Schema::TYPE_STRING . ' DEFAULT NULL',
            'effective_date' => Schema::TYPE_DATE . ' DEFAULT NULL',
            'created' => Schema::TYPE_TIMESTAMP . ' NULL DEFAULT NULL',
            'modified' => Schema::TYPE_TIMESTAMP . ' NULL DEFAULT NULL',
            'version_order' => Schema::TYPE_INTEGER . ' DEFAULT NULL'
        ], $tableOptions);
        $this->createIndex('versions_project_id', '{{%version}}', 'project_id');
        $this->createIndex('name', '{{%version}}', 'name');

        $this->createTable("{{%watcher}}", [
            'issue_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL'
        ], $tableOptions);
        $this->createIndex('fk_watcher_user_id', '{{%watcher}}', 'user_id');
        $this->createIndex('fk_watcher_issue_id', '{{%watcher}}', 'issue_id');
        $this->addPrimaryKey('pk_watcher', '{{%watcher}}', 'issue_id, user_id');

        $this->createTable("{{%tracker}}", [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'is_in_chlog' => Schema::TYPE_SMALLINT . " NOT NULL DEFAULT '0'",
            'is_in_roadmap' => Schema::TYPE_SMALLINT . " NOT NULL DEFAULT '0'",
            'position' => Schema::TYPE_INTEGER . " NOT NULL DEFAULT '0'"
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%action_log}}');
        $this->dropTable('{{%attachment}}');
        $this->dropTable('{{%author_user}}');
        $this->dropTable('{{%change}}');
        $this->dropTable('{{%changeset}}');
        $this->dropTable('{{%comment}}');
        $this->dropTable('{{%comment_detail}}');
        $this->dropTable('{{%issue}}');
        $this->dropTable('{{%issue_category}}');
        $this->dropTable('{{%issue_priority}}');
        $this->dropTable('{{%member}}');
        $this->dropTable('{{%project}}');
        $this->dropTable('{{%project_link}}');
        $this->dropTable('{{%project_tracker}}');
        $this->dropTable('{{%related_issue}}');
        $this->dropTable('{{%relation_type}}');
        $this->dropTable('{{%repository}}');
        $this->dropTable('{{%version}}');
        $this->dropTable('{{%watcher}}');
    }
}
