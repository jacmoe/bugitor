<?php

class m110525_082320_adjust_foreign_keys_project_delete extends CDbMigration
{
    public function up()
    {
        $this->dropForeignKey('fk_issue_category',
            '{{issue}}');
        
        $this->dropForeignKey('fk_issue_version',
            '{{issue}}');
        
        $this->addForeignKey('fk_issue_category',
            '{{issue}}',
            'issue_category_id',
            '{{issue_category}}', 'id',
            'SET NULL', 'NO ACTION'
        );
        
        $this->addForeignKey('fk_issue_version',
            '{{issue}}',
            'version_id',
            '{{version}}', 'id',
            'SET NULL', 'NO ACTION'
        );
    }

    public function down()
    {
            echo "m110525_082320_adjust_foreign_keys_project_delete does not support migration down.\n";
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