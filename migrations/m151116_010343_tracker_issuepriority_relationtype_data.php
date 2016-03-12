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

class m151116_010343_tracker_issuepriority_relationtype_data extends Migration
{

    public function safeUp()
    {
        $this->insert('{{%issue_priority}}', array(
            "name" => "Low",
        ));
        $this->insert('{{%issue_priority}}', array(
            "name" => "Normal",
        ));
        $this->insert('{{%issue_priority}}', array(
            "name" => "High",
        ));
        $this->insert('{{%issue_priority}}', array(
            "name" => "Critical",
        ));

        $this->insert('{{%tracker}}', array(
            "name" => "Bug",
            "position" => 1,
        ));
        $this->insert('{{%tracker}}', array(
            "name" => "Feature",
            "position" => 2,
        ));
        $this->insert('{{%tracker}}', array(
            "name" => "Proposal",
            "position" => 3,
        ));
        $this->insert('{{%tracker}}', array(
            "name" => "Task",
            "position" => 4,
        ));

        $this->insert('{{%relation_type}}', array(
            "name" => "related",
            "description" => "is related to",
        ));
        $this->insert('{{%relation_type}}', array(
            "name" => "duplicates",
            "description" => "is a duplicate of",
        ));
        $this->insert('{{%relation_type}}', array(
            "name" => "closes",
            "description" => "is closed by",
        ));
    }

    public function safeDown()
    {
        $this->delete('{{%issue_priority}}', array('name' => 'Low'));
        $this->delete('{{%issue_priority}}', array('name' => 'Normal'));
        $this->delete('{{%issue_priority}}', array('name' => 'High'));
        $this->delete('{{%issue_priority}}', array('name' => 'Critical'));

        $this->delete('{{%tracker}}', array('name' => 'Bug'));
        $this->delete('{{%tracker}}', array('name' => 'Feature'));
        $this->delete('{{%tracker}}', array('name' => 'Proposal'));
        $this->delete('{{%tracker}}', array('name' => 'Task'));

        $this->delete('{{%relation_type}}', array('name' => 'related'));
        $this->delete('{{%relation_type}}', array('name' => 'duplicates'));
        $this->delete('{{%relation_type}}', array('name' => 'closes'));
    }

}
