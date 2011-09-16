<?php

class m110916_061310_project_tagline extends CDbMigration
{
	public function up()
	{
            $this->addColumn("{{project}}", 
                    "tagline", 
                    "string DEFAULT NULL");
	}

	public function down()
	{
		echo "m110916_061310_project_tagline does not support migration down.\n";
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