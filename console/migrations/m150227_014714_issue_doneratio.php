<?php

use yii\db\Schema;
use yii\db\Migration;

class m150227_014714_issue_doneratio extends Migration
{
    public function safeUp()
    {
        $this->alterColumn("{{%issue}}",
            "done_ratio",
            "integer NOT NULL DEFAULT '0'");
    }

    public function safeDown()
    {
        $this->alterColumn("{{%issue}}",
            "done_ratio",
            "integer NOT NULL");
    }
}
