<?php

class m110517_190953_add_diff_column_changeset extends CDbMigration
{
	public function up()
	{
            $this->addColumn('{{change}}', 'diff', 'text DEFAULT NULL');
        }

	public function down()
	{
            $this->dropColumn('{{change}}', 'diff');
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