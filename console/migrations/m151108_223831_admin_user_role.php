<?php

use yii\db\Schema;
use yii\db\Migration;

class m151108_223831_admin_user_role extends Migration
{
    public function up()
    {
        $this->update("{{%user}}",
            array(
                'role' => 'super_admin'
            )
        );
    }

    public function down()
    {
        $this->update("{{%user}}",
            array(
                'role' => 'user'
            )
        );
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
