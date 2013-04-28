<?php

class m130428_204559_drop_changes extends CDbMigration
{
	public function up()
	{
		$this->dropTable('{{change}}');
	}

	public function down()
	{
		echo "m130428_204559_drop_changes does not support migration down.\n";
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