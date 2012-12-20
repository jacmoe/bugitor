<?php

class m111205_180601_repository_type extends CDbMigration
{
    public function up()
    {
        $this->addColumn("{{repository}}",
                "type",
                "string NOT NULL DEFAULT 'hg'");
    }

    public function down()
    {
        echo "m111205_180601_repository_type does not support migration down.\n";
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
