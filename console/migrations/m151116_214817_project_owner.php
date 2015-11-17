<?php

use yii\db\Schema;
use yii\db\Migration;

class m151116_214817_project_owner extends Migration
{

    public function safeUp()
    {
        $this->addColumn("{{%project}}",
            "owner",
            "integer");
        $this->createIndex('owner_idx', '{{%project}}', 'owner');
        $this->addForeignKey('fk_project_owner',
            '{{%project}}',
            'owner',
            '{{%user}}', 'id',
            'NO ACTION', 'NO ACTION'
        );
    }

    public function safeDown()
    {
        $this->dropColumn('{{%project}}', 'owner');
        $this->dropForeignKey('fk_project_owner',
            '{{%project}}');
    }

}
