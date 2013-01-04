<?php

class m110517_220431_change_diff_to_blob extends CDbMigration
{
    public function up()
    {
        $this->alterColumn("{{change}}",
                "diff",
                "binary DEFAULT NULL");
    }

    public function down()
    {
            echo "m110517_220431_change_diff_to_blob does not support migration down.\n";
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
