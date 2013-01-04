<?php

class m110618_223117_version_to_milestone extends CDbMigration {

    public function up() {
        $this->dropForeignKey('fk_issue_version',
            '{{issue}}');
        $this->dropIndex('fk_issue_version_id',
            '{{issue}}');

        $this->renameTable('{{version}}','{{milestone}}');

        $this->renameColumn('{{issue}}', 'version_id', 'milestone_id');

        $this->createIndex('fk_issue_milestone_id', '{{issue}}', 'milestone_id');

        $this->addForeignKey('fk_issue_milestone',
            '{{issue}}',
            'milestone_id',
            '{{milestone}}', 'id',
            'SET NULL', 'NO ACTION'
        );
    }

    public function down() {
        echo "m110618_223117_version_to_milestone does not support migration down.\n";
        return false;
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
