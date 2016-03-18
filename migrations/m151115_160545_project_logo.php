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
