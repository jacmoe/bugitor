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

use yii\db\Migration;
use app\commands\BugitorRbacCommand;

class m160323_101532_rbac_initial extends Migration
{
    public function safeUp()
    {
        \Yii::$app->runAction('rbac/update');
    }

    public function safeDown()
    {
        return true;
    }
}
