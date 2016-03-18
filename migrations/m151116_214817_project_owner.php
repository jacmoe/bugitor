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

class m151116_214817_project_owner extends Migration
{

    public function safeUp()
    {
        $this->addColumn("{{%project}}",
            "owner_id",
            "integer");
        $this->addColumn("{{%project}}",
            "updater_id",
            "integer");
        $this->createIndex('owner_idx', '{{%project}}', 'owner_id');
        $this->createIndex('updater_idx', '{{%project}}', 'updater_id');
        $this->addForeignKey('fk_project_owner',
            '{{%project}}',
            'owner_id',
            '{{%user}}', 'id',
            'NO ACTION', 'NO ACTION'
        );
        $this->addForeignKey('fk_project_updater',
            '{{%project}}',
            'updater_id',
            '{{%user}}', 'id',
            'NO ACTION', 'NO ACTION'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_project_owner',
            '{{%project}}');
        $this->dropForeignKey('fk_project_updater',
            '{{%project}}');
        $this->dropColumn('{{%project}}', 'owner_id');
        $this->dropColumn('{{%project}}', 'updater_id');
    }

}
