<?php

class m111206_105839_author_user_repository extends CDbMigration
{
	public function up()
	{
        $this->addColumn("{{author_user}}", 
                "repository_id", 
                "integer");
	}

	public function down()
	{
		echo "m111206_105839_author_user_repository does not support migration down.\n";
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