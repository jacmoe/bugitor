<?php

use yii\db\Schema;
use yii\db\Migration;

class m150227_015305_add_diff_column_changeset extends Migration
{
    public function safeUp()
    {
        $this->addColumn("{{%change}}",
            "diff",
            "binary DEFAULT NULL");
    }

    public function safeDown()
    {
        $this->dropColumn('{{%change}}', 'diff');
    }
}
