<?php

class m110523_133517_notification_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{notification}}',
                array(
                    "id" => "pk",
                    "issue_id" => "integer NOT NULL",
                    "comment_id" => "integer NOT NULL",
                    "updated_by" => "integer NOT NULL",
                ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");

        $this->createIndex("fk_notification_issue_id", "{{notification}}", "issue_id");
        $this->createIndex("fk_notification_comment_id", "{{notification}}", "comment_id");
        $this->createIndex("fk_notification_updated_by", "{{notification}}", "updated_by");

        $this->addForeignKey('fk_notification_issue_id',
            '{{notification}}',
            'issue_id',
            '{{issue}}', 'id',
            'CASCADE', 'CASCADE'
        );
        $this->addForeignKey('fk_notification_comment_id',
            '{{notification}}',
            'comment_id',
            '{{comment}}', 'id',
            'CASCADE', 'CASCADE'
        );
        $this->addForeignKey('fk_notification_updated_by',
            '{{notification}}',
            'updated_by',
            '{{users}}', 'id',
            'CASCADE', 'CASCADE'
        );
    }

    public function down()
    {
            echo "m110523_133517_notification_table does not support migration down.\n";
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
