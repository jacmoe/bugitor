<?php

use yii\db\Schema;
use yii\db\Migration;

class m151111_231759_foreign_keys extends Migration
{
    public function safeUp()
    {
        $this->addForeignKey('fk_action_log_project_id',
            '{{%action_log}}',
            'project_id',
            '{{%project}}', 'id',
            'CASCADE', 'CASCADE'
        );
        $this->addForeignKey('fk_action_log_user_id',
            '{{%action_log}}',
            'author_id',
            '{{%user}}', 'id',
            'CASCADE', 'CASCADE'
        );
        $this->addForeignKey('attachment_ibfk_1',
            '{{%attachment}}',
            'issue_id',
            '{{%issue}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('attachment_ibfk_2',
            '{{%attachment}}',
            'user_id',
            '{{%user}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('author_user_ibfk_1',
            '{{%author_user}}',
            'user_id',
            '{{%user}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('fk_change_changeset_id',
            '{{%change}}',
            'changeset_id',
            '{{%changeset}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('fk_changeset_repository',
            '{{%changeset}}',
            'scm_id',
            '{{%repository}}', 'id',
            'NO ACTION'
        );
        $this->addForeignKey('fk_changeset_user_id',
            '{{%changeset}}',
            'user_id',
            '{{%user}}', 'id',
            'NO ACTION'
        );
        $this->addForeignKey('changeset_issue_ibfk_1',
            '{{%changeset_issue}}',
            'changeset_id',
            '{{%changeset}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('changeset_issue_ibfk_2',
            '{{%changeset_issue}}',
            'issue_id',
            '{{%issue}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('FK_comment_author',
            '{{%comment}}',
            'create_user_id',
            '{{%user}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('FK_comment_issue',
            '{{%comment}}',
            'issue_id',
            '{{%issue}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('FK_comment_updater',
            '{{%comment}}',
            'update_user_id',
            '{{%user}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('comment_detail_ibfk_1',
            '{{%comment_detail}}',
            'comment_id',
            '{{%comment}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('fk_issue_assigned_to',
            '{{%issue}}',
            'assigned_to',
            '{{%user}}', 'id',
            'NO ACTION', 'NO ACTION'
        );
        $this->addForeignKey('fk_issue_category',
            '{{%issue}}',
            'issue_category_id',
            '{{%issue_category}}', 'id',
            'NO ACTION', 'NO ACTION'
        );
        $this->addForeignKey('fk_issue_issue_priority',
            '{{%issue}}',
            'issue_priority_id',
            '{{%issue_priority}}', 'id',
            'NO ACTION', 'NO ACTION'
        );
        $this->addForeignKey('fk_issue_project',
            '{{%issue}}',
            'project_id',
            '{{%project}}', 'id',
            'CASCADE', 'NO ACTION'
        );
        $this->addForeignKey('fk_issue_tracker',
            '{{%issue}}',
            'tracker_id',
            '{{%tracker}}', 'id',
            'NO ACTION', 'NO ACTION'
        );
        $this->addForeignKey('fk_issue_updated_by',
            '{{%issue}}',
            'updated_by',
            '{{%user}}', 'id',
            'NO ACTION', 'NO ACTION'
        );
        $this->addForeignKey('fk_issue_user',
            '{{%issue}}',
            'user_id',
            '{{%user}}', 'id',
            'NO ACTION', 'NO ACTION'
        );
        $this->addForeignKey('fk_issue_version',
            '{{%issue}}',
            'version_id',
            '{{%version}}', 'id',
            'NO ACTION', 'NO ACTION'
        );
        $this->addForeignKey('fk_issue_category_project',
            '{{%issue_category}}',
            'project_id',
            '{{%project}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('FK_member_project',
            '{{%member}}',
            'project_id',
            '{{%project}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('FK_member_user',
            '{{%member}}',
            'user_id',
            '{{%user}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('project_link_ibfk_1',
            '{{%project_link}}',
            'project_id',
            '{{%project}}', 'id'
        );
        $this->addForeignKey('fk_project_tracker_project_id',
            '{{%project_tracker}}',
            'project_id',
            '{{%project}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('fk_project_tracker_tracker_id',
            '{{%project_tracker}}',
            'tracker_id',
            '{{%tracker}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('fk_related_issue_issue_from_id',
            '{{%related_issue}}',
            'issue_from',
            '{{%issue}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('fk_related_issue_issue_to_id',
            '{{%related_issue}}',
            'issue_to',
            '{{%issue}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('fk_related_issue_relation_type_id',
            '{{%related_issue}}',
            'relation_type_id',
            '{{%relation_type}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('repository_project_id',
            '{{%repository}}',
            'project_id',
            '{{%project}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('version_ibfk_1',
            '{{%version}}',
            'project_id',
            '{{%project}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('fk_watcher_issue_id',
            '{{%watcher}}',
            'issue_id',
            '{{%issue}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('fk_watcher_user_id',
            '{{%watcher}}',
            'user_id',
            '{{%user}}', 'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_action_log_project_id',
            '{{%action_log}}');
        $this->dropForeignKey('fk_action_log_user_id',
            '{{%action_log}}');
        $this->dropForeignKey('attachment_ibfk_1',
            '{{%attachment}}');
        $this->dropForeignKey('attachment_ibfk_2',
            '{{%attachment}}');
        $this->dropForeignKey('author_user_ibfk_1',
            '{{%author_user}}');
        $this->dropForeignKey('fk_change_changeset_id',
            '{{%change}}');
        $this->dropForeignKey('fk_changeset_repository',
            '{{%changeset}}');
        $this->dropForeignKey('fk_changeset_user_id',
            '{{%changeset}}');
        $this->dropForeignKey('changeset_issue_ibfk_1',
            '{{%changeset_issue}}');
        $this->dropForeignKey('changeset_issue_ibfk_2',
            '{{%changeset_issue}}');
        $this->dropForeignKey('FK_comment_author',
            '{{%comment}}');
        $this->dropForeignKey('FK_comment_issue',
            '{{%comment}}');
        $this->dropForeignKey('FK_comment_updater',
            '{{%comment}}');
        $this->dropForeignKey('comment_detail_ibfk_1',
            '{{%comment_detail}}');
        $this->dropForeignKey('fk_issue_assigned_to',
            '{{%issue}}');
        $this->dropForeignKey('fk_issue_category',
            '{{%issue}}');
        $this->dropForeignKey('fk_issue_issue_priority',
            '{{%issue}}');
        $this->dropForeignKey('fk_issue_project',
            '{{%issue}}');
        $this->dropForeignKey('fk_issue_tracker',
            '{{%issue}}');
        $this->dropForeignKey('fk_issue_updated_by',
            '{{%issue}}');
        $this->dropForeignKey('fk_issue_user',
            '{{%issue}}');
        $this->dropForeignKey('fk_issue_version',
            '{{%issue}}');
        $this->dropForeignKey('fk_issue_category_project',
            '{{%issue_category}}');
        $this->dropForeignKey('FK_member_project',
            '{{%member}}');
        $this->dropForeignKey('FK_member_user',
            '{{%member}}');
        $this->dropForeignKey('project_link_ibfk_1',
            '{{%project_link}}');
        $this->dropForeignKey('fk_project_tracker_project_id',
            '{{%project_tracker}}');
        $this->dropForeignKey('fk_project_tracker_tracker_id',
            '{{%project_tracker}}');
        $this->dropForeignKey('fk_related_issue_issue_from_id',
            '{{%related_issue}}');
        $this->dropForeignKey('fk_related_issue_issue_to_id',
            '{{%related_issue}}');
        $this->dropForeignKey('fk_related_issue_relation_type_id',
            '{{%related_issue}}');
        $this->dropForeignKey('repository_project_id',
            '{{%repository}}');
        $this->dropForeignKey('version_ibfk_1',
            '{{%version}}');
        $this->dropForeignKey('fk_watcher_issue_id',
            '{{%watcher}}');
        $this->dropForeignKey('fk_watcher_user_id',
            '{{%watcher}}');
    }
}
