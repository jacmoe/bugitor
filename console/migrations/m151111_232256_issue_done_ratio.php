<?php

use yii\db\Schema;
use yii\db\Migration;

class m151111_232256_issue_done_ratio extends Migration
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
