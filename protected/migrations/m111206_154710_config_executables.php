<?php

class m111206_154710_config_executables extends CDbMigration
{
    public function up()
    {
        $this->execute("INSERT INTO `{{config}}` (`key`, `value`) VALUES
        ('git_executable', 's:12:\"/usr/bin/git\";'),
        ('svn_executable', 's:12:\"/usr/bin/svn\";');");
    }

    public function down()
    {
        echo "m111206_154710_config_executables does not support migration down.\n";
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
