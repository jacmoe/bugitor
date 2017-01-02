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

class m151111_232256_issue_done_ratio extends Migration
{
    public function safeUp()
    {
        $this->alterColumn("{{%issue}}",
            "done_ratio",
            "integer NOT NULL DEFAULT '0'");
    }

    public function safeDown()
    {
        $this->alterColumn("{{%issue}}",
            "done_ratio",
            "integer NOT NULL");
    }
}
