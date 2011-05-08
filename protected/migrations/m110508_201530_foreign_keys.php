<?php

class m110508_201530_foreign_keys extends CDbMigration
{
    public function safeUp()
    {
        //ALTER TABLE `bug_action_log`
        //  ADD CONSTRAINT `fk_action_log_project_id` 
        //  FOREIGN KEY (`project_id`) 
        //  REFERENCES `bug_project` (`id`) 
        //  ON DELETE CASCADE ON UPDATE CASCADE,
        //  ADD CONSTRAINT `fk_action_log_user_id` 
        //  FOREIGN KEY (`author_id`) 
        //  REFERENCES `bug_users` (`id`) 
        //  ON DELETE CASCADE ON UPDATE CASCADE;
        $this->addForeignKey('fk_action_log_project_id',
            'bug_action_log',
            'project_id',
            'bug_project', 'id',
            'CASCADE', 'CASCADE'
        );
        $this->addForeignKey('fk_action_log_user_id',
            'bug_action_log',
            'author_id',
            'bug_users', 'id',
            'CASCADE', 'CASCADE'
        );

        //ALTER TABLE `bug_attachment`
        //  ADD CONSTRAINT `bug_attachment_ibfk_1` 
        //  FOREIGN KEY (`issue_id`) 
        //  REFERENCES `bug_issue` (`id`) 
        //  ON DELETE CASCADE,
        //  ADD CONSTRAINT `bug_attachment_ibfk_2` 
        //  FOREIGN KEY (`user_id`) 
        //  REFERENCES `bug_users` (`id`) 
        //  ON DELETE CASCADE;
        $this->addForeignKey('bug_attachment_ibfk_1',
            'bug_attachment',
            'issue_id',
            'bug_issue', 'id',
            'CASCADE'
        );
        $this->addForeignKey('bug_attachment_ibfk_2',
            'bug_attachment',
            'user_id',
            'bug_users', 'id',
            'CASCADE'
        );

        //ALTER TABLE `bug_author_user`
        //  ADD CONSTRAINT `bug_author_user_ibfk_1`
        //   FOREIGN KEY (`user_id`) 
        //   REFERENCES `bug_users` (`id`)
        //   ON DELETE CASCADE;
        $this->addForeignKey('bug_author_user_ibfk_1',
            'bug_author_user',
            'user_id',
            'bug_users', 'id',
            'CASCADE'
        );

        //ALTER TABLE `bug_auth_assignment`
        //  ADD CONSTRAINT `bug_auth_assignment_ibfk_1` 
        //  FOREIGN KEY (`itemname`) 
        //  REFERENCES `bug_auth_item` (`name`) 
        //  ON DELETE CASCADE ON UPDATE CASCADE;
        $this->addForeignKey('bug_auth_assignment_ibfk_1',
            'bug_auth_assignment',
            'itemname',
            'bug_auth_item', 'name',
            'CASCADE', 'CASCADE'
        );

        //ALTER TABLE `bug_auth_item_child`
        //  ADD CONSTRAINT `bug_auth_item_child_ibfk_1` 
        //  FOREIGN KEY (`parent`) 
        //  REFERENCES `bug_auth_item` (`name`) 
        //  ON DELETE CASCADE ON UPDATE CASCADE,
        //  ADD CONSTRAINT `bug_auth_item_child_ibfk_2` 
        //  FOREIGN KEY (`child`) 
        //  REFERENCES `bug_auth_item` (`name`) 
        //  ON DELETE CASCADE ON UPDATE CASCADE;
        $this->addForeignKey('bug_auth_item_child_ibfk_1',
            'bug_auth_item_child',
            'parent',
            'bug_auth_item', 'name',
            'CASCADE', 'CASCADE'
        );
        $this->addForeignKey('bug_auth_item_child_ibfk_2',
            'bug_auth_item_child',
            'child',
            'bug_auth_item', 'name',
            'CASCADE', 'CASCADE'
        );

        //ALTER TABLE `bug_auth_item_weight`
        //  ADD CONSTRAINT `bug_auth_item_weight_ibfk_1` 
        //  FOREIGN KEY (`itemname`) 
        //  REFERENCES `bug_auth_item` (`name`) 
        //  ON DELETE CASCADE ON UPDATE CASCADE;
        $this->addForeignKey('bug_auth_item_weight_ibfk_1',
            'bug_auth_item_weight',
            'itemname',
            'bug_auth_item', 'name',
            'CASCADE', 'CASCADE'
        );

        //ALTER TABLE `bug_change`
        //  ADD CONSTRAINT `fk_change_changeset_id` 
        //  FOREIGN KEY (`changeset_id`) 
        //  REFERENCES `bug_changeset` (`id`) 
        //  ON DELETE CASCADE;
        $this->addForeignKey('fk_change_changeset_id',
            'bug_change',
            'changeset_id',
            'bug_changeset', 'id',
            'CASCADE'
        );

        //ALTER TABLE `bug_changeset`
        //  ADD CONSTRAINT `fk_changeset_repository` 
        //  FOREIGN KEY (`scm_id`) 
        //  REFERENCES `bug_repository` (`id`) 
        //  ON DELETE NO ACTION,
        //  ADD CONSTRAINT `fk_changeset_user_id` 
        //  FOREIGN KEY (`user_id`) 
        //  REFERENCES `bug_users` (`id`) 
        //  ON DELETE NO ACTION;
        $this->addForeignKey('fk_changeset_repository',
            'bug_changeset',
            'scm_id',
            'bug_repository', 'id',
            'NO ACTION'
        );
        $this->addForeignKey('fk_changeset_user_id',
            'bug_changeset',
            'user_id',
            'bug_users', 'id',
            'NO ACTION'
        );

        //ALTER TABLE `bug_changeset_issue`
        //  ADD CONSTRAINT `bug_changeset_issue_ibfk_1` 
        //  FOREIGN KEY (`changeset_id`) 
        //  REFERENCES `bug_changeset` (`id`) 
        //  ON DELETE CASCADE,
        //  ADD CONSTRAINT `bug_changeset_issue_ibfk_2` 
        //  FOREIGN KEY (`issue_id`) 
        //  REFERENCES `bug_issue` (`id`) 
        //  ON DELETE CASCADE;
        $this->addForeignKey('bug_changeset_issue_ibfk_1',
            'bug_changeset_issue',
            'changeset_id',
            'bug_changeset', 'id',
            'CASCADE'
        );
        $this->addForeignKey('bug_changeset_issue_ibfk_2',
            'bug_changeset_issue',
            'issue_id',
            'bug_issue', 'id',
            'CASCADE'
        );

        //ALTER TABLE `bug_comment`
        //  ADD CONSTRAINT `FK_comment_author` 
        //  FOREIGN KEY (`create_user_id`) 
        //  REFERENCES `bug_users` (`id`) 
        //  ON DELETE CASCADE,
        //  ADD CONSTRAINT `FK_comment_issue` 
        //  FOREIGN KEY (`issue_id`) 
        //  REFERENCES `bug_issue` (`id`) 
        //  ON DELETE CASCADE,
        //  ADD CONSTRAINT `FK_comment_updater` 
        //  FOREIGN KEY (`update_user_id`) 
        //  REFERENCES `bug_users` (`id`) 
        //  ON DELETE CASCADE;
        $this->addForeignKey('FK_comment_author',
            'bug_comment',
            'create_user_id',
            'bug_users', 'id',
            'CASCADE'
        );
        $this->addForeignKey('FK_comment_issue',
            'bug_comment',
            'issue_id',
            'bug_issue', 'id',
            'CASCADE'
        );
        $this->addForeignKey('FK_comment_updater',
            'bug_comment',
            'update_user_id',
            'bug_users', 'id',
            'CASCADE'
        );

        //ALTER TABLE `bug_comment_detail`
        //  ADD CONSTRAINT `bug_comment_detail_ibfk_1` 
        //  FOREIGN KEY (`comment_id`) 
        //  REFERENCES `bug_comment` (`id`) 
        //  ON DELETE CASCADE;
        $this->addForeignKey('bug_comment_detail_ibfk_1',
            'bug_comment_detail',
            'comment_id',
            'bug_comment', 'id',
            'CASCADE'
        );

        //ALTER TABLE `bug_issue`
        //  ADD CONSTRAINT `fk_bug_issue_assigned_to` 
        //  FOREIGN KEY (`assigned_to`) 
        //  REFERENCES `bug_users` (`id`) 
        //  ON DELETE NO ACTION ON UPDATE NO ACTION,
        //  ADD CONSTRAINT `fk_bug_issue_category` 
        //  FOREIGN KEY (`issue_category_id`) 
        //  REFERENCES `bug_issue_category` (`id`) 
        //  ON DELETE NO ACTION ON UPDATE NO ACTION,
        //  ADD CONSTRAINT `fk_bug_issue_issue_priority` 
        //  FOREIGN KEY (`issue_priority_id`) 
        //  REFERENCES `bug_issue_priority` (`id`) 
        //  ON DELETE NO ACTION ON UPDATE NO ACTION,
        //  ADD CONSTRAINT `fk_bug_issue_project` 
        //  FOREIGN KEY (`project_id`) 
        //  REFERENCES `bug_project` (`id`) 
        //  ON DELETE CASCADE ON UPDATE NO ACTION,
        //  ADD CONSTRAINT `fk_bug_issue_tracker` 
        //  FOREIGN KEY (`tracker_id`) 
        //  REFERENCES `bug_tracker` (`id`) 
        //  ON DELETE NO ACTION ON UPDATE NO ACTION,
        //  ADD CONSTRAINT `fk_bug_issue_updated_by` 
        //  FOREIGN KEY (`updated_by`) 
        //  REFERENCES `bug_users` (`id`) 
        //  ON DELETE NO ACTION ON UPDATE NO ACTION,
        //  ADD CONSTRAINT `fk_bug_issue_user` 
        //  FOREIGN KEY (`user_id`) 
        //  REFERENCES `bug_users` (`id`) 
        //  ON DELETE NO ACTION ON UPDATE NO ACTION,
        //  ADD CONSTRAINT `fk_bug_issue_version` 
        //  FOREIGN KEY (`version_id`) 
        //  REFERENCES `bug_version` (`id`) 
        //  ON DELETE NO ACTION ON UPDATE NO ACTION;
        $this->addForeignKey('fk_bug_issue_assigned_to',
            'bug_issue',
            'assigned_to',
            'bug_users', 'id',
            'NO ACTION', 'NO ACTION'
        );
        $this->addForeignKey('fk_bug_issue_category',
            'bug_issue',
            'issue_category_id',
            'bug_issue_category', 'id',
            'NO ACTION', 'NO ACTION'
        );
        $this->addForeignKey('fk_bug_issue_issue_priority',
            'bug_issue',
            'issue_priority_id',
            'bug_issue_priority', 'id',
            'NO ACTION', 'NO ACTION'
        );
        $this->addForeignKey('fk_bug_issue_project',
            'bug_issue',
            'project_id',
            'bug_project', 'id',
            'CASCADE', 'NO ACTION'
        );
        $this->addForeignKey('fk_bug_issue_tracker',
            'bug_issue',
            'tracker_id',
            'bug_tracker', 'id',
            'NO ACTION', 'NO ACTION'
        );
        $this->addForeignKey('fk_bug_issue_updated_by',
            'bug_issue',
            'updated_by',
            'bug_users', 'id',
            'NO ACTION', 'NO ACTION'
        );
        $this->addForeignKey('fk_bug_issue_user',
            'bug_issue',
            'user_id',
            'bug_users', 'id',
            'NO ACTION', 'NO ACTION'
        );
        $this->addForeignKey('fk_bug_issue_version',
            'bug_issue',
            'version_id',
            'bug_version', 'id',
            'NO ACTION', 'NO ACTION'
        );

        //ALTER TABLE `bug_issue_category`
        //  ADD CONSTRAINT `FK_bug_issue_category_bug_project` 
        //  FOREIGN KEY (`project_id`) 
        //  REFERENCES `bug_project` (`id`) 
        //  ON DELETE CASCADE;
        $this->addForeignKey('FK_bug_issue_category_bug_project',
            'bug_issue_category',
            'project_id',
            'bug_project', 'id',
            'CASCADE'
        );

        //ALTER TABLE `bug_member`
        //  ADD CONSTRAINT `FK_member_project` 
        //  FOREIGN KEY (`project_id`) 
        //  REFERENCES `bug_project` (`id`) 
        //  ON DELETE CASCADE,
        //  ADD CONSTRAINT `FK_member_user` 
        //  FOREIGN KEY (`user_id`) 
        //  REFERENCES `bug_users` (`id`) 
        //  ON DELETE CASCADE;
        $this->addForeignKey('FK_member_project',
            'bug_member',
            'project_id',
            'bug_project', 'id',
            'CASCADE'
        );
        $this->addForeignKey('FK_member_user',
            'bug_member',
            'user_id',
            'bug_users', 'id',
            'CASCADE'
        );

        //ALTER TABLE `bug_project_link`
        //  ADD CONSTRAINT `bug_project_link_ibfk_1` 
        //  FOREIGN KEY (`project_id`) 
        //  REFERENCES `bug_project` (`id`);
        $this->addForeignKey('bug_project_link_ibfk_1',
            'bug_project_link',
            'project_id',
            'bug_project', 'id'
        );

        //ALTER TABLE `bug_project_tracker`
        //  ADD CONSTRAINT `fk_project_tracker_project_id` 
        //  FOREIGN KEY (`project_id`) 
        //  REFERENCES `bug_project` (`id`) 
        //  ON DELETE CASCADE,
        //  ADD CONSTRAINT `fk_project_tracker_tracker_id` 
        //  FOREIGN KEY (`tracker_id`) 
        //  REFERENCES `bug_tracker` (`id`) 
        //  ON DELETE CASCADE;
        $this->addForeignKey('fk_project_tracker_project_id',
            'bug_project_tracker',
            'project_id',
            'bug_project', 'id',
            'CASCADE'
        );
        $this->addForeignKey('fk_project_tracker_tracker_id',
            'bug_project_tracker',
            'tracker_id',
            'bug_tracker', 'id',
            'CASCADE'
        );

        //ALTER TABLE `bug_related_issue`
        //  ADD CONSTRAINT `fk_related_issue_issue_from_id` 
        //  FOREIGN KEY (`issue_from`) 
        //  REFERENCES `bug_issue` (`id`) 
        //  ON DELETE CASCADE,
        //  ADD CONSTRAINT `fk_related_issue_issue_to_id` 
        //  FOREIGN KEY (`issue_to`) 
        //  REFERENCES `bug_issue` (`id`) 
        //  ON DELETE CASCADE,
        //  ADD CONSTRAINT `fk_related_issue_relation_type_id` 
        //  FOREIGN KEY (`relation_type_id`) 
        //  REFERENCES `bug_relation_type` (`id`) 
        //  ON DELETE CASCADE;
        $this->addForeignKey('fk_related_issue_issue_from_id',
            'bug_related_issue',
            'issue_from',
            'bug_issue', 'id',
            'CASCADE'
        );
        $this->addForeignKey('fk_related_issue_issue_to_id',
            'bug_related_issue',
            'issue_to',
            'bug_issue', 'id',
            'CASCADE'
        );
        $this->addForeignKey('fk_related_issue_relation_type_id',
            'bug_related_issue',
            'relation_type_id',
            'bug_relation_type', 'id',
            'CASCADE'
        );

        //ALTER TABLE `bug_repository`
        //  ADD CONSTRAINT `repository_project_id` 
        //  FOREIGN KEY (`project_id`) 
        //  REFERENCES `bug_project` (`id`) 
        //  ON DELETE CASCADE;
        $this->addForeignKey('repository_project_id',
            'bug_repository',
            'project_id',
            'bug_project', 'id',
            'CASCADE'
        );

        //ALTER TABLE `bug_version`
        //  ADD CONSTRAINT `bug_version_ibfk_1` 
        //  FOREIGN KEY (`project_id`) 
        //  REFERENCES `bug_project` (`id`) 
        //  ON DELETE CASCADE;
        $this->addForeignKey('bug_version_ibfk_1',
            'bug_version',
            'project_id',
            'bug_project', 'id',
            'CASCADE'
        );

        //ALTER TABLE `bug_watcher`
        //  ADD CONSTRAINT `fk_watcher_issue_id` 
        //  FOREIGN KEY (`issue_id`) 
        //  REFERENCES `bug_issue` (`id`) 
        //  ON DELETE CASCADE,
        //  ADD CONSTRAINT `fk_watcher_user_id` 
        //  FOREIGN KEY (`user_id`) 
        //  REFERENCES `bug_users` (`id`) 
        //  ON DELETE CASCADE;
        $this->addForeignKey('fk_watcher_issue_id',
            'bug_watcher',
            'issue_id',
            'bug_issue', 'id',
            'CASCADE'
        );
        $this->addForeignKey('fk_watcher_user_id',
            'bug_watcher',
            'user_id',
            'bug_users', 'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_action_log_project_id', 
            'bug_action_log');
        $this->dropForeignKey('fk_action_log_user_id', 
            'bug_action_log');

        $this->dropForeignKey('bug_attachment_ibfk_1',
            'bug_attachment');
        $this->dropForeignKey('bug_attachment_ibfk_2',
            'bug_attachment');
        
        $this->dropForeignKey('bug_author_user_ibfk_1',
            'bug_author_user');
        
        $this->dropForeignKey('bug_auth_assignment_ibfk_1',
            'bug_auth_assignment');
        
        $this->dropForeignKey('bug_auth_item_child_ibfk_1',
            'bug_auth_item_child');
        $this->dropForeignKey('bug_auth_item_child_ibfk_2',
            'bug_auth_item_child');
    
        $this->dropForeignKey('bug_auth_item_weight_ibfk_1',
            'bug_auth_item_weight');
        
        $this->dropForeignKey('fk_change_changeset_id',
            'bug_change');
        
        $this->dropForeignKey('fk_changeset_repository',
            'bug_changeset');
        $this->dropForeignKey('fk_changeset_user_id',
            'bug_changeset');
        
        $this->dropForeignKey('bug_changeset_issue_ibfk_1',
            'bug_changeset_issue');
        $this->dropForeignKey('bug_changeset_issue_ibfk_2',
            'bug_changeset_issue');

        $this->dropForeignKey('FK_comment_author',
            'bug_comment');
        $this->dropForeignKey('FK_comment_issue',
            'bug_comment');
        $this->dropForeignKey('FK_comment_updater',
            'bug_comment');

        $this->dropForeignKey('bug_comment_detail_ibfk_1',
            'bug_comment_detail');

        $this->dropForeignKey('fk_bug_issue_assigned_to',
            'bug_issue');
        $this->dropForeignKey('fk_bug_issue_category',
            'bug_issue');
        $this->dropForeignKey('fk_bug_issue_issue_priority',
            'bug_issue');
        $this->dropForeignKey('fk_bug_issue_project',
            'bug_issue');
        $this->dropForeignKey('fk_bug_issue_tracker',
            'bug_issue');
        $this->dropForeignKey('fk_bug_issue_updated_by',
            'bug_issue');
        $this->dropForeignKey('fk_bug_issue_user',
            'bug_issue');
        $this->dropForeignKey('fk_bug_issue_version',
            'bug_issue');

        $this->dropForeignKey('FK_bug_issue_category_bug_project',
            'bug_issue_category');

        $this->dropForeignKey('FK_member_project',
            'bug_member');
        $this->dropForeignKey('FK_member_user',
            'bug_member');

        $this->dropForeignKey('bug_project_link_ibfk_1',
            'bug_project_link');

        $this->dropForeignKey('fk_project_tracker_project_id',
            'bug_project_tracker');
        $this->dropForeignKey('fk_project_tracker_tracker_id',
            'bug_project_tracker');

        $this->dropForeignKey('fk_related_issue_issue_from_id',
            'bug_related_issue');
        $this->dropForeignKey('fk_related_issue_issue_to_id',
            'bug_related_issue');
        $this->dropForeignKey('fk_related_issue_relation_type_id',
            'bug_related_issue');

        $this->dropForeignKey('repository_project_id',
            'bug_repository');

        $this->dropForeignKey('bug_version_ibfk_1',
            'bug_version');

        $this->dropForeignKey('fk_watcher_issue_id',
            'bug_watcher');
        $this->dropForeignKey('fk_watcher_user_id',
            'bug_watcher');

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