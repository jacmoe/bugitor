<?php

use yii\db\Schema;
use yii\db\Migration;

class m151117_005111_timestamp_fixes extends Migration
{

    public function safeUp()
    {
        $this->alterColumn("{{%action_log}}",
            "theDate",
            Schema::TYPE_INTEGER . ' NOT NULL');
        $this->alterColumn("{{%attachment}}",
            "created",
            Schema::TYPE_INTEGER . ' NOT NULL');
        $this->alterColumn("{{%changeset}}",
            "commit_date",
            Schema::TYPE_INTEGER . ' NOT NULL');
        $this->alterColumn("{{%comment}}",
            "created",
            Schema::TYPE_INTEGER . ' NOT NULL');
        $this->alterColumn("{{%comment}}",
            "modified",
            Schema::TYPE_INTEGER . ' NOT NULL');
        $this->alterColumn("{{%issue}}",
            "created",
            Schema::TYPE_INTEGER . ' NOT NULL');
        $this->alterColumn("{{%issue}}",
            "modified",
            Schema::TYPE_INTEGER . ' NOT NULL');
        $this->alterColumn("{{%project}}",
            "created",
            Schema::TYPE_INTEGER . ' NOT NULL');
        $this->alterColumn("{{%project}}",
            "modified",
            Schema::TYPE_INTEGER . ' NOT NULL');
        $this->alterColumn("{{%version}}",
            "created",
            Schema::TYPE_INTEGER . ' NOT NULL');
        $this->alterColumn("{{%version}}",
            "modified",
            Schema::TYPE_INTEGER . ' NOT NULL');
        $this->alterColumn("{{%version}}",
            "effective_date",
            Schema::TYPE_INTEGER . ' NOT NULL');
    }

    public function safeDown()
    {
        $this->alterColumn("{{%action_log}}",
            "theDate",
            Schema::TYPE_DATETIME . ' NOT NULL');
        $this->alterColumn("{{%attachment}}",
            "created",
            Schema::TYPE_TIMESTAMP . ' NOT NULL');
        $this->alterColumn("{{%changeset}}",
            "commit_date",
            Schema::TYPE_TIMESTAMP . ' NOT NULL');
        $this->alterColumn("{{%comment}}",
            "created",
            Schema::TYPE_DATETIME . ' NOT NULL');
        $this->alterColumn("{{%comment}}",
            "modified",
            Schema::TYPE_DATETIME . ' NOT NULL');
        $this->alterColumn("{{%issue}}",
            "created",
            Schema::TYPE_TIMESTAMP . ' NOT NULL');
        $this->alterColumn("{{%issue}}",
            "modified",
            Schema::TYPE_TIMESTAMP . ' NOT NULL');
        $this->alterColumn("{{%project}}",
            "created",
            Schema::TYPE_TIMESTAMP . ' NOT NULL');
        $this->alterColumn("{{%project}}",
            "modified",
            Schema::TYPE_TIMESTAMP . ' NOT NULL');
        $this->alterColumn("{{%version}}",
            "created",
            Schema::TYPE_TIMESTAMP . ' NOT NULL');
        $this->alterColumn("{{%version}}",
            "modified",
            Schema::TYPE_TIMESTAMP . ' NOT NULL');
        $this->alterColumn("{{%version}}",
            "effective_date",
            Schema::TYPE_DATE . ' NOT NULL');
    }

}
