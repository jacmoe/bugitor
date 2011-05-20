<?php

class m110520_005821_kill_children_changeset extends CDbMigration
{
	public function up()
	{
            $this->dropColumn("{{changeset}}", 
                    "child_count");
            $this->dropColumn("{{changeset}}", 
                    "children");
	}

	public function down()
	{
		echo "m110520_005821_kill_children_changeset does not support migration down.\n";
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