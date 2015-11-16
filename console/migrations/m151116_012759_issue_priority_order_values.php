<?php

use yii\db\Schema;
use yii\db\Migration;

class m151116_012759_issue_priority_order_values extends Migration
{

    public function safeUp()
    {
        $this->update('{{%issue_priority}}', ['position' => 1], 'name = "Low"');
        $this->update('{{%issue_priority}}', ['position' => 2], 'name = "Normal"');
        $this->update('{{%issue_priority}}', ['position' => 3], 'name = "High"');
        $this->update('{{%issue_priority}}', ['position' => 4], 'name = "Critical"');
    }

    public function safeDown()
    {
        $this->update('{{%issue_priority}}', ['position' => 0], 'name = "Low"');
        $this->update('{{%issue_priority}}', ['position' => 0], 'name = "Normal"');
        $this->update('{{%issue_priority}}', ['position' => 0], 'name = "High"');
        $this->update('{{%issue_priority}}', ['position' => 0], 'name = "Critical"');
    }

}
