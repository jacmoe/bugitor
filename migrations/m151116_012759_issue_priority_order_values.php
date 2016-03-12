<?php
/*
* This file is part of
*  _                 _ _
* | |__  _   _  __ _(_) |_ ___  _ __
* | '_ \| | | |/ _` | | __/ _ \| '__|
* | |_) | |_| | (_| | | || (_) | |
* |_.__/ \__,_|\__, |_|\__\___/|_|
*              |___/
*                 issue tracker
*
*	Copyright (c) 2010 - 2016 Jacob Moen
*	Licensed under the MIT license
*/

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
