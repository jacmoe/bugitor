<?php

use yii\db\Schema;
use yii\db\Migration;

class m151108_223812_user_role extends Migration
{
    public function safeUp()
    {
        $this->addColumn("{{%user}}",
            "role",
            $this->string(64)->notNull()->defaultValue('user'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'role');
    }

}
