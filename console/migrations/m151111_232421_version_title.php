<?php

use yii\db\Schema;
use yii\db\Migration;

class m151111_232421_version_title extends Migration
{
    public function safeUp()
    {
        $this->addColumn("{{%version}}",
            "title",
            "string NOT NULL DEFAULT 'none'");
        $this->alterColumn("{{%version}}",
            "description",
            "text DEFAULT NULL");
    }

    public function safeDown()
    {
        $this->dropColumn('{{%version}}', 'title');
        $this->alterColumn("{{%version}}",
            "description",
            "string DEFAULT NULL");
    }
}
