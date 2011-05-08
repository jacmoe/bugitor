<?php

class m110508_201530_foreign_keys extends CDbMigration
{
    public function safeUp()
    {
        //ALTER TABLE `{{action_log}}`
        //  ADD CONSTRAINT `fk_action_log_project_id` 
        //  FOREIGN KEY (`project_id`) 
        //  REFERENCES `bug_project` (`id`) 
        //  ON DELETE CASCADE ON UPDATE CASCADE,
        //  ADD CONSTRAINT `fk_action_log_user_id` 
        //  FOREIGN KEY (`author_id`) 
        //  REFERENCES `bug_users` (`id`) 
        //  ON DELETE CASCADE ON UPDATE CASCADE;
        $this->addForeignKey('fk_action_log_project_id',
            '{{action_log}}',
            'project_id',
            '{{project}}', 'id',
            'CASCADE', 'CASCADE'
        );
        $this->addForeignKey('fk_action_log_user_id',
            '{{action_log}}',
            'author_id',
            '{{users}}', 'id',
            'CASCADE', 'CASCADE'
        );

        //ALTER TABLE `{{attachment}}`
        //  ADD CONSTRAINT `attachment_ibfk_1` 
        //  FOREIGN KEY (`issue_id`) 
        //  REFERENCES `{{issue}}` (`id`) 
        //  ON DELETE CASCADE,
        //  ADD CONSTRAINT `attachment_ibfk_2` 
        //  FOREIGN KEY (`user_id`) 
        //  REFERENCES `bug_users` (`id`) 
        //  ON DELETE CASCADE;
        $this->addForeignKey('attachment_ibfk_1',
            '{{attachment}}',
            'issue_id',
            '{{issue}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('attachment_ibfk_2',
            '{{attachment}}',
            'user_id',
            '{{users}}', 'id',
            'CASCADE'
        );

        //ALTER TABLE `{{author_user}}`
        //  ADD CONSTRAINT `author_user_ibfk_1`
        //   FOREIGN KEY (`user_id`) 
        //   REFERENCES `bug_users` (`id`)
        //   ON DELETE CASCADE;
        $this->addForeignKey('author_user_ibfk_1',
            '{{author_user}}',
            'user_id',
            '{{users}}', 'id',
            'CASCADE'
        );

        //ALTER TABLE `{{auth_assignment}}`
        //  ADD CONSTRAINT `auth_assignment_ibfk_1` 
        //  FOREIGN KEY (`itemname`) 
        //  REFERENCES `bug_auth_item` (`name`) 
        //  ON DELETE CASCADE ON UPDATE CASCADE;
        $this->addForeignKey('auth_assignment_ibfk_1',
            '{{auth_assignment}}',
            'itemname',
            '{{auth_item}}', 'name',
            'CASCADE', 'CASCADE'
        );

        //ALTER TABLE `{{auth_item_child}}`
        //  ADD CONSTRAINT `auth_item_child_ibfk_1` 
        //  FOREIGN KEY (`parent`) 
        //  REFERENCES `bug_auth_item` (`name`) 
        //  ON DELETE CASCADE ON UPDATE CASCADE,
        //  ADD CONSTRAINT `auth_item_child_ibfk_2` 
        //  FOREIGN KEY (`child`) 
        //  REFERENCES `bug_auth_item` (`name`) 
        //  ON DELETE CASCADE ON UPDATE CASCADE;
        $this->addForeignKey('auth_item_child_ibfk_1',
            '{{auth_item_child}}',
            'parent',
            '{{auth_item}}', 'name',
            'CASCADE', 'CASCADE'
        );
        $this->addForeignKey('auth_item_child_ibfk_2',
            '{{auth_item_child}}',
            'child',
            '{{auth_item}}', 'name',
            'CASCADE', 'CASCADE'
        );

        //ALTER TABLE `{{auth_item_weight}}`
        //  ADD CONSTRAINT `auth_item_weight_ibfk_1` 
        //  FOREIGN KEY (`itemname`) 
        //  REFERENCES `bug_auth_item` (`name`) 
        //  ON DELETE CASCADE ON UPDATE CASCADE;
        $this->addForeignKey('auth_item_weight_ibfk_1',
            '{{auth_item_weight}}',
            'itemname',
            '{{auth_item}}', 'name',
            'CASCADE', 'CASCADE'
        );

        //ALTER TABLE `{{change}}`
        //  ADD CONSTRAINT `fk_change_changeset_id` 
        //  FOREIGN KEY (`changeset_id`) 
        //  REFERENCES `{{changeset}}` (`id`) 
        //  ON DELETE CASCADE;
        $this->addForeignKey('fk_change_changeset_id',
            '{{change}}',
            'changeset_id',
            '{{changeset}}', 'id',
            'CASCADE'
        );

        //ALTER TABLE `{{changeset}}`
        //  ADD CONSTRAINT `fk_changeset_repository` 
        //  FOREIGN KEY (`scm_id`) 
        //  REFERENCES `{{repository}}` (`id`) 
        //  ON DELETE NO ACTION,
        //  ADD CONSTRAINT `fk_changeset_user_id` 
        //  FOREIGN KEY (`user_id`) 
        //  REFERENCES `bug_users` (`id`) 
        //  ON DELETE NO ACTION;
        $this->addForeignKey('fk_changeset_repository',
            '{{changeset}}',
            'scm_id',
            '{{repository}}', 'id',
            'NO ACTION'
        );
        $this->addForeignKey('fk_changeset_user_id',
            '{{changeset}}',
            'user_id',
            '{{users}}', 'id',
            'NO ACTION'
        );

        //ALTER TABLE `{{changeset_issue}}`
        //  ADD CONSTRAINT `changeset_issue_ibfk_1` 
        //  FOREIGN KEY (`changeset_id`) 
        //  REFERENCES `{{changeset}}` (`id`) 
        //  ON DELETE CASCADE,
        //  ADD CONSTRAINT `changeset_issue_ibfk_2` 
        //  FOREIGN KEY (`issue_id`) 
        //  REFERENCES `{{issue}}` (`id`) 
        //  ON DELETE CASCADE;
        $this->addForeignKey('changeset_issue_ibfk_1',
            '{{changeset_issue}}',
            'changeset_id',
            '{{changeset}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('changeset_issue_ibfk_2',
            '{{changeset_issue}}',
            'issue_id',
            '{{issue}}', 'id',
            'CASCADE'
        );

        //ALTER TABLE `{{comment}}`
        //  ADD CONSTRAINT `FK_comment_author` 
        //  FOREIGN KEY (`create_user_id`) 
        //  REFERENCES `bug_users` (`id`) 
        //  ON DELETE CASCADE,
        //  ADD CONSTRAINT `FK_comment_issue` 
        //  FOREIGN KEY (`issue_id`) 
        //  REFERENCES `{{issue}}` (`id`) 
        //  ON DELETE CASCADE,
        //  ADD CONSTRAINT `FK_comment_updater` 
        //  FOREIGN KEY (`update_user_id`) 
        //  REFERENCES `bug_users` (`id`) 
        //  ON DELETE CASCADE;
        $this->addForeignKey('FK_comment_author',
            '{{comment}}',
            'create_user_id',
            '{{users}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('FK_comment_issue',
            '{{comment}}',
            'issue_id',
            '{{issue}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('FK_comment_updater',
            '{{comment}}',
            'update_user_id',
            '{{users}}', 'id',
            'CASCADE'
        );

        //ALTER TABLE `{{comment_detail}}`
        //  ADD CONSTRAINT `comment_detail_ibfk_1` 
        //  FOREIGN KEY (`comment_id`) 
        //  REFERENCES `{{comment}}` (`id`) 
        //  ON DELETE CASCADE;
        $this->addForeignKey('comment_detail_ibfk_1',
            '{{comment_detail}}',
            'comment_id',
            '{{comment}}', 'id',
            'CASCADE'
        );

        //ALTER TABLE `{{issue}}`
        //  ADD CONSTRAINT `fk_issue_assigned_to` 
        //  FOREIGN KEY (`assigned_to`) 
        //  REFERENCES `bug_users` (`id`) 
        //  ON DELETE NO ACTION ON UPDATE NO ACTION,
        //  ADD CONSTRAINT `fk_issue_category` 
        //  FOREIGN KEY (`issue_category_id`) 
        //  REFERENCES `bug_issue_category` (`id`) 
        //  ON DELETE NO ACTION ON UPDATE NO ACTION,
        //  ADD CONSTRAINT `fk_issue_issue_priority` 
        //  FOREIGN KEY (`issue_priority_id`) 
        //  REFERENCES `{{issue_priority}}` (`id`) 
        //  ON DELETE NO ACTION ON UPDATE NO ACTION,
        //  ADD CONSTRAINT `fk_issue_project` 
        //  FOREIGN KEY (`project_id`) 
        //  REFERENCES `bug_project` (`id`) 
        //  ON DELETE CASCADE ON UPDATE NO ACTION,
        //  ADD CONSTRAINT `fk_issue_tracker` 
        //  FOREIGN KEY (`tracker_id`) 
        //  REFERENCES `{{tracker}}` (`id`) 
        //  ON DELETE NO ACTION ON UPDATE NO ACTION,
        //  ADD CONSTRAINT `fk_issue_updated_by` 
        //  FOREIGN KEY (`updated_by`) 
        //  REFERENCES `bug_users` (`id`) 
        //  ON DELETE NO ACTION ON UPDATE NO ACTION,
        //  ADD CONSTRAINT `fk_issue_user` 
        //  FOREIGN KEY (`user_id`) 
        //  REFERENCES `bug_users` (`id`) 
        //  ON DELETE NO ACTION ON UPDATE NO ACTION,
        //  ADD CONSTRAINT `fk_issue_version` 
        //  FOREIGN KEY (`version_id`) 
        //  REFERENCES `{{version}}` (`id`) 
        //  ON DELETE NO ACTION ON UPDATE NO ACTION;
        $this->addForeignKey('fk_issue_assigned_to',
            '{{issue}}',
            'assigned_to',
            '{{users}}', 'id',
            'NO ACTION', 'NO ACTION'
        );
        $this->addForeignKey('fk_issue_category',
            '{{issue}}',
            'issue_category_id',
            '{{issue_category}}', 'id',
            'NO ACTION', 'NO ACTION'
        );
        $this->addForeignKey('fk_issue_issue_priority',
            '{{issue}}',
            'issue_priority_id',
            '{{issue_priority}}', 'id',
            'NO ACTION', 'NO ACTION'
        );
        $this->addForeignKey('fk_issue_project',
            '{{issue}}',
            'project_id',
            '{{project}}', 'id',
            'CASCADE', 'NO ACTION'
        );
        $this->addForeignKey('fk_issue_tracker',
            '{{issue}}',
            'tracker_id',
            '{{tracker}}', 'id',
            'NO ACTION', 'NO ACTION'
        );
        $this->addForeignKey('fk_issue_updated_by',
            '{{issue}}',
            'updated_by',
            '{{users}}', 'id',
            'NO ACTION', 'NO ACTION'
        );
        $this->addForeignKey('fk_issue_user',
            '{{issue}}',
            'user_id',
            '{{users}}', 'id',
            'NO ACTION', 'NO ACTION'
        );
        $this->addForeignKey('fk_issue_version',
            '{{issue}}',
            'version_id',
            '{{version}}', 'id',
            'NO ACTION', 'NO ACTION'
        );

        //ALTER TABLE `bug_issue_category`
        //  ADD CONSTRAINT `fk_issue_category_project` 
        //  FOREIGN KEY (`project_id`) 
        //  REFERENCES `bug_project` (`id`) 
        //  ON DELETE CASCADE;
        $this->addForeignKey('fk_issue_category_project',
            '{{issue_category}}',
            'project_id',
            '{{project}}', 'id',
            'CASCADE'
        );

        //ALTER TABLE `{{member}}`
        //  ADD CONSTRAINT `FK_member_project` 
        //  FOREIGN KEY (`project_id`) 
        //  REFERENCES `bug_project` (`id`) 
        //  ON DELETE CASCADE,
        //  ADD CONSTRAINT `FK_member_user` 
        //  FOREIGN KEY (`user_id`) 
        //  REFERENCES `bug_users` (`id`) 
        //  ON DELETE CASCADE;
        $this->addForeignKey('FK_member_project',
            '{{member}}',
            'project_id',
            '{{project}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('FK_member_user',
            '{{member}}',
            'user_id',
            '{{users}}', 'id',
            'CASCADE'
        );

        //ALTER TABLE `{{project_link}}`
        //  ADD CONSTRAINT `project_link_ibfk_1` 
        //  FOREIGN KEY (`project_id`) 
        //  REFERENCES `bug_project` (`id`);
        $this->addForeignKey('project_link_ibfk_1',
            '{{project_link}}',
            'project_id',
            '{{project}}', 'id'
        );

        //ALTER TABLE `{{project_tracker}}`
        //  ADD CONSTRAINT `fk_project_tracker_project_id` 
        //  FOREIGN KEY (`project_id`) 
        //  REFERENCES `bug_project` (`id`) 
        //  ON DELETE CASCADE,
        //  ADD CONSTRAINT `fk_project_tracker_tracker_id` 
        //  FOREIGN KEY (`tracker_id`) 
        //  REFERENCES `{{tracker}}` (`id`) 
        //  ON DELETE CASCADE;
        $this->addForeignKey('fk_project_tracker_project_id',
            '{{project_tracker}}',
            'project_id',
            '{{project}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('fk_project_tracker_tracker_id',
            '{{project_tracker}}',
            'tracker_id',
            '{{tracker}}', 'id',
            'CASCADE'
        );

        //ALTER TABLE `{{related_issue}}`
        //  ADD CONSTRAINT `fk_related_issue_issue_from_id` 
        //  FOREIGN KEY (`issue_from`) 
        //  REFERENCES `{{issue}}` (`id`) 
        //  ON DELETE CASCADE,
        //  ADD CONSTRAINT `fk_related_issue_issue_to_id` 
        //  FOREIGN KEY (`issue_to`) 
        //  REFERENCES `{{issue}}` (`id`) 
        //  ON DELETE CASCADE,
        //  ADD CONSTRAINT `fk_related_issue_relation_type_id` 
        //  FOREIGN KEY (`relation_type_id`) 
        //  REFERENCES `{{relation_type}}` (`id`) 
        //  ON DELETE CASCADE;
        $this->addForeignKey('fk_related_issue_issue_from_id',
            '{{related_issue}}',
            'issue_from',
            '{{issue}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('fk_related_issue_issue_to_id',
            '{{related_issue}}',
            'issue_to',
            '{{issue}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('fk_related_issue_relation_type_id',
            '{{related_issue}}',
            'relation_type_id',
            '{{relation_type}}', 'id',
            'CASCADE'
        );

        //ALTER TABLE `{{repository}}`
        //  ADD CONSTRAINT `repository_project_id` 
        //  FOREIGN KEY (`project_id`) 
        //  REFERENCES `bug_project` (`id`) 
        //  ON DELETE CASCADE;
        $this->addForeignKey('repository_project_id',
            '{{repository}}',
            'project_id',
            '{{project}}', 'id',
            'CASCADE'
        );

        //ALTER TABLE `{{version}}`
        //  ADD CONSTRAINT `version_ibfk_1` 
        //  FOREIGN KEY (`project_id`) 
        //  REFERENCES `bug_project` (`id`) 
        //  ON DELETE CASCADE;
        $this->addForeignKey('version_ibfk_1',
            '{{version}}',
            'project_id',
            '{{project}}', 'id',
            'CASCADE'
        );

        //ALTER TABLE `bug_watcher`
        //  ADD CONSTRAINT `fk_watcher_issue_id` 
        //  FOREIGN KEY (`issue_id`) 
        //  REFERENCES `{{issue}}` (`id`) 
        //  ON DELETE CASCADE,
        //  ADD CONSTRAINT `fk_watcher_user_id` 
        //  FOREIGN KEY (`user_id`) 
        //  REFERENCES `bug_users` (`id`) 
        //  ON DELETE CASCADE;
        $this->addForeignKey('fk_watcher_issue_id',
            '{{watcher}}',
            'issue_id',
            '{{issue}}', 'id',
            'CASCADE'
        );
        $this->addForeignKey('fk_watcher_user_id',
            '{{watcher}}',
            'user_id',
            '{{users}}', 'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_action_log_project_id', 
            '{{action_log}}');
        $this->dropForeignKey('fk_action_log_user_id', 
            '{{action_log}}');

        $this->dropForeignKey('attachment_ibfk_1',
            '{{attachment}}');
        $this->dropForeignKey('attachment_ibfk_2',
            '{{attachment}}');
        
        $this->dropForeignKey('author_user_ibfk_1',
            '{{author_user}}');
        
        $this->dropForeignKey('auth_assignment_ibfk_1',
            '{{auth_assignment}}');
        
        $this->dropForeignKey('auth_item_child_ibfk_1',
            '{{auth_item_child}}');
        $this->dropForeignKey('auth_item_child_ibfk_2',
            '{{auth_item_child}}');
    
        $this->dropForeignKey('auth_item_weight_ibfk_1',
            '{{auth_item_weight}}');
        
        $this->dropForeignKey('fk_change_changeset_id',
            '{{change}}');
        
        $this->dropForeignKey('fk_changeset_repository',
            '{{changeset}}');
        $this->dropForeignKey('fk_changeset_user_id',
            '{{changeset}}');
        
        $this->dropForeignKey('changeset_issue_ibfk_1',
            '{{changeset_issue}}');
        $this->dropForeignKey('changeset_issue_ibfk_2',
            '{{changeset_issue}}');

        $this->dropForeignKey('FK_comment_author',
            '{{comment}}');
        $this->dropForeignKey('FK_comment_issue',
            '{{comment}}');
        $this->dropForeignKey('FK_comment_updater',
            '{{comment}}');

        $this->dropForeignKey('comment_detail_ibfk_1',
            '{{comment_detail}}');

        $this->dropForeignKey('fk_issue_assigned_to',
            '{{issue}}');
        $this->dropForeignKey('fk_issue_category',
            '{{issue}}');
        $this->dropForeignKey('fk_issue_issue_priority',
            '{{issue}}');
        $this->dropForeignKey('fk_issue_project',
            '{{issue}}');
        $this->dropForeignKey('fk_issue_tracker',
            '{{issue}}');
        $this->dropForeignKey('fk_issue_updated_by',
            '{{issue}}');
        $this->dropForeignKey('fk_issue_user',
            '{{issue}}');
        $this->dropForeignKey('fk_issue_version',
            '{{issue}}');

        $this->dropForeignKey('fk_issue_category_project',
            '{{issue_category}}');

        $this->dropForeignKey('FK_member_project',
            '{{member}}');
        $this->dropForeignKey('FK_member_user',
            '{{member}}');

        $this->dropForeignKey('project_link_ibfk_1',
            '{{project_link}}');

        $this->dropForeignKey('fk_project_tracker_project_id',
            '{{project_tracker}}');
        $this->dropForeignKey('fk_project_tracker_tracker_id',
            '{{project_tracker}}');

        $this->dropForeignKey('fk_related_issue_issue_from_id',
            '{{related_issue}}');
        $this->dropForeignKey('fk_related_issue_issue_to_id',
            '{{related_issue}}');
        $this->dropForeignKey('fk_related_issue_relation_type_id',
            '{{related_issue}}');

        $this->dropForeignKey('repository_project_id',
            '{{repository}}');

        $this->dropForeignKey('version_ibfk_1',
            '{{version}}');

        $this->dropForeignKey('fk_watcher_issue_id',
            '{{watcher}}');
        $this->dropForeignKey('fk_watcher_user_id',
            '{{watcher}}');

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