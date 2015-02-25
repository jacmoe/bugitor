<?php

use yii\db\Schema;
use yii\db\Migration;

class m150225_020142_initial_tables extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

    }

    public function down()
    {
        echo "m150225_020142_initial_tables cannot be reverted.\n";

        return false;
    }
}
