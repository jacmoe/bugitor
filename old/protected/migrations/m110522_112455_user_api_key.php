<?php

class m110522_112455_user_api_key extends CDbMigration
{
    public function up()
    {
            $this->addColumn("{{users}}",
                    "apikey",
                    "string DEFAULT NULL");
    }

    public function down()
    {
        echo "m110522_112455_user_api_key does not support migration down.\n";
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
