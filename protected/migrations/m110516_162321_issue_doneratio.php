<?php

class m110516_162321_issue_doneratio extends CDbMigration
{
    public function up()
    {
        //ALTER TABLE `bugitor_issue` 
        //CHANGE `done_ratio` `done_ratio` INT( 11 ) 
        //NOT NULL DEFAULT '0'
        $this->alterColumn("{{issue}}",
                "done_ratio", 
                "integer NOT NULL DEFAULT '0'");
    }

    public function down()
    {
            echo "m110516_162321_issue_doneratio does not support migration down.\n";
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