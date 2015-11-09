<?php

use yii\db\Schema;
use yii\db\Migration;

class m151108_223831_admin_user_role extends Migration
{
    public function safeUp()
    {
        $this->update("{{%user}}",
            array(
                'role' => 'super_admin'
            )
        );
    }

    public function safeDown()
    {
        $this->update("{{%user}}",
            array(
                'role' => 'user'
            )
        );
    }

}
