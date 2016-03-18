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
*	Copyright (c) 2009 - 2016 Jacob Moen
*	Licensed under the MIT license
*/

use yii\db\Schema;
use yii\db\Migration;

class m151111_232421_version_title extends Migration
{
    public function safeUp()
    {
        $this->addColumn("{{%version}}",
            "title",
            "string NOT NULL DEFAULT 'none'");
        $this->alterColumn("{{%version}}",
            "description",
            "text DEFAULT NULL");
    }

    public function safeDown()
    {
        $this->dropColumn('{{%version}}', 'title');
        $this->alterColumn("{{%version}}",
            "description",
            "string DEFAULT NULL");
    }
}
