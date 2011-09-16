<?php

class m110916_091322_issue_comment_count extends CDbMigration
{
	public function up()
	{
            $this->addColumn("{{issue}}", 
                    "comment_count", 
                    "integer NOT NULL DEFAULT 0");
	}

	public function down()
	{
		echo "m110916_091322_issue_comment_count does not support migration down.\n";
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