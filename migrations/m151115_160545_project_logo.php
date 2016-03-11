<?php

use yii\db\Schema;
use yii\db\Migration;

class m151115_160545_project_logo extends Migration
{

    public function safeUp()
    {
        $this->addColumn("{{%project}}",
            "logo",
            "string DEFAULT NULL");
            $this->addColumn("{{%project}}",
                "logoname",
                "string DEFAULT NULL");
    }

    public function safeDown()
    {
        $this->dropColumn('{{%project}}', 'logo');
        $this->dropColumn('{{%project}}', 'logoname');
    }

}
