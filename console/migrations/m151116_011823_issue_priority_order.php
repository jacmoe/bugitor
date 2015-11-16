<?php

use yii\db\Schema;
use yii\db\Migration;

class m151116_011823_issue_priority_order extends Migration
{

    public function safeUp()
    {
        $this->addColumn("{{%issue_priority}}",
            "position",
            "integer NOT NULL DEFAULT 0");
    }

    public function safeDown()
    {
        $this->dropColumn('{{%issue_priority}}', 'position');
    }

}
