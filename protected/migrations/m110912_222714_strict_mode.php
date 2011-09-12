<?php

class m110912_222714_strict_mode extends CDbMigration
{
	public function up()
	{
            $this->alterColumn("{{issue}}",
                    "closed", 
                    "boolean NOT NULL DEFAULT 0");
            $this->alterColumn("{{issue}}",
                    "pre_done_ratio", 
                    "integer NOT NULL DEFAULT 0");
	}

	public function down()
	{
		echo "m110912_222714_strict_mode does not support migration down.\n";
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