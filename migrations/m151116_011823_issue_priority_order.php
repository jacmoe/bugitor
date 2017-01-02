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
*	Copyright (c) 2009 - 2017 Jacob Moen
*	Licensed under the MIT license
*/

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
