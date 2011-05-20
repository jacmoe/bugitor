<?php

class m110520_025001_version_title extends CDbMigration
{
	public function up()
	{
            $this->addColumn("{{version}}", 
                    "title", 
                    "string NOT NULL DEFAULT 'none'");
            $this->alterColumn("{{version}}",
                    "description", 
                    "text DEFAULT NULL");
	}

	public function down()
	{
		echo "m110520_025001_version_title does not support migration down.\n";
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