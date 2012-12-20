<?php

class m110518_144022_pending_changeset extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{pending_changeset}}',
            array(
                'id' => 'pk',
                'changeset_id' => 'integer NOT NULL',
            ),"ENGINE=InnoDB DEFAULT CHARSET=utf8");

        $this->createIndex('fk_pending_changeset_id', '{{pending_changeset}}', 'changeset_id');

        $this->addForeignKey('fk_pending_changeset_id',
            '{{pending_changeset}}',
            'changeset_id',
            '{{changeset}}', 'id',
            'CASCADE'
        );
    }

    public function down()
    {
            echo "m110518_144022_pending_changeset does not support migration down.\n";
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
