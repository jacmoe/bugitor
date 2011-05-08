<?php

class m110508_200344_initial_data extends CDbMigration
{
	public function safeUp()
	{
            $sql = "";
            $fd = fopen(dirname(__FILE__).'/../data/data.sql', "r");
            if($fd) {
                while (!feof($fd)) {
                    $sql .= fread($fd, 1024);
                }
                fclose($fd);
            }
            $this->execute($sql);
        }

	public function safeDown()
	{
		echo "m110508_200344_initial_data does not support migration down.\n";
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